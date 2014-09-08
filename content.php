<?php 

/**
 * strytllr content template
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
		
	</div><!-- .entry-content -->
	<footer class="entry-footer">
		<div class="arrow-nav">
			<div class="prev "><?php previous_post_link('%link', '<div class="next-prev-button">&lt;</div>', TRUE); ?></div>    
			<div class="next"><?php next_post_link('%link', '<div class="next-prev-button">&gt;</div>', TRUE); ?></div>
		</div>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->