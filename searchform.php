<div class="search_container">
	<form role="search" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>">
   	 	<div class="search-text-div">
        	<input type="text" value="" name="s" id="s" />
    	</div>
    	<div class="search-image-div">
    		<input type="image" alt="Search" src="<?php bloginfo( 'home' ); ?>/wp-content/themes/noletia/images/lupa.jpg" />
    	</div>
	</form>
</div>
<?php if(is_search()): ?>
	<p class="search_example">Ejemplo de búsqueda: Conciertos <?php if($_COOKIE['noletia_prov'] != 'todas'){ echo $_COOKIE['noletia_prov']; } ?></p>
<?php endif; ?>