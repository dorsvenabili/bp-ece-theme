<?php


function google_jquery() {
wp_deregister_script( 'jquery' );
wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js', false, '1.7.1');
wp_enqueue_script( 'jquery' );
}
add_action('wp_enqueue_scripts', 'google_jquery');



//Attributes for Custom Image Sizes (To use for the Home Slide)
if (function_exists('add_theme_support')){ 
	add_theme_support('post-thumbnails');  
} 
function add_slider_imgsize(){ 
	if (function_exists('add_image_size')){ 
		add_image_size('new-slide-img', 700, 337, true);
		add_image_size('last_event-img', 90, 90, true); 
	} 
} 
add_action('admin_init', 'add_slider_imgsize');

function custom_excerpt_length( $length ) {
	return 30;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

//Función para desactivar la Toolbar de BuddyPress
if(!current_user_can('editor') && !current_user_can('administrator') && !current_user_can('author')){
	define( "BP_DISABLE_ADMIN_BAR", true );
}

//Función para que los autores puedan crear y editar categorías
function noletia_add_theme_caps() {
$role = get_role( 'author' ); // gets the author role
 
$role->add_cap( 'manage_categories' ); // would allow the author to edit others' posts for current theme only
}
add_action( 'admin_init', 'noletia_add_theme_caps');

 
// Añadimos el flitro para que ahora, en las búsquedas por defecto,
//añada los tipos que le hemos definido en la función anterior.
add_filter( 'the_search_query', 'searchAll' );


//Función para devolver un numero determinado de caracteres de la cadena dada
function the_excerpt_max_charlength($string, $charlength) {
	$excerpt = $string;
	$charlength++;

	if ( mb_strlen( $excerpt ) > $charlength ) {
		$subex = mb_substr( $excerpt, 0, $charlength - 5 );
		$exwords = explode( ' ', $subex );
		$excut = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
		if ( $excut < 0 ) {
			echo mb_substr( $subex, 0, $excut );
		} else {
			echo $subex;
		}
		echo '[...]';
	} else {
		echo $excerpt;
	}
}

//Personalización de la función de comentarios
if ( !function_exists( 'bp_dtheme_comment_form' ) ) :
function bp_dtheme_comment_form( $default_labels ) {
	global $user_identity;

	$commenter = wp_get_current_commenter();
	$req = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );
	$fields =  array(
		'author' => '<p class="comment-form-author">' . '<label for="author">' . __( 'Name', 'buddypress' ) . ( $req ? '<span class="required"> *</span>' : '' ) . '</label> ' .
		            '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></p>',
		'email'  => '<p class="comment-form-email"><label for="email">' . __( 'Email', 'buddypress' ) . ( $req ? '<span class="required"> *</span>' : '' ) . '</label> ' .
		            '<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></p>',
		'url'    => '<p class="comment-form-url"><label for="url">' . __( 'Website', 'buddypress' ) . '</label>' .
		            '<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p>',
	);

	$new_labels = array(
		'comment_field'  => '<p class="form-textarea"><textarea name="comment" id="comment" cols="60" rows="10" aria-required="true"></textarea></p>',
		'fields'         => apply_filters( 'comment_form_default_fields', $fields ),
		'logged_in_as'   => '',
		'must_log_in'    => '<p class="alert">Debes <a href="'.get_bloginfo('home').'/registro/?redirect_to='.get_permalink().'">identificarte</a> para dejar un comentario.</p>',
		'title_reply'    => __( 'Leave a reply', 'buddypress' )
	);

	return apply_filters( 'bp_dtheme_comment_form', array_merge( $default_labels, $new_labels ) );
}
add_filter( 'comment_form_defaults', 'bp_dtheme_comment_form', 10 );
endif;


/***********************************************************************************************************/
//****  FUNCIÓN PARA MOSTRAR EL ENLACE DE "Ver todas las noticias" dependiendo de la portada y de la provincia  ******//
/***********************************************************************************************************/
function mostrar_enlace_ver_todas_noticias($tipo_portada){
	
	$provincia = $_COOKIE['noletia_prov'];
	$prov_id = get_category_id($provincia);
	$tipo_id = get_category_id_from_slug($tipo_portada);
	$tipo_name = get_category_name_from_slug($tipo_portada);
	
	
	echo '<p class="ver_todas_noticias">';
		
	if($tipo_portada == 'descuentos' || $tipo_portada == 'concursos'){ 
	?>
		
		<a href="<?php bloginfo('home'); ?>/events/categories/<?php echo $tipo_portada; ?>/">Ver <?php if($tipo_portada == 'concursos' || $tipo_portada == 'descuentos'){ echo 'todos los '.$tipo_portada; } ?> &rarr;</a>
		
	<?php
	}elseif( $tipo_portada == 'global'){ 
			
			if($provincia == '' || $provincia == 'todas'): ?>
				<a href="<?php bloginfo('home'); ?>/?s=&x=0&y=0">Ver todas las noticias &rarr;</a>
			<?php else: ?>
				<a href="<?php bloginfo('home'); ?>/categories/<?php echo $prov_id;?>/search_type/and/order/default/">Ver <?php if($tipo_portada == 'concursos' || $tipo_portada == 'descuentos'){ echo 'todos los '.$tipo_portada; }else{ echo 'todas las noticias '; } echo 'de '.$provincia; ?> &rarr;</a>
			<?php endif;
			
		}else{
			
			if($provincia == '' || $provincia == 'todas'): ?>
				<a href="<?php bloginfo('home'); ?>/categories/<?php echo $tipo_id;?>/search_type/and/order/default/">Ver <?php if($tipo_portada == 'concursos' || $tipo_portada == 'descuentos'){ echo 'todos los '.$tipo_portada; }else{ echo 'todas las noticias '; } if($tipo_name != ''){ echo 'de '.$tipo_name;} ?> &rarr;</a>
			<?php else: ?>
				<a href="<?php bloginfo('home'); ?>/categories/<?php if($tipo_id != ''){ echo $tipo_id.','; } echo $prov_id;?>/search_type/and/order/default/">Ver <?php if($tipo_portada == 'concursos' || $tipo_portada == 'descuentos'){ echo 'todos los '.$tipo_portada; }else{ echo 'todas las noticias '; } if($tipo_name != ''){ echo 'de '.$tipo_name;} echo ' en '.$provincia; ?> &rarr;</a>
			<?php endif;
			
		}
	
	echo '</p>';
}


/***********************************************************************************************************/
//****  COOKIES  ******//
/***********************************************************************************************************/

//Función para crear las cookies
function set_noletia_prov_cookie() {
	
	global $wpdb, $current_user;
	get_currentuserinfo();
	
	$location = 'http://'.$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
	
    if(isset($_POST['header-provincia'])) {
        
        //Si han cambiado el selector de provincia de la cabecera, aumentamos en 1 el contador de esa provincia
    	$contador = get_option('noletia_estadisticas_select_cont_provincia_'.$_POST['header-provincia'],0);
		$contador++;
		update_option('noletia_estadisticas_select_cont_provincia_'.$_POST['header-provincia'], $contador);
		//Fin del código del contador de "changes" en el selector
		
		
		//Cada vez que alguien cambia el selector de provincia de la cabecera se aade un nuevo campo a la tabla: estadisticas_selector_prov     
		date_default_timezone_set('Europe/Madrid');
		$prov = $_POST['header-provincia'];
		$usu = $current_user->user_login;
		$fecha = date('Y-m-d H:i');
		
		$wpdb->query("INSERT INTO estadisticas_selector_prov(provincia, usuario, fecha) VALUES('".$prov."','".$usu."','".$fecha."')");
		//Fin estadisticas_selector_prov
		
        
        if(!isset($_COOKIE['noletia_prov'])){
        	setcookie('noletia_prov', $_POST['header-provincia'], time()+1209600, "/", COOKIE_DOMAIN, false, false);
        	wp_redirect( $location);
			exit;
        }else{
        	setcookie('noletia_prov', "", time()-360);
        	setcookie('noletia_prov', $_POST['header-provincia'], time()+1209600, "/", COOKIE_DOMAIN, false, false);
        	wp_redirect( $location);
			exit;
        }
   }
}
add_action( 'init', 'set_noletia_prov_cookie');


//PANEL DE ADMINISTRACIÓN: GESTOR DE PORTADAS
//Función para crear las cookies para el gestor de portadas del panel de administración. Campo Provincia
function set_noletia_gestor_portadas_cookie() {
	
	$location = 'http://'.$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];	
	if(isset($_POST['noletia_ver_portada'])){
	
		if(isset($_POST['noletia_portadas_prov']) || isset($_POST['noletia_portadas_tipo'])){
			
			setcookie('noletia_gest_port_tipo_prov', $_POST['noletia_portadas_tipo'].'---'.$_POST['noletia_portadas_prov'], time()+1080, "/", COOKIE_DOMAIN, false);
			wp_redirect( $location);
			exit;	
		}
	} 
}
add_action( 'admin_init', 'set_noletia_gestor_portadas_cookie');



/***********************************************************************************************************/
//****  END COOKIES  ******//
/***********************************************************************************************************/

function get_category_id($cat_name){
	$term = get_term_by('slug', $cat_name, 'category');
	return $term->term_id;
}

function get_category_slug_from_name($cat_name){
	$term = get_term_by('name', $cat_name, 'category');
	return $term->slug;
}

function get_category_id_from_slug($cat_slug){
	$term = get_term_by('slug', $cat_slug, 'category');
	return $term->term_id;
}

function get_category_name_from_slug($cat_slug){
	$term = get_term_by('slug', $cat_slug, 'category');
	return $term->name;
}

// Insertar Breadcrumb
function the_breadcrumb() {
	
	$prov = $_COOKIE['noletia_prov'];
	
	if (!is_front_page()) {
		echo '<h2 id="breadcrumb-title"><a class="black size26" href="';
		echo get_option('home');
	        echo '">Inicio</a>';
	    
	    if(is_category() || is_tag()){
	    	echo '<span class="breadcrumb-title2 size26"> | '.wp_title( false, false ).'</span>';
	    }else
		if (is_page()) {
			echo '<span class="breadcrumb-title2 size26"> | '.get_the_title().'</span>';
		}
		echo '</h2>';	
	}else{
		echo '<h2 id="breadcrumb-title"><a class="black size26" href="'.get_bloginfo('home').'">Inicio</a>';
		if($prov != 'todas'){
	    	echo '<span class="breadcrumb-title2 size26"> | '.$prov.'</span>';
	    }
	    echo '</h2>';
	}
}
// fin breadcrumb

// Insertar Breadcrumb
function noletia_the_path() {
	
	global $post;
	
	$prov = $_COOKIE['noletia_prov'];
	$the_post_type = get_post_type($post->ID);
	
	if(is_front_page()){
		echo '<h2 id="breadcrumb-title">Inicio';
		if($prov != 'todas'){
	    	echo '<span class="breadcrumb-title2 size26"> | '.$prov.'</span>';
	    }
	    echo '</h2>';
	}else{
		echo '<h2 id="breadcrumb-title">';
			if(is_category() || is_tag()){
				if(in_category('el-club-express-tv')){
					echo 'El Club Express Tv';
				}else{
					echo wp_title( false, false );
				}
			}elseif(is_search()){
			
				printf( __( '%1$s', 'buddypress' ), wp_title( false, false ) );
			
			}else{
				if(is_single()){
					
					if(in_category('musica')){
						echo 'Música';
					}elseif(in_category('artes-escenicas')){
						echo 'Artes escénicas';
					}elseif(in_category('arte')){
						echo 'Arte';
					}elseif(in_category('literatura')){
						echo 'Literatura';
					}elseif(in_category('audiovisual')){
						echo 'Audiovisual';
					}elseif(in_category('formacion')){
						echo 'Formación';
					}elseif(in_category('descuentos')){
						echo 'Descuentos';
					}elseif(in_category('concursos')){
						echo 'Concursos';
					}elseif(in_category('el-club-express-tv')){
						echo 'El Club Express Tv';
					}else{
						
						if($the_post_type == 'event' || $the_post_type == 'event-recurring'){
							
							if(is_object_in_term($post->ID, 'event-categories','descuentos')){
								echo 'Descuentos';
							}elseif(is_object_in_term($post->ID, 'event-categories','concursos')){
								echo 'Concursos';
							}else{
								echo 'Agenda';
							}
							
						}else{
							echo 'Actualidad';
						}
						
						
					}
					
				}else{
					if(is_page('agenda')){
						echo 'Próximos eventos';
					}else{
					
						$the_title = get_the_title();
						if($the_title != 'Música' && $the_title != 'Artes escénicas' && $the_title != 'Arte' && $the_title != 'Literatura' && $the_title != 'Audiovisual' && $the_title != 'Formación' && $the_title != 'Agenda' && $the_title != 'Descuentos' && $the_title != 'Concursos' && $the_title != 'Descargas' && $the_title != 'Newsletter'){
							echo 'Noticias';
						}else{
							echo get_the_title();
						}
					}
				}	
			}
		
		//Los singles de eventos mostrarán la provincia a la que pertenezca el evento, no la provincia de la cookie:	
		if(($the_post_type == 'event' || $the_post_type == 'event-recurring') && !is_search()){
						
			$prov = '';
						
			if(is_object_in_term( $post->ID, 'event-categories', 'a-coruna')){
				$prov .= ' | A Coruña';
			}if(is_object_in_term($post->ID, 'event-categories', 'almeria')){
				$prov .= ' | Almería';
			}if(is_object_in_term($post->ID, 'event-categories', 'cadiz')){
				$prov .= ' | Cádiz';			
			}if(is_object_in_term($post->ID, 'event-categories', 'ciudad-real')){
				$prov .= ' | Ciudad Real';			
			}if(is_object_in_term($post->ID, 'event-categories', 'cordoba')){
				$prov .= ' | Córdoba';			
			}if(is_object_in_term($post->ID, 'event-categories', 'granada')){
				$prov .= ' | Granada';			
			}if(is_object_in_term($post->ID, 'event-categories', 'huelva')){
				$prov .= ' | Huelva';			
			}if(is_object_in_term($post->ID, 'event-categories', 'malaga')){
				$prov .= ' | Málaga';			
			}if(is_object_in_term($post->ID, 'event-categories', 'madrid')){
				$prov .= ' | Madrid';			
			}if(is_object_in_term($post->ID, 'event-categories', 'ourense')){
				$prov .= ' | Ourense';			
			}if(is_object_in_term($post->ID, 'event-categories', 'sevilla')){
				$prov .= ' | Sevilla';			
			}if(is_object_in_term($post->ID, 'event-categories', 'toledo')){
				$prov .= ' | Toledo';			
			}
			
			echo '<span class="breadcrumb-title2 size26">'.$prov.'</span>';
			
		}else{
			if($prov != 'todas' && !is_search() && !is_page('facebook')){
	    		echo '<span class="breadcrumb-title2 size26"> | '.$prov.'</span>';
	    	}
		}
		
	    echo '</h2>';
	}
	
	
}
// fin breadcrumb

//Función para personalizar los cuadros de búsqueda por defecto
function noletia_search_form( $form ) {

    $form = '<form role="search" method="get" id="searchform" action="' . home_url( '/' ) . '" >
    <div>
    <input type="text" value="' . get_search_query() . '" name="s" id="s" />
    <input type="submit" id="searchsubmit" value="'. esc_attr__('Buscar') .'" />
    </div>
    </form>';

    return $form;
}
add_filter( 'get_search_form', 'noletia_search_form' );


//Función para mostrar el formulario de login en la cabecera
function noletia_login_header(){
	
	if ( is_user_logged_in() ) : ?>

		<?php do_action( 'bp_before_sidebar_me' ) ?>

		<div id="sidebar-me">
			<?php /* ?><a href="<?php echo bp_loggedin_user_domain() ?>">
				<?php bp_loggedin_user_avatar( 'type=thumb&width=30&height=30' ) ?>
			</a><?php */ ?>

			<h4>¡Hola <?php echo bp_core_get_userlink( bp_loggedin_user_id() ); ?>!</h4>	
		</div>
		<div id="access-button">
			<?php $back_url = get_permalink(); ?>
			<a class="button logout" href="<?php echo wp_logout_url( $back_url ) ?>"><?php _e( 'Cerrar sesión', 'buddypress' ) ?></a>
		</div>
		
		<?php do_action( 'bp_sidebar_me' ) ?>

		<?php do_action( 'bp_after_sidebar_me' ) ?>

		<?php if ( bp_is_active( 'messages' ) ) : ?>
			<?php bp_message_get_notices(); /* Site wide notices to all users */ ?>
		<?php endif; ?>

	<?php else : ?>

		<?php do_action( 'bp_before_sidebar_login_form' ) ?>
		
		<p>Indentifícate o <a target="_blank" href="<?php bloginfo('home'); ?>/registro/">regístrate</a> | <a href="<?php bloginfo('home'); ?>/porque-registrarse/">¿Por qué registrarse?</a></p>
		
		<form name="login-form" id="sidebar-login-form" class="standard-form" action="<?php echo site_url( 'wp-login.php', 'login_post' ) ?>" method="post">
			<input type="text" name="log" id="sidebar-user-login" class="input" value="<?php if ( isset( $user_login) ) echo esc_attr(stripslashes($user_login)); ?>" tabindex="97" />

			<input type="password" name="pwd" id="sidebar-user-pass" class="input" value="" tabindex="98" />
			<input type="submit" name="wp-submit" id="sidebar-wp-submit" value="<?php _e( 'Entrar', 'buddypress' ); ?>" tabindex="100" />
			
			<p class="forgetmenot"><label><input name="rememberme" type="checkbox" id="sidebar-rememberme" value="forever" tabindex="99" /> <?php _e( 'Recuérdame', 'buddypress' ) ?></label></p>
			
			<?php echo '<p class="forgot_pass"><a href="'.get_bloginfo('home').'/wp-login.php?action=lostpassword">¿Olvidó su contraseña?</a></p>'; ?>
			
			<?php do_action( 'bp_sidebar_login_form' ) ?>
			<input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI'];?>" />
			
			<input type="hidden" name="testcookie" value="1" />
		</form>

		<?php do_action( 'bp_after_sidebar_login_form' ) ?>

	<?php endif;
}



//Función para comprobar si nos encontramos en alguna de las portadas de sección: Música, Arte, etc.
function nombre_portada_de_seccion(){
	
	$es_portada_de_seccion = '';
	if(is_page('musica')){
		$es_portada_de_seccion = 'musica';
	}elseif(is_page('artes-escenicas')){
		$es_portada_de_seccion = 'artes-escenicas';
	}elseif(is_page('arte')){
		$es_portada_de_seccion = 'arte';
	}elseif(is_page('literatura')){
		$es_portada_de_seccion = 'literatura';
	}elseif(is_page('audiovisual')){
		$es_portada_de_seccion = 'audiovisual';
	}elseif(is_page('formacion')){
		$es_portada_de_seccion = 'formacion';
	}
	
	return $es_portada_de_seccion;
}

