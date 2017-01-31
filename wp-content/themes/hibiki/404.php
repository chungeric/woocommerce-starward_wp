<section class="page page_not_found">
  <div class="container">
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
      <h1>Page not found</h1>
      <h2>404 Error</h2>
      <p><?php _e('Sorry the page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', 'roots'); ?></p>
			<h2>Please try the following:</h2>
			<p>You could go back to <a href="javascript: history.go(-1)">where you were</a> or head straight to our <a href="<?php echo home_url(); ?>">home page</a></p>
		</article>
  </div>
</section>
