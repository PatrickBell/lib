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
$database = 'ssdev04';

// Sets the environment type 
Director::set_environment_type('test');


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

?>