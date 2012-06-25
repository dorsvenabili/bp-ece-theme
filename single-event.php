<?php 
	get_header();
	global $post, $wpdb;
 ?>

	<div id="content">
		<div class="padder double-border">

			<?php do_action( 'bp_before_blog_single_post' ) ?>

			<div class="page" id="blog-single" role="main">

			<?php //query_posts('posts_per_page=1'); ?>
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				
				<?php
					//Cargamos el post en el objeto Evento con todos sus cambios
					$EM_Event = em_get_event($post);
				?>
				
				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

					<div class="post-content">
					
						<?php do_action( 'bp_after_post_content_blog_single_post' ) ?>
					
						<h2 class="posttitle"><?php the_title(); ?></h2>

						<p class="date">
							<?php printf( __( '%1$s <span>in %2$s</span>', 'buddypress' ), get_the_date(), get_the_category_list( ', ' ) ); ?>
							<span class="post-utility alignright"><?php edit_post_link( __( 'Edit this entry', 'buddypress' ) ); ?></span>
						</p>

						<div class="entry">
							
							<?php
							if(has_post_thumbnail()) {
								the_post_thumbnail('medium');
							}
							/*?>
							<p class="postmetadata"><?php the_tags( '<span class="tags">' . __( 'Tags: ', 'buddypress' ), ', ', '</span>' ); ?>&nbsp;</p>
							
							<?php */
							$event_id = get_the_ID();
							$ficha_id = get_post_meta($event_id, 'noletia_ficha_actividad_id', true);
							
							$ficha = get_post($ficha_id);
							
							//Dependiendo de si es un descuento o no, mostramos the_content o el cuerpo y los campos personalizados por separado
							if(is_object_in_term($post->ID, 'event-categories','descuentos')):	
								
								//Mostramos los eventos que sean DESCUENTOS
								?>
								<div id="event-info">
									<?php if($EM_Event->output('#_EVENTDATES')){ ?>
									<p class="info">
										<strong>FECHA: </strong> <?php echo $EM_Event->output('#_EVENTDATES'); ?> | <strong>HORA: </strong><?php echo $EM_Event->output('#_EVENTTIMES'); ?>
									</p>
									<?php } ?>
									
									<?php if($EM_Event->output('#_ATT{Precio (€)}')){ ?>
									<p class="info">
										<strong>PRECIO: </strong> <?php echo $EM_Event->output('#_ATT{Precio (€)}'); ?> | <strong>PRECIO ORIGEN: </strong><span style="text-decoration:line-through;"> <?php echo $EM_Event->output('#_ATT{Precio origen tachado}'); ?></span>
									</p>
									<?php } ?>
									
									<?php if($EM_Event->output('#_ATT{Porcentaje de descuento}')){ ?>
									<p class="info">
										<strong>% DESCUENTO: </strong> <?php echo $EM_Event->output('#_ATT{Porcentaje de descuento}'); ?> | <strong>QUEDAN <span style="color:#DA8500;"><?php echo $EM_Event->output('#_AVAILABLESPACES'); ?></span> DESCUENTOS</strong>
									</p>
									<?php } ?>
									
									<?php if($EM_Event->output('#_LOCATIONLINK')){ ?>
									<p class="info">
										<strong>UBICACIÓN:</strong> <?php echo $EM_Event->output('#_LOCATIONLINK'); ?>
									</p>
									<?php } ?>
									
									<?php if($EM_Event->output('#_ATT{Horarios}')){ ?>
									<p class="info">
										<strong>OTRA INFORMACIÓN:</strong> <?php echo $EM_Event->output('#_ATT{Horarios}'); ?>
									</p>
									<?php } ?>
									
									<br style="clear:both" />
									<?php
										/* Sistema de votación */
										if(function_exists('wp_gdsr_render_article')) { 
											wp_gdsr_render_article();
										}	
									?>
									
									<br style="clear:both" />
									<?php 
										//Descripción del evento
										echo $EM_Event->output('#_EVENTNOTES'); 
										//Mostramos después la descripción de la ficha de actividad
										if($ficha_id != ''):
											echo '<p>'.$ficha->post_content.'</p>';
										endif;
										echo '<br /><br />';
									?>		
								</div>
								
								<?php if($EM_Event->output('#_MAP')){ ?>
									<div style="display:block; margin:20px 0px 20px 0px; border:1px solid #999"><?php echo $EM_Event->output('#_MAP'); ?></div>
								<?php } ?>
								
								<?php if($EM_Event->output('#_BOOKINGFORM')){ ?>
									<h3 class="orange">Reservas</h3>
									<?php echo $EM_Event->output('#_BOOKINGFORM'); ?>
								<?php } ?>
								
								<?php

							else:
								//Mostramos los eventos que NO SEAN DESCUENTOS
								?>
								<div id="event-info">
									<?php if($EM_Event->output('#_EVENTDATES')){ ?>
									<p class="info">
										<strong>FECHA: </strong> <?php echo $EM_Event->output('#_EVENTDATES'); ?> | <strong>HORA: </strong> <i><?php echo $EM_Event->output('#_EVENTTIMES'); ?></i>
									</p>
									<?php } ?>
									
									<?php if($EM_Event->output('#_LOCATIONLINK')){ ?>
									<p class="info">
										<strong>UBICACIÓN:</strong> <?php echo $EM_Event->output('#_LOCATIONLINK'); ?>
									</p>
									<?php } ?>
									
									<?php if($EM_Event->output('#_ATT{Precio (€)}')){ ?>
									<p class="info">
										<strong>PRECIO:</strong> <?php echo $EM_Event->output('#_ATT{Precio (€)}'); ?>
									</p>
									<?php } ?>
									
									<?php if($EM_Event->output('#_ATT{Horarios}')){ ?>
									<p class="info">
										<strong>OTRA INFORMACIÓN:</strong> <?php echo $EM_Event->output('#_ATT{Horarios}'); ?>
									</p>
									<?php } ?>
									
									<br style="clear:both" />
									<?php
										/* Sistema de votación */
										if(function_exists('wp_gdsr_render_article')) { 
											wp_gdsr_render_article();
										}	
									?>
									
									<br style="clear:both" />
									<?php 
										//Descripción del evento
										echo $EM_Event->output('#_EVENTNOTES'); 
										//Mostramos después la descripción de la ficha de actividad
										if($ficha_id != ''):
											echo '<p>'.$ficha->post_content.'</p>';
										endif;
										echo '<br /><br />';
									?>		
								</div>

								<?php if($EM_Event->output('#_MAP')){ ?>
									<div style="display:block; margin:20px 0px 20px 0px; border:1px solid #999"><?php echo $EM_Event->output('#_MAP'); ?></div>
								<?php } ?>
								
								
								<?php
							
							endif;

							
							?>

							<?php wp_link_pages( array( 'before' => '<div class="page-link"><p>' . __( 'Pages: ', 'buddypress' ), 'after' => '</p></div>', 'next_or_number' => 'number' ) ); ?>
						</div>

						
						<?php /* ?>
						<div class="alignleft"><?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'buddypress' ) . '</span> Anterior' ); ?></div>
						<div class="alignright"><?php next_post_link( '%link', 'Siguiente <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'buddypress' ) . '</span>' ); ?></div>
						<?php */ ?>
					</div>

				</div>

			<?php comments_template(); ?>
			<?php //echo '<p class="opina_class"><a target="_blank" href="'.get_bloginfo('home').'/opina/"/> Opina </a>'; ?>
			<?php break; ?>
			<?php endwhile; else: ?>

				<p><?php _e( 'Sorry, no posts matched your criteria.', 'buddypress' ) ?></p>

			<?php endif; ?>

		</div>

		<?php do_action( 'bp_after_blog_single_post' ) ?>

		</div><!-- .padder -->
	</div><!-- #content -->

	<?php get_sidebar() ?>

<?php get_footer() ?>