				<?php mostrar_publi_pie(); ?>
			
		</div> <!-- #container -->

		<?php do_action( 'bp_after_container' ) ?>
		<?php do_action( 'bp_before_footer' ) ?>

		</div><!-- End .contenedor -->
		
		<div id="footer" class="light-grey-background">
			
		<div id="footer-container">
			<div id="footer-left">
				<img src="<?php bloginfo('home'); ?>/wp-content/themes/noletia/images/logo_pie.png" />
				<ul>
					<li><span class="footer-title">NOSOTROS: </span><a target="_blank" href="<?php bloginfo('home'); ?>/servicios-centrales/">Servicios centrales</a> | <a href="<?php bloginfo('home'); ?>/delegaciones/">Delegaciones</a></li>
					<li><span class="footer-title">TRABAJO: </span><a href="<?php bloginfo('home'); ?>/abre-una-delegacion/">Abre una delegación</a> | <a href="<?php bloginfo('home'); ?>/unete/">Únete</a></li>
					<li><span class="footer-title">ASISTENCIA: </span><a href="<?php bloginfo('home'); ?>/descargas2/dosier_general.pdf">Dosier PDF</a> | <a href="<?php bloginfo('home'); ?>/aviso-legal/">Aviso legal</a> | <a href="<?php bloginfo('home'); ?>/ayuda/">Ayuda</a> | <a href="<?php bloginfo('home'); ?>/contacto/">Contacto</a></li>
					<li><span class="footer-title">AL DÍA: </span><a href="<?php bloginfo('home'); ?>/feed/">RSS</a> | <a href="<?php bloginfo('home'); ?>/newsletter/">Suscríbete al Newsletter</a> | <a target="_blank" href="https://twitter.com/elclubexpress">Twitter</a> | <a href="<?php bloginfo('home'); ?>/facebook/">Facebook</a></li>
					<li><span class="footer-title">VENTA: </span><a href="<?php bloginfo('home'); ?>/publicidad/">Publicidad</a> | <a href="<?php bloginfo('home'); ?>/otros-servicios/">Otros servicios</a></li>
				</ul>
				
				<div class="creative-commons">
					Los textos -NO las fotografías- de LaExpress se elaboran bajo Licencia Creative Commons Reconocimiento No Comercial - Compartir igual
					<img src="<?php bloginfo('home'); ?>/wp-content/themes/noletia/images/ico_cc.jpg" alt="Creative Commons"/>
				</div>
			</div>
			
			<div id="footer-line"></div>
			
			<div id="footer-middle"></div>
			
			<div id="footer-right">
					
				<p class="footer-logos">Un proyecto de</p>
				<p><a class="footer-logo-noletia" href="http://www.noletia.com"><img src="<?php bloginfo('home'); ?>/wp-content/themes/noletia/images/logo-noletia.png" /></a></p>
				
				<p class="footer-logos">Apoyado por</p>
				<p><a class="footer-logo-minist" href="http://www.mcu.es/"><img src="<?php bloginfo('home'); ?>/wp-content/themes/noletia/images/ministerio_cultura.png" /></a></p>
			
				
			</div>
		</div>
		
		
		</div><!-- #footer -->
		
		<?php do_action( 'bp_after_footer' ) ?>

		<?php wp_footer(); ?>
		
	</body>

</html>