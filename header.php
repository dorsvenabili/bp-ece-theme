<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
	<head profile="http://gmpg.org/xfn/11">
		<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ) ?>; charset=<?php bloginfo( 'charset' ) ?>" />
		<title><?php wp_title( '|', true, 'right' ); bloginfo( 'name' ); ?></title>

		<?php do_action( 'bp_head' ) ?>
  		<link href="http://www.elclubexpress.com/favicon.png" rel="shortcut icon" type="image/x-icon" />

		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ) ?>" />
		
		<?php
			if (function_exists('bp_is_blog_page')){ 
				if ( is_singular() && bp_is_blog_page() && get_option( 'thread_comments' ) )
					wp_enqueue_script( 'comment-reply' );

				wp_head();
			}
		
		?>
		<?php date_default_timezone_set('Europe/Madrid'); ?>
		<script type="text/javascript">
			function add_publi(id,tipo_portada,prov_portada,titulo){								
				window.location = 'http://elclubexpress.com/register.php?id=' + id + '&tipo_portada=' + tipo_portada + '&prov_portada=' + prov_portada + '&titulo=' + titulo; 
			}
		</script>

		
	</head>

	<body <?php body_class() ?> id="bp-default">
		
		<div class="publicidad_superior">
			<div class="publicidad-container">
			
				<?php if ( function_exists( 'mostrar_sup_izq' ) ) {  mostrar_sup_izq(); } ?>
				<?php if ( function_exists( 'mostrar_sup_der' ) ) {  mostrar_sup_der(); } ?>
				<?php dynamic_sidebar( 'header-left' ) ?>
				<?php dynamic_sidebar( 'header-right' ) ?>

				<!--<div class="pub_sup_left"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/banner-top-left.jpg" /></div>
				<div class="pub_sup_right"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/banner-top-right.jpg" /></div>-->
			
			</div>
		</div>
		
		<div class="contenedor">
		<?php do_action( 'bp_before_header' ) ?>
		
		<div id="header">
			
			<div id="header-shadow-left">
			
			</div>
			
			<div id="header-container">
				<div id="internal-header">
					<h1 id="logo" role="banner"><a href="<?php echo home_url(); ?>" title="<?php _ex( 'Home', 'Home page banner link title', 'buddypress' ); ?>"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/logo.png" /></a></h1>
					
					<div id="social-icons">
						<a target="_blank" href="http://www.facebook.com/ElClubExpress" title="Facebook"><img src="<?php bloginfo('home'); ?>/wp-content/themes/noletia/images/ico_facebook.png" /></a>
						<a target="_blank" href="https://twitter.com/#!/elclubexpress" title="Twitter"><img src="<?php bloginfo('home'); ?>/wp-content/themes/noletia/images/ico_twitter.png" /></a>
						<a href="<?php bloginfo('home'); ?>/contacto/" title="Email"><img src="<?php bloginfo('home'); ?>/wp-content/themes/noletia/images/ico_email.png" /></a>
						<a target="_blank" href="<?php bloginfo('home'); ?>/feed/" title="Feed"><img src="<?php bloginfo('home'); ?>/wp-content/themes/noletia/images/ico_feed.png" /></a>
					</div>
					
					<div class="register-links">
						<?php noletia_login_header(); ?>
					</div>
			
					<div class="select-prov">
						<form action="<?php echo 'http://'.$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]; ?>" method="post">
						<?php
						//$select = wp_dropdown_categories('show_option_none=Selecciona Provincia&orderby=name&echo=0&child_of=14&hide_empty=0&name=header-provincia&id=header-provincia');
						//$select = preg_replace("#<select([^>]*)>#", "<select$1 onchange='return this.form.submit()'>", $select);
						//echo $select;
						?>
						<select name="header-provincia" id="header-provincia" onchange="this.form.submit();"> 
 							<?php if (isset($_COOKIE['noletia_prov'])){ $prov_selected = $_COOKIE['noletia_prov']; }else{ $prov_selected = 'todas'; } ?>
 							<option value="todas" <?php if($prov_selected == 'todas'){ echo 'selected="selected"'; } ?>><?php echo esc_attr(__('Seleccionar provincia')); ?></option> 
 							<option value="A Coruña" <?php if($prov_selected == "A Coruña"){ echo ' selected="selected"'; } ?>>A Coruña</option>
 							<option value="Almería" <?php if($prov_selected == "Almería"){ echo ' selected="selected"'; } ?>>Almería</option>
 							<option value="Cádiz" <?php if($prov_selected == "Cádiz"){ echo ' selected="selected"'; } ?>>Cádiz</option>
 							<option value="Ciudad Real" <?php if($prov_selected == "Ciudad Real"){ echo ' selected="selected"'; } ?>>Ciudad Real</option>
 							<option value="Córdoba" <?php if($prov_selected == "Córdoba"){ echo ' selected="selected"'; } ?>>Córdoba</option>
 							<option value="Granada" <?php if($prov_selected == "Granada"){ echo ' selected="selected"'; } ?>>Granada</option>
 							<option value="Huelva" <?php if($prov_selected == "Huelva"){ echo ' selected="selected"'; } ?>>Huelva</option>
 							<option value="Madrid" <?php if($prov_selected == "Madrid"){ echo ' selected="selected"'; } ?>>Madrid</option>
 							<option value="Málaga" <?php if($prov_selected == "Málaga"){ echo ' selected="selected"'; } ?>>Málaga</option>		
 							<option value="Ourense" <?php if($prov_selected == "Ourense"){ echo ' selected="selected"'; } ?>>Ourense</option>
 							<option value="Pontevedra" <?php if($prov_selected == "Pontevedra"){ echo ' selected="selected"'; } ?>>Pontevedra</option>
 							<option value="Sevilla" <?php if($prov_selected == "Sevilla"){ echo ' selected="selected"'; } ?>>Sevilla</option>
 							<option value="Toledo" <?php if($prov_selected == "Toledo"){ echo ' selected="selected"'; } ?>>Toledo</option>
						</select>
						</form>			
					</div>
					
				</div>
			
				<div id="navigation" role="navigation">
					<ul id="nav">
						<li><a href="<?php bloginfo('home'); ?>/musica/">Música</a></li>
						<li> • </li>
						<li><a href="<?php bloginfo('home'); ?>/artes-escenicas/">Artes Escénicas</a></li>
						<li> • </li>
						<li><a href="<?php bloginfo('home'); ?>/arte/">Arte</a></li>
						<li> • </li>
						<li><a href="<?php bloginfo('home'); ?>/literatura/">Literatura</a></li>
						<li> • </li>
						<li><a href="<?php bloginfo('home'); ?>/audiovisual/">Audiovisual</a></li>
						<li> • </li>
						<li><a href="<?php bloginfo('home'); ?>/formacion/">Formación y encuentros</a></li>
						<li> • </li>
						<li><a class="orange" href="<?php bloginfo('home'); ?>/agenda/">Agenda</a></li>
						<?php
							if(current_user_can('contributor') || current_user_can('editor') || current_user_can('administrator')){ ?>
								<li style="float:right;margin-top:-34px;"><a href="<?php bloginfo('home'); ?>/gestionar-reservas/">Gestionar reservas</a></li>
							<?php }
						?>
					</ul>
					<?php //wp_nav_menu( array( 'container' => false, 'menu_id' => 'nav', 'theme_location' => 'primary', 'fallback_cb' => 'bp_dtheme_main_nav' ) ); ?>
				</div>
				<div id="navigation-secondary">
					<ul id="nav">
						<li><a href="<?php bloginfo('home'); ?>/descuentos/">Descuentos</a></li>
						<li> • </li>
						<li><a href="<?php bloginfo('home'); ?>/concursos/">Concursos</a></li>
						<li> • </li>
						<li><a href="<?php bloginfo('home'); ?>/descargas/">Descargas legales</a></li>
						<li> • </li>
						<li><a href="<?php bloginfo('home'); ?>/newsletter/">Newsletters</a></li>
						<li> • </li>
						<li><a href="<?php bloginfo('home'); ?>/blog/category/el-club-express-tv/">ElClubExpress Tv</a></li>
						
						
					</ul>
					
				</div>
			</div>
			
			<div id="header-shadow-right">
				
			</div>
			
			<?php do_action( 'bp_header' ) ?>

		</div><!-- #header -->
		
		<?php do_action( 'bp_after_header' ) ?>
		<?php do_action( 'bp_before_container' ) ?>

		<div id="container">
		
			<div id="title-nav">
				<!-- Breadcrumb -->
					<?php noletia_the_path(); ?>	
				<!-- Fin Breadcrumb -->
				<div class="search-form-header">
					<?php get_search_form(); ?> 
				</div>
			</div>