<?php
	/*
		Template Name: Script de creación de categorías para subscribe 2
	*/
?>
<?php get_header() ?>

<div style="height:100px;">&nbsp;</div>
<?php 

$args = array(
	'type'                     => 'post',
	'child_of'                 => 14,
	'parent'                   => '', // Provincias
	'orderby'                  => 'name',
	'order'                    => 'ASC',
	'hide_empty'               => 0,
	'hierarchical'             => 1,
	'exclude'                  => '',
	'include'                  => '',
	'number'                   => '',
	'taxonomy'                 => 'category',
	'pad_counts'               => false );
	
	
$categories = get_categories( $args ); 


$args2 = array(
	'type'                     => 'post',
	'child_of'                 => 3,
	'parent'                   => '', // Categorías artísticas 
	'orderby'                  => 'name',
	'order'                    => 'ASC',
	'hide_empty'               => 1,
	'hierarchical'             => 1,
	'exclude'                  => '',
	'include'                  => '',
	'number'                   => '',
	'taxonomy'                 => 'category',
	'pad_counts'               => false );


$categories2 = get_categories( $args2 ); 


$args_parent_categories = array(
	'type'                     => 'post',
	'child_of'                 => 0,
	'parent'                   => '3', // Hijas de categorías artísticas, para filtrar 
	'orderby'                  => 'name',
	'order'                    => 'ASC',
	'hide_empty'               => 1,
	'hierarchical'             => 1,
	'exclude'                  => '',
	'include'                  => '',
	'number'                   => '',
	'taxonomy'                 => 'category',
	'pad_counts'               => false );

$parent_categories = get_categories( $args_parent_categories );


foreach ( $categories as $category ) {
	echo $category->cat_name . '<br />';

	foreach ( $categories2 as $category2 ) {

		echo $category->cat_name;
		
			foreach ( $parent_categories as $parent_category ) {
				if ( $parent_category->cat_ID == $category2->category_parent ) {
					echo ' > ' . $parent_category->cat_name;
				}
			}

		
		echo  ' > ' . $category2->cat_name . '<br />';
	
	}

}




get_footer(); 
 
 
 ?>