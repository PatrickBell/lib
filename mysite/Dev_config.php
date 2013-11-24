<?php

global $project;
$project = 'mysite';

// global $databaseConfig;
// $databaseConfig = array(
	// "type" => "MySQLDatabase",
	// "server" => "tsrvsql5-dev.tdc.tdc.govt.nz", 
	// "username" => "root", 
	// "password" => "awesome", 
	// "database" => "ssdev01",
// );

global $database;
$database = 'ssdev02';

// Sets the environment type 
Director::set_environment_type('dev');


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
SSViewer::set_theme('tdc2');

// enable nested URLs for this site (e.g. page/sub-page/)
SiteTree::enable_nested_urls();

// Set admin e-mail for debug pages error message
Email::setAdminEmail('tama.easton@tasman.govt.nz');

i18n::set_locale('en_NZ');
Translatable::set_default_locale('en_NZ');
date_default_timezone_set('Pacific/Auckland');

Object::add_extension('SiteConfig', 'CustomSiteConfig');

DPSAdapter::set_pxpay_account('TDC_Dev', 'c58c1b5920d5f50d30bf9980723d6d442b53fa9cc55ed47ce6ffd34aade719eb');
DPSAdapter::$pxPay_Url = 'https://qa4.paymentexpress.com/pxpay/pxaccess.aspx';

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

