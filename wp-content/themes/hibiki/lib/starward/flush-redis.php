<?php
//On save flush web app redis server
add_action( 'save_post', 'devmode_save_post' );

function devmode_save_post() {

  if (function_exists('get_field') && get_field('developmentMode','option') == true) {

    $url =  get_field('redis_url','option') . '/api/flushredis';
    $response = wp_remote_get($url);

  }

}
?>
