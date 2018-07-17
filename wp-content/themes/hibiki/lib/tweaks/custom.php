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
  Include ACF data in revisions API response
------------------------------------------------------------------------ */
function acf_revision_support($types)
{
    $types['revision'] = 'revision';
    return $types;
}
add_filter('acf/rest_api/types', 'acf_revision_support');
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

/* ------------------------------------------------------------------------
	Admin CSS
------------------------------------------------------------------------ */
function admin_style() {
  wp_enqueue_style('admin-styles', get_template_directory_uri().'/assets/css/admin.css');
}
add_action('admin_enqueue_scripts', 'admin_style');

//google api for ACF
add_filter('acf/settings/google_api_key', function () {
    return 'AIzaSyATFBMD0IRsvqxTHRfJWwQH2lKQ1dikQ10';
});

//GF hide field label option
add_filter( 'gform_enable_field_label_visibility_settings', '__return_true' );

/* ------------------------------------------------------------------------
  Filter WooCommerce Products Filter Request
------------------------------------------------------------------------ */
function filter_product_category_multiple_attributes( $query ) {
  if ($query->is_main_query()) {
    return;
  }
  // // Filter by multiple attributes and terms.
  foreach ( wc_get_attribute_taxonomy_names() as $attribute ) {
    if ( isset($_GET[$attribute]) ) {
  		$array = array(
  			'relation' 	 => 'AND'
  		);
  		foreach ( wc_get_attribute_taxonomy_names() as $attribute ) {
  			if ( isset($_GET[$attribute]) ) {
  				$array[] = array(
  					'taxonomy' => $attribute,
  					'field'    => 'term_id',
  					'terms'    => explode(',', $_GET[$attribute]),
  					'operator' => 'IN'
  				);
  			}
  		}
      $tax_query = $query->get( 'tax_query' );
      $tax_query[] = $array;
  		$query->set( 'tax_query', $tax_query );
  		break;
    }
  }
  return $query;
}
add_action( 'pre_get_posts', 'filter_product_category_multiple_attributes' );

/* ------------------------------------------------------------------------
  Manipulating the WordPress Product response
------------------------------------------------------------------------ */
function filter_woocommerce_rest_prepare_product_object( $response, $object, $request ) {
  if( empty( $response->data ) ) {
    return $response;
  }

  $attribute_taxonomies = wc_get_attribute_taxonomies();

  // Loop through the attributes on current product
  $attributes = $response->data['attributes'];
  foreach($attributes as $attrkey => $attribute) {

    /* ########################################################
      - Adding new swatch key to attribute response for color attributes,
        which holds the hex code for each swatch color option
    ######################################################## */
    // Get an array of attributes whose attribute type is color
    $color_type_attribute_taxonomies = array_filter($attribute_taxonomies, function($attribute_taxonomy) {
      return $attribute_taxonomy->attribute_type == 'color';
    });
    // Loop through the color type attributes
    foreach($color_type_attribute_taxonomies as $tax_object) {
      //Check if current attribute is a color type attribute
      if ($attribute['id'] == $tax_object->attribute_id) {
        // Get current attribute's options
        $options = $response->data['attributes'][$attrkey]['options'];
        // Get current attribute's terms
        $color_terms = get_terms('pa_' . $tax_object->attribute_name);
        foreach( $options as $option ) {
          foreach($color_terms as $term) {
            if ($term->name == $option) {
              // Add a new swatch with hex value for each color option
              $response->data['attributes'][$attrkey]['swatches'][$option] = get_term_meta( $term->term_id, 'product_attribute_color', true);
            }
          }
        }
      }
    }

    /* ########################################################
      - Adding attribute slug to the attribute response
      - Adding more detailed option data to the attribute options response
    ######################################################## */
    foreach($attribute_taxonomies as $attribute_taxonomy) {
      if ($attribute['id'] == $attribute_taxonomy->attribute_id) {

        /* Add slug to current attribute response */
        $response->data['attributes'][$attrkey]['slug'] = ('pa_' . $attribute_taxonomy->attribute_name);

        /* Replace default options data with detailed options data for current attribute */
        $options = $response->data['attributes'][$attrkey]['options'];
        $new_options = array();
        $attribute_terms = get_terms('pa_' . $attribute_taxonomy->attribute_name);

        foreach( $options as $option ) {
          foreach($attribute_terms as $attribute_term) {
            if ($attribute_term->name == $option) {
              $new_options[] = (object) [
                id => $attribute_term->term_id,
                name => $attribute_term->name,
                slug => $attribute_term->slug,
                taxonomy => $attribute_term->taxonomy,
                description => $attribute_term->description,
                count => $attribute_term->count
              ];
            }
          }
        }
        $response->data['attributes'][$attrkey]['options'] = $new_options;
      }
    }
  }

  return $response;
}
add_filter( 'woocommerce_rest_prepare_product_object', 'filter_woocommerce_rest_prepare_product_object', 10, 3 );

?>
