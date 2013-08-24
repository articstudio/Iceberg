/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here.
	// For the complete reference:
	// http://docs.ckeditor.com/#!/api/CKEDITOR.config
        
        //http://docs.ckeditor.com/#!/api/CKEDITOR.config-cfg-extraPlugins
        config.extraPlugins = 'iceberg,justify,iframe,find,mediaembed';
        
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
                { name: 'styles', items : [ 'Styles','Format' ] },
	];

	// Se the most common block elements.
	//config.format_tags = 'p;h1;h2;h3;pre';

	// Make dialogs simpler.
	//config.removeDialogTabs = 'image:advanced;link:advanced';
    
    //elFinder
    config.filebrowserBrowseUrl = elFinderAPI_popup_ckeditor;
    config.filebrowserImageBrowseUrl = elFinderAPI_popup_ckeditor_Image;
    config.filebrowserFlashBrowseUrl = elFinderAPI_popup_ckeditor_Flash;
    config.filebrowserImageUploadUrl = elFinderAPI_popup_ckeditor_Image;
    config.filebrowserFlashUploadUrl = elFinderAPI_popup_ckeditor_Flash;
    config.filebrowserImageWindowWidth = '950';
    config.filebrowserImageWindowHeight = '490';
    config.filebrowserWindowWidth = '950';
    config.filebrowserWindowHeight = '490';
};
