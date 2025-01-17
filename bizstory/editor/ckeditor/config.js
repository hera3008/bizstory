﻿/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:

	config.language = 'fr';
	config.uiColor = '#dfdfdf';
	config.toolbar = 'MyToolbar';
	config.skin = 'v2';

	//config.stylesSet = 'my_styles';

// toolbar
	config.toolbar_Basic =
	[
		['Bold', 'Italic', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink','-','About']
	];

	config.toolbar_Full =
	[
		{ name: 'document', items : [ 'Source','-','Save','NewPage','DocProps','Preview','Print','-','Templates' ] },
		{ name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
		{ name: 'editing', items : [ 'Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt' ] },
		{ name: 'forms', items : [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 
			'HiddenField' ] },
		'/',
		{ name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
		{ name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv',
		'-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },
		{ name: 'links', items : [ 'Link','Unlink','Anchor' ] },
		{ name: 'insert', items : [ 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe' ] },
		'/',
		{ name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] },
		{ name: 'colors', items : [ 'TextColor','BGColor' ] },
		{ name: 'tools', items : [ 'Maximize', 'ShowBlocks','-','About' ] }
	];

	config.toolbar_MyToolbar =
	[
		{ name: 'document', items : [ 'Source' ] },
		{ name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript' ] },
		{ name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent',
		'-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },
		{ name: 'insert', items : [ 'Image','HorizontalRule','Smiley','SpecialChar' ] },
		'/',
		{ name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] },
		{ name: 'colors', items : [ 'TextColor','BGColor' ] },
		{ name: 'links', items : [ 'Link','Unlink','Anchor' ] }
	];
};

/*
CKEDITOR.replace( 'editor1',
{
	toolbar : 'MyToolbar',
	uiColor : '#9AB8F3'
});

CKEDITOR.replace( 'editor1',
{
	toolbar :
	[
		{ name: 'basicstyles', items : [ 'Bold','Italic' ] },
		{ name: 'paragraph', items : [ 'NumberedList','BulletedList' ] },
		{ name: 'tools', items : [ 'Maximize','-','About' ] }
	]
});
*/