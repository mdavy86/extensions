<?php
/**
 * jQueryUpload2 MediaWiki extension - allows files to be uploaded to the wiki or to specific pages using the jQueryFileUpload module
 * 
 * Version 2.0.0+ summary:
 * - uses MediaWiki's native files instead of just files outside the wiki
 * - uses categorisation to determine uploaded files belonging to a specific page
 * - i18n message determines category naming convention, e.g. "File uploaded to $1"
 * - adds a general upload special page that can be used in place of Special:Upload
 * - uses MediaWiki's thumbnails instead of BlueImp's code
 *
 * jQueryFileUpload module: https://github.com/blueimp/jQuery-File-Upload
 *
 * @package MediaWiki
 * @subpackage Extensions
 * @author Aran Dunkley (http://www.organicdesign.co.nz/nad)
 */
if( !defined( 'MEDIAWIKI' ) ) die( 'Not an entry point.' );
define( 'JQU_VERSION', "2.0.0, 2014-11-23" );

$wgJQUploadIconPrefix = dirname( __FILE__ ) . '/icons/Farm-Fresh_file_extension_';
$wgJQUploadFileMagic = 'file';
$wgHooks['LanguageGetMagic'][] = 'jQueryUpload::onLanguageGetMagic';
$wgJQUploadFileLinkPopup = true;

$wgAjaxExportList[] = 'jQueryUpload::server';

$wgExtensionFunctions[] = 'wfJQueryUploadSetup';
$wgSpecialPages['jQueryUpload'] = 'jQueryUpload';
$wgSpecialPageGroups['jQueryUpload'] = 'media';
$wgExtensionCredits['other'][] = array(
	'path'           => __FILE__,
	'name'           => "jQueryUpload",
	'descriptionmsg' => "jqueryupload-desc",
	'url'            => "http://www.organicdesign.co.nz/jQueryUpload",
	'author'         => array( "[http://www.organicdesign.co.nz/nad Aran Dunkley]", "[http://blueimp.net Sebastian Tschan]" ),
	'version'        => JQU_VERSION
);

// If the query-string arg mwaction is supplied, rename action and change mwaction to action
// - this hack was required because the jQueryUpload module uses the name "action" too
if( array_key_exists( 'mwaction', $_REQUEST ) ) {
	$wgJQUploadAction = array_key_exists( 'action', $_REQUEST ) ? $_REQUEST['action'] : false;
	$_REQUEST['action'] = $_GET['action'] = $_POST['action'] = $_REQUEST['mwaction'];
}

$dir = dirname( __FILE__ );
$wgExtensionMessagesFiles['jQueryUpload'] = "$dir/jQueryUpload.i18n.php";
$wgExtensionMessagesFiles['jQueryUploadAlias'] = "$dir/jQueryUpload.alias.php";
require( "$dir/upload/server/php/upload.class.php" );
require( "$dir/jQueryUpload_body.php" );

function wfJQueryUploadSetup() {
	global $wgJQueryUpload;
	$wgJQueryUpload = new jQueryUpload();
}
