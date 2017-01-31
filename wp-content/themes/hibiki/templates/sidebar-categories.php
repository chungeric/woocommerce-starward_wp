<div class="blog_categories">
  <h3>Categories</h3>
	<ul>
		<?php
		    $args = array(
    			'show_option_all'    => '',
    			'orderby'            => 'name',
    			'order'              => 'DESC',
    			'style'              => 'list',
    			'show_count'         => 0,
    			'hide_empty'         => 1,
    			'use_desc_for_title' => false,
    			'child_of'           => 0,
    			'feed'               => '',
    			'feed_type'          => '',
    			'feed_image'         => '',
    			'exclude'            => '1',
    			'exclude_tree'       => '',
    			'include'            => '',
    			'hierarchical'       => 1,
    			'title_li'           => 0,
    			'show_option_none'   => __( '' ),
    			'number'             => null,
    			'echo'               => 1,
    			'depth'              => 0,
    			'current_category'   => 0,
    			'pad_counts'         => 0,
    			'taxonomy'           => 'category',
    			'walker'             => null
		    );
		    wp_list_categories( $args );
		?>
	</ul>
</div>
