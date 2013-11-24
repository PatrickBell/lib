<?php
/**
 * Disable SilverStripe's built in authentication module
 **/
Authenticator::unregister('MemberAuthenticator');

/**
 * External Authentication server definitions
 * Change the parameters below to suit your authentication server, or disable 
 * this authentication method altogether
 **/
Authenticator::register_authenticator('ExternalAuthenticator');

/**
 * This module has a debug option. The debug option is aimed at system 
 * administrators and/or webmasters. It shows the login process on a step
 * by step basis to aid in finding the right settings for the module.
 * The debug option will log the steps to a file. 
 *
 * KEEP THIS DISABLED ON PRODUCTION SYSTEMS. The option is turned off by 
 * default. Set to filename to enable
 *
 * A successful logon for the SSTRIPE driver (without user auto creation) looks 
 * like:
 *     [timestamp] - Starting process for user [userid]
 *     [timestamp] - [userid] - User with source [sourcename] found in database
 *     [timestamp] - [userid] - loading driver LDAP
 *     [timestamp] - [userid] - executing authentication driver
 *     [timestamp] - [userid].ldap - Connecting to [LDAP URI] port [port] LDAP version [2/3]
 *     [timestamp] - [userid].ldap - If process stops here, check PHP LDAP module
 *     [timestamp] - [userid].ldap - Connect succeeded
 *     [timestamp] - [userid].ldap - LDAP set to protocol version [2/3]
 *     [timestamp] - [userid].ldap - TLS not set
 *     [timestamp] - [userid].ldap - Bind success
 *     [timestamp] - [userid].ldap - LDAP filter set to (& (uid=[userid])(objectclass=[classname]))
 *     [timestamp] - [userid].ldap - Searching in tree [base dn]
 *     [timestamp] - [userid].ldap - Search succeeded
 *     [timestamp] - [userid].ldap - Found 1 results
 *     [timestamp] - [userid].ldap - DN uid=[userid],[base dn] matches criteria
 *     [timestamp] - [userid].ldap - Binding to LDAP as uid=[userid],[base dn]
 *     [timestamp] - [userid].ldap - LDAP accepted password for uid=[userid],[base dn]
 *     [timestamp] - [userid].ldap - Reading details of DN uid=[userid],[base dn]
 *     [timestamp] - [userid].ldap - Lookup of details succeeded
 *     [timestamp] - [userid].ldap - Looking up shadowlastchange
 *     [timestamp] - [userid].ldap - Attribute shadowlastchange not set
 *     [timestamp] - [userid].ldap - Looking up shadowmin
 *     [timestamp] - [userid].ldap - Attribute shadowmin not set
 *     [timestamp] - [userid].ldap - Looking up shadowmax
 *     [timestamp] - [userid].ldap - Attribute shadowmax not set
 *     [timestamp] - [userid].ldap - Looking up shadowwarning
 *     [timestamp] - [userid].ldap - Attribute shadowwarning not set
 *     [timestamp] - [userid].ldap - Looking up givenname
 *     [timestamp] - [userid].ldap - givenname set to [first name]
 *     [timestamp] - [userid].ldap - Looking up sn
 *     [timestamp] - [userid].ldap - sn set to [surname]
 *     [timestamp] - [userid].ldap - Looking up [mail attribute]
 *     [timestamp] - [userid].ldap - [mail attribute set to [user e-mail]
 *     [timestamp] - [userid].ldap - Password expiry not enabled
 *     [timestamp] - [userid].ldap - LDAP Authentication success
 *     [timestamp] - [userid] - authentication success
 *     [timestamp] - Process for user [userid] ended
 **/
//ExternalAuthenticator::setAuthDebug('/var/www/sites/debug-logs/wwwtasman_ss_debug.log');

/** 
 * Next to the debug log, we can also create a logfile for auditing purposes
 * In this file only logon attempts are registered
 *
 * Set this option to false to disable (default) or a filename. Make sure the
 * file is writable by the webserver process
 **/
ExternalAuthenticator::setAuditLogFile('/var/www/sites/debug-logs/wwwtasman_ss_audit.log');

/**
 * Silverstripe also has an internal table for auditing. You can enable or 
 * disable (default) usage of it. It works in parallel with setAuditLogFile
 **/
//ExternalAuthenticator::setAuditLogSStripe(true);

/** 
 * Create your authentication source
 * The first parameter is the Source ID. Set this to something you deem
 *     approriate to this source. It must be unique among all authentication 
 *     sources, may not contain special characters or spaces and must be 
 *     shorter that 50 characters
 * The second parameters is the type of server.
 *     At the moment LDAP, FTP, IMAP and HTTP are supported
 * The third parameter is a nice name for this source, to be showed in 
 *     drop-down form fields to choose the source
 *
 * You can create multiple sources with different of same types
 **/
ExternalAuthenticator::createSource('tdcldap','LDAP','TDC Logon');

/**
 * On login, users can choose the authentication source they want, or all
 * sources can be checked in sequence till success (or failure)
 * In this is set to true, the source selection box on the login page
 * disappears. So you might want to set this to true if you have only one
 * source.
 *
 * WARNING: If you set this to true, accounts from the different sources can
 *          eclipse eachother. The process stops at the first success.
 *
 * NOTE:    The order in which accounts are checked depends on the order of the
 *          createSource statements
 **/
ExternalAuthenticator::setAuthSequential(false);

/**
 * How do we call a user ID?
 * This string is informational and will appear on the login page
 */
ExternalAuthenticator::setAnchorDesc('Username');

/**
 * If the authentication source does not have a mechanism to prevent password
 * disctionary attacks, we can use SilverStripes lockout mechanism.
 * Configuration for this also has to be set in the global config. You can also
 * do it here by uncommenting the next statement and defining the number of
 * failed logins you allow
 **/
