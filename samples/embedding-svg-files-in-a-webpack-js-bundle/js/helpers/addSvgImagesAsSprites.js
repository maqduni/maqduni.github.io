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
