<?php
/* ------------------------------------------------------------------------
  Allow SVG files to be uploaded via Media Library
------------------------------------------------------------------------ */
function cc_mime_types($mimes) {
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');
/* ------------------------------------------------------------------------
  Change Wordpress admin login logo
------------------------------------------------------------------------ */
add_action("login_head", "my_login_head");
function my_login_head() {
	echo "
	<style>
    body.login #login h1 a {
    background: url('".get_bloginfo('template_url')."/assets/img/birdbrain.svg') no-repeat scroll center top transparent;
    background-size: contain;
    height: 90px;
    width: 100%;
    margin-bottom: 40px;
	}
	</style>
	";
}
/* ------------------------------------------------------------------------
  Prepopulate name field within settings with the WP site name
------------------------------------------------------------------------ */
function fetch_client_name($field) {
   $field['default_value'] = get_bloginfo('name');
   return $field;
}
add_filter('acf/load_field/name=name', 'fetch_client_name');
/* ------------------------------------------------------------------------
	Maximum characters for Excerpt
------------------------------------------------------------------------ */
function excerpt_count_js(){
  if ('page' != get_post_type()) {
      echo
      '<script>jQuery(document).ready(function(){
        jQuery("#postexcerpt .handlediv").after("<div style=\"position:absolute;top:12px;right:34px;color:#666;\"><small>Excerpt length: </small><span id=\"excerpt_counter\"></span><span style=\"font-weight:bold; padding-left:7px;\">/ 180</span><small><span style=\"font-weight:bold; padding-left:7px;\">character(s).</span></small></div>");
        jQuery("span#excerpt_counter").text(jQuery("#excerpt").val().length);
        jQuery("#excerpt").keyup( function() {
           if(jQuery(this).val().length > 180){
              jQuery(this).val(jQuery(this).val().substr(0, 180));
          }
        jQuery("span#excerpt_counter").text(jQuery("#excerpt").val().length);
        });
      });</script>
    ';
  }
}
add_action( 'admin_head-post.php', 'excerpt_count_js');
add_action( 'admin_head-post-new.php', 'excerpt_count_js');

function new_excerpt_length($length) {
  return 20;
}
add_filter('excerpt_length', 'new_excerpt_length');
/* ------------------------------------------------------------------------
	TinyMCE - Add Formats dropdown
------------------------------------------------------------------------
function my_mce_before_init_insert_formats( $init_array ) {
  $style_formats = array(
    array(
      'title' => 'Button - Arrow Right (red)',
      'selector' => 'a',
      'classes' => 'icon-nav-large-arrow-right button cta-button',
    ),
    array(
      'title' => 'Button - Plus Symbol (red)',
      'selector' => 'a',
      'classes' => 'icon-nav-plus button cta-button',
    ),
  );
  $init_array['style_formats'] = json_encode( $style_formats );
  return $init_array;
}
add_filter( 'tiny_mce_before_init', 'my_mce_before_init_insert_formats' );*/
?>
