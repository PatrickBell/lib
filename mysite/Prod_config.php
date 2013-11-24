<?php

global $project;
$project = 'mysite';

global $database;
$database = 'ss_taslib';

// Sets the environment type 
Director::set_environment_type('live');

//Set BaseURL
//Director::setBaseURL('http://www.tasman.govt.nz/');

// Sites running on the following servers will be
// run in development mode. See
// http://doc.silverstripe.org/doku.php?id=configuration
// for a description of what dev mode does.
Director::set_dev_servers(array(
	'localhost',
	'127.0.0.1',
));


// Use _ss_environment.php file for configuration
require_once('conf/ConfigureFromEnv.php');

// This line set's the current theme. More themes can be
// downloaded from http://www.silverstripe.org/themes/
SSViewer::set_theme('libraries');

// enable nested URLs for this site (e.g. page/sub-page/)
SiteTree::enable_nested_urls();

// Set admin e-mail for debug pages error message
Email::setAdminEmail('webmaster@tasman.govt.nz');

i18n::set_locale('en_NZ');
Translatable::set_default_locale('en_NZ');
date_default_timezone_set('Pacific/Auckland');

// Needed so the XML loading does not kill the application.
libxml_use_internal_errors(true);

//Customises the TinyMCE editor by removing and adding buttons.
HtmlEditorConfig::get('cms')->removeButtons('underline', 'strikethrough','blockquote','justifycenter','justifyfull');
HtmlEditorConfig::get('cms')->addButtonsToLine(1, 'sup', 'sub');
// Add macron support
HtmlEditorConfig::get('cms')->enablePlugins(array('ssmacron' => '../../../cms/javascript/tinymce_ssmacron/editor_plugin_src.js'));
HtmlEditorConfig::get('cms')->insertButtonsAfter('charmap', 'ssmacron');

//Enable fulltext searching
FulltextSearchable::enable();

//Register Google Map API
GoogleMapPage::$gmaps_api_key = 'AIzaSyA2SHxJu19QLNtGgcomcudTcjzJp70kvJM';
MapPage::$gmaps_api_key = 'AIzaSyA2SHxJu19QLNtGgcomcudTcjzJp70kvJM';

//Register Custom Reports
SS_Report::register('ReportAdmin', 'UserActivity', 20);
SS_Report::register('ReportAdmin', 'UnpublishedPages', 20);

Object::add_extension('SiteConfig', 'CustomSiteConfig');

DPSAdapter::set_pxpay_account('TasmanDC', 'be1be6f9b12af6a87f04d09ace711e212bfb74b7e5369a50420ee1b074900b5a');
DPSAdapter::$pxPay_Url = 'https://sec.paymentexpress.com/pxpay/pxaccess.aspx';
if (!Director::isLive()) {
	// For testing purposes - mock server
	Director::addRules(100,array('ItsonMockFeed/$Action/$ID/$OtherID' => 'ItsonMockFeed'));
}
//Configure Workflow E-mails
WorkflowRequest::set_alert('WorkflowPublicationRequest', 'request', 'publisher', true);
WorkflowRequest::set_alert('WorkflowPublicationRequest', 'approve', 'publisher', false);
WorkflowRequest::set_alert('WorkflowPublicationRequest', 'deny', 'publisher', false);
WorkflowRequest::set_alert('WorkflowPublicationRequest', 'cancel', 'publisher', true);
WorkflowRequest::set_alert('WorkflowPublicationRequest', 'comment', 'publisher', false);
WorkflowRequest::set_alert('WorkflowPublicationRequest', 'requestedit', 'publisher', false);
WorkflowRequest::set_alert('WorkflowDeletionRequest', 'request', 'publisher', true);
WorkflowRequest::set_alert('WorkflowDeletionRequest', 'approve', 'publisher', false);
WorkflowRequest::set_alert('WorkflowDeletionRequest', 'deny', 'publisher', false);
WorkflowRequest::set_alert('WorkflowDeletionRequest', 'cancel', 'publisher', true);
WorkflowRequest::set_alert('WorkflowDeletionRequest', 'comment', 'publisher', false);
WorkflowRequest::set_alert('WorkflowPublicationRequest', 'request', 'author', false);
WorkflowRequest::set_alert('WorkflowPublicationRequest', 'approve', 'author', true);
WorkflowRequest::set_alert('WorkflowPublicationRequest', 'deny', 'author', true);
WorkflowRequest::set_alert('WorkflowPublicationRequest', 'cancel', 'author', false);
WorkflowRequest::set_alert('WorkflowPublicationRequest', 'comment', 'author', true);
WorkflowRequest::set_alert('WorkflowPublicationRequest', 'requestedit', 'author', true);
WorkflowRequest::set_alert('WorkflowDeletionRequest', 'request', 'author', false);
WorkflowRequest::set_alert('WorkflowDeletionRequest', 'approve', 'author', true);
WorkflowRequest::set_alert('WorkflowDeletionRequest', 'deny', 'author', true);
WorkflowRequest::set_alert('WorkflowDeletionRequest', 'cancel', 'author', false);
WorkflowRequest::set_alert('WorkflowDeletionRequest', 'comment', 'author', true);

