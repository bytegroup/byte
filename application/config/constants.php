<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


define("ABS_SERVER_PATH",rtrim($_SERVER['DOCUMENT_ROOT'], '/').'/');

define("SITE_FOLDER","ocl-backoffice/");
define("SQL_LOG_FOLDER","_sqllog/");

define('TEMPLATES_FOLDER', 'templates/');
define('SITE_TEMPLATES', 'site_templates/');
define('MAIN_TEMPLATE_FILE', 'main_layout.php');

define('USERGROUP_MENU_FOLDER', 'user_group_menu/');

define('ADMIN_FOLDER', 'site-admin/');
define('IT_MODULE_FOLDER', 'it-inventory/');
define('ASSETS_FOLDER', 'assets/');
define("DB_BACKUP_FOLDER","back_up_db_folder/");

define("CACHE_FOLDER","_cache/");

define("DROPDOWN_CACHE_FOLDER","dropdown/");
define("TEMPORARY_FOLDER","temp/");

define("PHOTOS_FOLDER",'photos/');
define("PROFILE_IMG","assets/uploads/files/");

/*** Table Style***/
define("TABLE_THEME","datatables");
/*** user groups table value ***/
define("SITE_USER_LEVEL",1);
define("SUBADMIN_LEVEL",8);
define("ADMIN_LEVEL",9);

/*** Prefix ***/
define("DB_PREFIX", "ocl_");
define("TBL_ADMINS", DB_PREFIX."admins");
define("TBL_VENDORS_CONTACTS", DB_PREFIX."vendors_contacts");
define("TBL_USER_GROUP", DB_PREFIX."user_group");
define("TBL_USERS", DB_PREFIX."users");
define("TBL_MESSAGES", DB_PREFIX."messages");
define("TBL_PHONEBOOK", DB_PREFIX."phonebook");
define("TBL_DEPARTMENTS", DB_PREFIX."departments");
define("TBL_COMPANIES", DB_PREFIX."companies");
define("TBL_DESIGNATIONS", DB_PREFIX."designations");

define("TBL_SITE_USERS", DB_PREFIX."site_users");

define("TBL_UNITS", DB_PREFIX."units");
define("TBL_ITEM_TYPES", DB_PREFIX."item_types");
define("TBL_VENDORS", DB_PREFIX."vendors");
define("TBL_CATEGORIES", DB_PREFIX."categories");
define("TBL_ITEMS_MASTER", DB_PREFIX."items_master");
define("TBL_ITEMS_MASTER_SUB", DB_PREFIX."items_master_sub");
define("TBL_ORGANIZATIONS", DB_PREFIX."organizations");
define("TBL_REQUISITIONS", DB_PREFIX."requisitions");
define("TBL_REQUISITIONS_DETAIL", DB_PREFIX."requisitions_detail");
define("TBL_QUOTATIONS", DB_PREFIX."quotations");
define("TBL_QUOTATIONS_DETAIL", DB_PREFIX."quotations_detail");
define("TBL_COUNTER", DB_PREFIX."counter");

define("TBL_RECEIVES", DB_PREFIX."receives");
define("TBL_RECEIVES_DETAIL", DB_PREFIX."receives_detail");

define("TBL_STOCK", DB_PREFIX."stock");
define("TBL_STOCK_DETAIL", DB_PREFIX."stock_detail");
define("TBL_STOCK_DETAIL_SUB", DB_PREFIX."stock_detail_sub");
define("TBL_ISSUES", DB_PREFIX."issues");
define("TBL_ISSUE_DETAIL", DB_PREFIX."issue_details");
define("TBL_ISSUE_UNCOUNTABLE_DETAIL", DB_PREFIX."issue_uncountable_details");
define("TBL_ISSUES_SUB", DB_PREFIX."issues_sub");

define("TBL_DAMAGE", DB_PREFIX."damage");
define("TBL_DAMAGE_DETAIL", DB_PREFIX.'damage_details');

define("TBL_REPAIR_TYPE", DB_PREFIX."repair_types");
define("TBL_REPAIR", DB_PREFIX."repair");
define("TBL_REPAIR_STATUS", DB_PREFIX."repair_status");

define("TBL_BUDGET", DB_PREFIX."budget");
define("TBL_BUDGET_DETAIL", DB_PREFIX."budget_detail");
define("TBL_BUDGET_HEAD", DB_PREFIX."budget_head");

define("TBL_BILL", DB_PREFIX."bill");
define("TBL_SERVICE_AGREEMENTS", DB_PREFIX.'service_agreements');

define("TBL_GUEST_USERS", DB_PREFIX."active_guests");
define("TBL_ACTIVE_USERS", DB_PREFIX."active_users");
define("TBL_ADMIN_SETTINGS", DB_PREFIX."admin_settings");
define("TBL_APPROVE_QUOTATION", DB_PREFIX."approve_quotation");

define('TBL_DAMAGE_SOLD', DB_PREFIX.'damage_sold');
define("TBL_ITEM_TRANSFER", DB_PREFIX.'item_transfer');
define("TBL_ITEM_TRANSFER_DETAILS", DB_PREFIX.'item_transfer_details');

define("TBL_DISPOSAL", DB_PREFIX."disposal");
define("TBL_DISPOSAL_DETAILS", DB_PREFIX."disposal_details");

/***************************************************************************************/
define('ICONS', 'assets/images/icons/');

/********** For Report *******************************************/
define('REPORT_FOLDER', 'report/');
define('REPORT_TEMPLATES', 'report_templates/');
define("REPORT_LAYOUT", REPORT_TEMPLATES."report_layout.php");
define("REPORT_BODY", 'reports_body/');
define("REPORT_MODELS", "report_models/");
define("REPORT_ASSETS", ASSETS_FOLDER."report_assets/");

/* End of file constants.php */
/* Location: ./application/config/constants.php */