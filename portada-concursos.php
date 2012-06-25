<?php
	/*
		Template Name: Portada Concursos
	*/
?>
<?php get_header() ?>

	<div id="content">
		
		<div class="slide-home">
			<?php
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			
			noletia_show_main_slide_descuentos('concursos');
				
			?>	
		</div>
		
		<div class="padder double-border">

		<?php do_action( 'bp_before_blog_page' ) ?>

		
			
			<div class="last-desc-home">
				<?php mostrar_modulo_portada_concursos('concursos', 0); ?>
			</div>
			
			<div id="all_desc_frontpage">
				<h2 class="all_desc_frontpage_title orange">Más concursos</h2>
				<?php mostrar_modulos_peq_portada_descuentos('concursos', 0); ?>
				<?php mostrar_enlace_ver_todas_noticias('concursos'); ?>
			</div>

		

		<?php do_action( 'bp_after_blog_page' ) ?>

		</div><!-- .padder -->

	</div><!-- #content -->
	<?php get_sidebar() ?>

<?php get_footer(); ?>