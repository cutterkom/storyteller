<?php get_header(); ?>

<section id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
		<?php 
		if (have_posts()) {
			while (have_posts()) {
				the_post(); 
				get_template_part( 'content', get_post_format() ); 
			}
		}
		?>

	</main><!-- #main -->
</section><!-- #primary -->
<?php get_footer(); ?>