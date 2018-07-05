<?php // Install:
// composer require automattic/woocommerce
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

// Setup:
require __DIR__ . '/../vendor/autoload.php';

use Automattic\WooCommerce\Client;


define('_baseUrl','YOUR_SITE_URL');


 $woocommerce = [
    'url'=>_baseUrl."",
    'consumerKey'=>'YOUR_WOOCOMMERCE_REST_API_CK_KEYs',
    'consumerSecret'=>'YOUR_WOOCOMMERCE_REST_API_CS_KEYs',
    'wp_api'=>true,
    'version'=>'wc/v2'
]; 

$wordpress = [
    'username'=>'YOUR_WORDPRESS_ADMIN_USERNAME',
    'password'=>'YOUR_WORDPRESS_ADMIN_PASSWORD'
];

define('_MaxRows','100');

/* //for connect database direct (OPTION)
define('_CFG_driver',"mysql");
define('_CFG_host',"localhost");
define('_CFG_user',"YOUR_DATABASE_USERNAME");
define('_CFG_pass', "YOUR_DATABASE_PASSWORD");
define('_CFG_db_name', "YOUR_DATABASE_NAME"); 

define('_CFG_connectInfo',"mysql:host="._CFG_host.";dbname="._CFG_db_name.";charset=utf8");  */

include_once (dirname(__FILE__)."/class_database.php");

include_once (dirname(__FILE__)."/class_wc.php");
include_once (dirname(__FILE__)."/class_product.php");


?>