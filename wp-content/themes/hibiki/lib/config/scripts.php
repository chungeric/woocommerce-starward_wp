<?php
/**
 * Scripts and stylesheets
*/

function roots_scripts() {
  // jQuery is loaded in header.php using the same method from HTML5 Boilerplate:
  // Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if offline
  // It's kept in the header instead of footer to avoid conflicts with plugins.
  if (!is_admin()) {
    //wp_enqueue_script( 'jquery' );
    //wp_enqueue_script( 'slick',     SITE_URL . '/assets/js/vendor/slick.min.js',            array('jquery'), '', true);
    //wp_enqueue_script( 'global',    SITE_URL . '/assets/js/global.min.js',                  array('jquery'), THEME_VERSION, true);
    //Styles
    //wp_enqueue_style( 'global-style', SITE_URL.'/assets/css/styles.min.css', '', THEME_VERSION );
  }
  if (is_single() && comments_open() && get_option('thread_comments')) {
    //wp_enqueue_script( 'comment-reply' );
  }

}

add_action('wp_enqueue_scripts', 'roots_scripts', 100);
