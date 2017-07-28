<?php
  if( function_exists('acf_add_options_page') ) {
    acf_add_options_sub_page(array(
      'page_title' 	=> 'Web App Settings',
      'menu_title' 	=> 'Web App',
      'parent_slug' 	=> 'options-general.php',
      'capability'	=> 'edit_posts',
    ));
  }

  //Flush Redis Button

  function get_acf_option($atts) {

    $atts = shortcode_atts(
  		array(
  			'option' => '',
  		),
  		$atts
  	);
    return get_field($atts['option'] , 'option');

  }
  add_shortcode('get_acf_option','get_acf_option');

  /*
  *  ACF Field Class
  *
  *  All the logic for this field type
  *
  *  @class 		acf_field_html
  *  @extends		acf_field
  *  @package		ACF
  *  @subpackage	Fields
  */

  if( ! class_exists('acf_field_html') ) :

  class acf_field_html extends acf_field {

  	function __construct() {

  		// vars
  		$this->name = 'html';
  		$this->label = __("HTML+Shortcodes",'acf');
  		$this->category = 'layout';
  		$this->defaults = array(
  			'html'		=> '',
  			'esc_html'		=> 0,
  			'new_lines'		=> 'wpautop',
  		);


  		// do not delete!
      	parent::__construct();
  	}

  	function render_field( $field ) {

  		// vars
  		$m = $field['html'];


  		// wptexturize (improves "quotes")
  		$m = wptexturize( $m );


  		// esc_html
  		if( $field['esc_html'] ) {

  			$m = esc_html( $m );

  		}


  		// new lines
  		if( $field['new_lines'] == 'wpautop' ) {

  			$m = wpautop($m);

  		} elseif( $field['new_lines'] == 'br' ) {

  			$m = nl2br($m);

  		}


  		// return
  		echo do_shortcode($m);

  	}

  	function render_field_settings( $field ) {

  		// default_value
  		acf_render_field_setting( $field, array(
  			'label'			=> __('HTML','acf'),
  			'instructions'	=> '',
  			'type'			=> 'textarea',
  			'name'			=> 'html',
  		));


  		// formatting
  		acf_render_field_setting( $field, array(
  			'label'			=> __('New Lines','acf'),
  			'instructions'	=> __('Controls how new lines are rendered','acf'),
  			'type'			=> 'select',
  			'name'			=> 'new_lines',
  			'choices'		=> array(
  				'wpautop'		=> __("Automatically add paragraphs",'acf'),
  				'br'			=> __("Automatically add &lt;br&gt;",'acf'),
  				''				=> __("No Formatting",'acf')
  			)
  		));


  		// HTML
  		acf_render_field_setting( $field, array(
  			'label'			=> __('Escape HTML','acf'),
  			'instructions'	=> __('Allow HTML markup to display as visible text instead of rendering','acf'),
  			'name'			=> 'esc_html',
  			'type'			=> 'true_false',
  			'ui'			=> 1,
  		));

  	}

  	function translate_field( $field ) {

  		// translate
  		$field['html'] = acf_translate( $field['html'] );


  		// return
  		return $field;

  	}


  	function load_field( $field ) {

  		// remove name to avoid caching issue
  		$field['name'] = '';


  		// remove required to avoid JS issues
  		$field['required'] = 0;


  		// set value other than 'null' to avoid ACF loading / caching issue
  		$field['value'] = false;


  		// return
  		return $field;

  	}

  }


  // initialize
  acf_register_field_type( new acf_field_html() );

  endif; // class_exists check
?>