/***********************************************************************************************************/
//****  FUNCIÓN PARA MOSTRAR EL SLIDE DE LAS PORTADAS  ******//
/***********************************************************************************************************/
function noletia_show_main_slide($tipo_portada){
	
	global $wpdb;
	
	$prov_slug = get_category_slug_from_name($_COOKIE['noletia_prov']);
	
	if($_COOKIE['noletia_prov'] != ''){
				$prov_selected = ' cat_ID="'.get_category_id($_COOKIE['noletia_prov']).'"';
	}else{
				$prov_selected = '';
	}	
			
	if($prov_selected == ''){
				$opciones_prefijo = 'noletia_'.$tipo_portada.'_todas_';
	}else{
				$opciones_prefijo = 'noletia_'.$tipo_portada.'_'.$prov_slug.'_';
	}
			
	$slide1 = get_option( $opciones_prefijo.'slide1' );
	
		
	if($slide1 != 0){
		$slide2 = get_option( $opciones_prefijo.'slide2' );
		$slide3 = get_option( $opciones_prefijo.'slide3' );
		$slide4 = get_option( $opciones_prefijo.'slide4' );
				
		$slides = $slide1.','.$slide2.','.$slide3.','.$slide4;
			
		if(is_plugin_active('spd-shortcode-slider/spd-shortcode-slider.php')){
			$spd_string = '[spd_slider max_slides="4" slider_display_slide_excerpt="no" slider_layout="numbers-bottom" post_type="post" pageorpost_ids="'.$slides.'" slide_img_size="new-slide-img"]';
					 
		}			
	}else{
		
		$provincia_portada = $_COOKIE['noletia_prov'];
		
		if($tipo_portada == 'global' && $provincia_portada == 'todas'){
			
			$entradas = new WP_Query( 'post_status=publish&order=DESC&orderby=rand&posts_per_page=4&post_type=post&category__not_in=47' );
	
		}else{
			
			$cat_in = '';
			$flag = '';
			$flag2 = '';
			if($tipo_portada != 'global'){ $cat_in .= $tipo_portada; $flag = 'y'; }
			if($provincia_portada != 'todas'){ if($flag == 'y'){ $cat_in .= ','; $flag2 = 'y'; } $cat_in .= $provincia_portada; }
	
			//Si se filtra por provinca y por tipo, tenemos que utilizar category__and
			if($flag2 != 'y'){
				$entradas = new WP_Query( 'post_status=publish&order=DESC&orderby=rand&posts_per_page=4&post_type=post&category__not_in=47&category_name='.$cat_in );
			}else{
				$cat1 = get_category_id($tipo_portada);
				$cat2 = get_category_id($provincia_portada);
				$cat1_and_cat2 = array($cat1,$cat2);
		
				$query = array('post_status' => 'publish', 'order' => 'DESC', 'orderby' => 'rand', 'posts_per_page' => 4, 'post_type' => 'post', 'category__not_in' => 47, 'category__and' => $cat1_and_cat2);
		
				$entradas = new WP_Query($query);

			}
	
		}
		
		// The Loop para el SLIDE
		$slides = '';
		$cont_articulos = 0;
		while ( $entradas->have_posts() ) : $entradas->the_post();
	
			if($cont_articulos > 0){ $slides .= ','; }
			$slides .= get_the_id();
	
			$cont_articulos++;
	
		endwhile;
		// Reset Post Data
		wp_reset_postdata();
		
		//Si no hay noticias con esas características, mostramos el general
		if($slides == ''){
			if(is_plugin_active('spd-shortcode-slider/spd-shortcode-slider.php')){
				$spd_string = '[spd_slider max_slides="4" slider_display_slide_excerpt="no" slider_layout="numbers-bottom" post_type="post" slide_img_size="new-slide-img"]';
			}
		}else{
			if(is_plugin_active('spd-shortcode-slider/spd-shortcode-slider.php')){
				$spd_string = '[spd_slider max_slides="4" slider_display_slide_excerpt="no" slider_layout="numbers-bottom" post_type="post" pageorpost_ids="'.$slides.'" slide_img_size="new-slide-img"]';
			}
		}
				
	}
				
	do_shortcode($spd_string);
	
}

/***********************************************************************************************************/
//****  FUNCIÓN PARA MOSTRAR EL SLIDE DE LAS PORTADAS DE DESCUENTOS ******//
/***********************************************************************************************************/
function noletia_show_main_slide_descuentos($tipo_portada){
	
	global $wpdb;
	
	$current_date = microtime();
	$current_date_secs = explode(" ",$current_date);
	$current_date = $current_date_secs[1];
	
	$prov_slug = get_category_slug_from_name($_COOKIE['noletia_prov']);
	
	if($_COOKIE['noletia_prov'] != '' && $_COOKIE['noletia_prov'] != 'todas'){
				$prov_selected = ' tax_slug="event-categories" term_slug="'.$tipo_portada.','.$prov_slug.'"';
				$opciones_prefijo = 'noletia_'.$tipo_portada.'_'.$prov_slug.'_';
	}else{
				$prov_selected = ' tax_slug="event-categories" term_slug="'.$tipo_portada.'"';
				$opciones_prefijo = 'noletia_'.$tipo_portada.'_todas_';
	}	
			
			
	$slide1 = get_option( $opciones_prefijo.'slide1' );
	
	
	if($slide1 != 0){
		$slide2 = get_option( $opciones_prefijo.'slide2' );
		$slide3 = get_option( $opciones_prefijo.'slide3' );
		$slide4 = get_option( $opciones_prefijo.'slide4' );
		
		$slides = $slide1.','.$slide2.','.$slide3.','.$slide4;
		
		
		
		if(is_plugin_active('spd-shortcode-slider/spd-shortcode-slider.php')){
			$spd_string = '[spd_slider max_slides="4" slider_display_slide_excerpt="no" slider_layout="numbers-bottom" post_type="event" pageorpost_ids="'.$slides.'" slide_img_size="new-slide-img"]';
					 
		}			
	}else{
		$prov = $_COOKIE['noletia_prov'];
		
		//Mostramos los descuentos de la provincia seleccionada
		if($prov != '' && $prov != 'todas'){
			$cont_eventos = 0;
			
			$args = array( 'post_status' => 'publish', 'posts_per_page' => 4, 'post_type' => 'event', 'orderby' => 'meta_value', 'meta_key' => '_event_start_date', 'order' => 'ASC', 
				'tax_query' => array( 
				'relation' => 'AND',
					array(
						'taxonomy' => 'event-categories',
						'field' => 'slug',
						'terms' => array( 'concursos' )
					),
					array(
						'taxonomy' => 'event-categories',
						'field' => 'slug',
						'terms' => array( $prov_portada )
					)
				),
				'meta_query' => array(
					'relation' => 'AND',
					array(
 						'key' => '_start_ts',
 						'value' => $current_date,
 						'type' => 'NUMERIC',
 						'compare' => '>='
 					),
 					array(
 						'key' => '_end_ts',
 						'value' => $current_date,
 						'type' => 'NUMERIC',
 						'compare' => '>='
 					)
				)
			);
			
			$silde_descuentos_ids = new WP_Query($args);
			
			if ($silde_descuentos_ids->have_posts()){ 
	
				while ( $silde_descuentos_ids->have_posts() ){ $silde_descuentos_ids->the_post();
					
					if($cont_eventos > 0){ $slides .= ','; }
					$slides .= get_the_ID();
				
					$cont_eventos++;
					
				}
			}
			
			
		
		}
		
		//Si no hay ningún descuento para esa provincia, que muestre los de la general
		if($cont_eventos == 0 || $prov == '' || $prov == 'todas'){
		
			$cont_eventos = 0;
			
			$args = array( 'post_status' => 'publish', 'posts_per_page' => 4, 'post_type' => 'event', 'orderby' => 'meta_value', 'meta_key' => '_event_start_date', 'order' => 'ASC', 
				'tax_query' => array( 
				'relation' => 'AND',
					array(
						'taxonomy' => 'event-categories',
						'field' => 'slug',
						'terms' => array( 'concursos' )
					)
				),
				'meta_query' => array(
					'relation' => 'AND',
					array(
 						'key' => '_start_ts',
 						'value' => $current_date,
 						'type' => 'NUMERIC',
 						'compare' => '>='
 					),
 					array(
 						'key' => '_end_ts',
 						'value' => $current_date,
 						'type' => 'NUMERIC',
 						'compare' => '>='
 					)
				)
			);
			
			$modulo_concursos_ids = new WP_Query($args);
			
			if ($modulo_concursos_ids->have_posts()){ 
	
				while ( $modulo_concursos_ids->have_posts() ){ $modulo_concursos_ids->the_post();
					
					if($cont_eventos > 0){ $slides .= ','; }
					$slides .= get_the_ID();
				
					$cont_eventos++;
					
				}
			}
			
		}

	
		if(is_plugin_active('spd-shortcode-slider/spd-shortcode-slider.php')){
			$spd_string = '[spd_slider max_slides="4" slider_display_slide_excerpt="no" slider_layout="numbers-bottom" post_type="event" pageorpost_ids="'.$slides.'" slide_img_size="new-slide-img"]';
		}
				
	}//End de la opción automática
	
	do_shortcode($spd_string);
	
}


/***********************************************************************************************************/
//****  FUNCIÓN PARA MOSTRAR LOS CONCURSOS DE LAS PORTADAS  ******//
/***********************************************************************************************************/

//Función para mostrar los últimos eventos de la categoría Concursos en la portada
function mostrar_ult_post_concursos($tipo_portada){
	
	global $wpdb;
	
	$prov_portada = (isset($_COOKIE['noletia_prov'])) ? $_COOKIE['noletia_prov'] : 'todas';
	$tipo_portada = (($tipo_portada == '')) ? 'global' : $tipo_portada; 
	
	$concurso1 = get_option( 'noletia_'.$tipo_portada.'_'.$prov_portada.'_concurso1' );
	$concurso2 = get_option( 'noletia_'.$tipo_portada.'_'.$prov_portada.'_concurso2' );
	$concurso3 = get_option( 'noletia_'.$tipo_portada.'_'.$prov_portada.'_concurso3' );
	$concurso4 = get_option( 'noletia_'.$tipo_portada.'_'.$prov_portada.'_concurso4' );
	
	$current_date = microtime();
	$current_date_secs = explode(" ",$current_date);
	$current_date = $current_date_secs[1];
	
	//Si han seleccionado, al menos un concurso de forma manual, se mostrarán los seleccionados
	if($concurso1 != '' && $concurso1 != 0){
		
		$concursos_in = array($concurso1,$concurso2,$concurso3,$concurso4);
		$args = array( 'post_type' => 'event', 'post__in' => $concursos_in);
	
	//Si en la gestión de portadas no se ha seleccionado el concurso1, entonces se mostrarán de forma automática
	}else{
		
		if($tipo_portada == 'global' && $prov_portada == 'todas'){
			
			$args = array( 'posts_per_page' => 4, 'post_type' => 'event', 'tax_query' => array( 
				'relation' => 'AND',
					array(
						'taxonomy' => 'event-categories',
						'field' => 'slug',
						'terms' => array( 'concursos' )
					)
				),
				'meta_query' => array(
					'relation' => 'AND',
					array(
 						'key' => '_start_ts',
 						'value' => $current_date,
 						'type' => 'NUMERIC',
 						'compare' => '>='
 					),
 					array(
 						'key' => '_end_ts',
 						'value' => $current_date,
 						'type' => 'NUMERIC',
 						'compare' => '>='
 					)
				)
			);
		
		}elseif($tipo_portada == 'global' && $prov_portada != 'todas'){
			
			$prov_id = get_cat_ID($prov_portada);
			$args = array( 'posts_per_page' => 4, 'post_type' => 'event', 'tax_query' => array( 
				'relation' => 'AND',
					array(
						'taxonomy' => 'event-categories',
						'field' => 'slug',
						'terms' => array( 'concursos' )
					),
					array(
						'taxonomy' => 'event-categories',
						'field' => 'id',
						'terms' => array( $prov_id )
					)
				),
				'meta_query' => array(
					'relation' => 'AND',
					array(
 						'key' => '_start_ts',
 						'value' => $current_date,
 						'type' => 'NUMERIC',
 						'compare' => '>='
 					),
 					array(
 						'key' => '_end_ts',
 						'value' => $current_date,
 						'type' => 'NUMERIC',
 						'compare' => '>='
 					)
				)
			);
			
		}elseif($tipo_portada != 'global' && $prov_portada == 'todas'){
			
			$args = array( 'posts_per_page' => 4, 'post_type' => 'event', 'tax_query' => array( 
				'relation' => 'AND',
					array(
						'taxonomy' => 'event-categories',
						'field' => 'slug',
						'terms' => array( 'concursos' )
					),
					array(
						'taxonomy' => 'event-categories',
						'field' => 'slug',
						'terms' => array( $tipo_portada )
					)
				),
				'meta_query' => array(
					'relation' => 'AND',
					array(
 						'key' => '_start_ts',
 						'value' => $current_date,
 						'type' => 'NUMERIC',
 						'compare' => '>='
 					),
 					array(
 						'key' => '_end_ts',
 						'value' => $current_date,
 						'type' => 'NUMERIC',
 						'compare' => '>='
 					)
				) 
			);
		
		}elseif($tipo_portada != 'global' && $prov_portada != 'todas'){	
			
			$prov_id = get_cat_ID($prov_portada);
		
			$args = array( 'posts_per_page' => 4, 'post_type' => 'event', 'tax_query' => array( 
				'relation' => 'AND',
					array(
						'taxonomy' => 'event-categories',
						'field' => 'slug',
						'terms' => array( 'concursos' )
					),
					array(
						'taxonomy' => 'event-categories',
						'field' => 'id',
						'terms' => array( $prov_id )
					),
					array(
						'taxonomy' => 'event-categories',
						'field' => 'slug',
						'terms' => array( $tipo_portada )
					)
				),
				'meta_query' => array(
					'relation' => 'AND',
					array(
 						'key' => '_start_ts',
 						'value' => $current_date,
 						'type' => 'NUMERIC',
 						'compare' => '>='
 					),
 					array(
 						'key' => '_end_ts',
 						'value' => $current_date,
 						'type' => 'NUMERIC',
 						'compare' => '>='
 					)
				)
			);
			
		}
		
	}
	
	
	
	
	$the_query = new WP_Query( $args );

	$cont = 0;
	if ($the_query->have_posts()) :
		while ( $the_query->have_posts() ) : $the_query->the_post();
		
		$concurso_id = get_the_ID();
		
		if(has_post_thumbnail() && $cont == 0):
			echo '<div class="imagen-descuentos"><a href="'.get_permalink().'">';
				echo get_the_post_thumbnail($concurso_id, 'thumbnail');
				echo '</a><a href="'.get_bloginfo('home').'/concursos/"><span class="info"><h3>concursos</h3></span>';
			echo '</a></div>';
		endif;

		//if($cont == 0){ echo '<ul>'; }
		
			$event_start_date = $wpdb->get_var("SELECT event_start_date FROM wp_em_events WHERE post_id = '$concurso_id'");
			$date_event = date_i18n('d/m/Y', strtotime($event_start_date));
		
			/*echo '<li';
			if($cont == 0) { echo ' class="last-li-event"'; }
			echo '><a href="' . get_permalink() . '">'.get_the_title().' <span class="event-portada-date">'.$date_event.'</span></a></li>';*/
			
			if($cont == 0){ echo '<div id="list_concur_frontpage"><ul>'; }
			
			echo '<li';
			if($cont == 0) { echo ' class="last-li-concur"'; }
			echo '><a href="' . get_permalink() . '"><p class="list_concurs_frontpage_title">'.get_the_title().' </p><p class="concur-portada-date">'.$date_event.'</p></a></li>';
		
		$cont++;
	
		endwhile;
		if($cont != 0): echo '</ul></div>'; endif;
	
	else:
		
		//Si en cualquiera de los casos anteriores, la query sale vacía, que muestre todos los últimos Concursos (global-todas)
		$args = array( 'posts_per_page' => 4, 'post_type' => 'event', 'tax_query' => array( 
				'relation' => 'AND',
					array(
						'taxonomy' => 'event-categories',
						'field' => 'slug',
						'terms' => array( 'concursos' )
					)
				),
				'meta_query' => array(
					'relation' => 'AND',
					array(
 						'key' => '_start_ts',
 						'value' => $current_date,
 						'type' => 'NUMERIC',
 						'compare' => '>='
 					),
 					array(
 						'key' => '_end_ts',
 						'value' => $current_date,
 						'type' => 'NUMERIC',
 						'compare' => '>='
 					)
				)
			);
			
		$the_query2 = new WP_Query( $args );
		
		$cont = 0;
		
		if ($the_query2->have_posts()){
			while ( $the_query2->have_posts() ) : $the_query2->the_post();
		
				$concurso_id = get_the_ID();
		
				if(has_post_thumbnail() && $cont == 0):
				echo '<div class="imagen-descuentos"><a href="'.get_permalink().'">';
					echo get_the_post_thumbnail($concurso_id, 'thumbnail');
					echo '</a><a href="'.get_bloginfo('home').'/concursos/"><span class="info"><h3>concursos</h3></span>';
				echo '</a></div>';
				endif;
		
				//if($cont == 0){ echo '<ul>'; }
		
				$event_start_date = $wpdb->get_var("SELECT event_start_date FROM wp_em_events WHERE post_id = '$concurso_id'");
				$date_event = date_i18n('d/m/Y', strtotime($event_start_date));
		
				/*echo '<li';
				if($cont == 0) { echo ' class="last-li-event"'; }
				echo '><a href="' . get_permalink() . '">'.get_the_title().' <span class="event-portada-date">'.$date_event.'</span></a></li>';*/
			
				if($cont == 0){ echo '<div id="list_concur_frontpage"><ul>'; }
			
				echo '<li';
				if($cont == 0) { echo ' class="last-li-concur"'; }
				echo '><a href="' . get_permalink() . '"><p class="list_concurs_frontpage_title">'.get_the_title().' </p><p class="concur-portada-date">'.$date_event.'</p></a></li>';
		
				$cont++;
	
			endwhile;
			if($cont != 0) echo '</ul></div>';
		}else{
			?>
				<div class="imagen-descuentos"><img src="http://elclubexpress.com/wp-content/uploads/low1-658x320-345x200.jpg" class="attachment-thumbnail wp-post-image" alt="low1-658x320" title="low1-658x320" height="200" width="345"><span class="info"><h3>concursos</h3></span>
				</div>
				<div id="list_concur_frontpage"><ul><li class="last-li-concur"><p class="list_concurs_frontpage_title">PRÓXIMAMENTE</p><p class="concur-portada-date"></p></li></ul>
				</div>
			<?php
		}
		

	endif;

	// Reset Post Data
	wp_reset_postdata();

}

