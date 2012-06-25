<?php get_header(); ?>

	<div id="content">
		<div class="padder double-border">

		<?php do_action( 'bp_before_archive' ) ?>

		<div class="page" id="blog-archives" role="main">

			<?php /* ?><h3 class="pagetitle"><?php printf( __( 'You are browsing the archive for %1$s.', 'buddypress' ), wp_title( false, false ) ); ?></h3><?php */ ?>

			<?php if ( have_posts() ) : ?>

				<?php $i = 0; ?>

				<?php while (have_posts()) : the_post(); ?>

					<?php do_action( 'bp_before_blog_post' ) ?>
					
					
					
						<?php 
							$ece_tv_id = get_the_ID();
							$video_url = get_post_meta($ece_tv_id, 'enlace-video-youtube', true);								
						?>
						
						
						<div class="bloque-ele-tv<?php if($i%2 != 0){echo ' margin-left15';} ?>">
							<?php  
							echo '<div class="imagen-ele-tv"><a href="'.get_permalink().'">';
								echo do_shortcode('[youtube='.$video_url.'&w=325&h=183]');
							echo '</a></div>';
							?>
							<h2 class="title-bloque2 font-normal"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							<?php the_excerpt(); ?>
						</div>
						<?php $i++; ?>
						
					
					

					<?php do_action( 'bp_after_blog_post' ) ?>

				<?php endwhile; ?>

				<?php //bp_dtheme_content_nav( 'nav-below' ); ?>
				<?php wp_pagenavi(); ?>

			<?php else : ?>

				<h2 class="center"><?php _e( 'Not Found', 'buddypress' ) ?></h2>
				<?php get_search_form() ?>

			<?php endif; ?>

		</div>

		<?php do_action( 'bp_after_archive' ) ?>

		</div><!-- .padder -->
	</div><!-- #content -->

	<?php get_sidebar() ?>

<?php get_footer(); ?>