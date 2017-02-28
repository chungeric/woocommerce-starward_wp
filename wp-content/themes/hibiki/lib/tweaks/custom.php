<?php
// Custom Functions

// Add home or internal class to body tag
function body_classes($classes) {

  $classes[] = is_front_page() ? 'home' : 'internal';
  return $classes;

}
add_filter('body_class', 'body_classes', 10, 1);

// Allow SVG files to be uploaded via Media Library
function cc_mime_types($mimes) {
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');

// Remove Emoji Support
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

// Tweet
function get_tweet() {
require dirname( __FILE__ ) . '/twitter/tmhOAuth.php';
require dirname( __FILE__ ) . '/twitter/tmhUtilities.php';
$tmhOAuth = new tmhOAuth(array(
  'consumer_key'        => 'jJEOMbyUQq3PGL6dVYjcyWQqv',
  'consumer_secret'     => 'z6sMzBKE6yIoHjY8wUlYxr30ctzpN8OsRJdpXW986zVL2vJpiL',
  'user_token'          => '338781770-LimiozRWBNR5sljOM3oMpunIdkM5OOlnR9Imwprp',
  'user_secret'         => 'sRs92EI3bVQSnsWFW0F5tME5MEAh8Uj6dO76R1epz1HpP',
  'curl_ssl_verifypeer' => false
));
$code = $tmhOAuth->request('GET', $tmhOAuth->url('1.1/statuses/user_timeline'), array(
  'screen_name' => 'username',
  'count' => '1'
));
$response = $tmhOAuth->response['response'];
$tweets = json_decode($response, true);
foreach($tweets as $tweet):
  ?>
  <!-- Begin tweet -->
  <p class="tweet">
    <?php
    // Access as an object
    $tweetText = $tweet['text'];
    $tweetDate = strtotime($tweet['created_at']);
    //Convert urls to <a> links
    $tweetText = preg_replace('/https?:\/\/([a-z0-9_\.\-\+\&\!\#\~\/\,]+)/i', '<a href="http://$1" target="_blank" rel="nofollow">http://$1</a>', $tweetText);
    //Convert attags to twitter profiles in &lt;a&gt; links
    $tweetText = preg_replace('/@([a-z0-9_]+)/i', '<a href="http://twitter.com/$1" target="_blank" rel="nofollow">@$1</a>', $tweetText);
    //Convert hashtags to twitter searches in <a> links
    $tweetText = preg_replace('/#([A-Za-z0-9\/\.]*)/', '<a href="http://twitter.com/search?q=$1" target="_blank" rel="nofollow">#$1</a>', $tweetText);
    // Output
    echo $tweetText;
    ?>
  </p>
  <!-- End tweet -->
<?php
endforeach;
}

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
if( function_exists('get_field') && get_field('google_map_api_key','option') ) {
  $googleMapApikey = get_field('google_map_api_key','option');
} else {
  $googleMapApiKey = '';
}
function my_acf_init() {
	acf_update_setting('google_api_key', $googleMapApikey);
}
add_action('acf/init', 'my_acf_init');

/* ------------------------------------------------------------------------
  Gravity form - enable hide label
------------------------------------------------------------------------ */
add_filter("gform_enable_field_label_visibility_settings", "__return_true");

/* ------------------------------------------------------------------------
	Gravity form - go to anchor after form submit
------------------------------------------------------------------------ */
add_filter("gform_confirmation_anchor", create_function("","return true;"));

/* ------------------------------------------------------------------------
	Fix Gravity Form jQuery error
------------------------------------------------------------------------ */
function remove_head_scripts() {
  remove_action('wp_head', 'wp_print_scripts');
  remove_action('wp_head', 'wp_print_head_scripts', 9);
  remove_action('wp_head', 'wp_enqueue_scripts', 1);

  add_action('wp_footer', 'wp_print_scripts', 5);
  add_action('wp_footer', 'wp_enqueue_scripts', 5);
  add_action('wp_footer', 'wp_print_head_scripts', 5);
}
add_action( 'wp_enqueue_scripts', 'remove_head_scripts' );

add_filter('gform_init_scripts_footer', '__return_true');

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
