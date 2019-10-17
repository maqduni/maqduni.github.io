/*
 * This is a simple raw file loader that allows to add svg as a sprite through a javascript file.
 * It's VERY IMPORTANT to note that an exclamation mark MUST be place at the beginning of the file path
 * with the loader name in order to disable default loaders for file types in question
 */

const { getOptions } = require('loader-utils');
const validateOptions = require('schema-utils');
const path = require('path');
const SVGO = require('svgo');
const schema = {};

module.exports = function svgSpriteLoader(source, map, meta) {
    const options = getOptions(this) || {};
    validateOptions(schema, options, 'Svg Sprite Loader');

    const loaderContext = this;
    const { resourcePath } = loaderContext;

    const callback = this.async();

    const svgo = new SVGO({
        plugins : [
            'cleanupAttrs',
            'removeDoctype',
            'removeXMLProcInst',
            'removeComments',
            'removeMetadata',
            'removeTitle',
            'removeDesc',
            'removeUselessDefs',
            'removeXMLNS',
            'removeEditorsNSData',
            'removeEmptyAttrs',
            'removeHiddenElems',
            'removeEmptyText',
            'removeEmptyContainers',
            'minifyStyles',
            'removeUnknownsAndDefaults',
            'removeUselessStrokeAndFill',
            'removeUnusedNS',
            'cleanupIDs',
            'collapseGroups',
            'mergePaths',
            'removeDimensions',
            'removeOffCanvasPaths',
            'removeScriptElement',
        ],
    });
    svgo.optimize(source)
        .then((optimizedSource) => {
            const json = JSON.stringify(optimizedSource.data)
                .replace(/\u2028/g, '\\u2028')
                .replace(/\u2029/g, '\\u2029');
            const result = `export default { fileName: '${path.basename(resourcePath)}', svg: ${json} }`;

            callback(null, result, map, meta);
        }).catch((error) => {
            callback(error);
        });
};
