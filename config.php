<?php
/*---------------------------------------------------------------
 *                    TIMEZONE
  --------------------------------------------------------------- */
date_default_timezone_set("asia/ho_chi_minh");
/*---------------------------------------------------------------
 *                    DOMAIN
  --------------------------------------------------------------- */
$root = realpath(dirname(__FILE__));
$domain = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
if (in_array($domain, ["recipes.test"])) {
  $url = "http://";
} else {
  $url = "https://";
}
$root = str_replace("\\", '/', $root);
$url .= $domain;
/*---------------------------------------------------------------=----
 *                    CONFIG
  --------------------------------------------------------------- */
$helper = array('url', 'file', 'security', 'language', 'debug', 'images', 'menus', 'urls', 'datetime', 'data', 'admin/admin');
$library = array('database', 'session', 'form_validation', 'email', 'pagination', 'breadcrumbs');
/*---------------------------------------------------------------
 *                    DEFINE
  --------------------------------------------------------------- */
  define('ASSET_VERSION', '1.0');
define('BASE_URL', $url . '/');
define('BASE_ADMIN_URL', $url . '/admin/');
define('HELPER', $helper);
define('LIBRARY', $library);
define('MEDIA_NAME', "media/"); //Tên đường dẫn lưu media
define('MEDIA_PATH', $root . DIRECTORY_SEPARATOR . MEDIA_NAME); //Đường dẫn lưu media
define('MEDIA_URL', BASE_URL . "public/" . MEDIA_NAME); //Đường dẫn lưu media
define('VENDOR_PATH', "theme/"); //Đường dẫn lưu theme
define('TEMPLATE_ASSET', "/assets/admin/"); //Đường dẫn lưu theme admin
define('TEMPLATE_DEFAULT', "/assets/default/"); //Đường dẫn lưu theme default
/*---------------------------------------------------------------
 *                    SETUP PATH
 * public/PATH
 * views/PATH
  --------------------------------------------------------------- */

/*---------------------------------------------------------------
 *                    DATABASE
  --------------------------------------------------------------- */
  define('DB_DEFAULT_HOST', '95.111.202.146'); //DB HOST
  define('DB_DEFAULT_USER', 'thuc_db'); //DB USER
  define('DB_DEFAULT_PASSWORD', 'thuc_db123'); //DB PASSWORD
  define('DB_DEFAULT_NAME', 'thuc_db'); //DB NAME
/*---------------------------------------------------------------
 *                    DEBUG
  --------------------------------------------------------------- */
define('login_max_attempts', '10'); //Bảo trì
define('MAINTAIN_MODE', FALSE); //Bảo trì
define('DEBUG_MODE', false);
/*---------------------------------------------------------------
 *                    CACHE
  --------------------------------------------------------------- */
define('CACHE_MODE', TRUE);
define('CACHE_ADAPTER', 'file');
define('CACHE_PREFIX_NAME', 'MY_');
