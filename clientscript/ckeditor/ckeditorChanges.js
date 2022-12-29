// https://davidwalsh.name/prevent-xss-ckeditor
// Prevent bad on* attributes (https://github.com/ckeditor/ckeditor-dev/commit/1b9a322)
var oldHtmlDataProcessorProto = CKEDITOR.htmlDataProcessor.prototype.toHtml;
CKEDITOR.htmlDataProcessor.prototype.toHtml = function(data, fixForBody) {
    function protectInsecureAttributes(html) {
        return html.replace( /([^a-z0-9<\-])(on\w{3,})(?!>)/gi, '$1data-cke-' + CKEDITOR.rnd + '-$2' );
    }

    data = protectInsecureAttributes(data);
    data = oldHtmlDataProcessorProto.apply(this, arguments);
    data = data.replace( new RegExp( 'data-cke-' + CKEDITOR.rnd + '-', 'ig' ), '' );

    return data;
};