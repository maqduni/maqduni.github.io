export default function htmlToElement(html) {
    const template = document.createElement('div');
    html = html.trim(); // Never return a text node of whitespace as the result
    template.innerHTML = html;
    return template.firstChild;
};
