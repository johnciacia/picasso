<?php
/*
Plugin Name: Picsso - WordPress Albums
Plugin URI: http://www.johnciacia.com/picasso/
Description: Extend the WordPress gallery be adding support of albums.
Version: 1.1.4
Author: John Ciacia
Author URI: http://www.johnciacia.com

Copyright 2011  John Ciacia  (email : software [at] johnciacia [dot] com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/


/**
 * Note: To enable Post Thumbnails, the current theme must include 
 * add_theme_support( 'post-thumbnails' ); in its functions.php file. 
 * See also Post Thumbnails. <http://codex.wordpress.org/Post_Thumbnails>
 * http://codex.wordpress.org/Gallery_Shortcode
 */
add_shortcode('album', 'picasso_album');

function picasso_album($atts) {
	
	global $post, $wp_locale;
	
	$id = get_the_ID();
	static $instance = 0;
	$instance++;

	// Allow plugins/themes to override the default gallery template.
	$output = apply_filters( 'post_album', '', $atts );
	if( $output != '' )
		return $output;

	// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
	if( isset( $atts['orderby'] ) ) {
		$atts['orderby'] = sanitize_sql_orderby( $atts['orderby'] );
		if ( !$atts['orderby'] )
			unset( $atts['orderby'] );
	}

	extract(shortcode_atts( array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post->ID,
		'itemtag'    => 'dl',
		'title'      => false,
		'icontag'    => 'dt',
		'captiontag' => 'dd',
		'columns'    => 3,
		'size'       => 'thumbnail',
		'include'    => '',
		'exclude'    => ''
	), $atts));


	if ('RAND' == $order)
		$orderby = 'none';



	$defaults = array( 
		'post_parent' 	=> $id,
		'post_type'   	=> 'page', 
		'numberposts' 	=> -1,
		'post_status'	=> 'publish',
		'order' 		=> $order,
		'oderby' 		=> $orderby
	);
	
	if (!empty($include)) {
		$include = preg_replace('/[^0-9,]+/', '', $include);
		$defaults['include'] = $include;
	} else if (!empty($exclude)) {
		$exclude = preg_replace('/[^0-9,]+/', '', $exclude);
		$defaults['exclude'] = $exclude;
	} 
	
	$galleries = get_children($defaults);


	$itemtag = tag_escape($itemtag);
	$captiontag = tag_escape($captiontag);
	$columns = intval($columns);
	$itemwidth = $columns > 0 ? floor(100/$columns) : 100;
	$float = is_rtl() ? 'right' : 'left';

	$selector = "gallery-{$instance}";

	$gallery_style = $gallery_div = '';
	if (apply_filters('use_default_gallery_style', true))
		$gallery_style = "
		<style type='text/css'>
			#{$selector} {
				margin: auto;
			}
			#{$selector} .gallery-item {
				float: {$float};
				margin-top: 10px;
				text-align: center;
				width: {$itemwidth}%;
			}
			#{$selector} img {
				border: 2px solid #cfcfcf;
			}
			#{$selector} .gallery-caption {
				margin-left: 0;
			}
		</style>
		<!-- see gallery_shortcode() in wp-includes/media.php -->";
	$size_class = sanitize_html_class($size);
	$gallery_div = "<div id='$selector' class='gallery galleryid-{$id} gallery-columns-{$columns} gallery-size-{$size_class}'>";
	$output = apply_filters('gallery_style', $gallery_style . "\n\t\t" . $gallery_div);

	$i = 0;
	foreach($galleries as $gallery) {
		$q = get_post_thumbnail_id($gallery->ID);
		$permalink = get_permalink($gallery->ID);
		$output .= "<$itemtag class='gallery-item'>";
		$output .= "<$icontag class='gallery-icon'>";
		$output .= "<a href='$permalink'>";
		$output .= get_the_post_thumbnail($gallery->ID, 'thumbnail');
		$output .= "</a>";
		if( false !== $title ) {
			$output .= "<br />";
			$output .= get_the_title( $gallery->ID );
		}
		$output .= "</$icontag>";
		if ($captiontag && trim($attachment->post_excerpt)) {
			$output .= "
				<{$captiontag} class='wp-caption-text gallery-caption'>
				" . wptexturize($attachment->post_excerpt) . "
				</{$captiontag}>";
		}
		$output .= "</$itemtag>";
		if ($columns > 0 && ++$i % $columns == 0)
			$output .= '<br style="clear: both" />';
	}


	$output .= "
			<br style='clear: both;' />
		</div>\n";

	return $output;

		
}


?>
