<section class="page">
  <div class="container">
    <?php while (have_posts()) : the_post(); ?>
      <article <?php post_class() ?> id="post-<?php the_ID(); ?>">
        <div class="content">
          <h1><?php the_title(); ?></h1>
          <?php the_content(); ?>
          <?php wp_link_pages(array('before' => '<nav class="pagination">', 'after' => '</nav>')); ?>
        </div>
      </article>
      <aside class="<?php echo roots_sidebar_class(); ?>">
        <?php get_template_part('templates/sidebar'); ?>
      </aside>
    <?php endwhile; ?>
  </div>
</section>
