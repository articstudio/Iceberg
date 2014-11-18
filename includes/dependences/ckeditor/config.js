/**
 * @license Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
    
    config.toolbar = 'IcebergToolbar';
 
	config.toolbar_IcebergToolbar =
	[
        { name: 'tools', items : [ 'Maximize' ] },
        { name: 'document', items : [ 'Source' ] },
        { name: 'editing', items : [ 'Find','Replace' ] },
        { name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
        { name: 'insert', items : [ 'Image','MediaEmbed','NumberedList','BulletedList','Table','Blockquote','HorizontalRule','Iframe','SpecialChar' ] },
        { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
        { name: 'paragraph', items : [ 'JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','Outdent','Indent' ] },
        { name: 'links', items : [ 'Link','Unlink','Anchor' ] },
        { name: 'styles', items : [ 'Styles','Format' ] }
	];
    
    //elFinder
    if (typeof js_elfinder !== 'undefined')
    {
        config.filebrowserBrowseUrl = js_elfinder.popup_ckeditor;
        config.filebrowserImageBrowseUrl = js_elfinder.popup_ckeditor_image;
        config.filebrowserFlashBrowseUrl = js_elfinder.popup_ckeditor_flash;
        config.filebrowserImageUploadUrl = js_elfinder.popup_ckeditor_image;
        config.filebrowserFlashUploadUrl = js_elfinder.popup_ckeditor_flash;
        config.filebrowserImageWindowWidth = '950';
        config.filebrowserImageWindowHeight = '490';
        config.filebrowserWindowWidth = '950';
        config.filebrowserWindowHeight = '490';
    }
};
