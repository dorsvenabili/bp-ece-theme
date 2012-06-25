<?php
	/*
		Template Name: Selector Provincias
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
				<?php if(current_user_can('editor')){ ?>
						
						<br /><br />
						<p>Lista detallada de selección de provincias en la cabecera de ElClubExpress.com desde el 5 de Junio de 2012</p>
						
						<table cellpadding="0" cellspacing="0">
						<tr>
							<td style="font-weight:bold;width:20%;">Provincia</td>
							<td style="font-weight:bold;width:60%;">Usuario</td>
							<td style="font-weight:bold;width:20%;">Fecha</td>
						</tr>
					<?php 
						$contador = 0;
						
						$click_provs = $wpdb->get_col("SELECT DISTINCT provincia FROM estadisticas_selector_prov");
							
							foreach( $click_provs as $click_prov ){ 

								$linea_provs = $wpdb->get_results("SELECT * FROM estadisticas_selector_prov WHERE provincia = '".$click_prov."'");
								foreach( $linea_provs as $linea_prov ){ 
							
									$prov = $linea_prov->provincia;
									if($linea_prov->usuario == ''){$usu = 'Usuario no registrado'; }else{ $usu = $linea_prov->usuario; };
									echo "
									<tr>
							    	<td style='width:20%;'>".$prov."</td>
							    	<td style='width:60%;'>".$usu."</td>
							    	<td style='width:20%;'>".$linea_prov->fecha."</td>
									</tr>";
									$contador++;
								
								 }//provincia
								 
								 echo "<tr><td colspan='3' style='font-weight:bold;background-color:#F29400;'>Resumen $prov: $contador</td></tr>";
								 $contador = 0;
						} // $click_provs
						
	
						
						//print_r($contador);
						
					?>


						
						
						
						</table>
						
						<br />
						<hr>
						
						<p>Lista total del número total de veces que han seleccionado una provincia en el selector de provincias de la cabecera de ElClubExpress.com desde el 29 de Mayo de 2012 (Incluyendo los valores de arriba) </p>
						
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
			
						
				<?php } //current_user_can ?>	
						
						</div>

						

					</div>

				</div>


		</div>


		</div><!-- .padder -->
	</div><!-- #content -->

	<?php //get_sidebar() ?>

<?php get_footer() ?>