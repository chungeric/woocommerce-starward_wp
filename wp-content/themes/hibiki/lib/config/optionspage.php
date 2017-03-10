<?php
  if( function_exists('acf_add_options_page') ) {
  	acf_add_options_page(array(
  		'page_title' 	=> get_bloginfo('name'),
  		'menu_title'	=> get_bloginfo('name'),
  		'menu_slug' 	=> 'settings',
      'icon_url'    =>  get_template_directory_uri() . '/lib/img/bb.png',
  		'capability'	=> 'edit_posts',
  		'redirect'		=> true
  	));
    acf_add_options_sub_page(array(
  		'page_title' 	=> 'Settings',
  		'menu_title' 	=> 'Settings',
  		'parent_slug' 	=> 'settings',
  	));
  }
?>
