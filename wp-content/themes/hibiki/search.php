<section class="page">
  <div class="container">
    <h1><?php printf(	__('Search Results for: <br><span>%s</span>', 'frontier'), get_search_query() ); ?></h1>
    <?php while (have_posts()) : the_post(); ?>
      <article <?php post_class() ?> id="post-<?php the_ID(); ?>">
        <div class="content">
          <h2><?php the_title(); ?></h2>
          <?php the_excerpt(); ?>
          <?php wp_link_pages(array('before' => '<nav class="pagination">', 'after' => '</nav>')); ?>
        </div>
      </article>
      <aside class="<?php echo roots_sidebar_class(); ?>">
        <?php get_template_part('templates/sidebar'); ?>
      </aside>
    <?php endwhile; ?>
  </div>
</section>
