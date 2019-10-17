---
title: Embedding SVG files in a JS bundle using Webpack
date: 2019-07-16 20:44:43
tags:
- webpack
- webpack loader
- svg sprite
- vazhaju
---

## The cause

Recent issues with the internet in Tajikistan made me think about the loading speed of my online dictionary [Vazhaju (Вожаҷӯ / واژه‌جو)][1]. As soon as the Tajik government announced that they would raise mobile internet prices I rushed to check the loading speed of the site. The speed was high, the site had been tailored for mobile devices, was served using GZIP compression and practically didn't have anything unnecessary. 

But then I noticed an interesting detail, the actual size of the content didn't exceed 7kb, whereas every page in it's compressed form took at least 12kb, and the uncompressed form took around 24kb. Not such a tiny size for so little content, isn't it. This size inflation was caused by the SVG images that were added in their entirety to the main template on each page serving.

### A little bit on the architecture
The front end is structured as a set of independent micro applications, aka micro front ends. There's one for public users, one for admins, one for editors, etc, therefore,

1. Not all of the applications needed the entire SVG package, each used only a few images.
2. I could take advantage of the browser cache, load the images only once for each of my micro applications and simply serve them from there.

The solution was quite obvious, generate individual [SVG sprite][2] for each micro front end, embed it in it's JS bundle, and add the sprite to the DOM on page load.

## Search for an existing solution
I embarked on a quite lengthy search for Webpack loaders and plugins that could solve my problem. To my surprise existing loaders wouldn't allow me to create an individual file per each micro application. They could only gather all of the SVG images used in all of the bundles and combine them into one gigantic SVG sprite `oneGiganticSprite.svg`, that all of the micro applications had to use. It was exactly what I was trying to avoid.

Even if I managed to build individual sprites, I still had to somehow load it. There were recommendations to asynchronously load the sprite file on page load and add it to the DOM. This approach has three huge downsides:
1. Need for an HTTP library, which means increase of the size of my bundle
2. Extra HTTP call
3. Implementation of custom caching

All this could be avoided by simply including the sprites in the JS bundle and all of this would be taken care of by the built-in browser functionality.

Finally I gave up looking for third party solutions and decided to write my own. I needed write a custom Webpack SVG loader, to gather the necessary SVG images and add them to individual JS bundles.

To my luck it turned out to be very simple.

## Implementation

Before you continue, I recommend you to look at the [sample project][3] containing all of the provided here source code.

### Custom Webpack loader

`svgSpriteLoader.js` - The loader almost entirely copies the Rawloader except for the last line

```javascript
// svgSpriteLoader.js
const { getOptions } = require('loader-utils');
const validateOptions = require('schema-utils');
const path = require('path');
const schema = {};

module.exports = function svgSpriteLoader(source) {
    const options = getOptions(this) || {};
    validateOptions(schema, options, 'Svg Sprite Loader');

    const loaderContext = this;
    const { resourcePath } = loaderContext;

    let json = JSON.stringify(source)
        .replace(/\u2028/g, '\\u2028')
        .replace(/\u2029/g, '\\u2029');

    return `export default { fileName: '${path.basename(resourcePath)}', svg: ${json} }`;
};
```

<!-- more -->

it simply loads and SVG file and returns the name of the file and it's content in the following form
```javascript
export default {
    fileName: 'someImage.svg',
    svg: '<svg></svg>'
}
```

`webpack.config` - In order to use the loader it has to be specified in the Webpack configuration file as follows
```javascript
// webpack.config
{
    resolveLoader: {
        alias: {
            svgSprite: path.resolve('./webpack/svgSpriteLoader.js'),
        }
    },
}
```

### Loading and embedding of the SVG images loaded via the custom loader

From now on I'll be using helper methods that I wrote to build the SVG sprite and add it to the DOM on page load.

`addSvgImagesAsSprites.js` - Take the loaded SVG image objects, build a sprite element and add it to the DOM

```javascript
// addSvgImagesAsSprites.js
import htmlToElement from "./htmlToElement";

export default function addSvgImagesAsSprites(images, idPrefix = '') {
    const spriteContainerHtml = `<svg style="position: absolute; width: 0; height: 0; overflow: hidden;" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
<defs></defs>
</svg>`;

    const $spriteContainer = htmlToElement(spriteContainerHtml);
    const $defs = $spriteContainer.getElementsByTagName('defs')[0];

    images.forEach(image => {
        const id = `${idPrefix}${image.fileName.replace('.svg', '')}`;
        const $el = htmlToElement(image.svg);
        $el.setAttribute('id', id);

        $defs.appendChild($el);
    });

    document.body.insertAdjacentElement('afterbegin', $spriteContainer);
}
```

`htmlToElement.js` - Convert serialized SVG image text to an HTML element
```javascript
// htmlToElement.js
export default function htmlToElement(html) {
    const template = document.createElement('div');
    html = html.trim(); // Never return a text node of whitespace as the result
    template.innerHTML = html;
    return template.firstChild;
};
```

`icons.js` - We are almost there, we just need to include the images in our JS bundle. For that I'll be using context loading which allows me to import files by a wild card pattern.

```javascript
// icons.js
import addSvgImagesAsSprites from '../common/helpers/addSvgImagesAsSprites';
import requireContextToValues from '../common/helpers/requireContextToValues';

const common = require.context('!svgSprite!../../images/common', false, /\.svg$/);
const word = require.context('!svgSprite!../../images/common/word', false, /\.svg$/);
const search = require.context('!svgSprite!../../images/search', false, /\.svg$/);

addSvgImagesAsSprites([
    ...requireContextToValues(common),
    ...requireContextToValues(word),
    ...requireContextToValues(search),
], 'icon-');
```

You must have noticed the following details:
1. Exclamation mark in `!svgSprite` instructs Webpack to use this loader instead of the ones that are specified in it's configuration file for files that match the specified pattern.
2. Argument `icon-` is the prefix for SVG identifiers that all SVG elements receive once they are added to the sprite.

`requireContextToValues.js` Abstracts a call to `context()` for retrieving objects with the content of the imported SVG files
```javascript
// requireContextToValues.js
export default function requireContextToValues(context) {
    return context.keys().map((key) => context(key).default);
}
```

## تمام، از توجه‌تان متشکرم
That is it! I hope you didn't have to drink a double espresso to get to the end, but I assure you if you did, it wasn't totally in vein. 

The source code is available on [my GitHub page][3].

## Summary
You just saw how to include SVG sprites in JS bundles, without extra files, without extra HTTP calls, and without implementing a custom cache. Although this approach requires a certain level of understanding of Webpack, SVG sprites, and the module resolution system of NodeJS, it makes an optimal alternative to existing methods, which implicitly impose certain constraints on the way they can be used which may cause a totally unnecessary comlication of the architecture of your project. Never limit yourself by the existing tools if you have an opportunity to create your own.

[1]: https://vazhaju.com
[2]: https://css-tricks.com/css-sprites/#article-header-id-0
[3]: https://github.com/maqduni/maqduni.github.io/projects/embedding-svg-files-in-a-webpack-js-bundle
