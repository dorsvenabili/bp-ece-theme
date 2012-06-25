<?php get_header() ?>

	<div id="content">
		<div class="padder double-border">

			<?php do_action( 'bp_before_blog_single_post' ) ?>

			<div class="page" id="blog-single" role="main">

			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

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
								if(is_super_admin()){
									$ece_tv_id = get_the_ID();
									$video_url = get_post_meta($ece_tv_id, 'enlace-video-youtube', true);
								
									echo do_shortcode('[youtube='.$video_url.'&w=660&h=371]');
								
								}else{
									if(has_post_thumbnail()):
										the_post_thumbnail('medium');
									endif;
								}
																
							?>
							
							<p class="postmetadata"><?php the_tags( '<span class="tags">' . __( 'Tags: ', 'buddypress' ), ', ', '</span>' ); ?>&nbsp;</p>
							
							<?php 
									$post_id = get_the_ID(); ?>
									
									<?php 
											$desc_rel = get_post_meta($post_id, 'descuento-relacionado', true); 
											$conc_rel = get_post_meta($post_id, 'concurso-relacionado', true);
											$entr_rel = get_post_meta($post_id, 'entrada-relacionada', true);
											$fich_rel = get_post_meta($post_id, 'ficha-relacionada', true);
											$descarga_rel = get_post_meta($post_id, 'descarga-relacionada', true);
										
					
									if($desc_rel != '' || $conc_rel != '' || !empty($entr_rel) || !empty($fich_rel) || $descarga_rel != ''){ ?>
										<p class="postmetadatarelated"><span class="tags">Contenidos relacionados:</span>
											<?php if($desc_rel != ''){ 
												echo '<a class="ico_not_desc" href="'.$desc_rel.'"> Descuento ';
													//echo '<img src="'.get_bloginfo('home').'/wp-content/themes/noletia/images/ico_not_desc.png" alt="Descuento relacionado" />Descuento'; 
												echo '</a> | ';
											} ?>
											
											<?php if($conc_rel != ''){ 
												echo '<a class="ico_not_conc" href="'.$conc_rel.'"> Concurso ';
													//echo '<img src="'.get_bloginfo('home').'/wp-content/themes/noletia/images/ico_not_conc.png" alt="Concurso relacionado" />Concurso'; 
												echo '</a> | ';
											} ?>
											
											<?php if(!empty($entr_rel)){ 
												$entr_rel_permalink = get_permalink($entr_rel[0]);
												echo '<a class="ico_not_not" href="'.$entr_rel_permalink.'"> Noticia ';
													//echo '<img src="'.get_bloginfo('home').'/wp-content/themes/noletia/images/ico_not_not.png" alt="Noticia relacionada" />Noticia'; 
												echo '</a> | ';
											} ?>
											
											<?php if($descarga_rel != ''){ 
												echo '<a class="ico_not_descarga" href="'.$descarga_rel.'"> Descarga ';
													//echo '<img src="'.get_bloginfo('home').'/wp-content/themes/noletia/images/ico_not_descarga.png" alt="Descarga relacionada" />Descarga'; 
												echo '</a> | ';
											} ?>
											
											<?php if(!empty($fich_rel)){ 
												$fich_rel_permalink = get_permalink($fich_rel[0]);
												echo '<a class="ico_not_ficha" href="'.$fich_rel_permalink.'"> Ficha ';
													//echo '<img src="'.get_bloginfo('home').'/wp-content/themes/noletia/images/ico_not_ficha.png" alt="Ficha relacionada" />Ficha'; 
												echo '</a>';
											} ?>
											
										</p>
									<?php } ?>
							
						
							<?php the_content( __( 'Read the rest of this entry &rarr;', 'buddypress' ) ); ?>

							<?php wp_link_pages( array( 'before' => '<div class="page-link"><p>' . __( 'Pages: ', 'buddypress' ), 'after' => '</p></div>', 'next_or_number' => 'number' ) ); ?>
						</div>

						

						<?php /* ?>
						<div class="alignleft"><?php previous_post_link( '%link', '<span class="meta-nav">' . _x( '&larr;', 'Previous post link', 'buddypress' ) . '</span> Anterior' ); ?></div>
						<div class="alignright"><?php next_post_link( '%link', 'Siguiente <span class="meta-nav">' . _x( '&rarr;', 'Next post link', 'buddypress' ) . '</span>' ); ?></div>
						<?php */ ?>
					</div>

				</div>

			<?php comments_template(); ?>

			<?php endwhile; else: ?>

				<p><?php _e( 'Sorry, no posts matched your criteria.', 'buddypress' ) ?></p>

			<?php endif; ?>

		</div>

		<?php do_action( 'bp_after_blog_single_post' ) ?>

		</div><!-- .padder -->
	</div><!-- #content -->

	<?php get_sidebar() ?>

<?php get_footer() ?>