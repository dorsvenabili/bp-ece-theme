<?php
/**
 * The template for displaying lists of events
 *
 */

?>
<?php get_header(); ?>

	<div id="content">
		<div class="padder">

		<?php do_action( 'bp_before_archive' ) ?>

		<div class="page" id="blog-archives" role="main">

			<?php if($_SERVER["REQUEST_URI"] != '/agenda/'): ?>
			<h3 class="pagetitle"><?php echo 'Agenda de '; if(eo_get_venue_name()): eo_venue_name(); endif; ?></h3>
			
			<div class="ficha-venue">
				
				<h4>Ficha</h4>
				<div class="float-left col-left">
					<?php 
					$address = eo_get_venue_address();
				
					echo '<p>Dirección: '.$address['address'].'</p>';
					echo '<p>Código postal: '.$address['postcode'].'</p>'; 
					echo '<p>Población: '.eo_venue_city().'</p>'; 
					echo '<p>Provincia: '.eo_venue_county().'</p>'; 
					echo '<p>Teléfono de información: '.eo_venue_telInf().'</p>'; 
					echo '<p>Teléfono de taquilla: '.eo_venue_telTaq().'</p>'; 
					echo '<p>Horario de taquilla: '.eo_venue_scheduleTaq().'</p>'; 
					echo '<p>Email: '.eo_venue_email().'</p>'; 
					echo '<p>Web: '.eo_venue_web().'</p>'; 
					echo '<p>Facebook: '.eo_venue_facebook().'</p>'; 
					echo '<p>Twitter: '.eo_venue_twitter().'</p>';
					
					?>
				</div>
				<div class="float-right col-right">
					<?php
						echo '<p>Cómo llegar: '.eo_venue_how().'</p>';
						echo do_shortcode('[eo_venue_map width="100%"]');
					?>
					<?php //Get the description and print it if it exists
					$venue_description =eo_get_venue_description();

					if(!empty($venue_description)){?>
						<!---- If the venue has a description display it-->
						<p>
							<?php echo $venue_description; ?>
						</p>
					<?php } ?>
				</div>		
			</div>
			<?php else: ?>
				<h3 class="pagetitle">Agenda</h3>
			<?php endif; ?>
			

			<?php if ( have_posts() ) : ?>

				<?php bp_dtheme_content_nav( 'nav-above' ); ?>

				<?php while (have_posts()) : the_post(); ?>

					<?php do_action( 'bp_before_blog_post' ) ?>

					<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

						<div class="author-box">
							<?php if(has_post_thumbnail()):
										the_post_thumbnail( array(90,90) );
								endif; 
							?>
						</div>

						<div class="post-content">
							<h2 class="posttitle"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e( 'Permanent Link to', 'buddypress' ) ?> <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>

							<p class="date"><?php printf( __( '%1$s <span>in %2$s</span>', 'buddypress' ), get_the_date(), get_the_category_list( ', ' ) ); ?></p>

							<div class="entry">
								<?php //the_excerpt( __( 'Read the rest of this entry &rarr;', 'buddypress' ) ); ?>
								<?php //wp_link_pages( array( 'before' => '<div class="page-link"><p>' . __( 'Pages: ', 'buddypress' ), 'after' => '</p></div>', 'next_or_number' => 'number' ) ); ?>
							</div>
							
							<div class="entry-meta">
								<!-- Output the date of the occurrence-->
								<?php if(eo_is_allday()):?>
									<!-- Event is an all day event -->
									<?php eo_the_start('d F Y'); ?> 
								<?php else: ?>
									<!-- Event is not an all day event - display time -->
									<?php eo_the_start('d F Y G:ia'); ?> 
								<?php endif; ?>

								<!-- If the event has a venue saved, display this-->
								<?php if(eo_get_venue_name()):?>
									<?php _e('at','eventorganiser');?> <a href="<?php eo_venue_link();?>"><?php eo_venue_name(); ?></a>
								<?php endif;?>
							</div><!-- .entry-meta -->

							<p class="postmetadata"><?php the_tags( '<span class="tags">' . __( 'Tags: ', 'buddypress' ), ', ', '</span>' ); ?> <span class="comments"><?php comments_popup_link( __( 'No Comments &#187;', 'buddypress' ), __( '1 Comment &#187;', 'buddypress' ), __( '% Comments &#187;', 'buddypress' ) ); ?></span></p>
						</div>

					</div>

					<?php do_action( 'bp_after_blog_post' ) ?>

				<?php endwhile; ?>

				<?php bp_dtheme_content_nav( 'nav-below' ); ?>

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
