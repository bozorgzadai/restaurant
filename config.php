<?
if (!defined('test')) { echo "Forbidden Request"; exit; }

global $config;
$config['db']['host'] = 'localhost';
$config['db']['user'] = 'root';
$config['db']['pass'] = '';
$config['db']['name'] = 'restaurant';

$config['lang'] = 'fa';

$config['salt'] = 'suya9s8ydaiu987vqo28bv9q87B87VPq7E98QVB';
$config['base'] = '/restaurant-v2';
$config['route'] = array(
  '/home' => '/page/home',
  '/register' => '/user/register',
  '/signup' => '/user/register',
  '/contactUs' => '/page/contactUs',
  '/menu' => '/page/menu',
  '/login' => '/user/login',
  '/خانه' => '/page/home',
  '/ثبت نام' => '/user/register',
  '/تماس با ما' => '/page/contactUs',
  '/منو' => '/page/menu',
  '/ورود' => '/user/login',
);

$config['zarinpal']['merchantId'] = '51d7d8ce-32f4-48bd-a8a2-3f8a5ee8a9d4';