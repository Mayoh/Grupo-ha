/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
  config.extraPlugins = 'cms_source';
  config.filebrowserBrowseUrl  = "webassist/kfm/index.php?uicolor=eee&theme=webassist_v2&showsidebar=true&startup_folder=cms_files";
  if (window.cms_root != null) config.filebrowserBrowseUrl  = cms_root + "/" + config.filebrowserBrowseUrl;
  config.enterMode = CKEDITOR.ENTER_BR;
  config.allowedContent=true;
  config.stylesCombo_stylesSet='my_styles:style_lists/stylelist1.js';
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
};
