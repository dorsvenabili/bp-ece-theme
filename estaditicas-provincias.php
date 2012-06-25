<?php
	/*
		Template Name: Estadísticas selector de provincias
	*/
?>

<?php get_header() ?>

	<div id="content">
	
		<div class="padder double-border">

		<?php do_action( 'bp_before_blog_page' ) ?>

		<div class="page" id="blog-page" role="main">

			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				
					<div class="post-content">
							<?php /* ?><h2 class="posttitle"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e( 'Permanent Link to', 'buddypress' ) ?> <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2><?php */ ?>
							<?php
								if(has_post_thumbnail()):
									the_post_thumbnail('medium');
								endif;
							?>

							<p class="date"><?php printf( __( '%1$s <span>in %2$s</span>', 'buddypress' ), get_the_date(), get_the_category_list( ', ' ) ); ?></p>
			
		<?php if(current_user_can('editor')){ ?>
			
							<div class="entry">
								<?php the_content( __( 'Read the rest of this entry &rarr;', 'buddypress' ) ); ?>
			<?php
			
			
				$cont = get_option('noletia_estadisticas_select_cont_provincia_A Coruña',0);
				echo '<strong>A Coruña:</strong>'.$cont.'<br />';
				$cont = get_option('noletia_estadisticas_select_cont_provincia_Almería',0);
				echo '<strong>Almería:</strong>'.$cont.'<br />';
				$cont = get_option('noletia_estadisticas_select_cont_provincia_Cádiz',0);
				echo '<strong>Cádiz:</strong>'.$cont.'<br />';
				$cont = get_option('noletia_estadisticas_select_cont_provincia_Ciudad Real',0);
				echo '<strong>Ciudad Real:</strong>'.$cont.'<br />';
				$cont = get_option('noletia_estadisticas_select_cont_provincia_Córdoba',0);
				echo '<strong>Córdoba:</strong>'.$cont.'<br />';
				$cont = get_option('noletia_estadisticas_select_cont_provincia_Granada',0);
				echo '<strong>Granada:</strong>'.$cont.'<br />';
				$cont = get_option('noletia_estadisticas_select_cont_provincia_Huelva',0);
				echo '<strong>Huelva:</strong>'.$cont.'<br />';
				$cont = get_option('noletia_estadisticas_select_cont_provincia_Madrid',0);
				echo '<strong>Madrid:</strong>'.$cont.'<br />';
				$cont = get_option('noletia_estadisticas_select_cont_provincia_Málaga',0);
				echo '<strong>Málaga:</strong>'.$cont.'<br />';
				$cont = get_option('noletia_estadisticas_select_cont_provincia_Ourense',0);
				echo '<strong>Ourense:</strong>'.$cont.'<br />';
				$cont = get_option('noletia_estadisticas_select_cont_provincia_Pontevedra',0);
				echo '<strong>Pontevedra:</strong>'.$cont.'<br />';
				$cont = get_option('noletia_estadisticas_select_cont_provincia_Sevilla',0);
				echo '<strong>Sevilla:</strong>'.$cont.'<br />';
				$cont = get_option('noletia_estadisticas_select_cont_provincia_Toledo',0);
				echo '<strong>Toledo:</strong>'.$cont.'<br />';
								
			?>
							</div>

		<?php }else{
			echo '<p>No tienes permisos para ver esta página.</p>';
		} ?>
						</div>


				</div>

			<?php comments_template(); ?>

			<?php endwhile; endif; ?>

		</div><!-- .page -->

		<?php do_action( 'bp_after_blog_page' ) ?>

		</div><!-- .padder -->
	</div><!-- #content -->

	<?php get_sidebar() ?>

<?php get_footer();?>