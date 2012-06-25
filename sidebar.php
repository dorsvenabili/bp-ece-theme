<?php do_action( 'bp_before_sidebar' ) ?>

<div id="sidebar" role="complementary">
	<div class="padder">

	<?php do_action( 'bp_inside_before_sidebar' ) ?>

	<?php mostrar_lat_der_big() ?>
		
	<?php /*if(is_user_logged_in()): ?>
		<?php if(current_user_can('administrator') || current_user_can('editor') || current_user_can('author')): ?>
			<div>
			<h3 class="widgettitle">Zona privada Noletia</h3>
			<div class="widget">
				<ul>
					<li><img class="ico-private" src="<?php bloginfo('home'); ?>/wp-content/themes/noletia/images/ico-gest-event.jpg" /><a href="<?php bloginfo('home'); ?>/gestionar-eventos/">Gestionar eventos</a></li>
					<li><img class="ico-private" src="<?php bloginfo('home'); ?>/wp-content/themes/noletia/images/ico-gest-espac.jpg" /><a href="<?php bloginfo('home'); ?>/gestionar-espacios/">Gestionar espacios</a></li>
					<li><img class="ico-private" src="<?php bloginfo('home'); ?>/wp-content/themes/noletia/images/ico-gest-reserv.jpg" /><a href="<?php bloginfo('home'); ?>/gestionar-reservas/">Gestionar reservas</a></li>
				</ul>
			</div>
			</div>
		<?php endif; ?>
	<?php endif;*/ ?>

	<?php dynamic_sidebar( 'sidebar-1' ) ?>

	<?php 
		if(is_plugin_active('publicidad/publicidad.php')) {
			mostrar_lat_der_small1();
			mostrar_lat_der_small2();
			mostrar_lat_der_small3();
			mostrar_facebook();
		}
	?>
	
	<?php dynamic_sidebar( 'sidebar-debajo-fb' ); ?>

	<?php do_action( 'bp_inside_after_sidebar' ); ?>

	</div><!-- .padder -->
</div><!-- #sidebar -->

<?php do_action( 'bp_after_sidebar' ) ?>