/***********************************************************************************************************/
//****  FUNCIÓN PARA MOSTRAR LOS DESCUENTOS DE LAS PORTADAS  ******//
/***********************************************************************************************************/
function mostrar_ult_post_descuentos($tipo_portada){
	
	global $wpdb;
	
	$prov_portada = (isset($_COOKIE['noletia_prov'])) ? $_COOKIE['noletia_prov'] : 'todas';
	$tipo_portada = (($tipo_portada == '')) ? 'global' : $tipo_portada; 
	
	$descuento1 = get_option( 'noletia_'.$tipo_portada.'_'.$prov_portada.'_descuento1' );
	$descuento2 = get_option( 'noletia_'.$tipo_portada.'_'.$prov_portada.'_descuento2' );
	$descuento3 = get_option( 'noletia_'.$tipo_portada.'_'.$prov_portada.'_descuento3' );
	$descuento4 = get_option( 'noletia_'.$tipo_portada.'_'.$prov_portada.'_descuento4' );
	
	
	//Si han seleccionado, al menos un concurso de forma manual, se mostrarán los seleccionados
	if($descuento1 != '' && $descuento1 != 0){
		
		$descuentos_in = array($descuento1,$descuento2,$descuento3,$descuento4);
		$args = array( 'post_type' => 'event', 'post__in' => $descuentos_in);
	
	//Si en la gestión de portadas no se ha seleccionado el concurso1, entonces se mostrarán de forma automática
	}else{
		
		if($tipo_portada == 'global' && $prov_portada == 'todas'){
			
			$args = array( 'posts_per_page' => 4, 'post_type' => 'event', 'tax_query' => array( 
				'relation' => 'AND',
					array(
						'taxonomy' => 'event-categories',
						'field' => 'slug',
						'terms' => array( 'descuentos' )
					)
				) 
			);
		
		}elseif($tipo_portada == 'global' && $prov_portada != 'todas'){
			
			$prov_id = get_cat_ID($prov_portada);
			$args = array( 'posts_per_page' => 4, 'post_type' => 'event', 'tax_query' => array( 
				'relation' => 'AND',
					array(
						'taxonomy' => 'event-categories',
						'field' => 'slug',
						'terms' => array( 'descuentos' )
					),
					array(
						'taxonomy' => 'event-categories',
						'field' => 'id',
						'terms' => array( $prov_id )
					)
				) 
			);
			
		}elseif($tipo_portada != 'global' && $prov_portada == 'todas'){
			
			$args = array( 'posts_per_page' => 4, 'post_type' => 'event', 'tax_query' => array( 
				'relation' => 'AND',
					array(
						'taxonomy' => 'event-categories',
						'field' => 'slug',
						'terms' => array( 'descuentos' )
					),
					array(
						'taxonomy' => 'event-categories',
						'field' => 'slug',
						'terms' => array( $tipo_portada )
					)
				) 
			);
		
		}elseif($tipo_portada != 'global' && $prov_portada != 'todas'){	
			
			$prov_id = get_cat_ID($prov_portada);
		
			$args = array( 'posts_per_page' => 4, 'post_type' => 'event', 'tax_query' => array( 
				'relation' => 'AND',
					array(
						'taxonomy' => 'event-categories',
						'field' => 'slug',
						'terms' => array( 'descuentos' )
					),
					array(
						'taxonomy' => 'event-categories',
						'field' => 'id',
						'terms' => array( $prov_id )
					),
					array(
						'taxonomy' => 'event-categories',
						'field' => 'slug',
						'terms' => array( $tipo_portada )
					)
				) 
			);
			
		}
		
	}
	
	
	
	
	$the_query = new WP_Query( $args );

	$cont = 0;
	if ($the_query->have_posts()) :
		while ( $the_query->have_posts() ) : $the_query->the_post();
		
		//Cargamos el post en el objeto Evento con todos sus cambios
		$EM_Event = em_get_event($post);
		
		if(has_post_thumbnail() && $cont == 0):
			echo '<div class="imagen-descuentos"><a href="'.get_permalink().'">';
				echo get_the_post_thumbnail(get_the_ID(), 'thumbnail');
				echo '</a><a href="'.get_bloginfo('home').'/descuentos/"><span class="info"><h3>descuentos</h3></span>';
			echo '</a></div>';
		endif;
		
		//if($cont == 0){ echo '<ul>'; }
		
			$event_start_date = $wpdb->get_var("SELECT event_start_date FROM wp_em_events WHERE post_id = '$desc_id'");
			$date_event = date_i18n('d/m/Y', strtotime($event_start_date));
		
			/*echo '<li';
			if($cont == 0) { echo ' class="last-li-event"'; }
			echo '><a href="' . get_permalink() . '">'.get_the_title().' <span class="event-portada-date">'.$date_event.'</span></a></li>';*/
			
			if($cont == 0){ echo '<div id="list_concur_frontpage"><ul>'; }
			
			echo '<li';
			if($cont == 0) { echo ' class="last-li-concur"'; }
			echo '><a href="' . get_permalink() . '"><p class="list_concurs_frontpage_title">'.get_the_title().' </p><p class="concur-portada-date">'.$date_event.'</p></a></li>';
		
		$cont++;
	
		endwhile;
		if($cont != 0): echo '<li><a href="'.get_bloginfo('home').'/descuentos/" style="color:#F29400 !important; font-weight:bold;">Ver más descuentos</a></li></ul></div>'; endif;
	
	else:
		
		//Si en cualquiera de los casos anteriores, la query sale vacía, que muestre todos los últimos Concursos (global-todas)
		$args = array( 'posts_per_page' => 4, 'post_type' => 'event', 'tax_query' => array( 
				'relation' => 'AND',
					array(
						'taxonomy' => 'event-categories',
						'field' => 'slug',
						'terms' => array( 'descuentos' )
					)
				) 
			);
			
		$the_query = new WP_Query( $args );
		
		$cont = 0;
		while ( $the_query->have_posts() ) : $the_query->the_post();
		
		if(has_post_thumbnail() && $cont == 0):
			echo '<div class="imagen-descuentos"><a href="'.get_permalink().'">';
				echo get_the_post_thumbnail(get_the_ID(), 'thumbnail');
				echo '</a><a href="'.get_bloginfo('home').'/descuentos/"><span class="info"><h3>descuentos</h3></span>';
			echo '</a></div>';
		endif;
		
		
			$event_start_date = $wpdb->get_var("SELECT event_start_date FROM wp_em_events WHERE post_id = '$desc_id'");
			$date_event = date_i18n('d/m/Y', strtotime($event_start_date));
			
			if($cont == 0){ echo '<div id="list_concur_frontpage"><ul>'; }
			
			echo '<li';
			if($cont == 0) { echo ' class="last-li-concur"'; }
			echo '><a href="' . get_permalink() . '"><p class="list_concurs_frontpage_title">'.get_the_title().' </p><p class="concur-portada-date">'.$date_event.'</p></a></li>';
		
		$cont++;
	
		endwhile;
		if($cont != 0){ echo '<li><a href="'.get_bloginfo('home').'/descuentos/" style="color:#F29400 !important; font-weight:bold;">Ver más descuentos</a></li></ul></div>'; }
	
	endif;

	// Reset Post Data
	wp_reset_postdata();
	
}

/***********************************************************************************************************/
//****  FUNCIÓN PARA MOSTRAR LA AGENDA DE LAS PORTADAS  ******//
/***********************************************************************************************************/
function mostrar_ult_post_agenda2($tipo_portada){
	
	global $wpdb;
	
	$prov_portada = (isset($_COOKIE['noletia_prov'])) ? $_COOKIE['noletia_prov'] : 'todas';
	$tipo_portada = (($tipo_portada == '')) ? 'global' : $tipo_portada; 
	
	$agenda1 = get_option( 'noletia_'.$tipo_portada.'_'.$prov_portada.'_agenda1' );
	$agenda2 = get_option( 'noletia_'.$tipo_portada.'_'.$prov_portada.'_agenda2' );
	$agenda3 = get_option( 'noletia_'.$tipo_portada.'_'.$prov_portada.'_agenda3' );
	$agenda4 = get_option( 'noletia_'.$tipo_portada.'_'.$prov_portada.'_agenda4' );
	$agenda5 = get_option( 'noletia_'.$tipo_portada.'_'.$prov_portada.'_agenda5' );
	
	$current_date = microtime();
	$current_date_secs = explode(" ",$current_date);
	$current_date = $current_date_secs[1];
	

	//Si han seleccionado, al menos un concurso de forma manual, se mostrarán los seleccionados
	if($agenda1 != '' && $agenda1 != 0){
		
		$agendas_in = array($agenda1,$agenda2,$agenda3,$agenda4,$agenda5);
		$args = array( 'post_type' => 'event', 'post__in' => $agendas_in);
	
	//Si en la gestión de portadas no se ha seleccionado la agenda1, entonces se mostrarán de forma automática
	}else{
		
		if($tipo_portada == 'global' && $prov_portada == 'todas'){
			
			//Le quito el parámetro: 'monthnum' => date( 'n', current_time( 'timestamp' ) )
			$args = array( 'posts_per_page' => 5, 'post_type' => 'event', 'orderby' => 'meta_value', 'meta_key' => '_event_start_date', 'order' => 'ASC', 'tax_query' => array( 
				'relation' => 'AND',
					array(
						'taxonomy' => 'event-categories',
						'field' => 'slug',
						'terms' => array( 'descuentos', 'concursos' ),
						'operator' => 'NOT IN'
					)
				),
				'meta_query' => array(
					'relation' => 'AND',
					array(
 						'key' => '_start_ts',
 						'value' => $current_date,
 						'type' => 'NUMERIC',
 						'compare' => '>='
 					),
 					array(
 						'key' => '_end_ts',
 						'value' => $current_date,
 						'type' => 'NUMERIC',
 						'compare' => '>='
 					)
				)
			);
		
		}elseif($tipo_portada == 'global' && $prov_portada != 'todas'){
			
			//Le quito el parámetro: 'monthnum' => date( 'n', current_time( 'timestamp' ) )
			$prov_id = get_term_by( 'slug', $prov_portada, 'event-categories' );
			$args = array( 'posts_per_page' => 5, 'post_type' => 'event', 'orderby' => 'meta_value', 'meta_key' => '_event_start_date', 'order' => 'ASC', 'tax_query' => array( 
				'relation' => 'AND',
					array(
						'taxonomy' => 'event-categories',
						'field' => 'slug',
						'terms' => array( 'descuentos', 'concursos' ),
						'operator' => 'NOT IN'
					),
					array(
						'taxonomy' => 'event-categories',
						'field' => 'id',
						'terms' => array( $prov_id->term_id ),
						'operator' => 'IN'
					)
				),
				'meta_query' => array(
					'relation' => 'AND',
					array(
 						'key' => '_start_ts',
 						'value' => $current_date,
 						'type' => 'NUMERIC',
 						'compare' => '>='
 					),
 					array(
 						'key' => '_end_ts',
 						'value' => $current_date,
 						'type' => 'NUMERIC',
 						'compare' => '>='
 					)
				)
			);
			
		}elseif($tipo_portada != 'global' && $prov_portada == 'todas'){
			
			//Le quito el parámetro: 'monthnum' => date( 'n', current_time( 'timestamp' ) )
			$args = array( 'posts_per_page' => 5, 'post_type' => 'event', 'orderby' => 'meta_value', 'meta_key' => '_event_start_date', 'order' => 'ASC', 'tax_query' => array( 
				'relation' => 'AND',
					array(
						'taxonomy' => 'event-categories',
						'field' => 'slug',
						'terms' => array( 'descuentos', 'concursos' ),
						'operator' => 'NOT IN'
					),
					array(
						'taxonomy' => 'event-categories',
						'field' => 'slug',
						'terms' => array( $tipo_portada )
					)
				),
				'meta_query' => array(
					'relation' => 'AND',
					array(
 						'key' => '_start_ts',
 						'value' => $current_date,
 						'type' => 'NUMERIC',
 						'compare' => '>='
 					),
 					array(
 						'key' => '_end_ts',
 						'value' => $current_date,
 						'type' => 'NUMERIC',
 						'compare' => '>='
 					)
				)
			);
		
		}elseif($tipo_portada != 'global' && $prov_portada != 'todas'){	
			
					
			//Le quito el parámetro: 'monthnum' => date( 'n', current_time( 'timestamp' ) )
			$args = array( 'posts_per_page' => 5, 'post_type' => 'event', 'orderby' => 'meta_value', 'meta_key' => '_event_start_date', 'order' => 'ASC', 'tax_query' => array( 
				'relation' => 'AND',
					array(
						'taxonomy' => 'event-categories',
						'field' => 'slug',
						'terms' => array( 'descuentos', 'concursos' ),
						'operator' => 'NOT IN'
					),
					array(
						'taxonomy' => 'event-categories',
						'field' => 'slug',
						'terms' => array( $prov_portada )
					),
					array(
						'taxonomy' => 'event-categories',
						'field' => 'slug',
						'terms' => array( $tipo_portada )
					)					
				),
				'meta_query' => array(
					'relation' => 'AND',
					array(
 						'key' => '_start_ts',
 						'value' => $current_date,
 						'type' => 'NUMERIC',
 						'compare' => '>='
 					),
 					array(
 						'key' => '_end_ts',
 						'value' => $current_date,
 						'type' => 'NUMERIC',
 						'compare' => '>='
 					)
				) 
			);
			
		}
		
	}
	
	//Añadimos con el gancho, la condición de ordenar la query con las condiciones que le pasamos en la función
	//add_action('posts_join','noletia_posts_join' );
	//add_action('posts_groupby','noletia_posts_groupby' );	
	//The query
	$the_query = new WP_Query( $args );
	//Borramos el gancho, para que el resto de querys funcionen si esa ordenación
	//remove_action('posts_join','noletia_posts_join');
	//remove_action('posts_groupby','noletia_posts_groupby');

	$cont = 0;
	if ($the_query->have_posts()) :
		while ( $the_query->have_posts() ) : $the_query->the_post();
			
			$the_event_id = get_the_ID();
			$event_start_date = $wpdb->get_var("SELECT event_start_date FROM wp_em_events WHERE post_id = '$the_event_id'");
			$date_event = date_i18n('d/m/Y', strtotime($event_start_date));
						
			if($cont == 0){
				echo '<div id="last_event_frontpage">';
					if(has_post_thumbnail()):
						echo '<div id="last_event_frontpage_image"><a href="'.get_permalink().'">';
						echo get_the_post_thumbnail(get_the_ID(), 'last_event-img');
						echo '</a></div>';
					endif;
				
					echo '<p><a href="' . get_permalink() . '"><span class="last_event_frontpage_title">'.get_the_title().' </span><span class="last_event_frontpage_date">'.$date_event.'</span></a></p>';
				
				echo '</div>';
			}else{
			
				if($cont == 1){ echo '<div id="list_events_frontpage"><ul>'; }
			
				echo '<li><a href="' . get_permalink() . '"><p class="list_events_frontpage_title">'.get_the_title().' </p><p class="event-portada-date">'.$date_event.'</p></a></li>';
			
		}
		
		$cont++;
	
		endwhile;
		
		
		if($cont != 1): echo '</ul></div>'; endif;
		
		
	
	else:
		
		//Si en cualquiera de los casos anteriores, la query sale vacía, que muestre todos los últimos Concursos (global-todas)
		//Le quito el parámetro: 'monthnum' => date( 'n', current_time( 'timestamp' ) )
		$args = array( 'posts_per_page' => 5, 'post_type' => 'event', 'orderby' => 'meta_value', 'meta_key' => '_event_start_date', 'order' => 'ASC', 'tax_query' => array( 
					array(
						'taxonomy' => 'event-categories',
						'field' => 'slug',
						'terms' => array( 'descuentos', 'concursos' ),
						'operator' => 'NOT IN'
					)
				),
				'meta_query' => array(
					'relation' => 'AND',
					array(
 						'key' => '_start_ts',
 						'value' => $current_date,
 						'type' => 'NUMERIC',
 						'compare' => '>='
 					),
 					array(
 						'key' => '_end_ts',
 						'value' => $current_date,
 						'type' => 'NUMERIC',
 						'compare' => '>='
 					)
				) 
			);
			
		$the_query = new WP_Query( $args );
		
		$cont = 0;
		while ( $the_query->have_posts() ) : $the_query->the_post();
			
			$the_event_id = get_the_ID();
			$event_start_date = $wpdb->get_var("SELECT event_start_date FROM wp_em_events WHERE post_id = '$the_event_id'");
			$date_event = date_i18n('d/m/Y', strtotime($event_start_date));
		
			if($cont == 0){
				echo '<div id="last_event_frontpage">';
					if(has_post_thumbnail()):
						echo '<div id="last_event_frontpage_image"><a href="'.get_permalink().'">';
						echo get_the_post_thumbnail(get_the_ID(), 'last_event-img');
						echo '</a></div>';
					endif;
				
					echo '<p><a href="' . get_permalink() . '"><span class="last_event_frontpage_title">'.get_the_title().' </span><span class="last_event_frontpage_date">'.$date_event.'</span></a></p>';
				
				echo '</div>';
			}else{
			
				if($cont == 1){ echo '<div id="list_events_frontpage"><ul>'; }
			
				echo '<li><a href="' . get_permalink() . '">'.get_the_title().' <span class="event-portada-date">'.$date_event.'</span></a></li>';
			
		}
		
		$cont++;
	
		endwhile;
		
		if($cont != 1): echo '</ul></div>'; endif;	
	
	endif;

	// Reset Post Data
	wp_reset_postdata();
	
}


//Función para mostrar aquellos eventos que estén en wp_events y en wp_posts
function noletia_posts_join($join) {
    global $wpdb;
    $join .= " INNER JOIN wp_em_events ON wp_posts.ID = wp_em_events.post_id ";
    return $join;
}
//Función para incluir la ordenación de las querys por los campos de fecha de la tabla wp_em_events
function noletia_posts_groupby($groupby) {
    global $wpdb;
    $groupby = " wp_em_events.event_start_date ASC ";
    return $groupby;
}

function noletia_posts_where($where) {
    global $wpdb;
    $where .= $wpdb->prepare( " AND wp_posts.post_title NOT REGEXP %s ", '^[[:alpha:]]' );
    return $where;
}

function posts_fields($field_list) {
    global $wpdb;
    $field_list = " {$wpdb->terms}.term_id, {$wpdb->terms}.name AS term_name, {$wpdb->terms}.slug AS term_slug, COUNT(*) as post_count ";
    return $field_list;
  }




