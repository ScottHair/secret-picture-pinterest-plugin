<?php
/* 
Plugin Name: Secret Picture Pinterest Plugin
Plugin URI: http;//twodeuces.com/
Version: 0.0.1
Author: Scott Hair
Description: Adds a link to to a unique image for use in pinterest pins. It collects url and met description information from Custom Fields for the post.
*/

if (!class_exists("SecretPicturePinterest")) {
	class SecretPicturePinterest {
		// Class constuctor function
		function SecretPicturePinterest() {			
			// add things here to set default values if needed.
		}
		
		
		// Init function is run when plugin is activated
		function init() {
			// Add Post Meta Codes to Post #1 with some values
			// This will ensure they are in the post custom fields box.
			add_post_meta(1, 'Pinterest_Image_Meta', 'Some Descriptive Text', TRUE);
			add_post_meta(1, 'Pinterest_Image_Url', 'A Url for image', TRUE);
		}
		
		
		// Enqueues the pinterest javascript
		function enqueue_these_scripts() {
			wp_enqueue_script( 'pinterest', 'http://assets.pinterest.com/js/pinit.js' );
		}
		
		
		// Main workhorse will add pinterest image and meta data to content
		// data-pin-media & blank.gif were suggested by Jesse Gardner
		function test_content_addition( $content='' ) {
			global $post;
		
			if ( is_single() ) {
			
				$blank_gif_url = plugins_url( 'images/blank.gif', __FILE__ );
				$site_link_url = get_site_url();
		
				// This ads pinterest pin it button to top of post
				$new_content = '<a href="//www.pinterest.com/pin/create/button/" data-pin-do="buttonBookmark"  data-pin-config="beside" data-pin-color="grey" data-pin-height="28"><img src="//assets.pinterest.com/images/pidgets/pinit_fg_en_rect_grey_28.png" /></a>';
				
				$pinterest_image_url = get_post_meta( $post->ID, 'Pinterest_Image_Url', TRUE );		
				$pinterest_image_meta = get_post_meta( $post->ID, 'Pinterest_Image_Meta', TRUE );
		
				if ( $pinterest_image_meta == '' ) { $pinterest_image_meta = get_the_title( $post->ID ). " ~ $site_link_url"; } else { $pinterest_image_meta .= " ~ $site_link_url"; }
				
				if ( $pinterest_image_url != '' ) {
					$new_content .= '<div class="pin-image" style="display: none;">';
					$new_content .= '<img src="'.$blank_gif_url.'" alt="" data-pin-media="'.$pinterest_image_url.'" data-pin-description="'.$pinterest_image_meta.'" >';
					$new_content .= '</div>';
				}
		
				// This section adds pin meta data to all the images on the post
				$pattern = '/<img(.*?)src="(.*?).(bmp|gif|jpeg|jpg|png)"(.*?)>/i';
			  	$replacement = '<img$1src="$2.$3" data-pin-description=\''.$pinterest_image_meta.'\'$4>';
				$content = preg_replace( $pattern, $replacement, $content );
				
				$content = $new_content . $content;
			}
		
			return $content;
		}
		
		
	} //End Class SecretPicturePinterest
} 


// Instantiate a new object of our plugin class
if (class_exists("SecretPicturePinterest")) {
	$secretpp = new SecretPicturePinterest();
}


//Actions and Filters	
if (isset($secretpp)) {
	// Activation Hook
	register_activation_hook(__FILE__, array(&$secretpp, 'init'));					// On activation - initialize Custom Fields
		
	// Actions
	add_action('wp_enqueue_scripts', array(&$secretpp, 'enqueue_these_scripts'));	// Enqueues the pinterest script
	
	// Filters
	add_filter('the_content', array(&$secretpp, 'test_content_addition'));			// Main work horse of plugin adds appropriate content

}

?>