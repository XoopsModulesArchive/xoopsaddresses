<?php
// $Id: modinfo.php,v 1.12 2003/04/11 15:20:05 okazu Exp $
// Module Info

// The name of this module
define('_MI_MYADRESSES_NAME', 'Addresses');

// A brief description of this module
define('_MI_MYADRESSES_DESC', 'Creates an addresses section where users can search/submit/rate various addresses.');

// Names of blocks for this module (Not all module has blocks)
define('_MI_MYADRESSES_BNAME1', 'Recent Addresses');
define('_MI_MYADRESSES_BNAME2', 'Top Addresses');

// Sub menu titles
define('_MI_MYADRESSES_SMNAME1', 'Submit');
define('_MI_MYADRESSES_SMNAME2', 'Popular');
define('_MI_MYADRESSES_SMNAME3', 'Top Rated');

// Names of admin menu items
define('_MI_MYADRESSES_ADMENU2', 'Add/Edit Addresses/Categories');
define('_MI_MYADRESSES_ADMENU3', 'Submitted Addresses');
define('_MI_MYADRESSES_ADMENU4', 'Broken Addresses');
define('_MI_MYADRESSES_ADMENU5', 'Modified Addresses');
define('_MI_MYADRESSES_ADMENU6', 'Addreslink Checker');

// Title of config items
define('_MI_MYADRESSES_POPULAR', 'Select the number of views for addresses to be marked as popular');
define('_MI_MYADRESSES_NEWLINKS', 'Select the maximum number of new addresses displayed on top page');
define('_MI_MYADRESSES_PERPAGE', 'Select the maximum number of addresses displayed in each page');
define('_MI_MYADRESSES_USESHOTS', 'Select yes to display banner/logo images for each address');
//define('_MI_MYADRESSES_USEFRAMES', 'Would you like to display the linked address withing a frame?');
define('_MI_MYADRESSES_SHOTWIDTH', 'Maximum allowed width of each banner/logo image');
define('_MI_MYADRESSES_ANONPOST', 'Allow anonymous users to post addresses?');
define('_MI_MYADRESSES_AUTOAPPROVE', 'Auto approve new adresses without admin intervention?');

// Description of each config items
define('_MI_MYADRESSES_POPULARDSC', '');
define('_MI_MYADRESSES_NEWLINKSDSC', '');
define('_MI_MYADRESSES_PERPAGEDSC', '');
define('_MI_MYADRESSES_USEFRAMEDSC', '');
define('_MI_MYADRESSES_USESHOTSDSC', '');
define('_MI_MYADRESSES_SHOTWIDTHDSC', '');
define('_MI_MYADRESSES_AUTOAPPROVEDSC', '');

// Text for notifications

define('_MI_MYADRESSES_GLOBAL_NOTIFY', 'Global');
define('_MI_MYADRESSES_GLOBAL_NOTIFYDSC', 'Global addresses notification options.');

define('_MI_MYADRESSES_CATEGORY_NOTIFY', 'Category');
define('_MI_MYADRESSES_CATEGORY_NOTIFYDSC', 'Notification options that apply to the current address category.');

define('_MI_MYADRESSES_LINK_NOTIFY', 'Address');
define('_MI_MYADRESSES_LINK_NOTIFYDSC', 'Notification options that aply to the current adsress.');

define('_MI_MYADRESSES_GLOBAL_NEWCATEGORY_NOTIFY', 'New Category');
define('_MI_MYADRESSES_GLOBAL_NEWCATEGORY_NOTIFYCAP', 'Notify me when a new address category is created.');
define('_MI_MYADRESSES_GLOBAL_NEWCATEGORY_NOTIFYDSC', 'Receive notification when a new address category is created.');
define('_MI_MYADRESSES_GLOBAL_NEWCATEGORY_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : New address category');

define('_MI_MYADRESSES_GLOBAL_LINKMODIFY_NOTIFY', 'Modify Address Requested');
define('_MI_MYADRESSES_GLOBAL_LINKMODIFY_NOTIFYCAP', 'Notify me of any address modification request.');
define('_MI_MYADRESSES_GLOBAL_LINKMODIFY_NOTIFYDSC', 'Receive notification when any address modification request is submitted.');
define('_MI_MYADRESSES_GLOBAL_LINKMODIFY_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : Address Modification Requested');

define('_MI_MYADRESSES_GLOBAL_LINKBROKEN_NOTIFY', 'Broken Addresslink Submitted');
define('_MI_MYADRESSES_GLOBAL_LINKBROKEN_NOTIFYCAP', 'Notify me of any broken addresslink report.');
define('_MI_MYADRESSES_GLOBAL_LINKBROKEN_NOTIFYDSC', 'Receive notification when any broken addresslink report is submitted.');
define('_MI_MYADRESSES_GLOBAL_LINKBROKEN_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : Broken Addresslink Reported');

define('_MI_MYADRESSES_GLOBAL_LINKSUBMIT_NOTIFY', 'New Address Submitted');
define('_MI_MYADRESSES_GLOBAL_LINKSUBMIT_NOTIFYCAP', 'Notify me when any new address is submitted (awaiting approval).');
define('_MI_MYADRESSES_GLOBAL_LINKSUBMIT_NOTIFYDSC', 'Receive notification when any new address is submitted (awaiting approval).');
define('_MI_MYADRESSES_GLOBAL_LINKSUBMIT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : New address submitted');

define('_MI_MYADRESSES_GLOBAL_NEWLINK_NOTIFY', 'New Address');
define('_MI_MYADRESSES_GLOBAL_NEWLINK_NOTIFYCAP', 'Notify me when any new address is posted.');
define('_MI_MYADRESSES_GLOBAL_NEWLINK_NOTIFYDSC', 'Receive notification when any new address is posted.');
define('_MI_MYADRESSES_GLOBAL_NEWLINK_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : New address');

define('_MI_MYADRESSES_CATEGORY_LINKSUBMIT_NOTIFY', 'New address Submitted');
define('_MI_MYADRESSES_CATEGORY_LINKSUBMIT_NOTIFYCAP', 'Notify me when a new address is submitted (awaiting approval) to the current category.');
define('_MI_MYADRESSES_CATEGORY_LINKSUBMIT_NOTIFYDSC', 'Receive notification when a new address is submitted (awaiting approval) to the current category.');
define('_MI_MYADRESSES_CATEGORY_LINKSUBMIT_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : New address submitted in category');

define('_MI_MYADRESSES_CATEGORY_NEWLINK_NOTIFY', 'New Address');
define('_MI_MYADRESSES_CATEGORY_NEWLINK_NOTIFYCAP', 'Notify me when a new address is posted to the current category.');
define('_MI_MYADRESSES_CATEGORY_NEWLINK_NOTIFYDSC', 'Receive notification when a new address is posted to the current category.');
define('_MI_MYADRESSES_CATEGORY_NEWLINK_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : New address in category');

define('_MI_MYADRESSES_LINK_APPROVE_NOTIFY', 'Address Approved');
define('_MI_MYADRESSES_LINK_APPROVE_NOTIFYCAP', 'Notify me when this address is approved.');
define('_MI_MYADRESSES_LINK_APPROVE_NOTIFYDSC', 'Receive notification when this address is approved.');
define('_MI_MYADRESSES_LINK_APPROVE_NOTIFYSBJ', '[{X_SITENAME}] {X_MODULE} auto-notify : Address approved');