function mostrar_ult_post_agenda($tipo_portada){
	
	global $wpdb;
	
	$prov_portada = (isset($_COOKIE['noletia_prov'])) ? $_COOKIE['noletia_prov'] : 'todas';
	$tipo_portada = (($tipo_portada == '')) ? 'global' : $tipo_portada; 
	
	
	?>
	<div class="agenda-portada-ult">
		<div class="agenda-portada-desc">
			
			
			<?php 
				$query_agenda = devolver_query_eventos($tipo_portada, $prov_portada, 5);
				$args_ult_event = $wpdb->get_results($query_agenda);
			
				//print_r($args_ult_event);
				
				$cont = 0;
				foreach($args_ult_event as $arg_ult_event){
	
					//$event_start_date = $wpdb->get_var("SELECT event_start_date FROM wp_em_events WHERE post_id = '$desc_id'");
					$date_event = date_i18n('d/m/Y', strtotime($arg_ult_event->event_start_date));
		
					if($cont == 0){
						echo '<div id="last_event_frontpage">';
						if(has_post_thumbnail($arg_ult_event->ID)):
							echo '<div id="last_event_frontpage_image"><a href="'.get_permalink($arg_ult_event->ID).'">';
							echo get_the_post_thumbnail($arg_ult_event->ID, 'last_event-img');
							echo '</a></div>';
						endif;
				
						echo '<p><a href="' . get_permalink($arg_ult_event->ID) . '"><span class="last_event_frontpage_title">'.get_the_title($arg_ult_event->ID).' </span><span class="last_event_frontpage_date">'.$date_event.'</span></a></p>';
				
						echo '</div>';
					}else{
			
						if($cont == 1){ echo '<div id="list_events_frontpage"><ul>'; }
			
						echo '<li><a href="' . get_permalink($arg_ult_event->ID) . '"><p class="list_events_frontpage_title">'.get_the_title($arg_ult_event->ID).' </p><p class="event-portada-date">'.$date_event.'</p></a></li>';
					}
					$cont++;
				}
				if($cont>=2){ echo '</ul></div>'; }
			?>
		</div>
	</div>
	
	
	<?php
	

}


/***********************************************************************************************************/
//****  FUNCIÓN PARA devilver la query de eventos dependiendo del tipo de portada, provincia
//****  y numero de posts que le pasamos por parámetros ******//
/***********************************************************************************************************/
function devolver_query_eventos($tipo_portada, $prov_portada, $num_posts){

	$agenda1 = get_option( 'noletia_'.$tipo_portada.'_'.$prov_portada.'_agenda1' );
	
	$current_date = microtime();
	$current_date_secs = explode(" ",$current_date);
	$current_date = $current_date_secs[1];
	
	if($agenda1 != '' && $agenda1 != 0){
	
		$agenda2 = get_option( 'noletia_'.$tipo_portada.'_'.$prov_portada.'_agenda2' );
		$agenda3 = get_option( 'noletia_'.$tipo_portada.'_'.$prov_portada.'_agenda3' );
		$agenda4 = get_option( 'noletia_'.$tipo_portada.'_'.$prov_portada.'_agenda4' );
		$agenda5 = get_option( 'noletia_'.$tipo_portada.'_'.$prov_portada.'_agenda5' );
		//echo 'a1:'.$agenda1.'-a2:'.$agenda2.'-a3:'.$agenda3.'-a4:'.$agenda4.'-a5:'.$agenda5;
		$args = "SELECT * FROM wp_posts WHERE ID=".$agenda1." OR ID=".$agenda2." OR ID=".$agenda3." OR ID=".$agenda4." OR ID=".$agenda5."";
		
	}else{
		
		//Portada global para todas las provincias
		if($tipo_portada == 'global' && $prov_portada == 'todas'){
				
			$args = "SELECT DISTINCT wp_posts.ID FROM (wp_posts
				INNER JOIN wp_em_events ON(wp_posts.ID = wp_em_events.post_id)
				INNER JOIN wp_term_relationships ON(wp_em_events.post_id = wp_term_relationships.object_id)
				INNER JOIN wp_term_taxonomy ON(wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_taxonomy_id)
				INNER JOIN wp_terms ON(wp_term_taxonomy.term_id = wp_terms.term_id)
				)
				INNER JOIN
				(
				wp_posts AS wp_posts2
				INNER JOIN wp_em_events AS wp_em_events2 ON(wp_posts2.ID = wp_em_events2.post_id)
				INNER JOIN wp_term_relationships AS wp_term_relationships2 ON(wp_em_events2.post_id = wp_term_relationships2.object_id)
				INNER JOIN wp_term_taxonomy AS wp_term_taxonomy2 ON(wp_term_relationships2.term_taxonomy_id = wp_term_taxonomy2.term_taxonomy_id)
				INNER JOIN wp_terms AS wp_terms2 ON(wp_term_taxonomy2.term_id = wp_terms2.term_id)
				)
				WHERE wp_posts.post_type = 'event'
				AND wp_posts.post_status = 'publish'
				AND wp_term_taxonomy.taxonomy = 'event-categories'
				AND wp_terms.slug <> 'concursos'
				AND wp_posts2.post_type = 'event'
				AND wp_posts2.post_status = 'publish'
				AND wp_term_taxonomy2.taxonomy = 'event-categories'
				AND wp_terms2.slug <> 'descuentos'
				AND wp_em_events.event_end_date >= '".date('Y-m-d')."'
				AND wp_em_events2.event_end_date >= '".date('Y-m-d')."'
				AND wp_posts.ID = wp_posts2.ID
				ORDER BY wp_em_events.event_start_date ASC, wp_em_events.event_start_time ASC";
			
		
		//Portada global con una provincia seleccionada
		}elseif($tipo_portada == 'global' && $prov_portada != 'todas'){
			
			$args = "SELECT DISTINCT wp_posts.ID FROM (wp_posts
				INNER JOIN wp_em_events ON(wp_posts.ID = wp_em_events.post_id)
				INNER JOIN wp_term_relationships ON(wp_em_events.post_id = wp_term_relationships.object_id)
				INNER JOIN wp_term_taxonomy ON(wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_taxonomy_id)
				INNER JOIN wp_terms ON(wp_term_taxonomy.term_id = wp_terms.term_id)
				)
				INNER JOIN
				(
				wp_posts AS wp_posts2
				INNER JOIN wp_em_events AS wp_em_events2 ON(wp_posts2.ID = wp_em_events2.post_id)
				INNER JOIN wp_term_relationships AS wp_term_relationships2 ON(wp_em_events2.post_id = wp_term_relationships2.object_id)
				INNER JOIN wp_term_taxonomy AS wp_term_taxonomy2 ON(wp_term_relationships2.term_taxonomy_id = wp_term_taxonomy2.term_taxonomy_id)
				INNER JOIN wp_terms AS wp_terms2 ON(wp_term_taxonomy2.term_id = wp_terms2.term_id)
				)
				WHERE wp_posts.post_type = 'event'
				AND wp_posts.post_status = 'publish'
				AND wp_term_taxonomy.taxonomy = 'event-categories'
				AND wp_terms.slug != 'concursos'
				AND wp_posts2.post_type = 'event'
				AND wp_posts2.post_status = 'publish'
				AND wp_term_taxonomy2.taxonomy = 'event-categories'
				AND wp_terms2.slug != 'descuentos'
				AND wp_posts.ID = wp_posts2.ID
				AND wp_em_events.event_end_date >= '".date('Y-m-d')."'
				AND wp_em_events2.event_end_date >= '".date('Y-m-d')."'
				AND wp_em_events.event_start_date <= '".date('Y-m-d')."'
				AND wp_em_events2.event_start_date <= '".date('Y-m-d')."'
				ORDER BY wp_em_events.event_start_date ASC, wp_em_events.event_start_time ASC";
			
		
		//Portada de algún tipo (música...) para todas las provincias
		}elseif($tipo_portada != 'global' && $prov_portada == 'todas'){
			
			$args = "SELECT DISTINCT wp_posts.ID FROM (wp_posts
				INNER JOIN wp_em_events ON(wp_posts.ID = wp_em_events.post_id)
				INNER JOIN wp_term_relationships ON(wp_em_events.post_id = wp_term_relationships.object_id)
				INNER JOIN wp_term_taxonomy ON(wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_taxonomy_id)
				INNER JOIN wp_terms ON(wp_term_taxonomy.term_id = wp_terms.term_id)
				)
				INNER JOIN
				(
				wp_posts AS wp_posts2
				INNER JOIN wp_em_events AS wp_em_events2 ON(wp_posts2.ID = wp_em_events2.post_id)
				INNER JOIN wp_term_relationships AS wp_term_relationships2 ON(wp_em_events2.post_id = wp_term_relationships2.object_id)
				INNER JOIN wp_term_taxonomy AS wp_term_taxonomy2 ON(wp_term_relationships2.term_taxonomy_id = wp_term_taxonomy2.term_taxonomy_id)
				INNER JOIN wp_terms AS wp_terms2 ON(wp_term_taxonomy2.term_id = wp_terms2.term_id)
				)
				WHERE wp_posts.post_type = 'event'
				AND wp_posts.post_status = 'publish'
				AND wp_term_taxonomy.taxonomy = 'event-categories'
				AND wp_terms.slug != 'concursos'
				AND wp_posts2.post_type = 'event'
				AND wp_posts2.post_status = 'publish'
				AND wp_term_taxonomy2.taxonomy = 'event-categories'
				AND wp_terms2.slug != 'descuentos'
				AND wp_posts.ID = wp_posts2.ID
				AND wp_em_events.event_end_date >= '".date('Y-m-d')."'
				AND wp_em_events2.event_end_date >= '".date('Y-m-d')."'
				AND wp_em_events.event_start_date <= '".date('Y-m-d')."'
				AND wp_em_events2.event_start_date <= '".date('Y-m-d')."'
				ORDER BY wp_em_events.event_start_date ASC, wp_em_events.event_start_time ASC";
			
		
		//Portada de algún tipo y con una provincia seleccionada
		}elseif($tipo_portada != 'global' && $prov_portada != 'todas'){	
			
			$args = "SELECT DISTINCT wp_posts.ID FROM (wp_posts
				INNER JOIN wp_em_events ON(wp_posts.ID = wp_em_events.post_id)
				INNER JOIN wp_term_relationships ON(wp_em_events.post_id = wp_term_relationships.object_id)
				INNER JOIN wp_term_taxonomy ON(wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_taxonomy_id)
				INNER JOIN wp_terms ON(wp_term_taxonomy.term_id = wp_terms.term_id)
				)
				INNER JOIN
				(
				wp_posts AS wp_posts2
				INNER JOIN wp_em_events AS wp_em_events2 ON(wp_posts2.ID = wp_em_events2.post_id)
				INNER JOIN wp_term_relationships AS wp_term_relationships2 ON(wp_em_events2.post_id = wp_term_relationships2.object_id)
				INNER JOIN wp_term_taxonomy AS wp_term_taxonomy2 ON(wp_term_relationships2.term_taxonomy_id = wp_term_taxonomy2.term_taxonomy_id)
				INNER JOIN wp_terms AS wp_terms2 ON(wp_term_taxonomy2.term_id = wp_terms2.term_id)
				)
				WHERE wp_posts.post_type = 'event'
				AND wp_posts.post_status = 'publish'
				AND wp_term_taxonomy.taxonomy = 'event-categories'
				AND wp_terms.slug != 'concursos'
				AND wp_posts2.post_type = 'event'
				AND wp_posts2.post_status = 'publish'
				AND wp_term_taxonomy2.taxonomy = 'event-categories'
				AND wp_terms2.slug != 'descuentos'
				AND wp_posts.ID = wp_posts2.ID
				AND wp_em_events.event_end_date >= '".date('Y-m-d')."'
				AND wp_em_events2.event_end_date >= '".date('Y-m-d')."'
				AND wp_em_events.event_start_date <= '".date('Y-m-d')."'
				AND wp_em_events2.event_start_date <= '".date('Y-m-d')."'
				ORDER BY wp_em_events.event_start_date ASC, wp_em_events.event_start_time ASC";
			
			
		}
		if($num_posts != 0) $args .= " LIMIT ".$num_posts."";
	}

	return $args;	

}


/***********************************************************************************************************/
//****  FUNCIÓN PARA devolver la query de eventos dependiendo del tipo de portada, provincia
//****  y numero de posts que le pasamos por parámetros ******//
/***********************************************************************************************************/
function devolver_query_eventos_gestor_portada($tipo_portada, $prov_portada, $num_posts = -1){
	
	global $wpdb;
	
	$current_date = microtime();
	$current_date_secs = explode(" ",$current_date);
	$current_date = $current_date_secs[1];
	
	
	$nombre_query_cache = 'gestor_portada_'.$tipo_portada.'_'.$provincia_portada.'_seccion_agenda';
	$agenda_ids = get_site_transient( $nombre_query_cache );
	if ( false === $agenda_ids ) {
		$agenda_ids = new WP_Query( array( 'post_status' => 'publish', 'posts_per_page' => 200, 'post_type' => 'event', 'orderby' => 'meta_value', 'meta_key' => '_event_start_date', 'order' => 'ASC', 'meta_query' => array(
					'relation' => 'AND',
					array(
 						'key' => '_start_ts',
 						'value' => $current_date,
 						'type' => 'NUMERIC',
 						'compare' => '>='
 					),
 					array(
 						'key' => '_end_ts',
 						'value' => $current_date,
 						'type' => 'NUMERIC',
 						'compare' => '>='
 					)
				) 
			) );
		set_site_transient( $nombre_query_cache, $agenda_ids, 300 );
	}

	
	if ($agenda_ids->have_posts()){ 
	
		$noletia_agenda_id = array(0);
		$noletia_agenda = array('Automático');
		$cont_eventos = 1;
		
		while ( $agenda_ids->have_posts() ){ $agenda_ids->the_post();
			
			$agenda_id = get_the_ID();
			$the_event = $wpdb->get_row("SELECT * FROM wp_em_events WHERE post_id = '$agenda_id'");
			$date_event = date_i18n('d/m/Y', strtotime($the_event->event_start_date));
			$the_location = $wpdb->get_row("SELECT * FROM wp_em_locations WHERE location_id = '".$the_event->location_id."'");
			
			if($agenda_id_anterior != $agenda_id){
				$noletia_agenda_id[$cont_eventos] = $agenda_id;
				$noletia_agenda[$cont_eventos] = get_the_title($agenda_id).' - Fecha inicio: '.$date_event.' - Hora: '.$the_event->event_start_time.' - Espacio: '.$the_location->location_name.' ( '.$the_location->location_town.' )';
				$agenda_id_anterior = $agenda_id;
			
				$cont_eventos++;
			}
	
		}	
	}
	
	//Cargamos los arrays en una matriz para devolverla con la función
	$noletia_agenda_selects['ids'] = $noletia_agenda_id;
	$noletia_agenda_selects['names'] = $noletia_agenda;


	// Reset Post Data
	wp_reset_postdata();

	return $noletia_agenda_selects;	

}


/***********************************************************************************************************/
//****  FUNCIÓN PARA devolver la query de eventos (descuentos o concursos) dependiendo del tipo de portada, provincia
//****  y numero de posts que le pasamos por parámetros ******//
/***********************************************************************************************************/
function devolver_query_desc_o_conc_gestor_portada($tipo_portada, $prov_portada, $tipo_evento, $num_posts = -1){
	
	global $wpdb, $post;
	
	$current_date = microtime();
	$current_date_secs = explode(" ",$current_date);
	$current_date = $current_date_secs[1];
	
	$querystr = "
	SELECT * FROM $wpdb->posts
	LEFT JOIN $wpdb->postmeta ON($wpdb->posts.ID = $wpdb->postmeta.post_id)
	LEFT JOIN $wpdb->term_relationships ON($wpdb->posts.ID = $wpdb->term_relationships.object_id)
	LEFT JOIN $wpdb->term_taxonomy ON($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)
	LEFT JOIN $wpdb->terms ON($wpdb->term_taxonomy.term_id = $wpdb->terms.term_id)
	WHERE $wpdb->terms.name = '$tipo_evento'
	AND $wpdb->term_taxonomy.taxonomy = 'event-categories'
	AND $wpdb->posts.post_status = 'publish'
	AND $wpdb->posts.post_type = 'event'
	AND $wpdb->postmeta.meta_key = '_end_ts'
	AND $wpdb->postmeta.meta_value >= $current_date
	ORDER BY $wpdb->postmeta.meta_value ASC
	";


	$nombre_query_cache = 'gestor_portada_'.$tipo_portada.'_'.$provincia_portada.'_seccion_'.$tipo_evento;
	$pageposts = get_site_transient( $nombre_query_cache );
	if ( false === $pageposts ) {
		$pageposts = $wpdb->get_results($querystr, OBJECT);
		set_site_transient( $nombre_query_cache, $pageposts, 300 );
	}


if ($pageposts):
  	global $post;
  	
  	$noletia_agenda_id = array(0);
	$noletia_agenda = array('Automático');
	$cont_eventos = 1;
		
  	foreach ($pageposts as $post):
    	setup_postdata($post);
    
    		$agenda_id = get_the_ID();
			$the_event = $wpdb->get_row("SELECT * FROM wp_em_events WHERE post_id = '$agenda_id'");
			$date_event = date_i18n('d/m/Y', strtotime($the_event->event_end_date));
			$the_location = $wpdb->get_row("SELECT * FROM wp_em_locations WHERE location_id = '".$the_event->location_id."'");
			
			if($agenda_id_anterior != $agenda_id){
				$noletia_agenda_id[$cont_eventos] = $agenda_id;
				$noletia_agenda[$cont_eventos] = get_the_title($agenda_id).' - Fecha fin: '.$date_event.' - Hora: '.$the_event->event_start_time.' - Espacio: '.$the_location->location_name.' ( '.$the_location->location_town.' )';
				$agenda_id_anterior = $agenda_id;
			
				$cont_eventos++;
			}
    
  	endforeach;
endif;
		
	//Cargamos los arrays en una matriz para devolverla con la función
	$noletia_agenda_selects['ids'] = $noletia_agenda_id;
	$noletia_agenda_selects['names'] = $noletia_agenda;


	// Reset Post Data
	wp_reset_postdata();

	return $noletia_agenda_selects;	

}