//Member::lock_out_after_incorrect_logins(3);

/**
 * The statement to enable lock checking for this source. This functionality is
 * enabled by default
 *
 * Do not use this together with ldap password expiration (for POSIX type 
 * accounts)
 **/
ExternalAuthenticator::setAuthSSLock('tdcldap',false);

/**
 * Hostname of the authentication server
 * you can specify it like a normal hostname or IP number.
 * If you use SSL or TLS, use the name matching the server certificate here  
 **/
/**
 * NOTE: The first parameter we set is the source ID. This is needed for all
 *       settings from hereon
 **/
ExternalAuthenticator::setAuthServer('tdcldap','192.168.101.3'); 

/**
 * Authentication server port, normally 389 for normal LDAP or 636 for LDAPS
 * 110 for POP3, 995 for POP3/SSL, 143 for IMAP and 993 for IMAPS
 * If you comment this out, it will use the default.
 **/
//ExternalAuthenticator::setAuthPort('tdcldap', 389); 

/**
 * You can use TLS or SSL for encryption, for the methods that support it.
 * Don't set it, or set it to TLS or SSL
 **/
//ExternalAuthenticator::setAuthEnc('tdcldap', 'tls');

/**
 * The DN where your users reside. Be as specific as possible
 * to prevent unexpected guests in the CMS, so typically your
 * directory's base dn (o=.... or dc=....,dc=....) augmented with
 * the ou where the accounts are
 *
 * It is possible to define multiple basedn's. The configuration should look 
 * like:
 *
 * ExternalAuthenticator::setOption('tdcldap', 'basedn', 
 *                                  array('ou=People,dc=silverstripe,dc=com',
 *                                        'ou=Admins,dc=silverstripe,dc=com'));
 *
 * If you configure multiple DNs, make sure the account is uniquely defined. 
 * The search will halt on the first hit, so a DN that is also valid in the
 * second basedn will be eclipsed. For instance a maill adress is a unique
 * identifier.
 * You could also use multiple sources to do this. The added benefit there is
 * that you could let users choose their own DN. Now user IDs don't have to be
 * unique across the DNs
 **/
ExternalAuthenticator::setOption('tdcldap', 'basedn', 'ou=Accounts,dc=tdc,dc=tdc,dc=govt,dc=nz');

/**
 * LDAP protocol version to use
 * If yor have enabled, the version must be 3. The default is 3
 **/
//ExternalAuthenticator::setOption('tdcldap', 'ldapversion', 3); 

/**
 * You can use any unique attribute to authenticate as, this
 * mail, or uid, or any other unique attribute. 
 *
 * SilverStripe will search the ldap for this attribute set to the ID entered
 * on the basedn and below 
 **/
ExternalAuthenticator::setOption('tdcldap', 'attribute', 'sAMAccountName');

/**
 * You may specify extra search criteria for the user account (not mandatory)
 * this allows you to set some additional rules for the useraccount. You
 * can for instance specify the object class to only let users with the 
 * posixAccount object logon. You could also filter on gidNumber=[groupnr] 
 * (attribute from the posixAccount objectclass) to allow only users with
 * a certain primary group logon
 *
 * When the LDAP drivers searches for the account, all search criteria must be
 * matched. Wildcards (see example) are allowed.
 *
 * The criteria must be specified in the form of a named array
 **/
//ExternalAuthenticator::setOption('tdcldap', 'extra_attributes', array(
//                                 'objectclass' => 'posix*',
//                                 'gidNumber'   => 500,
//                                ));

/**
 * If your LDAP has POSIX style user accounts with shadow support
 * (your LDAP is probably also used to authenticate users on UNIX
 * boxes, you can set expiration to yes. That way, when a user
 * account expires, ha can also not login to silverstripe
 **/
//ExternalAuthenticator::setOption('tdcldap', 'passwd_expiration', true); 

/**
 * You have to possibility to auto create non existing users that do exist
 * within the LDAP database. Set the option below to the group name you want
 * to add the user to (case sensitive) or to false if users should not be
 * created automatically
 *
 * WARNING WARNING WARNING
 * If you do not have control over the external authentication source, you no
 * longer control who can log in. USE WITH CARE
 **/
ExternalAuthenticator::setAutoAdd('tdcldap', 'View Only');
//ExternalAuthenticator::setAutoAdd('tdcldap', false);

/**
 * When an account is auto created, the e-mail address must be added to the
 * user account. By default, the mail address is equal to the user account
 * If the setting below is configured, is will be 
 * [username]@[what's configured below]
 * If the e-mail address is stored in the directory, see further below to
 * get the mail address from the directory. This option will be ignored if the
 * driver is able to return the correct mail address
 **/
//ExternalAuthenticator::setDefaultDomain('tdcldap', 'tasman.govt.nz');

/**
 * If you have auto adding of users enabled, you can automatically set some
 * properties of their user accounts. The options below link the attribute 
 * names in your LDAP directory to parameters in the LDAP driver.
 **/
ExternalAuthenticator::setOption('tdcldap', 'firstname_attr', 'givenName');
ExternalAuthenticator::setOption('tdcldap', 'surname_attr', 'sn');
ExternalAuthenticator::setOption('tdcldap', 'email_attr', 'mail');

/**
  * If your directory doesn't support anonymous searches you can
  * specify an account below that will be used to search for the
  * attribute containing the user ID as (dn, passwd)
  **/
ExternalAuthenticator::setOption('tdcldap', 'bind_as','cn=SilverStripe Web_svc,ou=System Accounts,ou=Accounts,dc=tdc,dc=tdc,dc=govt,dc=nz'); 
ExternalAuthenticator::setOption('tdcldap', 'bind_pw','@@tNlq2p1'); 

?>