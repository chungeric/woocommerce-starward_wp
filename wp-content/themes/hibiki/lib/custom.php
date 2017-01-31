<?php
// Custom Functions


// Allow SVG files to be uploaded via Media Library
function cc_mime_types($mimes) {
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');

/* ------------------------------------------------------------------------
  Birdbrain settings - Admin options page
------------------------------------------------------------------------ */
if( function_exists('acf_add_options_page') ) {

	acf_add_options_page(array(
		'page_title' 	=> 'Client Settings',
		'menu_title'	=> 'Client Settings',
		'menu_slug' 	=> 'clientsettings',
    'icon_url'    =>  get_template_directory_uri() . '/lib/img/bb.png',
		'capability'	=> 'edit_posts',
		'redirect'		=> true
	));

  acf_add_options_sub_page(array(
		'page_title' 	=> 'Contact Information',
		'menu_title' 	=> 'Contact Information',
		'parent_slug' 	=> 'clientsettings',
	));

  acf_add_options_sub_page(array(
		'page_title' 	=> 'SEO & Social',
		'menu_title' 	=> 'SEO & Social',
		'parent_slug' 	=> 'clientsettings',
	));

  acf_add_options_sub_page(array(
		'page_title' 	=> 'Admin Settings',
		'menu_title' 	=> 'Admin Settings',
		'parent_slug' 	=> 'clientsettings',
	));
}

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
    margin-bottom: 20px;
	}
	</style>
	";
}

/* ------------------------------------------------------------------------
	Google Map API Key
------------------------------------------------------------------------ */
if(function_exists(get_field)){
  if(get_field('google_map_api_key','option')) {
    $googleMapApikey = get_field('google_map_api_key','option');
  } else {
    $googleMapApiKey = '';
  }
  function my_acf_init() {
  	function_exists(acf_update_setting('google_api_key', $googleMapApikey));
  }
  add_action('acf/init', 'my_acf_init');
}

/* ------------------------------------------------------------------------
	Maximum characters for Excerpt
------------------------------------------------------------------------ */
function excerpt_count_js(){

if ('page' != get_post_type()) {

      echo '<script>jQuery(document).ready(function(){
jQuery("#postexcerpt .handlediv").after("<div style=\"position:absolute;top:12px;right:34px;color:#666;\"><small>Excerpt length: </small><span id=\"excerpt_counter\"></span><span style=\"font-weight:bold; padding-left:7px;\">/ 180</span><small><span style=\"font-weight:bold; padding-left:7px;\">character(s).</span></small></div>");
     jQuery("span#excerpt_counter").text(jQuery("#excerpt").val().length);
     jQuery("#excerpt").keyup( function() {
         if(jQuery(this).val().length > 180){
            jQuery(this).val(jQuery(this).val().substr(0, 180));
        }
     jQuery("span#excerpt_counter").text(jQuery("#excerpt").val().length);
   });
});</script>';
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