/***********************************************************************************************************/
//****  FUNCIÓN PARA MOSTRAR LOS MÓDULOS MEDIANOS DE LAS PORTADAS DE DESCUENTOS ******//
/***********************************************************************************************************/
function mostrar_modulo_portada_descuentos($tipo_portada, $offset){
	
	global $wpdb, $post;
	
	$prov_slug = get_category_slug_from_name($_COOKIE['noletia_prov']);
	
	$prov_portada = (isset($_COOKIE['noletia_prov'])) ? $prov_slug : 'todas';
	$tipo_portada = (($tipo_portada == '')) ? 'global' : $tipo_portada;
	
	$descuento1 = get_option( 'noletia_'.$tipo_portada.'_'.$prov_portada.'_descuento1' );
	
		
	if($descuento1 != '' && $descuento1 != 0 ){
		
		$descuento2 = get_option( 'noletia_'.$tipo_portada.'_'.$prov_portada.'_descuento2' );
		$descuento3 = get_option( 'noletia_'.$tipo_portada.'_'.$prov_portada.'_descuento3' );
		$descuento4 = get_option( 'noletia_'.$tipo_portada.'_'.$prov_portada.'_descuento4' );
		
		$post_in = array($descuento1,$descuento2,$descuento3,$descuento4);
		
		$args = array( 'post_type' => 'event', 'order' => 'DESC', 'orderby' => 'modified', 'post__in' => $post_in );
		
	}else{
				
		$prov = $_COOKIE['noletia_prov'];
		
		//Mostramos los descuentos de la provincia seleccionada
		if($prov != '' && $prov != 'todas'){
			
			$cont_eventos = 0;
			$modulo_descuentos_ids = $wpdb->get_results("SELECT * FROM
(wp_posts
INNER JOIN wp_em_events ON(wp_posts.ID = wp_em_events.post_id)
INNER JOIN wp_term_relationships ON(wp_em_events.post_id = wp_term_relationships.object_id)
INNER JOIN wp_term_taxonomy ON(wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_taxonomy_id)
INNER JOIN wp_terms ON(wp_term_taxonomy.term_id = wp_terms.term_id)
)
INNER JOIN
(
wp_posts AS wp_posts2
INNER JOIN wp_em_events AS wp_em_events2 ON(wp_posts2.ID = wp_em_events2.post_id)
INNER JOIN wp_term_relationships AS wp_term_relationships2 ON(wp_em_events2.post_id = wp_term_relationships2.object_id)
INNER JOIN wp_term_taxonomy AS wp_term_taxonomy2 ON(wp_term_relationships2.term_taxonomy_id = wp_term_taxonomy2.term_taxonomy_id)
INNER JOIN wp_terms AS wp_terms2 ON(wp_term_taxonomy2.term_id = wp_terms2.term_id)
)

WHERE wp_posts.post_type = 'event'
AND wp_posts.post_status = 'publish'
AND wp_term_taxonomy.taxonomy = 'event-categories'
AND wp_terms.slug = 'descuentos'
AND wp_posts2.post_type = 'event'
AND wp_posts2.post_status = 'publish'
AND wp_term_taxonomy2.taxonomy = 'event-categories'
AND wp_terms2.slug = '".$prov."'
AND wp_posts.ID = wp_posts2.ID
ORDER BY wp_em_events.event_start_date ASC, wp_em_events.event_start_time ASC LIMIT 4
");
			
			foreach($modulo_descuentos_ids as $descuentos_id){
		
				if($cont_eventos > 0){ $slides .= ','; }
				$modulos[$cont_eventos] = $descuentos_id->ID;
				
				$cont_eventos++;
			}
		
		}
		
		//Si no hay ningún descuento para esa provincia, que muestre los de la general
		if($cont_eventos == 0 || $prov == '' || $prov == 'todas'){
			
			$cont_eventos = 0;
			$modulo_descuentos_ids = $wpdb->get_results("SELECT * FROM
(wp_posts
INNER JOIN wp_em_events ON(wp_posts.ID = wp_em_events.post_id)
INNER JOIN wp_term_relationships ON(wp_em_events.post_id = wp_term_relationships.object_id)
INNER JOIN wp_term_taxonomy ON(wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_taxonomy_id)
INNER JOIN wp_terms ON(wp_term_taxonomy.term_id = wp_terms.term_id)
)

WHERE wp_posts.post_type = 'event'
AND wp_posts.post_status = 'publish'
AND wp_term_taxonomy.taxonomy = 'event-categories'
AND wp_terms.slug = 'descuentos'
ORDER BY wp_em_events.event_start_date ASC, wp_em_events.event_start_time ASC LIMIT 4
");
			foreach($modulo_descuentos_ids as $descuentos_id){
		
				if($cont_eventos > 0){ $slides .= ','; }
				
				$modulos[$cont_eventos] = $descuentos_id->ID;
				
				$cont_eventos++;
				
			}
		}
		
		$args = array( 'posts_per_page' => 4, 'post_type' => 'event', 'order' => 'DESC', 'orderby' => 'modified', 'post__in' => $modulos );

		
	}// End if($descuento1 != '' && $descuento1 != 0 )
	

	
	$modulos_desc = new WP_Query( $args );
	$i = 0;
	
	if ( $modulos_desc->have_posts() ):
		while ( $modulos_desc->have_posts() ) : $modulos_desc->the_post(); 
			?>
			
			<?php
				//Cargamos el post en el objeto Evento con todos sus cambios
				$EM_Event = em_get_event($post);
			?>
			
			<div class="bloque-desc-home<?php if($i == 1 || $i == 3){echo ' margin-left22';}else{ echo ' clear-left'; } ?>">
				<?php  
				echo '<div class="imagen-descuentos"><a href="'.get_permalink().'">';
					echo get_the_post_thumbnail(get_the_ID(), array(315,200));
				echo '</a></div>';
				?>
				<?php if($EM_Event->output('#_EVENTDATES')){ ?>
					<p class="date-desc">
						<?php echo $EM_Event->output('#_EVENTDATES'); ?>
					</p>
				<?php } ?>
				<h2 class="title-modulo-desc font-normal"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				
				
				<?php if($EM_Event->output('#_ATT{Precio (€)}') || $EM_Event->output('#_ATT{Precio origen tachado}') ){ ?>
						<p class="info">
							<strong>PRECIO: </strong> <?php echo $EM_Event->output('#_ATT{Precio (€)}'); ?> | <strong>PRECIO ORIGEN: </strong><span style="text-decoration:line-through;"> <?php echo $EM_Event->output('#_ATT{Precio origen tachado}'); ?></span>
						</p>
				<?php } ?>
									
									<?php if($EM_Event->output('#_ATT{Porcentaje de descuento}')){ ?>
									<p class="info">
										<strong>PORCENTAJE DESCUENTO: </strong> <?php echo $EM_Event->output('#_ATT{Porcentaje de descuento}'); ?>
									</p>
									<?php } ?>
									
									<?php if($EM_Event->output('#_AVAILABLESPACES')){ ?>
									<p class="info">
										<strong>QUEDAN <span style="color:#DA8500;"><?php echo $EM_Event->output('#_AVAILABLESPACES'); ?></span> DESCUENTOS</strong>
									</p>
									<?php } ?>
				<p>
					<?php 
					//$desc = get_post($post->ID);
					//the_excerpt_max_charlength($desc->post_content, 80);
					?>
				</p>
			</div>
			<?php $i++;
		endwhile;
		
	else:
		
		//Si no hay resultados para esa provincia y ese tipo de portada se ponen las dos últimas noticias que no sean fichas de actividad
		$args = array( 'posts_per_page' => 4, 'post_type' => 'event', 'order' => 'DESC', 'orderby' => 'modified', 'category_name' => $prov_portada );
		$lastposts = query_posts( $args );
		
		if ( have_posts() ):
			while ( have_posts() ) : the_post(); ?>
				<div class="bloque-desc-home<?php if($i == 1 || $i == 3){echo ' margin-left22';}else{ echo ' clear-left'; } ?>">
				
					<?php
					//Cargamos el post en el objeto Evento con todos sus cambios
					$EM_Event = em_get_event($post);
					?>
				
					<?php  
					echo '<div class="imagen-descuentos"><a href="'.get_permalink().'">';
						echo get_the_post_thumbnail(get_the_ID(), array(315,200));
					echo '</a></div>';
					?>
					<?php if($EM_Event->output('#_EVENTDATES')){ ?>
					<p class="date-desc">
						<?php echo $EM_Event->output('#_EVENTDATES'); ?>
					</p>
					<?php } ?>
					<h2 class="title-modulo-desc font-normal"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				
				
					<?php if($EM_Event->output('#_ATT{Precio (€)}') || $EM_Event->output('#_ATT{Precio origen tachado}') ){ ?>
						<p class="info">
							<strong>PRECIO: </strong> <?php echo $EM_Event->output('#_ATT{Precio (€)}'); ?> | <strong>PRECIO ORIGEN: </strong><span style="text-decoration:line-through;"> <?php echo $EM_Event->output('#_ATT{Precio origen tachado}'); ?></span>
						</p>
					<?php } ?>
									
									<?php if($EM_Event->output('#_ATT{Porcentaje de descuento}')){ ?>
									<p class="info">
										<strong>PORCENTAJE DESCUENTO: </strong> <?php echo $EM_Event->output('#_ATT{Porcentaje de descuento}'); ?>
									</p>
									<?php } ?>
									
									<?php if($EM_Event->output('#_AVAILABLESPACES')){ ?>
									<p class="info">
										<strong>QUEDAN <span style="color:#DA8500;"><?php echo $EM_Event->output('#_AVAILABLESPACES'); ?></span> DESCUENTOS</strong>
									</p>
									<?php } ?>
					<p>
						<?php 
						//$desc = get_post($post->ID);
						//the_excerpt_max_charlength($desc->post_content, 80);
						?>
					</p>
					
					<?php //the_excerpt(); ?>
				</div>
				<?php $i++;
			endwhile;
		else:
			
			$args = array( 'posts_per_page' => 4, 'post_type' => 'post', 'category__not_in' => '47', 'order' => 'DESC', 'orderby' => 'modified', 'category_name' => $tipo_portada );	
			$lastposts = query_posts( $args );
		
			while ( have_posts() ) : the_post(); ?>
			
				<?php
					//Cargamos el post en el objeto Evento con todos sus cambios
					$EM_Event = em_get_event($post);
				?>
			
				<div class="bloque-desc-home<?php if($i == 1 || $i == 3){echo ' margin-left22';}else{ echo ' clear-left'; } ?>">
					<?php  
						echo '<div class="imagen-descuentos"><a href="'.get_permalink().'">';
							echo get_the_post_thumbnail(get_the_ID(), array(315,200));
						echo '</a></div>';
					?>
					<?php if($EM_Event->output('#_EVENTDATES')){ ?>
						<p class="date-desc">
							<?php echo $EM_Event->output('#_EVENTDATES'); ?>
						</p>
					<?php } ?>
					<h2 class="title-modulo-desc font-normal"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<?php //the_excerpt(); ?>
					
					<?php if($EM_Event->output('#_ATT{Precio (€)}') || $EM_Event->output('#_ATT{Precio origen tachado}') ){ ?>
						<p class="info">
							<strong>PRECIO: </strong> <?php echo $EM_Event->output('#_ATT{Precio (€)}'); ?> | <strong>PRECIO ORIGEN: </strong><span style="text-decoration:line-through;"> <?php echo $EM_Event->output('#_ATT{Precio origen tachado}'); ?></span>
						</p>
					<?php } ?>
									
									<?php if($EM_Event->output('#_ATT{Porcentaje de descuento}')){ ?>
									<p class="info">
										<strong>PORCENTAJE DESCUENTO: </strong> <?php echo $EM_Event->output('#_ATT{Porcentaje de descuento}'); ?>
									</p>
									<?php } ?>
									
									<?php if($EM_Event->output('#_AVAILABLESPACES')){ ?>
									<p class="info">
										<strong>QUEDAN <span style="color:#DA8500;"><?php echo $EM_Event->output('#_AVAILABLESPACES'); ?></span> DESCUENTOS</strong>
									</p>
									<?php } ?>
					<p>
						<?php 
						//$desc = get_post($post->ID);
						//the_excerpt_max_charlength($desc->post_content, 80);
						?>
					</p>
				</div>
				<?php $i++;
			endwhile;		
			
		endif;
		
	endif;
	
	// Reset Query
	wp_reset_query();
	

}


//Función para mostrar las noticias pequeñas del final de las portadas
function mostrar_modulos_peq_portada_descuentos($tipo_portada, $offset){

	global $wpdb, $post;
	
	$current_date = microtime();
	$current_date_secs = explode(" ",$current_date);
	$current_date = $current_date_secs[1];
	
	$prov_slug = get_category_slug_from_name($_COOKIE['noletia_prov']);
	
	$prov_portada = (isset($_COOKIE['noletia_prov']) && $_COOKIE['noletia_prov'] != 'todas') ? $prov_slug : 'todas';
	$tipo_portada = (($tipo_portada == '')) ? 'global' : $tipo_portada;

	
		$prov = $_COOKIE['noletia_prov'];
		
		//Mostramos los descuentos de la provincia seleccionada
		if($prov != '' && $prov != 'todas'){
			
			$cont_eventos = 0;
			
			$args = array( 'post_status' => 'publish', 'posts_per_page' => 10, 'post_type' => 'event', 'orderby' => 'meta_value', 'meta_key' => '_event_start_date', 'order' => 'ASC', 
				'tax_query' => array( 
				'relation' => 'AND',
					array(
						'taxonomy' => 'event-categories',
						'field' => 'slug',
						'terms' => array( $tipo_portada )
					),
					array(
						'taxonomy' => 'event-categories',
						'field' => 'slug',
						'terms' => array( $prov_portada )
					)
				),
				'meta_query' => array(
 					array(
 						'key' => '_end_ts',
 						'value' => $current_date,
 						'type' => 'NUMERIC',
 						'compare' => '>='
 					)
				)
			);
			//if(is_super_admin()){ echo 'hola'; print_r($args); }
			$modulo_concursos_ids = new WP_Query($args);
			
			if ($modulo_concursos_ids->have_posts()){ 
	
				while ( $modulo_concursos_ids->have_posts() ){ $modulo_concursos_ids->the_post();
					
					$modulos[$cont_eventos] = get_the_ID();
				
					$cont_eventos++;
					
				}
			}
			// Reset Query
			wp_reset_query();			
			
		}
		
		//Si no hay ningún descuento para esa provincia, que muestre los de la general
		if($cont_eventos == 0 || $prov == '' || $prov == 'todas'){
			
			$cont_eventos = 0;
			
			$args = array( 'post_status' => 'publish', 'posts_per_page' => 10, 'post_type' => 'event', 'orderby' => 'meta_value', 'meta_key' => '_event_start_date', 'order' => 'ASC', 
				'tax_query' => array( 
				'relation' => 'AND',
					array(
						'taxonomy' => 'event-categories',
						'field' => 'slug',
						'terms' => array( $tipo_portada )
					)
				),
				'meta_query' => array(
 					array(
 						'key' => '_end_ts',
 						'value' => $current_date,
 						'type' => 'NUMERIC',
 						'compare' => '>='
 					)
				)
			);
			
			$modulo_concursos_ids = new WP_Query($args);
			
			if ($modulo_concursos_ids->have_posts()){ 
	
				while ( $modulo_concursos_ids->have_posts() ){ $modulo_concursos_ids->the_post();
					
					$modulos[$cont_eventos] = get_the_ID();
				
					$cont_eventos++;
					
				}
			}
			// Reset Query
			wp_reset_query();
			
			
		}
		
		
	$args2 = array( 'posts_per_page' => 10, 'post_type' => 'event', 'order' => 'DESC', 'orderby' => 'date', 'post__in' => $modulos, 'paged' => get_query_var( 'page' ));
	
	
	$modulos_desc = new WP_Query( $args2 );
	$i = 0;


	
	if ( $modulos_desc->have_posts() ):
		while ( $modulos_desc->have_posts() ) : $modulos_desc->the_post(); 
			 
			//if($i >= 4){ ?>
			<div class="bloque-desc-peq<?php if($i%2 == 0){ echo ' clear-left'; } ?>">
				<?php  
				echo '<div class="imagen-desc-peq"><a href="'.get_permalink().'">';
					if(has_post_thumbnail()): 
						echo get_the_post_thumbnail(get_the_ID(), array(90,60));
					else:
						echo '<img style="width:90px;height:60px;" src="'.get_bloginfo('home').'/wp-content/themes/noletia/images/logo_club_express.png" />';
					endif;
				echo '</a></div>';
				?>
				<div class="desc-peq-text">
					<h2 class="title-modulo-desc-peq font-normal"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<p class="date-desc-peq"><?php printf( __( '%1$s', 'buddypress' ), get_the_date('d M Y')); ?></p>
				</div>		
			</div>
			<?php //}
			$i++;
		endwhile;
		
		echo '<p class="desc-peq-pagination">'.paginate_links().'</p>';
		
	endif;
		
	// Reset Query
	wp_reset_query();


}



/***********************************************************************************************************/
//****  FUNCIÓN PARA MOSTRAR LOS MÓDULOS MEDIANOS DE LAS PORTADAS DE CONCURSOS ******//
/***********************************************************************************************************/
function mostrar_modulo_portada_concursos($tipo_portada, $offset){
	
	global $wpdb, $post;
	
	$prov_slug = get_category_slug_from_name($_COOKIE['noletia_prov']);
	
	$prov_portada = (isset($_COOKIE['noletia_prov']) && ($_COOKIE['noletia_prov'] != 'todas')) ? $prov_slug : 'todas';
	$tipo_portada = (($tipo_portada == '')) ? 'global' : $tipo_portada;
	
	$concurso1 = get_option( 'noletia_'.$tipo_portada.'_'.$prov_portada.'_concurso1' );
	
	$current_date = microtime();
	$current_date_secs = explode(" ",$current_date);
	$current_date = $current_date_secs[1];
	
		
	if($concurso1 != '' && $concurso1 != 0 ){
		
		$concurso2 = get_option( 'noletia_'.$tipo_portada.'_'.$prov_portada.'_concurso2' );
		$concurso3 = get_option( 'noletia_'.$tipo_portada.'_'.$prov_portada.'_concurso3' );
		$concurso4 = get_option( 'noletia_'.$tipo_portada.'_'.$prov_portada.'_concurso4' );
		
		$post_in = array($concurso1,$concurso2,$concurso3,$concurso4);
		
		$args = array( 'post_type' => 'event', 'order' => 'DESC', 'orderby' => 'modified', 'post__in' => $post_in );
		
	}else{
				
		$prov = $_COOKIE['noletia_prov'];
		
		//Mostramos los descuentos de la provincia seleccionada
		if($prov != '' && $prov != 'todas'){
			
			$cont_eventos = 0;
			
					
			$args = array( 'post_status' => 'publish', 'posts_per_page' => 4, 'post_type' => 'event', 'orderby' => 'meta_value', 'meta_key' => '_event_start_date', 'order' => 'ASC', 
				'tax_query' => array( 
				'relation' => 'AND',
					array(
						'taxonomy' => 'event-categories',
						'field' => 'slug',
						'terms' => array( 'concursos' )
					),
					array(
						'taxonomy' => 'event-categories',
						'field' => 'slug',
						'terms' => array( $prov_portada )
					)
				),
				'meta_query' => array(
					'relation' => 'AND',
					array(
 						'key' => '_start_ts',
 						'value' => $current_date,
 						'type' => 'NUMERIC',
 						'compare' => '>='
 					),
 					array(
 						'key' => '_end_ts',
 						'value' => $current_date,
 						'type' => 'NUMERIC',
 						'compare' => '>='
 					)
				)
			);
			
			$modulo_concursos_ids = new WP_Query($args);
			
			if ($modulo_concursos_ids->have_posts()){ 
	
				while ( $modulo_concursos_ids->have_posts() ){ $modulo_concursos_ids->the_post();
					
					if($cont_eventos > 0){ $slides .= ','; }
					$modulos[$cont_eventos] = get_the_ID();
				
					$cont_eventos++;
					
				}
			}
		
		}
		
		//Si no hay ningún descuento para esa provincia, que muestre los de la general
		if($cont_eventos == 0 || $prov == '' || $prov == 'todas'){
			
			$cont_eventos = 0;
			
			$args = array( 'post_status' => 'publish', 'posts_per_page' => 4, 'post_type' => 'event', 'orderby' => 'meta_value', 'meta_key' => '_event_start_date', 'order' => 'ASC', 
				'tax_query' => array( 
				'relation' => 'AND',
					array(
						'taxonomy' => 'event-categories',
						'field' => 'slug',
						'terms' => array( 'concursos' )
					)
				),
				'meta_query' => array(
					'relation' => 'AND',
					array(
 						'key' => '_start_ts',
 						'value' => $current_date,
 						'type' => 'NUMERIC',
 						'compare' => '>='
 					),
 					array(
 						'key' => '_end_ts',
 						'value' => $current_date,
 						'type' => 'NUMERIC',
 						'compare' => '>='
 					)
				)
			);
			
			$modulo_concursos_ids = new WP_Query($args);
			
			if ($modulo_concursos_ids->have_posts()){ 
	
				while ( $modulo_concursos_ids->have_posts() ){ $modulo_concursos_ids->the_post();
					
					if($cont_eventos > 0){ $slides .= ','; }
				
					$modulos[$cont_eventos] = get_the_ID();
				
					$cont_eventos++;
					
				}
			}
			
		}
		
		$args = array( 'posts_per_page' => 4, 'post_type' => 'event', 'order' => 'DESC', 'orderby' => 'modified', 'post__in' => $modulos );

		
	}// End if($descuento1 != '' && $descuento1 != 0 )
	

	
	$modulos_conc = new WP_Query( $args );
	$i = 0;
	
	if ( $modulos_conc->have_posts() ):
		while ( $modulos_conc->have_posts() ) : $modulos_conc->the_post(); 
			?>
			
			<?php
				//Cargamos el post en el objeto Evento con todos sus cambios
				$EM_Event = em_get_event($post);
			?>
			
			<div class="bloque-desc-home<?php if($i == 1 || $i == 3){echo ' margin-left22';}else{ echo ' clear-left'; } ?>">
				<?php  
				echo '<div class="imagen-descuentos"><a href="'.get_permalink().'">';
					echo get_the_post_thumbnail(get_the_ID(), array(315,200));
				echo '</a></div>';
				?>
				<?php if($EM_Event->output('#_EVENTDATES')){ ?>
					<p class="date-desc">
						<?php echo $EM_Event->output('#_EVENTDATES'); ?>
					</p>
				<?php } ?>
				<h2 class="title-modulo-desc font-normal"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				
				
				<?php if($EM_Event->output('#_ATT{Precio (€)}') || $EM_Event->output('#_ATT{Precio origen tachado}') ){ ?>
						<p class="info">
							<strong>PRECIO: </strong> <?php echo $EM_Event->output('#_ATT{Precio (€)}'); ?> | <strong>PRECIO ORIGEN: </strong><span style="text-decoration:line-through;"> <?php echo $EM_Event->output('#_ATT{Precio origen tachado}'); ?></span>
						</p>
				<?php } ?>
									
									<?php if($EM_Event->output('#_ATT{Porcentaje de descuento}')){ ?>
									<p class="info">
										<strong>PORCENTAJE DESCUENTO: </strong> <?php echo $EM_Event->output('#_ATT{Porcentaje de descuento}'); ?>
									</p>
									<?php } ?>
									
									<?php if($EM_Event->output('#_AVAILABLESPACES')){ ?>
									<p class="info">
										<strong>QUEDAN <span style="color:#DA8500;"><?php echo $EM_Event->output('#_AVAILABLESPACES'); ?></span> DESCUENTOS</strong>
									</p>
									<?php } ?>
				<p>
					<?php 
					//$desc = get_post($post->ID);
					//the_excerpt_max_charlength($desc->post_content, 80);
					?>
				</p>
			</div>
			<?php $i++;
		endwhile;
		
	endif;
	
	// Reset Query
	wp_reset_query();
	

}



/***********************************************************************************************************/
//****  FUNCIÓN PARA MOSTRAR LAS NOTICIAS PEQUEÑAS EN PORTADAS  ******//
/***********************************************************************************************************/
function mostrar_modulos_peq_portadas($tipo_portada, $offset = 0){
	
	global $wpdb;
	
	//La cookie guarda el nombre de la provincia y necesitamos el slug
	$prov_slug = get_category_slug_from_name($_COOKIE['noletia_prov']);
	$provincia_portada = (isset($_COOKIE['noletia_prov'])) ? $_COOKIE['noletia_prov'] : 'todas';
	
	if($_COOKIE['noletia_prov'] != ''){
				$prov_selected = ' cat_ID="'.get_category_id($_COOKIE['noletia_prov']).'"';
	}else{
				$prov_selected = '';
	}	
	
	//Caso para la portada general: Todas los tipos y para todas las provincias
	if($tipo_portada == 'global' && $provincia_portada == 'todas'){
			
			$args = 'post_status=publish&order=DESC&orderby=modified&posts_per_page=10&post_type=post&category__not_in=47&offset='.$offset;
	
	//Caso en el que hay o alguna provincia seleccionada o estamos en algún tipo de portada (música,...)
	}else{
			
			$cat_in = '';
			$flag = '';
			$flag2 = '';
			if($tipo_portada != 'global'){ $cat_in .= $tipo_portada; $flag = 'y'; }
			if($provincia_portada != 'todas'){ if($flag == 'y'){ $cat_in .= ','; $flag2 = 'y'; } $cat_in .= $provincia_portada; }
	
			//Si se filtra por provincia y por tipo, tenemos que utilizar category__and
			if($flag2 != 'y'){
				
				$args = 'post_status=publish&order=DESC&orderby=modified&posts_per_page=10&post_type=post&category__not_in=47&category_name='.$cat_in.'&offset='.$offset;
			
			}else{
				$cat1 = get_category_id($tipo_portada);
				$cat2 = get_category_id($provincia_portada);
				$cat1_and_cat2 = array($cat1,$cat2);
				
				$args = array('post_status' => 'publish', 'order' => 'DESC', 'orderby' => 'modified', 'posts_per_page' => 10, 'post_type' => 'post', 'category__not_in' => 47, 'category__and' => $cat1_and_cat2, 'offset' => $offset);

			}
	
	}
	
	
	$noticias_peq = new WP_Query( $args );
	$i = 0;

	
	if ( $noticias_peq->have_posts() ):
		while ( $noticias_peq->have_posts() ) : $noticias_peq->the_post(); 
			global $post; 
			?>
			<div class="bloque-desc-peq<?php if($i == 1 || $i == 3 || $i == 5 || $i == 7 || $i == 9){echo ' margin-left14';} ?>" <?php if($i%2==0){ echo ' style="clear:left;"'; } ?>>
				<?php  
				echo '<div class="imagen-desc-peq"><a href="'.get_permalink().'">';
					if(has_post_thumbnail(get_the_ID())) {
						echo get_the_post_thumbnail(get_the_ID(), array(90,60));
					}else{
						echo '<img class="attachment-last_event-img wp-post-image" src="'.get_bloginfo('home').'/wp-content/themes/noletia/images/logo-90.png" />';
					}
				echo '</a></div>';
				?>
				<div class="desc-peq-text">
					<h2 class="title-modulo-desc-peq font-normal"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<p class="date-desc-peq"><?php printf( __( '%1$s', 'buddypress' ), get_the_date('d M Y')); ?></p>
				</div>		
			</div>
			<?php
			$i++;
		endwhile;
		
		echo '<p class="desc-peq-pagination">'.paginate_links( ).'</p>';
		
	endif;
		
	// Reset Query
	wp_reset_query();
	
	
	//Si no hay noticias con esas características, mostramos el general
	if($i == 0){
		$args2 = 'post_status=publish&order=DESC&orderby=modified&posts_per_page=10&post_type=post&category__not_in=47&offset='.$offset;
	}
							
	$noticias_peq2 = new WP_Query( $args2 );
	$i = 0;
	
	if ( $noticias_peq2->have_posts() ):
		while ( $noticias_peq2->have_posts() ) : $noticias_peq2->the_post(); 
			global $post; 
			?>
			<div class="bloque-desc-peq<?php if($i == 1 || $i == 3 || $i == 5 || $i == 7 || $i == 9){echo ' margin-left14';} ?>" <?php if($i%2==0){ echo ' style="clear:left;"'; } ?>>
				<?php  
				echo '<div class="imagen-desc-peq"><a href="'.get_permalink().'">';
					if(has_post_thumbnail(get_the_ID())) {
						echo get_the_post_thumbnail(get_the_ID(), array(90,60));
					}else{
						echo '<img class="attachment-last_event-img wp-post-image" src="'.get_bloginfo('home').'/wp-content/themes/noletia/images/logo-90.png" />';
					}
				echo '</a></div>';
				?>
				<div class="desc-peq-text">
					<h2 class="title-modulo-desc-peq font-normal"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<p class="date-desc-peq"><?php printf( __( '%1$s', 'buddypress' ), get_the_date('d M Y')); ?></p>
				</div>		
			</div>
			<?php
			$i++;
		endwhile;
		
		echo '<p class="desc-peq-pagination">'.paginate_links().'</p>';
		
	endif;
		
	// Reset Query
	wp_reset_query();
	
}


//Función llamada desde todas las portadas para mostrar la primera fila de noticias de actualidad
function mostrar_ult_post_noticias($tipo_portada){
	
	$prov_slug = get_category_slug_from_name($_COOKIE['noletia_prov']);
	
	$prov_portada = (isset($_COOKIE['noletia_prov'])) ? $prov_slug : 'todas';
	$tipo_portada = (($tipo_portada == '')) ? 'global' : $tipo_portada;
	
	$actualidad1 = get_option( 'noletia_'.$tipo_portada.'_'.$prov_portada.'_actualidad1' );
	$actualidad2 = get_option( 'noletia_'.$tipo_portada.'_'.$prov_portada.'_actualidad2' );
	
	if($actualidad1 != '' && $actualidad1 != 0 ){
		
		$post_in = array($actualidad1,$actualidad2);
		$args = array( 'posts_per_page' => 2, 'post_type' => 'post', 'category__not_in' => '47', 'order' => 'DESC', 'orderby' => 'modified', 'post__in' => $post_in );

		
	}else{
				
		if($tipo_portada == 'global' && $prov_portada == 'todas'){
		
			$args = array( 'posts_per_page' => 2, 'post_type' => 'post', 'category__not_in' => '47', 'order' => 'DESC', 'orderby' => 'modified' );
		
		}elseif($tipo_portada == 'global' && $prov_portada != 'todas'){
			
			$args = array( 'posts_per_page' => 2, 'post_type' => 'post', 'category__not_in' => '47', 'order' => 'DESC', 'orderby' => 'modified', 'category_name' => $prov_portada );
			
		}elseif($tipo_portada != 'global' && $prov_portada == 'todas'){
			
			$args = array( 'posts_per_page' => 2, 'post_type' => 'post', 'category__not_in' => '47', 'order' => 'DESC', 'orderby' => 'modified', 'category_name' => $tipo_portada );
		
		}elseif($tipo_portada != 'global' && $prov_portada != 'todas'){	
			
			$prov_portada_id = get_category_id($prov_portada);
			$tipo_portada_id = get_category_id($tipo_portada);
			$cats_and = array($prov_portada_id,$tipo_portada_id);
		
			$args = array( 'posts_per_page' => 2, 'post_type' => 'post', 'category__not_in' => '47', 'order' => 'DESC', 'orderby' => 'modified', 'category__and' => $cats_and );
			
		}
	}
	
	$lastposts = query_posts( $args );
	$i = 0;
	
	if ( have_posts() ):
		while ( have_posts() ) : the_post(); ?>
			<div class="bloque-news-home<?php if($i == 1){echo ' margin-left14';} ?>">
			<?php  
				echo '<div class="imagen-descuentos"><a href="'.get_permalink().'">';
					echo get_the_post_thumbnail(get_the_ID(), 'thumbnail');
					echo '<span class="info"><h3>actualidad</h3></span>';
				echo '</a></div>';
			?>
			<h2 class="title-bloque2 font-normal"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			<?php the_excerpt(); ?>
			</div>
			<?php $i++;
		endwhile;
	else:
		
		//Si no hay resultados para esa provincia y ese tipo de portada se ponen las dos últimas noticias que no sean fichas de actividad
		$args = array( 'posts_per_page' => 2, 'post_type' => 'post', 'category__not_in' => '47', 'order' => 'DESC', 'orderby' => 'modified', 'category_name' => $prov_portada );
		$lastposts = query_posts( $args );
		
		if ( have_posts() ):
			while ( have_posts() ) : the_post(); ?>
			<div class="bloque-news-home<?php if($i == 1){echo ' margin-left14';} ?>">
			<?php  
				echo '<div class="imagen-descuentos"><a href="'.get_permalink().'">';
					echo get_the_post_thumbnail(get_the_ID(), 'thumbnail');
					echo '<span class="info"><h3>actualidad</h3></span>';
				echo '</a></div>';
			?>
			<h2 class="title-bloque2 font-normal"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			<?php the_excerpt(); ?>
			</div>
			<?php $i++;
			endwhile;
		else:
			
			$args = array( 'posts_per_page' => 2, 'post_type' => 'post', 'category__not_in' => '47', 'order' => 'DESC', 'orderby' => 'modified', 'category_name' => $tipo_portada );	
			$lastposts = query_posts( $args );
		
			while ( have_posts() ) : the_post(); ?>
				<div class="bloque-news-home<?php if($i == 1){echo ' margin-left14';} ?>">
				<?php  
					echo '<div class="imagen-descuentos"><a href="'.get_permalink().'">';
					echo get_the_post_thumbnail(get_the_ID(), 'thumbnail');
					echo '<span class="info"><h3>actualidad</h3></span>';
					echo '</a></div>';
				?>
				<h2 class="title-bloque2 font-normal"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<?php the_excerpt(); ?>
				</div>
				<?php $i++;
			endwhile;		
			
		endif;
		
	endif;
	
	// Reset Query
	wp_reset_query();

}

//Función llamada desde todas las portadas para mostrar la segunda fila de noticias de actualidad
function mostrar_ult_post_noticias_seg_fila($tipo_portada){
	
	$prov_slug = get_category_slug_from_name($_COOKIE['noletia_prov']);
	
	$prov_portada = (isset($_COOKIE['noletia_prov'])) ? $prov_slug : 'todas';
	$tipo_portada = (($tipo_portada == '')) ? 'global' : $tipo_portada;
	
	$actualidad3 = get_option( 'noletia_'.$tipo_portada.'_'.$prov_portada.'_actualidad3' );
	$actualidad4 = get_option( 'noletia_'.$tipo_portada.'_'.$prov_portada.'_actualidad4' );
	
	if($actualidad3 != '' && $actualidad3 != 0 ){
		
		$post_in = array($actualidad3,$actualidad4);
		$args = array( 'posts_per_page' => 2, 'post_type' => 'post', 'category__not_in' => '47', 'order' => 'DESC', 'orderby' => 'modified', 'post__in' => $post_in );

		
	}else{
				
		if($tipo_portada == 'global' && $prov_portada == 'todas'){
		
			$args = array( 'posts_per_page' => 2, 'post_type' => 'post', 'category__not_in' => '47', 'order' => 'DESC', 'orderby' => 'modified', 'offset' => 2 );
		
		}elseif($tipo_portada == 'global' && $prov_portada != 'todas'){
			
			$args = array( 'posts_per_page' => 2, 'post_type' => 'post', 'category__not_in' => '47', 'order' => 'DESC', 'orderby' => 'modified', 'category_name' => $prov_portada, 'offset' => 2 );
			
		}elseif($tipo_portada != 'global' && $prov_portada == 'todas'){
			
			$args = array( 'posts_per_page' => 2, 'post_type' => 'post', 'category__not_in' => '47', 'order' => 'DESC', 'orderby' => 'modified', 'category_name' => $tipo_portada, 'offset' => 2 );
		
		}elseif($tipo_portada != 'global' && $prov_portada != 'todas'){	
			
			$prov_portada_id = get_category_id($prov_portada);
			$tipo_portada_id = get_category_id($tipo_portada);
			$cats_and = array($prov_portada_id,$tipo_portada_id);
		
			$args = array( 'posts_per_page' => 2, 'post_type' => 'post', 'category__not_in' => '47', 'order' => 'DESC', 'orderby' => 'modified', 'category__and' => $cats_and );
			
		}
	}
	
	$lastposts = query_posts( $args );
	$i = 0;
	
	if ( have_posts() ):
		while ( have_posts() ) : the_post(); ?>
			<div class="bloque-news-home<?php if($i == 1){echo ' margin-left14';} ?>">
			<?php  
				echo '<div class="imagen-descuentos"><a href="'.get_permalink().'">';
					echo get_the_post_thumbnail(get_the_ID(), 'thumbnail');
					echo '<span class="info"><h3>actualidad</h3></span>';
				echo '</a></div>';
			?>
			<h2 class="title-bloque2 font-normal"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			<?php the_excerpt(); ?>
			</div>
			<?php $i++;
		endwhile;
	else:
		
		//Si no hay resultados para esa provincia y ese tipo de portada se ponen las dos últimas noticias que no sean fichas de actividad
		$args = array( 'posts_per_page' => 2, 'post_type' => 'post', 'category__not_in' => '47', 'order' => 'DESC', 'orderby' => 'modified', 'category_name' => $prov_portada, 'offset' => 2 );
		$lastposts = query_posts( $args );
		
		if ( have_posts() ):
			while ( have_posts() ) : the_post(); ?>
			<div class="bloque-news-home<?php if($i == 1){echo ' margin-left14';} ?>">
			<?php  
				echo '<div class="imagen-descuentos"><a href="'.get_permalink().'">';
					echo get_the_post_thumbnail(get_the_ID(), 'thumbnail');
					echo '<span class="info"><h3>actualidad</h3></span>';
				echo '</a></div>';
			?>
			<h2 class="title-bloque2 font-normal"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			<?php the_excerpt(); ?>
			</div>
			<?php $i++;
			endwhile;
		else:
			
			$args = array( 'posts_per_page' => 2, 'post_type' => 'post', 'category__not_in' => '47', 'order' => 'DESC', 'orderby' => 'modified', 'category_name' => $tipo_portada, 'offset' => 2 );	
			$lastposts = query_posts( $args );
		
			while ( have_posts() ) : the_post(); ?>
				<div class="bloque-news-home<?php if($i == 1){echo ' margin-left14';} ?>">
				<?php  
					echo '<div class="imagen-descuentos"><a href="'.get_permalink().'">';
					echo get_the_post_thumbnail(get_the_ID(), 'thumbnail');
					echo '<span class="info"><h3>actualidad</h3></span>';
					echo '</a></div>';
				?>
				<h2 class="title-bloque2 font-normal"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<?php the_excerpt(); ?>
				</div>
				<?php $i++;
			endwhile;		
			
		endif;
		
	endif;
	
	// Reset Query
	wp_reset_query();

}

function noletia_widgets_init() {

	register_sidebar( array(
		'name' => __( 'Banner superior izquierdo 710x85', 'noletia' ),
		'id' => 'header-left',
		'before_widget' => '<div class="pub_sup_left">',
		'after_widget' => '</div>',
		'before_title' => '<span style="display:none;">',
		'after_title' => '</span>',
	) );
	register_sidebar( array(
		'name' => __( 'Banner superior derecho 260x85', 'noletia' ),
		'id' => 'header-right',
		'before_widget' => '<div class="pub_sup_right">',
		'after_widget' => '</div>',
		'before_title' => '<span style="display:none;">',
		'after_title' => '</span>',
	) );
	register_sidebar( array(
		'name' => __( 'Banner lateral derecho 245x303', 'noletia' ),
		'id' => 'sidebar-big',
		'before_widget' => '<div id="ads-sidebar-top">
		<p>Publicidad</p>',
		'after_widget' => '<p>Publicidad</p>
	</div>',
		'before_title' => '<span style="display:none;">',
		'after_title' => '</span>',
	) );
	register_sidebar( array(
		'name' => __( 'Banner lateral derecho 245x85 (1)', 'noletia' ),
		'id' => 'sidebar-small1',
		'before_widget' => '<div id="ads-sidebar-top">
		<p>Publicidad</p>',
		'after_widget' => '<p>Publicidad</p>
	</div>',
		'before_title' => '<span style="display:none;">',
		'after_title' => '</span>',
	) );
	register_sidebar( array(
		'name' => __( 'Banner lateral derecho 245x85 (2)', 'noletia' ),
		'id' => 'sidebar-small2',
		'before_widget' => '<div id="ads-sidebar-top">
		<p>Publicidad</p>',
		'after_widget' => '<p>Publicidad</p>
	</div>',
		'before_title' => '<span style="display:none;">',
		'after_title' => '</span>',
	) );
	register_sidebar( array(
		'name' => __( 'Banner lateral derecho 245x85 (3)', 'noletia' ),
		'id' => 'sidebar-small3',
		'before_widget' => '<div id="ads-sidebar-top">
		<p>Publicidad</p>',
		'after_widget' => '<p>Publicidad</p>
	</div>',
		'before_title' => '<span style="display:none;">',
		'after_title' => '</span>',
	) );

	register_sidebar( array(
		'name' => __( 'Banner Horizontal Portada 695x85', 'noletia' ),
		'id' => 'sidebar-banner',
		'before_widget' => '<div class="banner-home">',
		'after_widget' => '</div>',
		'before_title' => '<span style="display:none;">',
		'after_title' => '</span>',
	) );
	
	register_sidebar( array(
		'name' => __( 'Banner Horizontal Pie 968x130', 'noletia' ),
		'id' => 'sidebar-bottom',
		'before_widget' => '<div class="banner-bottom">',
		'after_widget' => '</div>',
		'before_title' => '<span style="display:none;">',
		'after_title' => '</span>',
	) );

	register_sidebar( array(
		'name' => __( 'Sidebar (debajo de FB)', 'noletia' ),
		'id' => 'sidebar-debajo-fb',
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widgettitle">',
		'after_title'   => '</h3>'
	) );
	
}
add_action( 'widgets_init', 'noletia_widgets_init' );

function my_post_types($types) {
    $types[] = 'ep_event';
    return $types;
}
add_filter('s2_post_types', 'my_post_types');

function my_taxonomy_types($taxonomies) {
    $taxonomies[] = 'my_taxonomy_type';
    return $taxonomies;
}
add_filter('s2_taxonomies', 'my_taxonomy_types');


/*************************************************************************************************************************************************
// Página de opciones de portada
*************************************************************************************************************************************************/


function noletia_admin_head() { ?>

<?php }

// VARIABLES

global $wpdb;
$themename = "Portadas";
$shortname = "noletia";
$manualurl = get_bloginfo('home');
$options = array();
$options_descuentos = array();


add_option("noletia_settings",$options);

$template_path = get_bloginfo('template_directory');

$layout_path = TEMPLATEPATH . '/layouts/'; 
$layouts = array();

$alt_stylesheet_path = TEMPLATEPATH . '/styles/';
$alt_stylesheets = array();

$functions_path = TEMPLATEPATH . '/functions/';

// Do something with $result;
//Los offsets permitidos para la selección de posts automática
$noletia_slide = array('Automático');
$noletia_slide_id = array(0);
$noletia_slide_descuentos = array('Automático');
$noletia_slide_descuentos_id = array(0);
$noletia_concurso = array('Automático');
$noletia_concurso_id = array(0);
$noletia_descuento = array('Automático');
$noletia_descuento_id = array(0);
$noletia_agenda = array('Automático');
$noletia_agenda_id = array(0);
$noletia_articulo = array('Automático');
$noletia_articulo_id = array(0);

//El tipo de portada y la provincia de la portada que se está visualizando
$tipo_prov = $_COOKIE['noletia_gest_port_tipo_prov'];
$tipo_prov = explode('---',$tipo_prov);
$tipo_portada = $tipo_prov[0] ? $tipo_prov[0] : 'global';
$provincia_portada = $tipo_prov[1] ? $tipo_prov[1] : 'todas';


/*************************************************************************************************************************************************
// SISTEMA DE CACHÉ: 
//Vamos a meter en un if absolutamente todas estas querys, para que el sistema, primero mire si la variable $options y $optionsdescuentos están en canché.
//Si así es, las cogerá de caché y no hará ninguna de las suiguientes llamadas a la BD
*************************************************************************************************************************************************/

$nombre_query_cache = 'gestor_portadas_variable_options_'.$provincia_portada.'_'.$provincia_portada;
$nombre_query_cache_desc = 'gestor_portadas_variable_options_descuentos_'.$tipo_portada.'_'.$provincia_portada;
$result = get_site_transient( $nombre_query_cache );
if ( false === $result ) {
	
	
/*************************************************************************************************************************************************
// SLIDE
*************************************************************************************************************************************************/
// Últimas entradas que no sean de la categoría fichas de actividad
if($tipo_portada == 'global' && $provincia_portada == 'todas'){
	
	$entradas = get_site_transient( 'gestor_portadas_global_todas_slide' );
	if ( false === $entradas ) {
		$entradas = new WP_Query( 'post_status=publish&order=DESC&orderby=modified&posts_per_page=60&post_type=post&category__not_in=47' );
		set_site_transient( 'gestor_portadas_global_todas', $entradas, 300 );
	}
			
}else{
	
	$cat_in = '';
	$flag = '';
	$flag2 = '';
	if($tipo_portada != 'global'){ $cat_in .= $tipo_portada; $flag = 'y'; }
	if($provincia_portada != 'todas'){ if($flag == 'y'){ $cat_in .= ','; $flag2 = 'y'; } $cat_in .= $provincia_portada; }
	
	//Si se filtra por provinca y por tipo, tenemos que utilizar category__and
	if($flag2 != 'y'){
		
		$nombre_query_cache = 'gestor_portadas_'.$tipo_portada.'_'.$provincia_portada.'_slide';
		$entradas = get_site_transient( $nombre_query_cache );
		if ( false === $entradas ) {
			$entradas = new WP_Query( 'post_status=publish&order=DESC&orderby=modified&posts_per_page=60&post_type=post&category__not_in=47&category_name='.$cat_in );
			set_site_transient( 'gestor_portadas_global_todas', $entradas, 300 );
		} 
	
		
	}else{
		
		//La siguiente función devuelve el id de la categoría, dado el SLUG
		$cat1 = get_category_id($tipo_portada);
		$cat2 = get_category_id($provincia_portada);
		$cat1_and_cat2 = array($cat1,$cat2);
		
		$query = array('post_status' => 'publish', 'order' => 'DESC', 'orderby' => 'modified', 'posts_per_page' => '60', 'post_type' => 'post', 'category__not_in' => '47', 'category__and' => $cat1_and_cat2);
		
		$nombre_query_cache = 'gestor_portadas_'.$tipo_portada.'_'.$provincia_portada.'_slide';
		$entradas = get_site_transient( $nombre_query_cache );
		if ( false === $entradas ) {
			$entradas = new WP_Query($query);
			set_site_transient( $nombre_query_cache, $entradas, 300 );
		}

	}
	
}

// The Loop para el SLIDE
$cont_articulos = 1;
while ( $entradas->have_posts() ) : $entradas->the_post();
	
	$noletia_slide_id[$cont_articulos] = get_the_ID();
	$noletia_slide[$cont_articulos] = get_the_title();
	
	$cont_articulos++;
	
endwhile;
// Reset Post Data
wp_reset_postdata();


/*************************************************************************************************************************************************
// SLIDE DESCUENTOS
*************************************************************************************************************************************************/
// Últimas entradas que no sean de la categoría fichas de actividad
//IMPORTANTE: COMENTO EL IF PARA QUE DE MOMENTO PUEDAN SELECCIONAR TODOS LOS DESCUENTOS EN TODAS LAS PROVINCIAS
//if($tipo_portada == 'descuentos' && $provincia_portada == 'todas'){
	
	$cont_eventos = 1;
	$query_slide_descuentos = "SELECT * FROM (wp_posts
		INNER JOIN wp_em_events ON(wp_posts.ID = wp_em_events.post_id)
		INNER JOIN wp_term_relationships ON(wp_em_events.post_id = wp_term_relationships.object_id)
		INNER JOIN wp_term_taxonomy ON(wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_taxonomy_id)
		INNER JOIN wp_terms ON(wp_term_taxonomy.term_id = wp_terms.term_id)
	)
	WHERE wp_posts.post_type = 'event'
	AND wp_posts.post_status = 'publish'
	AND wp_term_taxonomy.taxonomy = 'event-categories'
	AND wp_terms.slug = 'descuentos'
	ORDER BY wp_em_events.event_start_date DESC, wp_em_events.event_start_time DESC
	";
	
	$nombre_query_cache = 'gestor_portada_slide_'.$tipo_portada.'_'.$provincia_portada;
	$descuentos_ids = get_site_transient( $nombre_query_cache );
	if ( false === $descuentos_ids ) {
		$descuentos_ids = $wpdb->get_results($query_slide_descuentos);
		set_site_transient( $nombre_query_cache, $descuentos_ids, 300 );
	}
	
	
	foreach($descuentos_ids as $descuentos_id){
		
		$nombre_query_cache = 'gestor_portada_'.$tipo_portada.'_'.$provincia_portada.'_slide_wp_em_events';
		$descuentos = get_site_transient( $nombre_query_cache );
		if ( false === $descuentos ) {
			$descuentos = $wpdb->get_results("SELECT * FROM wp_em_events WHERE post_id='".$descuentos_id->ID."'");
			set_site_transient( $nombre_query_cache, $descuentos, 300 );
		}
		$the_location = $wpdb->get_row("SELECT * FROM wp_em_locations WHERE location_id = '".$descuentos_id->location_id."'");

	
		foreach($descuentos as $descuento){
		
			$noletia_slide_descuentos_id[$cont_eventos] = $descuentos_id->ID;
			$noletia_slide_descuentos[$cont_eventos] = get_the_title($descuentos_id->ID);
			$noletia_slide_descuentos[$cont_eventos] .= ' - Fecha Inicio: '.$descuentos_id->event_start_date.' - Fecha Fin: '.$descuentos_id->event_end_date;
			$noletia_slide_descuentos[$cont_eventos] .= ' - Hora: '.$descuentos_id->event_start_time.' - Espacio: '.$the_location->location_name.' ( '.$the_location->location_town.' )';
				
			$cont_eventos++;
		}
	}	



/*************************************************************************************************************************************************
// CONCURSOS
*************************************************************************************************************************************************/

$noletia_concurso_selects = devolver_query_desc_o_conc_gestor_portada($tipo_portada, $provincia_portada, 'concursos', 100);

$noletia_concurso_id = $noletia_concurso_selects['ids'];
$noletia_concurso = $noletia_concurso_selects['names'];


/*************************************************************************************************************************************************
// DESCUENTOS
*************************************************************************************************************************************************/

$noletia_descuento_selects = devolver_query_desc_o_conc_gestor_portada($tipo_portada, $provincia_portada, 'descuentos', 100);

$noletia_descuento_id = $noletia_descuento_selects['ids'];
$noletia_descuento = $noletia_descuento_selects['names'];


/*************************************************************************************************************************************************
// AGENDA
*************************************************************************************************************************************************/

$cont_eventos = 1;
$noletia_agenda_selects = devolver_query_eventos_gestor_portada($tipo_portada, $provincia_portada, 100);

$noletia_agenda_id = $noletia_agenda_selects['ids'];
$noletia_agenda = $noletia_agenda_selects['names'];

/*************************************************************************************************************************************************
// ACTUALIDAD
*************************************************************************************************************************************************/
// Últimas entradas que no sean de la categoría fichas de actividad
if($tipo_portada == 'global' && $provincia_portada == 'todas'){
	
	$nombre_query_cache = 'gestor_portada_'.$tipo_portada.'_'.$provincia_portada.'_seccion_actualidad';
	$actualidad = get_site_transient( $nombre_query_cache );
	if ( false === $actualidad ) {
		$actualidad = new WP_Query( 'post_status=publish&order=DESC&orderby=modified&posts_per_page=60&post_type=post&category__not_in=47' );
		set_site_transient( $nombre_query_cache, $actualidad, 300 );
	}
	
}else{
	
	$cat_in = '';
	$flag = '';
	$flag2 = '';
	if($tipo_portada != 'global'){ $cat_in .= $tipo_portada; $flag = 'y'; }
	if($provincia_portada != 'todas'){ if($flag == 'y'){ $cat_in .= ','; $flag2 = 'y'; } $cat_in .= $provincia_portada; }
	
	//Si se filtra por provinca y por tipo, tenemos que utilizar category__and
	if($flag2 != 'y'){
	
		$nombre_query_cache = 'gestor_portada_'.$tipo_portada.'_'.$provincia_portada.'_seccion_actualidad';
		$actualidad = get_site_transient( $nombre_query_cache );
		if ( false === $actualidad ) {
			$actualidad = new WP_Query( 'post_status=publish&order=DESC&orderby=modified&posts_per_page=60&post_type=post&category__not_in=47&category_name='.$cat_in );
			set_site_transient( $nombre_query_cache, $actualidad, 300 );
		}
		
	}else{
		$cat1 = get_category_id($tipo_portada);
		$cat2 = get_category_id($provincia_portada);
		$cat1_and_cat2 = array($cat1,$cat2);
		
		$query2 = array('post_status' => 'publish', 'order' => 'DESC', 'orderby' => 'modified', 'posts_per_page' => '60', 'post_type' => 'post', 'category__not_in' => '47', 'category__and' => $cat1_and_cat2);
		
		$nombre_query_cache = 'gestor_portada_'.$tipo_portada.'_'.$provincia_portada.'_seccion_actualidad';
		$actualidad = get_site_transient( $nombre_query_cache );
		if ( false === $actualidad ) {
			$actualidad = new WP_Query($query2);
			set_site_transient( $nombre_query_cache, $actualidad, 300 );
			//echo 'entra';
		}else{
			//echo 'no entra';		
		}
		

	}
	
}
//print_r($entradas);
// The Loop para el SLIDE
$cont_articulos = 1;
while ( $entradas->have_posts() ) : $entradas->the_post();
	
	$noletia_articulo_id[$cont_articulos] = get_the_ID();
	$noletia_articulo[$cont_articulos] = get_the_title();
	
	$cont_articulos++;
	
endwhile;
// Reset Post Data
wp_reset_postdata();



// THESE ARE THE DIFFERENT FIELDS

$options = array (

				array(	"name" => "Slide: Si seleccionas el modo Automático en el primer Slide, se mostrarán en el Slide, las últimas entradas que tengan la etiqueta 'Portada' (sin las comillas).",
						"type" => "heading"),
	
				array(	"name" => "Primera entrada en el slide",
						"id" => $shortname."_".$tipo_portada."_".$provincia_portada."_slide1",
						"std" => "Última",
						"type" => "select",
						"options" => $noletia_slide,
						"ids" => $noletia_slide_id),
				
				array(	"name" => "Segunda entrada en el slide",
						"id" => $shortname."_".$tipo_portada."_".$provincia_portada."_slide2",
						"std" => "Penúltima",
						"type" => "select",
						"options" => $noletia_slide,
						"ids" => $noletia_slide_id),
				
				array(	"name" => "Tercera entrada en el slide",
						"id" => $shortname."_".$tipo_portada."_".$provincia_portada."_slide3",
						"std" => "Antepenúltima",
						"type" => "select",
						"options" => $noletia_slide,
						"ids" => $noletia_slide_id),
				
				array(	"name" => "Cuarta entrada en el slide",
						"id" => $shortname."_".$tipo_portada."_".$provincia_portada."_slide4",
						"std" => "Anterior a la antepenúltima",
						"type" => "select",
						"options" => $noletia_slide,
						"ids" => $noletia_slide_id),
						
						
						
				array(	"name" => "Concursos",
						"type" => "heading"),
	
				array(	"name" => "Primer concurso (se mostrará su imagen destacada)",
						"id" => $shortname."_".$tipo_portada."_".$provincia_portada."_concurso1",
						"std" => "Última",
						"type" => "select",
						"options" => $noletia_concurso,
						"ids" => $noletia_concurso_id),
				
				array(	"name" => "Segundo concurso",
						"id" => $shortname."_".$tipo_portada."_".$provincia_portada."_concurso2",
						"std" => "Penúltima",
						"type" => "select",
						"options" => $noletia_concurso,
						"ids" => $noletia_concurso_id),
				
				array(	"name" => "Tercer concurso",
						"id" => $shortname."_".$tipo_portada."_".$provincia_portada."_concurso3",
						"std" => "Antepenúltima",
						"type" => "select",
						"options" => $noletia_concurso,
						"ids" => $noletia_concurso_id),
				
				array(	"name" => "Cuarto concurso",
						"id" => $shortname."_".$tipo_portada."_".$provincia_portada."_concurso4",
						"std" => "Anterior a la antepenúltima",
						"type" => "select",
						"options" => $noletia_concurso,
						"ids" => $noletia_concurso_id),
				
				
				
				array(	"name" => "Descuentos",
						"type" => "heading"),
	
				array(	"name" => "Primer descuento (se mostrará su imagen destacada)",
						"id" => $shortname."_".$tipo_portada."_".$provincia_portada."_descuento1",
						"std" => "Última",
						"type" => "select",
						"options" => $noletia_descuento,
						"ids" => $noletia_descuento_id),
				
				array(	"name" => "Segundo descuento",
						"id" => $shortname."_".$tipo_portada."_".$provincia_portada."_descuento2",
						"std" => "Penúltima",
						"type" => "select",
						"options" => $noletia_descuento,
						"ids" => $noletia_descuento_id),
				
				array(	"name" => "Tercer descuento",
						"id" => $shortname."_".$tipo_portada."_".$provincia_portada."_descuento3",
						"std" => "Antepenúltima",
						"type" => "select",
						"options" => $noletia_descuento,
						"ids" => $noletia_descuento_id),
				
				array(	"name" => "Cuarto descuento",
						"id" => $shortname."_".$tipo_portada."_".$provincia_portada."_descuento4",
						"std" => "Anterior a la antepenúltima",
						"type" => "select",
						"options" => $noletia_descuento,
						"ids" => $noletia_descuento_id),
						
			
				
				array(	"name" => "Agenda",
						"type" => "heading"),
	
				array(	"name" => "Primer evento",
						"id" => $shortname."_".$tipo_portada."_".$provincia_portada."_agenda1",
						"std" => "Automático",
						"type" => "select",
						"options" => $noletia_agenda,
						"ids" => $noletia_agenda_id),
				
				array(	"name" => "Segundo evento",
						"id" => $shortname."_".$tipo_portada."_".$provincia_portada."_agenda2",
						"std" => "Automático",
						"type" => "select",
						"options" => $noletia_agenda,
						"ids" => $noletia_agenda_id),
				
				array(	"name" => "Tercer evento",
						"id" => $shortname."_".$tipo_portada."_".$provincia_portada."_agenda3",
						"std" => "Automático",
						"type" => "select",
						"options" => $noletia_agenda,
						"ids" => $noletia_agenda_id),
				
				array(	"name" => "Cuarto evento",
						"id" => $shortname."_".$tipo_portada."_".$provincia_portada."_agenda4",
						"std" => "Automático",
						"type" => "select",
						"options" => $noletia_agenda,
						"ids" => $noletia_agenda_id),
				
				array(	"name" => "Quinto evento",
						"id" => $shortname."_".$tipo_portada."_".$provincia_portada."_agenda5",
						"std" => "Automático",
						"type" => "select",
						"options" => $noletia_agenda,
						"ids" => $noletia_agenda_id),		
						
						
				array(	"name" => "Actualidad",
						"type" => "heading"),
	
				array(	"name" => "Primera fila: Derecha",
						"id" => $shortname."_".$tipo_portada."_".$provincia_portada."_actualidad1",
						"std" => "Última",
						"type" => "select",
						"options" => $noletia_articulo,
						"ids" => $noletia_articulo_id),
				
				array(	"name" => "Primera fila: Izquierda",
						"id" => $shortname."_".$tipo_portada."_".$provincia_portada."_actualidad2",
						"std" => "Penúltima",
						"type" => "select",
						"options" => $noletia_articulo,
						"ids" => $noletia_articulo_id),
				
				array(	"name" => "Segunda fila: Izquierda",
						"id" => $shortname."_".$tipo_portada."_".$provincia_portada."_actualidad3",
						"std" => "Última",
						"type" => "select",
						"options" => $noletia_articulo,
						"ids" => $noletia_articulo_id),
				
				array(	"name" => "Segunda fila: Derecha",
						"id" => $shortname."_".$tipo_portada."_".$provincia_portada."_actualidad4",
						"std" => "Penúltima",
						"type" => "select",
						"options" => $noletia_articulo,
						"ids" => $noletia_articulo_id)
				
			);
			
			

		

$options_descuentos = array (
						
				array(	"name" => "Slide: Si seleccionas el modo Automático en el primer Slide, se mostrarán en el Slide, las últimas entradas que tengan la etiqueta 'Portada' (sin las comillas). Importante: Si no hay ningún descuento de la provincia seleccinada, los selectores mostrarán todos los descuentos.",
						"type" => "heading"),
	
				array(	"name" => "Primera entrada en el slide",
						"id" => $shortname."_".$tipo_portada."_".$provincia_portada."_slide1",
						"std" => "Última",
						"type" => "select",
						"options" => $noletia_slide_descuentos,
						"ids" => $noletia_slide_descuentos_id),
				
				array(	"name" => "Segunda entrada en el slide",
						"id" => $shortname."_".$tipo_portada."_".$provincia_portada."_slide2",
						"std" => "Penúltima",
						"type" => "select",
						"options" => $noletia_slide_descuentos,
						"ids" => $noletia_slide_descuentos_id),
				
				array(	"name" => "Tercera entrada en el slide",
						"id" => $shortname."_".$tipo_portada."_".$provincia_portada."_slide3",
						"std" => "Antepenúltima",
						"type" => "select",
						"options" => $noletia_slide_descuentos,
						"ids" => $noletia_slide_descuentos_id),
				
				array(	"name" => "Cuarta entrada en el slide",
						"id" => $shortname."_".$tipo_portada."_".$provincia_portada."_slide4",
						"std" => "Anterior a la antepenúltima",
						"type" => "select",
						"options" => $noletia_slide_descuentos,
						"ids" => $noletia_slide_descuentos_id),
						
						
						
				array(	"name" => "Módulos medianos de descuentos",
						"type" => "heading"),
	
				array(	"name" => "Primera fila izquierda",
						"id" => $shortname."_".$tipo_portada."_".$provincia_portada."_descuento1",
						"std" => "Automático",
						"type" => "select",
						"options" => $noletia_slide_descuentos,
						"ids" => $noletia_slide_descuentos_id),
				
				array(	"name" => "Primera fila derecha",
						"id" => $shortname."_".$tipo_portada."_".$provincia_portada."_descuento2",
						"std" => "Automático",
						"type" => "select",
						"options" => $noletia_slide_descuentos,
						"ids" => $noletia_slide_descuentos_id),
				
				array(	"name" => "Segunda fila izquierda",
						"id" => $shortname."_".$tipo_portada."_".$provincia_portada."_descuento3",
						"std" => "Automático",
						"type" => "select",
						"options" => $noletia_slide_descuentos,
						"ids" => $noletia_slide_descuentos_id),
				
				array(	"name" => "Segunda fila derecha",
						"id" => $shortname."_".$tipo_portada."_".$provincia_portada."_descuento4",
						"std" => "Automático",
						"type" => "select",
						"options" => $noletia_slide_descuentos,
						"ids" => $noletia_slide_descuentos_id)
						
		);
		
		
$options_concursos = array (
						
				array(	"name" => "Slide: Si seleccionas el modo Automático en el primer Slide, se mostrarán en el Slide, las últimas entradas que tengan la etiqueta 'Portada' (sin las comillas). Importante: Si no hay ningún descuento de la provincia seleccinada, los selectores mostrarán todos los descuentos.",
						"type" => "heading"),
	
				array(	"name" => "Primera entrada en el slide",
						"id" => $shortname."_".$tipo_portada."_".$provincia_portada."_slide1",
						"std" => "Última",
						"type" => "select",
						"options" => $noletia_concurso,
						"ids" => $noletia_concurso_id),
				
				array(	"name" => "Segunda entrada en el slide",
						"id" => $shortname."_".$tipo_portada."_".$provincia_portada."_slide2",
						"std" => "Penúltima",
						"type" => "select",
						"options" => $noletia_concurso,
						"ids" => $noletia_concurso_id),
				
				array(	"name" => "Tercera entrada en el slide",
						"id" => $shortname."_".$tipo_portada."_".$provincia_portada."_slide3",
						"std" => "Antepenúltima",
						"type" => "select",
						"options" => $noletia_concurso,
						"ids" => $noletia_concurso_id),
				
				array(	"name" => "Cuarta entrada en el slide",
						"id" => $shortname."_".$tipo_portada."_".$provincia_portada."_slide4",
						"std" => "Anterior a la antepenúltima",
						"type" => "select",
						"options" => $noletia_concurso,
						"ids" => $noletia_concurso_id),
						
						
						
				array(	"name" => "Módulos medianos de descuentos (estos cuatro módulos serán ordenados por su fecha a la hora de visualizarse)",
						"type" => "heading"),
	
				array(	"name" => "Módulo 1",
						"id" => $shortname."_".$tipo_portada."_".$provincia_portada."_concurso1",
						"std" => "Automático",
						"type" => "select",
						"options" => $noletia_concurso,
						"ids" => $noletia_concurso_id),
				
				array(	"name" => "Módulo 2",
						"id" => $shortname."_".$tipo_portada."_".$provincia_portada."_concurso2",
						"std" => "Automático",
						"type" => "select",
						"options" => $noletia_concurso,
						"ids" => $noletia_concurso_id),
				
				array(	"name" => "Módulo 3",
						"id" => $shortname."_".$tipo_portada."_".$provincia_portada."_concurso3",
						"std" => "Automático",
						"type" => "select",
						"options" => $noletia_concurso,
						"ids" => $noletia_concurso_id),
				
				array(	"name" => "Módulo 4",
						"id" => $shortname."_".$tipo_portada."_".$provincia_portada."_concurso4",
						"std" => "Automático",
						"type" => "select",
						"options" => $noletia_concurso,
						"ids" => $noletia_concurso_id)
						
		);
	

	
	set_site_transient( $nombre_query_cache, $options, 300 );
	set_site_transient( $nombre_query_cache_desc, $options_descuentos, 300 );
} 

/*************************************************************************************************************************************************
// FIN SISTEMA DE CACHÉ: 
//Hemos metido en un if absolutamente todas estas querys, para que el sistema, primero mire si la variable $options y $optionsdescuentos están en canché.
//Si así es, las cogerá de caché y no hará ninguna de las suiguientes llamadas a la BD
*************************************************************************************************************************************************/




// ADMIN PANEL

function noletia_add_admin() {

	 global $themename, $options, $options_descuentos, $options_concursos;
	
	$tipo_prov = $_COOKIE['noletia_gest_port_tipo_prov'];
	$tipo_prov = explode('---',$tipo_prov);
	$cookie_tipo = $tipo_prov[0];
	$cookie_prov = $tipo_prov[1];
	
	if($cookie_tipo == 'descuentos'){ $options = $options_descuentos; }elseif($cookie_tipo == 'concursos'){ $options = $options_concursos; }
	
	if ( $_GET['page'] == basename(__FILE__) ) {	
        if ( 'save' == $_REQUEST['action'] ) {
	
                foreach ($options as $value) {
					if($value['type'] != 'multicheck'){
                    	update_option( $value['id'], $_REQUEST[ $value['id'] ] ); 
					}else{
						foreach($value['options'] as $mc_key => $mc_value){
							$up_opt = $value['id'].'_'.$mc_key;
							update_option($up_opt, $_REQUEST[$up_opt] );
						}
					}
				}

                foreach ($options as $value) {
					if($value['type'] != 'multicheck'){
                    	if( isset( $_REQUEST[ $value['id'] ] ) ) { update_option( $value['id'], $_REQUEST[ $value['id'] ]  ); } else { /*delete_option( $value['id'] );*/ } 
					}else{
						foreach($value['options'] as $mc_key => $mc_value){
							$up_opt = $value['id'].'_'.$mc_key;						
							if( isset( $_REQUEST[ $up_opt ] ) ) { update_option( $up_opt, $_REQUEST[ $up_opt ]  ); } else { /*delete_option( $up_opt );*/ } 
						}
					}
				}
						
				header("Location: admin.php?page=functions.php&saved=true");								
			
			die;

		} else if ( 'reset' == $_REQUEST['action'] ) {
			delete_option('sandbox_logo');
			
			header("Location: admin.php?page=functions.php&reset=true");
			die;
		}

	}

add_menu_page("Portadas", "Portadas", 'moderate_comments', basename(__FILE__), 'noletia_page');
}


function noletia_page (){

	global $options, $options_descuentos, $options_concursos, $themename, $manualurl;
		

	//Mostramos en el primer select todos los TIPOS de portadas		
	$noletia_tipos_ids[0] = 'musica';
	$noletia_tipo_nombre[0] = 'Música';
	$noletia_tipos_ids[1] = 'artes-escenicas';
	$noletia_tipo_nombre[1] = 'Artes escénicas';
	$noletia_tipos_ids[2] = 'arte';
	$noletia_tipo_nombre[2] = 'Arte';
	$noletia_tipos_ids[3] = 'literatura';
	$noletia_tipo_nombre[3] = 'Literatura';
	$noletia_tipos_ids[4] = 'audiovisual';
	$noletia_tipo_nombre[4] = 'Audiovisual';
	$noletia_tipos_ids[5] = 'formacion';
	$noletia_tipo_nombre[5] = 'Formación';
	$noletia_tipos_ids[6] = 'descuentos';
	$noletia_tipo_nombre[6] = 'Descuentos';
  	$noletia_tipos_ids[7] = 'concursos';
	$noletia_tipo_nombre[7] = 'Concursos';
  	$noletia_tipos_ids[8] = 'agenda';
	$noletia_tipo_nombre[8] = 'Agenda';
	  
  
	
	
	$noletia_prov_id[0] = 'a-coruna';
	$noletia_prov_name[0] = 'A Coruña';
	$noletia_prov_id[1] = 'almeria';
	$noletia_prov_name[1] = 'Almería';
	$noletia_prov_id[2] = 'cadiz';
	$noletia_prov_name[2] = 'Cádiz';
	$noletia_prov_id[3] = 'ciudad-real';
	$noletia_prov_name[3] = 'Ciudad Real';
	$noletia_prov_id[4] = 'cordoba';
	$noletia_prov_name[4] = 'Córdoba';
	$noletia_prov_id[5] = 'granada';
	$noletia_prov_name[5] = 'Granada';
	$noletia_prov_id[6] = 'huelva';
	$noletia_prov_name[6] = 'Huelva';
	$noletia_prov_id[7] = 'madrid';
	$noletia_prov_name[7] = 'Madrid';
	$noletia_prov_id[8] = 'malaga';
	$noletia_prov_name[8] = 'Málaga';
	$noletia_prov_id[9] = 'ourense';
	$noletia_prov_name[9] = 'Ourense';
	$noletia_prov_id[10] = 'pontevedra';
	$noletia_prov_name[10] = 'Pontevedra';
	$noletia_prov_id[11] = 'sevilla';
	$noletia_prov_name[11] = 'Sevilla';
	$noletia_prov_id[12] = 'toledo';
	$noletia_prov_name[12] = 'Toledo';
	

		
		
		//Explode de las cookies de Provincia y tipo de portada
		$tipo_prov = $_COOKIE['noletia_gest_port_tipo_prov'];
		$tipo_prov = explode('---',$tipo_prov);
		$cookie_tipo = $tipo_prov[0];
		$cookie_prov = $tipo_prov[1];
		?>
		<div class="wrap">
    		
    		<div class="seleccion-tipo-portada">
    			<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" name="form-seleccion-tipo-portada">
				<h2>Portadas</h2>
				<p style="clear:both;width:90%;border:1px solid #E6DB55;padding:10px;background-color:#FFFFE0;">Si seleccionas el modo Automático en el primer selector de cualquiera de las secciones (Slide, concursos...), se mostrarán las últimas entradas de forma automática, si seleccionas una entrada en el primer selector, tendrás que seleccionar también las demás, pues estará en modo manual.</p>
						
					<div style="clear:both;height:20px;"></div>  			
					<!--START: GENERAL SETTINGS-->
     						
     					<table class="maintable">
     						<tr class="mainrow">
     							<td class="titledesc" style="margin: -5px 0 0 0;vertical-align:text-top;">Selecciona la portada que deseas personalizar:</td>
							</tr>	
							<tr>
								<td class="forminp">
									<select name="noletia_portadas_tipo" id="noletia_portadas_tipo" style="width: 300px;margin-right:80px;">
	                					<option <?php if($cookie_tipo == 'global'){ echo ' selected="selected"'; } ?> value="global">Global</option>
	                					<?php for($i=0;$i<=7;$i++){ ?>
	                						<option <?php if($cookie_tipo == $noletia_tipos_ids[$i]){echo ' selected="selected"';}?> value="<?php echo $noletia_tipos_ids[$i]; ?>">
	                							<?php echo $noletia_tipo_nombre[$i]; ?>
	                						</option>
	                					<?php } ?>
	            					</select>
	            					<br/><br />
									<span>Selecciona la opción 'Global' si deseas ver la portada principal.<br /><br /></span>
								</td>
								<td class="forminp">
									<select name="noletia_portadas_prov" id="noletia_portadas_prov" style="width: 300px;">
	                					<option <?php if($cookie_prov == 'todas') { echo ' selected="selected"'; }?> value="todas">Todas</option>
	                					<?php for($j=0;$j<=12;$j++){ ?>
	                						<option<?php if($cookie_prov == $noletia_prov_id[$j]){echo ' selected="selected"';}?> value="<?php echo $noletia_prov_id[$j]; ?>">
	                							<?php echo $noletia_prov_name[$j]; ?>
	                						</option>
	                					<?php } ?>
	            					</select>
	            					<br/><br />
									<span>Selecciona la opción 'Todas' si deseas ver la portada sin ningún filtro por provincias.<br /><br /></span>
								</td>	
							</tr>		
						</table>
			

							<p class="submit">
								<input class="button-primary" name="save" type="submit" value="Ver Portada" />    
								<input type="hidden" name="noletia_ver_portada" value="true" />
							</p>							
							
							<div style="clear:both;"></div>		
						
						<!--END: GENERAL SETTINGS-->						  
            	</form>
    		</div><!--  // End if div.seleccion-tipo-portada -->
    
    
 			<?php if(isset($_COOKIE['noletia_gest_port_tipo_prov'])){ ?>
 			
 				<div class="secciones-de-portada">
 				<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" name="form-secciones-de-portada">
				<h2>Portadas</h2>

					<?php if ( $_REQUEST['saved'] ) { ?><div style="clear:both;height:20px;width:90%;border:1px solid #E6DB55;padding:20px;background-color:#FFFFE0;">La portada se ha actualizado</div><?php } ?>
											
					<div style="clear:both;height:20px;"></div>  			
					<!--START: GENERAL SETTINGS-->
     						
     					<table class="maintable">
     						
     					<?php 
     					
     					if($cookie_tipo == 'descuentos'){
     						
     						$options = $options_descuentos;
     						
     					}elseif($cookie_tipo == 'concursos'){
     					
     						$options = $options_concursos;
     					
     					}
     					
     					?>
     						
							<?php foreach ($options as $value) { ?>
	
									<?php if ( $value['type'] <> "heading" ) { ?>
	
										<tr class="mainrow">
										<td class="titledesc" style="margin: -5px 0 0 0;vertical-align:text-top;"><?php echo $value['name']; ?></td>
										<td class="forminp">
		
									<?php } ?>		 
	
									<?php
										
										switch ( $value['type'] ) {
										
										case 'select':?>
										
											<select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" style="width: 300px">
	                						<?php $i=0; ?>
	                						<?php foreach ($value['options'] as $option) { ?>
	                							<?php $ids = $value['ids']; ?>
	                							<option<?php if ( get_settings( $value['id'] ) == $ids[$i]) { echo ' selected="selected"'; } elseif ($option == $value['std']) { echo ' selected="selected"'; } ?> value="<?php echo $ids[$i]; ?>"><?php echo $option; ?></option>
	                							<?php $i++; ?>
	                						<?php } ?>
	            							</select><?php
		
										break;
										
										case "heading":
									?>
											</table> 	
		    								<h3 class="title"><?php echo $value['name']; ?></h3>
											<table class="maintable">
									<?php
										
										break;
										default:
										break;
									
									} ?>
	
									<?php if ( $value['type'] <> "heading" ) { ?>
	
										<?php if ( $value['type'] <> "checkbox" ) { ?><br/><br /><?php } ?><span><?php echo $value['desc']; ?></span>
										</td></tr>
	
									<?php } ?>		
									
							<?php } ?>	
							
							</table>	


							<p class="submit">
								<input class="button-primary" name="save" type="submit" value="Guardar cambios" />    
								<input type="hidden" name="action" value="save" />
							</p>							
							
							<div style="clear:both;"></div>		
						
						<!--END: GENERAL SETTINGS-->						  
            	</form>
 				</div>
 			
 			<?php } // End if div.secciones-de-portada ?>
 			
			
            
</div><!--wrap-->

<div style="clear:both;height:20px;"></div>
 
 <?php

};

add_action('admin_menu', 'noletia_add_admin');
add_action('admin_head', 'noletia_admin_head');
?>