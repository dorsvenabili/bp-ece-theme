<?php
	/*
		Template Name: Publicidad
	*/
?>
<?php get_header() ?>

	<div id="content">
		<div class="padder double-border">


			<div class="page" id="blog-single" role="main">


				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

					<div class="post-content">
					
						
						<h2 class="posttitle"><?php the_title(); ?></h2>

						<p class="date">
						</p>

						<div class="entry">
						
						<table cellpadding="0" cellspacing="0">
						<tr>
							<td style="font-weight:bold;">vistas</td>
							<td style="font-weight:bold;">título</td>
							<td style="font-weight:bold;">tipo</td>
							<td style="font-weight:bold;">provincia</td>
							<td style="font-weight:bold;">fecha</td>
						</tr>
					<?php 
						$contador2 = 0;
						$publis = $wpdb->get_results("SELECT DISTINCT titulo FROM publicidad WHERE fecha LIKE '%2012-04%'");
							foreach( $publis as $publi ){ 
							$portadas = $wpdb->get_results("SELECT DISTINCT tipo_portada FROM publicidad WHERE fecha LIKE '%2012-04%' AND titulo = '$publi->titulo'");
							foreach ( $portadas as $portada ){
								$provincias = $wpdb->get_results("SELECT DISTINCT prov_portada FROM publicidad WHERE fecha LIKE '%2012-04%' AND titulo = '$publi->titulo' AND tipo_portada = '$portada->tipo_portada'");
								foreach ( $provincias as $provincia ){
									$contador = $wpdb->get_var("SELECT COUNT(*) FROM publicidad WHERE fecha LIKE '%2012-04%' AND titulo = '$publi->titulo' AND tipo_portada = '$portada->tipo_portada' AND prov_portada = '$provincia->prov_portada'");
									$contador2 = $contador2 + $contador;
									echo "
									<tr>
										<td>$contador</td>
										<td>$publi->titulo</td>
										<td>$portada->tipo_portada</td>
										<td>$provincia->prov_portada</td>
										<td>04-2012</td>
									</tr>";

								} // provincias
							
							} // portadas
							echo "
							<tr>
							    <td style='font-weight:bold;'>$contador2</td>
							    <td colspan='3' style='font-weight:bold;'>$publi->titulo</td>
							    <td style='font-weight:bold;'>04-2012</td>
							</tr>";
							$contador2 = 0;
						} // publi

						$contador2 = 0;
						$publis = $wpdb->get_results("SELECT DISTINCT titulo FROM publicidad WHERE fecha LIKE '%2012-05%'");
							foreach( $publis as $publi ){ 
							$portadas = $wpdb->get_results("SELECT DISTINCT tipo_portada FROM publicidad WHERE fecha LIKE '%2012-05%' AND titulo = '$publi->titulo'");
							foreach ( $portadas as $portada ){
								$provincias = $wpdb->get_results("SELECT DISTINCT prov_portada FROM publicidad WHERE fecha LIKE '%2012-05%' AND titulo = '$publi->titulo' AND tipo_portada = '$portada->tipo_portada'");
								foreach ( $provincias as $provincia ){
									$contador = $wpdb->get_var("SELECT COUNT(*) FROM publicidad WHERE fecha LIKE '%2012-05%' AND titulo = '$publi->titulo' AND tipo_portada = '$portada->tipo_portada' AND prov_portada = '$provincia->prov_portada'");
									$contador2 = $contador2 + $contador;
									echo "
									<tr>
										<td>$contador</td>
										<td>$publi->titulo</td>
										<td>$portada->tipo_portada</td>
										<td>$provincia->prov_portada</td>
										<td>05-2012</td>
									</tr>";

								} // provincias
							
							} // portadas
							echo "
							<tr>
							    <td style='font-weight:bold;'>$contador2</td>
							    <td colspan='3' style='font-weight:bold;'>$publi->titulo</td>
							    <td style='font-weight:bold;'>05-2012</td>
							</tr>";
							$contador2 = 0;
						} // publi
						
					?>


						
						
						
						</table>
						
						
						
						</div>

						

					</div>

				</div>


		</div>


		</div><!-- .padder -->
	</div><!-- #content -->

	<?php //get_sidebar() ?>

<?php get_footer() ?>