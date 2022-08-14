<?php
date_default_timezone_set("asia/ho_chi_minh");
$root = realpath(dirname(__FILE__));
$root = str_replace("\\", '/', $root);
$domain = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';
$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)) || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'))
    ? "https://" : "http://";
$url = $protocol . $domain;
$domain_name = 'all-star.tk';

define('ASSET_VERSION', '1.0');
define('BASE_URL', $url . '/');
define('DOMAIN_PLAY', $url . '/');
define('BASE_ADMIN_URL', BASE_URL . "admin/");
define('MEDIA_NAME', "picture/"); //Tên đường dẫn lưu media
define('MEDIA_PATH', str_replace('\\', '/', $root . DIRECTORY_SEPARATOR . MEDIA_NAME)); //Đường dẫn lưu media
define('MEDIA_HIDE_FOLDER', 'mcith|thumb');
define('MEDIA_URL', $protocol . $domain_name . DIRECTORY_SEPARATOR . MEDIA_NAME);
define('API_DATACENTER', "https://dataxoso.webest.asia/");
define('TEMPLATES_ASSETS', BASE_URL . 'public/');

define('MAINTAIN_MODE', FALSE); //Bảo trì      ets 

define('DEBUG_MODE', FALSE);
define('CACHE_MODE', TRUE);
define('CACHE_FILE_MODE', FALSE);
define('CACHE_ADAPTER', 'file');
define('CACHE_PREFIX_NAME', 'XSTVT_');

define('DB_DEFAULT_HOST', '95.111.202.146'); //DB HOST
define('DB_DEFAULT_USER', 'thuc_db'); //DB USER
define('DB_DEFAULT_PASSWORD', 'thuc_db123'); //DB PASSWORD
define('DB_DEFAULT_NAME', 'thuc_db'); //DB NAME

define('CACHE_TIMEOUT_LOGIN', 1800);

//Config zalo
define('ZALO_APP_ID_CFG', '');
define('ZALO_APP_SECRET_KEY_CFG', '');
define('ZALO_CAL_BACK', BASE_URL . 'auth/loginzalo');

//Config zalo
define('API_KEY', '');

define('FB_API', '');
define('FB_SECRET', '');
define('FB_VER', 'v2.9');

define('GG_API', '');
define('GG_SECRET', '');
define('GG_KEY', ''); //AIzaSyAhR8OG9cUL1jDfAAc6i35nt5Ki1ZJnykA
define('GG_CAPTCHA_MODE', FALSE);
define('GG_CAPTCHA_SITE_KEY', '');
define('GG_CAPTCHA_SECRET_KEY', '');
