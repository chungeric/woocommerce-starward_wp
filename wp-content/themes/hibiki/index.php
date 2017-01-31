<section class="blog">
	<div class="container">
		<div class="blog_items">
			<?php while ( have_posts() ) : the_post();?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
          <span class="categories"><?php the_category(' ') ?></span>
          <?php if ( has_post_thumbnail() ) { ?>
						<div class="featured_image">
							<?php the_post_thumbnail('medium'); ?>
						</div>
					<?php }?>
					<h2><a href="<?php echo the_permalink(); ?>"><?php echo get_the_title(); ?></a></h2>
					<?php the_excerpt(); ?>
          <div class="post_meta">
						<span><?php the_date('M d, Y'); ?></span>
					</div>
				</article>
			<?php endwhile; ?>
			<?php the_posts_pagination(); ?>
		</div>
		<aside>
			<?php get_template_part('templates/sidebar', 'categories'); ?>
		</aside>
	</div>
</section>
