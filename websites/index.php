<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
global $domain, $config, $lessGAPApi;
$domain = $_SERVER['HTTP_HOST'];
if(!is_dir('./domains.'.$domain)){
    $domain = 'test.com';
}
include './include.php';
load_config();
$lessGAPApi = new LessgapApi();
//include './themes/' . $config['theme'] . '/index.php';
get('/', './themes/' . $config['theme'] . '/page.php');
get('/$pageSlug', './themes/' . $config['theme'] . '/page.php');
get('/post/$postSlug', './themes/' . $config['theme'] . '/post.php');
get('/tag/$tagSlug', './themes/' . $config['theme'] . '/tag.php');
