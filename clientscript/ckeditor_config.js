/*======================================================================*\
|| #################################################################### ||
|| # 4.2.6 by vBS
|| # ---------------------------------------------------------------- # ||
|| # Copyright ©2000-2018 vBulletin Solutions Inc. All Rights Reserved. ||
|| # This file may not be redistributed in whole or significant part. # ||
|| # ---------------- VBULLETIN IS NOT FREE SOFTWARE ---------------- # ||
|| #        www.vbulletin.com | www.vbulletin.com/license.html        # ||
|| #################################################################### ||
\*======================================================================*/
CKEDITOR.editorConfig = function (C) {

    C.filebrowserUploadUrl = fetch_ajax_url('newattachment.php');
    C.filebrowserImageUploadUrl = fetch_ajax_url('newattachment.php');
    C.simpleuploads_containerover = 'box-shadow: 0 0 10px 1px #333 !important;';
    C.simpleuploads_editorover = 'box-shadow: 0 0 10px 1px #333 inset !important;';
    C.simpleuploads_hideImageProgress = false;

    C.disableNativeSpellChecker = false;
    C.autoUpdateElement = false;
    C.enterMode = CKEDITOR.ENTER_BR;
    C.shiftEnterMode = CKEDITOR.ENTER_BR;
    C.forcePasteAsPlainText = false;
    C.fontSize_style = {
        element: "font",
        attributes: {
            size: "#(size)"
        }
    };
    C.coreStyles_subscript = {
        element: "sub"
    };
    C.coreStyles_superscript = {
        element: "sup"
    };
    C.smiley_path = "./";
    C.toolbarCanCollapse = false;
    CKEDITOR.on("dialogDefinition", function (I) {
        var J = I.data.name;
        var K = I.data.definition;
        var H = I.data.definition.dialog;
        if (J == "image") {
            K.removeContents("advanced");
            K.removeContents("Link")
        }
        if (J == "flash") {
            K.removeContents("advanced")
        }
    });
    C.resize_dir = "vertical";
    C.disableObjectResizing = true;
    var G = "about,a11yhelp,bidi,blockquote,colorbutton,contextmenu,dialogadvtab,div,scayt,elementspath,filebrowser,find,flash,link,newpage,pagebreak,preview,print,removeformat,save,showblocks,showborders,smiley,sourcearea,specialchar,table,undo";
    C.removePlugins += (C.removePlugins ? "," + G : G);
    var B = "bbcode,attachment,codetags,videotag,tablebuttons,smiliebox,enhancedsourcearea,vbbutton,vbimage,vblink,vbremoveformat,vbresize,vbsmiley,vbtable,vbtextcolor,vbundo,simpleuploads";
    C.extraPlugins += (C.extraPlugins ? "," + B : B);
    var F = CKEDITOR.basePath;
    C.vbPluginPath = F.substr(0, F.lastIndexOf("ckeditor/")) + "ckeplugins/";
    var A = C.extraPlugins.split(",");
    for (var D = 0; D < A.length; D++) {
        try {
            if (!CKEDITOR.plugins.get(A[D])) {
                CKEDITOR.plugins.addExternal(A[D], C.vbPluginPath + A[D] + "/", "plugin.js")
            }
        } catch (E) {}
    }
};
