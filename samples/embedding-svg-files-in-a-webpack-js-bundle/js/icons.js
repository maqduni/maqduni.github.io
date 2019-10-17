import addSvgImagesAsSprites from './helpers/addSvgImagesAsSprites';
import requireContextToValues from './helpers/requireContextToValues';

const common = require.context('!svgSprite!../images', false, /\.svg$/);

addSvgImagesAsSprites([
    ...requireContextToValues(common),
], 'icon-');
