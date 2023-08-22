<?php
include "./lessgap/LessgapApi.php";
include "./router.php";
function load_config()
{
    global $config, $domain;
    $config = json_decode(file_get_contents('./domains/'.$domain . '/config.json'), true);
}

function config($key, $default = null)
{
    global $config;
    return $config[$key]??$default;
}
function page_url($slug)
{
    global $config;
    return $config['app_url'] . '/'. $slug;
}
function post_url($slug)
{
    global $config;
    return $config['app_url'] . '/post/'. $slug;
}
function tag_url($slug)
{
    global $config;
    return $config['app_url'] . '/tag/'. $slug;
}

function theme_file($file)
{
    global $config;
    return $config['app_url'] . '/themes/' . $config['theme'] . '/' . $file;
}


