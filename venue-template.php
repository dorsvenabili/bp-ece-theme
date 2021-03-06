<?php
/**
 * The template for displaying the venue page
 *
**/

//Call the template header
get_header(); ?>

		<!-- This template follows the TwentyEleven theme-->
		<div id="primary">
		<div id="content" role="main">

			<?php if ( have_posts() ) : ?>

				<!---- Page header, display venue title-->
				<header class="page-header">	
				<h1 class="page-title"><?php
					printf( __( 'Events ataa: %s', 'eventorganiser' ), '<span>' .eo_get_venue_name(). '</span>' );
				?></h1>

				<?php
						//Get the description and print it if it exists
						$venue_description =eo_get_venue_description();

						if(!empty($venue_description)){?>
							<!---- If the venue has a description display it-->
							<div class="venue-archive-meta">
								<?php echo $venue_description; ?>
							</div>
						<?php } ?>
		
				<!-- Display the venue map. If you specify a class, ensure that class has height/width dimensions-->
				<?php echo do_shortcode('[eo_venue_map width="100%"]'); ?>
			</header><!-- end header -->

				<!---- Navigate between pages-->
				<!---- In TwentyEleven theme this is done by twentyeleven_content_nav-->
				<?php 
				if ( $wp_query->max_num_pages > 1 ) : ?>
					<nav id="nav-above">
						<div class="nav-next events-nav-newer"><?php next_posts_link( __( 'Later events <span class="meta-nav">&rarr;</span>' , 'eventorganiser' ) ); ?></div>
						<div class="nav-previous events-nav-newer"><?php previous_posts_link( __( ' <span class="meta-nav">&larr;</span> Newer events', 'eventorganiser' ) ); ?></div>
					</nav><!-- #nav-above -->
				<?php endif; ?>

				<!-- This is the usual loop, familiar in WordPress templates-->
				<?php while ( have_posts()) : the_post(); ?>
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

						<header class="entry-header">
							<h1 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>

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
									<?php _e('at','eventorganiser');?> <a href="<?php eo_venue_link();?>"><?php eo_venue_name();?></a>
								<?php endif;?>
							</div><!-- .entry-meta -->

						</header><!-- .entry-header -->

					</article><!-- #post-<?php the_ID(); ?> -->

    				<?php endwhile; ?><!----The Loop ends-->

				<!---- Navigate between pages-->
				<?php 
				global $wp_query;
				if ( $wp_query->max_num_pages > 1 ) : ?>
					<nav id="nav-below">
						<div class="nav-next events-nav-newer"><?php next_posts_link( __( 'Later events <span class="meta-nav">&rarr;</span>' , 'eventorganiser' ) ); ?></div>
						<div class="nav-previous events-nav-newer"><?php previous_posts_link( __( ' <span class="meta-nav">&larr;</span> Newer events', 'eventorganiser' ) ); ?></div>
					</nav><!-- #nav-below -->
				<?php endif; ?>

			<?php else : ?>
				<!---- If there are no events -->
				<article id="post-0" class="post no-results not-found">
					<header class="entry-header">
						<h1 class="entry-title"><?php _e( 'Nothing Found', 'eventorganiser' ); ?></h1>
					</header><!-- .entry-header -->

					<div class="entry-content">
						<p><?php _e( 'Apologies, but no events were found for the requested venue. ', 'eventorganiser' ); ?></p>
					</div><!-- .entry-content -->
				</article><!-- #post-0 -->

			<?php endif; ?>

			</div><!-- #content -->
		</div><!-- #primary -->

<!-- Call template sidebar and footer -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>
