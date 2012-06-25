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
					$EM_Event = em_get_location($post);
				?>
				
				<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

					<div class="post-content">
					
						<?php do_action( 'bp_after_post_content_blog_single_post' ) ?>
					
						<h2 class="posttitle"><?php the_title(); ?></h2>

						<p class="date">
							Ficha del espacio<span class="post-utility alignright"><?php edit_post_link( __( 'Edit this entry', 'buddypress' ) ); ?></span>
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
							
									
								//Mostramos los eventos que sean DESCUENTOS
								?>
								<div id="event-info">
									<?php if($EM_Event->output('#_LOCATIONADDRESS')){ ?>
									<p class="info">
										<strong>DIRECCIÓN: </strong> <?php echo $EM_Event->output('#_LOCATIONADDRESS'); ?>
									</p>
									<?php } ?>
									
									<?php if($EM_Event->output('#_LOCATIONPOSTCODE') || $EM_Event->output('#_LOCATIONTOWN')){ ?>
									<p class="info">
										<strong>CÓD.POSTAL: </strong> <?php echo $EM_Event->output('#_LOCATIONPOSTCODE'); ?> | <strong>LOCALIDAD: </strong><?php echo $EM_Event->output('#_LOCATIONTOWN'); ?>
									</p>
									<?php } ?>
									
									<?php if($EM_Event->output('#_LATT{Teléfono información}') || $EM_Event->output('#_LATT{Teléfono taquilla}')){ ?>
									<p class="info">
										<strong>TEL. INFORMACIÓN: </strong> <?php echo $EM_Event->output('#_LATT{Teléfono información}'); ?> | <strong>TEL. TAQUILLA: </strong><?php echo $EM_Event->output('#_LATT{Teléfono taquilla}'); ?>
									</p>
									<?php } ?>
									
									<?php if($EM_Event->output('#_LATT{Horario taquilla}')){ ?>
									<p class="info">
										<strong>HORARIO DE TAQUILLA: </strong> <?php echo $EM_Event->output('#_LATT{Horario taquilla}'); ?>
									</p>
									<?php } ?>
									
									<?php if($EM_Event->output('#_LATT{Correo electrónico}')){ ?>
									<p class="info">
										<strong>CORREO ELECTRÓNICO: </strong><a target="_blank" href="mailto:<?php echo $EM_Event->output('#_LATT{Correo electrónico}'); ?>"><?php echo $EM_Event->output('#_LATT{Correo electrónico}'); ?></a>
									</p>
									<?php } ?>
									
									<?php if($EM_Event->output('#_LATT{Web}')){ ?>
									<p class="info">
										<strong>WEB: </strong><a target="_blank" href="<?php echo $EM_Event->output('#_LATT{Web}'); ?>"><?php echo $EM_Event->output('#_LATT{Web}'); ?></a>
									</p>
									<?php } ?>
									
									<?php if($EM_Event->output('#_LATT{Facebook}')){ ?>
									<p class="info">
										<strong>FACEBOOK: </strong><a target="_blank" href="<?php echo $EM_Event->output('#_LATT{Facebook}'); ?>"><?php echo $EM_Event->output('#_LATT{Facebook}'); ?></a>
									</p>
									<?php } ?>
									
									<?php if($EM_Event->output('#_LATT{Twitter}')){ ?>
									<p class="info">
										<strong>TWITTER: </strong><a target="_blank" href="<?php echo $EM_Event->output('#_LATT{Twitter}'); ?>"><?php echo $EM_Event->output('#_LATT{Twitter}'); ?></a>
									</p>
									<?php } ?>
									
									<?php if($EM_Event->output('#_LATT{Cómo llegar}')){ ?>
									<p class="info">
										<strong>CÓMO LLEGAR: </strong> <?php echo $EM_Event->output('#_LATT{Cómo llegar}'); ?>
									</p>
									<?php } ?>
	
									<br style="clear:both" />
									<?php 
										//Descripción del evento
										echo $EM_Event->output('#_LOCATIONNOTES'); 
										
										echo '<br />';
									?>		
								</div>
								
								<?php if($EM_Event->output('#_MAP')){ ?>
									<div style="display:block; margin:20px 0px 20px 0px; border:1px solid #999"><?php echo $EM_Event->output('#_MAP'); ?></div>
								<?php } ?>
								
								
								<br style="clear:both" />
								
								<h3 class="orange">Próximos eventos</h3>
								<p>
									<?php if($EM_Event->output('#_LOCATIONNEXTEVENTS')){ ?>
										<?php echo $EM_Event->output('#_LOCATIONNEXTEVENTS'); ?>
									<?php } ?>
								</p>
								

							<?php wp_link_pages( array( 'before' => '<div class="page-link"><p>' . __( 'Pages: ', 'buddypress' ), 'after' => '</p></div>', 'next_or_number' => 'number' ) ); ?>
						</div>

						
						
						<div class="alignleft"><?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'buddypress' ) . '</span> Anterior' ); ?></div>
						<div class="alignright"><?php next_post_link( '%link', 'Siguiente <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'buddypress' ) . '</span>' ); ?></div>
					</div>

				</div>

			<?php //comments_template(); ?>
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