File::$allowed_extensions = array( 
'','html','htm','xhtml','js','css', 
'bmp','png','gif','jpg','jpeg','ico','pcx','tif','tiff', 
'au','mid','midi','mpa','mp3','ogg','m4a','ra','wma','wav','cda', 
'avi','mpg','mpeg','asf','wmv','m4v','mov','mkv','mp4','swf','flv','ram','rm', 
'doc','docx','txt','rtf','xls','xlsx','pages', 
'ppt','pptx','pps','csv', 
'cab','arj','tar','zip','zipx','sit','sitx','gz','tgz','bz2','ace','arc','pkg','dmg','hqx','jar', 
'xml','pdf','odt','ods' 
);

GoogleLogger::activate('SiteConfig');
GoogleAnalyzer::activate('SiteConfig');

Object::add_extension('DPSPayment', 'TasmanPayment');

// Default CMS HTMLEditorConfig
HtmlEditorConfig::get('cms')->setOptions(array(
	'friendly_name' => 'Default CMS',
	'priority' => '50',
	'mode' => 'none',

	'body_class' => 'typography',
	'document_base_url' => Director::absoluteBaseURL(),

	'urlconverter_callback' => "nullConverter",
	'setupcontent_callback' => "sapphiremce_setupcontent",
	'cleanup_callback' => "sapphiremce_cleanup",
	'use_native_selects' => true, // fancy selects are bug as of SS 2.3.0
	'valid_elements' => "@[id|class|style|title],#a[id|rel|rev|dir|tabindex|accesskey|type|name|href|target|title|class],abbr[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style|title],acronym[class|dir<ltr?rtl|id|id|lang|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style|title],-strong/-b[class],-em/-i[class],-strike[class],-u[class],#p[id|dir|class|align|style],-ol[class],-ul[class],-li[class],br,img[id|dir|longdesc|usemap|class|src|border|alt=|title|width|height|align],-sub[class],-sup[class],-blockquote[dir|class],-table[border=0|cellspacing|cellpadding|width|height|class|align|summary|dir|id|style],-tr[id|dir|class|rowspan|width|height|align|valign|bgcolor|background|bordercolor|style],tbody[id|class|style],thead[id|class|style],tfoot[id|class|style],#td[id|dir|class|colspan|rowspan|width|height|align|valign|scope|style],-th[id|dir|class|colspan|rowspan|width|height|align|valign|scope|style],caption[id|dir|class],-div[id|dir|class|align|style],-span[class|align|style],-pre[class|align],address[class|align],-h1[id|dir|class|align|style],-h2[id|dir|class|align|style],-h3[id|dir|class|align|style],-h4[id|dir|class|align|style],-h5[id|dir|class|align|style],-h6[id|dir|class|align|style],hr[class],dd[id|class|title|dir],dl[id|class|title|dir],dt[id|class|title|dir],@[id,style,class]",
	'extended_valid_elements' => "img[class|src|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name|usemap],iframe[src|name|width|height|align|frameborder|marginwidth|marginheight|scrolling],object[width|height|data|type],param[name|value],map[class|name|id],area[shape|coords|href|target|alt]"
));

Form::disable_all_security_tokens();

SortableDataObject::add_sortable_class('HomePage_CarouselItem');

DataObjectManager::allow_assets_override(false);

/* define('SS_DISABLE_COMPASS_MODULE', true); */

if(defined('SS_DISABLE_COMPASS_MODULE')) {
	Compass::$force_no_rebuild = true;
} else {
	if(!Director::isDev()) Compass::$force_no_rebuild = true;
	if(Director::isDev()) Compass::$errors_are_errors = true;
}

DataObject::add_extension('HomePage', 'HomePageDecorator');

?>