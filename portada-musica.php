<?php
	/*
		Template Name: Portada Música
	*/
?>
<?php get_header() ?>

	<div id="content">
		
		<div class="slide-home">
			<?php
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			
			noletia_show_main_slide('musica');
				
			?>	
		</div>
		
		<div class="padder">

		<?php do_action( 'bp_before_blog_page' ) ?>

		<div class="page" id="blog-page" role="main">
		
			<div class="conc-y-desc-home">
				<div class="bloque-concursos">
					<div>
						<?php /* ?>
						<div class="imagen-descuentos"><img src="http://elclubexpress.com/wp-content/uploads/low1-658x320-345x200.jpg" class="attachment-thumbnail wp-post-image" alt="low1-658x320" title="low1-658x320" height="200" width="345"><span class="info"><h3>concursos</h3></span>
						</div>
						<div id="list_concur_frontpage"><ul><li class="last-li-concur"><p class="list_concurs_frontpage_title">PRÓXIMAMENTE</p><p class="concur-portada-date"></p></li></ul>
						</div>
						<?php */ ?>
						<?php mostrar_ult_post_concursos('musica'); ?>
					</div>
				</div>
				<div class="bloque-descuentos">
					<div>
						<?php mostrar_ult_post_descuentos('musica'); ?>
					</div>
				</div>
			</div>
			
			<?php /* ?>
			<div class="bloque-agenda">
				<?php mostrar_ult_post_agenda('musica'); ?>
			</div>
			<?php */ ?>
			
			
			<div class="bloque-agenda">
				<?php mostrar_ult_post_agenda2('musica'); ?>	
			</div>
			
			
			<div class="last-news-home">
				<?php mostrar_ult_post_noticias('musica'); ?>
			</div>
			
			
			<div class="last-news-home">
				<?php mostrar_ult_post_noticias_seg_fila('musica'); ?>
			</div>
			
			<div id="more_news_frontpage">
				<h2 class="all_desc_frontpage_title">Más noticias</h2>
				<?php mostrar_modulos_peq_portadas('musica', 8); ?>
				<?php mostrar_enlace_ver_todas_noticias('musica'); ?>
			</div>

		</div><!-- .page -->

		<?php do_action( 'bp_after_blog_page' ) ?>

		</div><!-- .padder -->

	
	</div><!-- #content -->
<?php get_sidebar() ?>
<?php get_footer(); ?>