<?php
/*
Plugin Name: WP SEO HTML Sitemap
Version: 0.5
Plugin URI: http://riseofweb.com
Description: A responsive HTML sitemap shortcode that uses all of the settings for your XML sitemap in the WordPress SEO by Yoast Plugin.
Author: Daniel Chase
Author URI: http://riseofweb.com
*/

if ( !function_exists( 'wpseo_sitemap_shortcode' ) ) {
function wpseo_sitemap_shortcode() {

// ==============================================================================
// General Variables
$checkOptions = get_option('wpseo_xml');
//print_r($checkOptions);

//Hard Coded Styles
?>
<style>
#wpseo_sitemap{width:100%; position:relative; clear:both;}
#wpseo_sitemap .wpseo_leftCol{width:100%; max-width:350px; position:relative; float:left;}
#wpseo_sitemap .wpseo_rightCol{width:100%; max-width:350px; position:relative; float:right;}
#wpseo_sitemap h3{font-size:26px; line-height:32px; padding-bottom:0; margin-bottom:8px; width:100%;}
#wpseo_sitemap h4{font-size:18px; line-height:20px; padding-bottom:0; margin-bottom:4px;}
#wpseo_sitemap ul{list-style-type:disc; margin-bottom:0; padding-bottom:15px; margin-before:1em; margin-after:1em; padding-start:40px; font-size:15px; line-height:24px; font-weight:normal; background:none;}
#wpseo_sitemap ul ul{margin-bottom:0; list-style-type:circle;}
#wpseo_sitemap li{display:list-item;}
#wpseo_sitemap .wpseo_clearRow{width:100%; height:1px; display:block; clear:both; position:relative;}
@media screen and (max-width:980px){#wpseo_sitemap .wpseo_rightCol{float:left;}}
</style>

<div id="wpseo_sitemap">
<div class="wpseo_rightCol">
<?php 
// ==============================================================================
// Posts
if ( $checkOptions['post_types-post-not_in_sitemap'] !== true ){
	?>
	<h3>Posts</h3>
	<ul>
	<?php
	//Categories
	$cateEx = '';
	$getCate = get_option('wpseo_taxonomy_meta');
	foreach( $getCate['category'] as $cateID => $item ) {
		if( ( $item['wpseo_noindex'] == 'noindex' ) || ( $item['wpseo_sitemap_include'] == 'never' ) ){
			if( $cateEx == '' ) {
				$cateEx = $cateID;
			}
			else{
				$cateEx .= ', '.$cateID;
			}
		}
	}
	$cats = get_categories('exclude='.$cateEx);
	foreach ($cats as $cat) {
		echo "<li style='margin-top:10px;'><h4><a href='".esc_url( get_term_link( $cat ) )."'>".$cat->cat_name."</a></h4>";
		echo "<ul>";
		query_posts('posts_per_page=-1&cat='.$cat->cat_ID);
		while(have_posts()) {
			the_post();
			if ( ( get_post_meta( get_the_ID(), '_yoast_wpseo_meta-robots-noindex', true ) === '1' && get_post_meta( get_the_ID(), '_yoast_wpseo_sitemap-include', true ) !== 'always' ) || ( get_post_meta( get_the_ID(), '_yoast_wpseo_sitemap-include', true ) === 'never' ) ||  ( get_post_meta( get_the_ID(), '_yoast_wpseo_redirect', true ) !== '' ) ) {
				continue;
			}

			$category = get_the_category();
			// Only display a post link once, even if it's in multiple categories
			if ( $category[0]->cat_ID == $cat->cat_ID ) {
				echo '<li><a href="'.get_permalink().'">'.get_the_title().'</a></li>';
			}
		}
		echo "</ul>";
		echo "</li>";
	}
	?>
	</ul>
<?php }

// ==============================================================================
// Link To Sitemap

echo '<h3><a href="'.site_url().'/sitemap_index.xml" target="_blank">Sitemap XML</a></h3>';

?>
<div class="wpseo_clearRow"></div>
</div>
<div class="wpseo_leftCol">
<?php 

// ==============================================================================
// Authors
if ( $checkOptions['disable_author_sitemap'] !== true ){
	?>
	<h3>Authors</h3>
	<ul>
	<?php
	$authEx = implode (", ", get_users( 'orderby=nicename&meta_key=wpseo_excludeauthorsitemap&meta_value=on') );
	wp_list_authors(
	  array(
		'exclude_admin' => false,
		'exclude' => $authEx
	  )
	);
	?>
	</ul>
<?php
} 

// ==============================================================================
// Pages
if ( $checkOptions['post_types-page-not_in_sitemap'] !== true ){
	?>
	<h3>Pages</h3>
	<ul>
	<?php
	// Add pages you'd like to exclude in the exclude here
	$pageInc = '';
	$getPages = get_all_page_ids();
	foreach( $getPages as $pageID ) {
		if ( ( get_post_meta( $pageID, '_yoast_wpseo_meta-robots-noindex', true ) === '1' && get_post_meta( $pageID, '_yoast_wpseo_sitemap-include', true ) !== 'always' ) || ( get_post_meta( $pageID, '_yoast_wpseo_sitemap-include', true ) === 'never' ) ||  ( get_post_meta( $pageID, '_yoast_wpseo_redirect', true ) !== '' ) ) {
			continue;
		}
		if( $pageInc == ''){
			$pageInc = $pageID;
			continue;
		}
		$pageInc .= ', '.$pageID;
	}
	wp_list_pages( array( 'include' => $pageInc,'title_li' => '', 'sort_column'  => 'post_title', 'sort_order' => 'ASC' ) );
	?>
	</ul>
<?php
}

// ==============================================================================
// Custom Post Types

foreach( get_post_types( array('public' => true) ) as $post_type ) {
	$checkSitemap = 'post_types-'.$post_type.'-not_in_sitemap';
	if ( ( in_array( $post_type, array('post','page','attachment') ) ) || ( $checkOptions[$checkSitemap] === true ) ){
		continue;
	}
	$postType = get_post_type_object( $post_type );
	$postTypeLink = get_post_type_archive_link($postType->name);
	if( !empty( $postTypeLink ) ){
		echo '<h3><a href="'. $postTypeLink .'">'. $postType->labels->name .'</a></h3>';
	}
	else{
		echo '<h3>'. $postType->labels->name .'</h3>';
	}
	echo '<ul>';

	query_posts('post_type='.$post_type.'&posts_per_page=-1&orderby=title&order=ASC');
	while( have_posts() ) {
		the_post();
		if ( ( get_post_meta( get_the_ID(), '_yoast_wpseo_meta-robots-noindex', true ) === '1' && get_post_meta( get_the_ID(), '_yoast_wpseo_sitemap-include', true ) !== 'always' ) || ( get_post_meta( get_the_ID(), '_yoast_wpseo_sitemap-include', true ) === 'never' ) ||  ( get_post_meta( get_the_ID(), '_yoast_wpseo_redirect', true ) !== '' ) ) {
			continue;
		}
		echo '<li><a href="'.get_permalink().'">'.get_the_title().'</a></li>';
	}

	echo '</ul>';
}
?>
<div class="wpseo_clearRow"></div>
</div>
<div class="wpseo_clearRow"></div>
</div>
<?php
}
}
add_shortcode('wpseo_html_sitemap', 'wpseo_sitemap_shortcode');

?>