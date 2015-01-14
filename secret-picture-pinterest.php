<?php
/*
Plugin Name: Secret Picture Pinterest Plugin
Plugin URI: http://twodeuces.com/
Version: 0.1.0
Author: Scott Hair
Description: Adds a link to to a unique image for use in pinterest pins. It collects url and met description information from Custom Fields for the post.
*/

if (!class_exists("SecretPicturePinterest")) {
	class SecretPicturePinterest {
		var $adminOptionsName = "SecretPPAdminOptions";

		// Class constuctor function
		function SecretPicturePinterest() {
			// add things here to set default values if needed.
		}

		// Retunrs an array of admin options and loads defaults
		function getAdminOptions() {
			$secretPPAdminOptions = array('include_js' => 'true',
				'use_button' => 'true',
				'button_size' => 'large',
				'button_shape' => 'rect',
				'button_color' => 'red',
				'button_lang' => 'en',
				'show_pin_count' => 'beside');
			$devOptions = get_option($this->adminOptionsName);
			if (!empty($devOptions)) {
				foreach ($devOptions as $key => $option) $secretPPAdminOptions[$key] = $option;
			}
			update_option($this->adminOptionsName, $secretPPAdminOptions);
			return $secretPPAdminOptions;
		}


		// Init function is run when plugin is activated
		function init() {
			$this->getAdminOptions();
			// Add Post Meta Codes to Post #1 with some values
			// This will ensure they are in the post custom fields box.
			add_post_meta(1, 'Pinterest_Image_Meta', 'Some Descriptive Text', TRUE);
			add_post_meta(1, 'Pinterest_Image_Url', 'A Url for image', TRUE);
		}


		// Prints out the admin page
		function printAdminPage() {
			$devOptions = $this->getAdminOptions();

			if (isset($_POST['update_secretPicturePinterestSeriesSettings'])) {
				if (isset($_POST['secretPPincludeJS'])) { $devOptions['include_js'] = $_POST['secretPPincludeJS']; }
				if (isset($_POST['secretPPuseButton'])) { $devOptions['use_button'] = $_POST['secretPPuseButton']; }
				if (isset($_POST['secretPPsize'])) { $devOptions['button_size'] = $_POST['secretPPsize']; }
				if (isset($_POST['secretPPshape'])) { $devOptions['button_shape'] = $_POST['secretPPshape']; }
				if (isset($_POST['secretPPcolor'])) { $devOptions['button_color'] = $_POST['secretPPcolor']; }
				if (isset($_POST['secretPPlang'])) { $devOptions['button_lang'] = $_POST['secretPPlang']; }
				if (isset($_POST['secretPPshowPC'])) { $devOptions['show_pin_count'] = $_POST['secretPPshowPC']; }
				update_option($this->adminOptionsName, $devOptions);
				?>
				<div class="updated"><p><strong><?php _e("Settings Updated.", "SecretPicturePinterestPlugin"); ?></strong></p></div>
			<?php } ?>

<div class=wrap>
<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
<h2>Secret Pinterest Picture Plugin Settings</h2>

<h3>Insert the PinIt Javascript file?</h3>
<p>Selecting "Yes" will insert the "pinit.js" file in the header. Use the "No" option if the PinIt javascript file is inserted somewhere else in either your theme or other plugins.</p>
<p><label for="secretPPinclude_yes"><input type="radio" id="secretPPinclude_yes" name="secretPPincludeJS" value="true" <?php if ($devOptions['include_js'] == "true") { _e('checked="checked"', "SecretPicturePinterestPlugin"); }?> /> Yes</label>&nbsp;&nbsp;&nbsp;&nbsp;<label for="secretPPinclude_no"><input type="radio" id="secretPPinclude_no" name="secretPPincludeJS" value="false" <?php if ($devOptions['include_js'] == "false") { _e('checked="checked"', "SecretPicturePinterestPlugin"); }?>/> No</label></p>

<h3>Use the PinIt button below the title of the post?</h3>
<p>Selecting "Yes" will insert a [Pin It] button below the title of the post. Use the "No" option to omit this button.</p>
<p><label for="secretPPuseButton_yes"><input type="radio" id="secretPPuseButton_yes" name="secretPPuseButton" value="true" <?php if ($devOptions['use_button'] == "true") { _e('checked="checked"', "SecretPicturePinterestPlugin"); }?> /> Yes </label>&nbsp;&nbsp;&nbsp;&nbsp;<label for="secretPPuseButton_no"><input type="radio" id="secretPPuseButton_no" name="secretPPuseButton" value="false" <?php if ($devOptions['use_button'] == "false") { _e('checked="checked"', "SecretPicturePinterestPlugin"); }?>/> No </label></p>

<?php if ($devOptions['use_button'] == 'true'): ?>
<div class="secretPPsettings" style="margin:20px; padding:10px; background-color: #fff; -webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1); box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);">
<h3>What size Pin It button would you like?</h3>
<p><label for="secretPPsize_large"><input type="radio" id="secretPPsize_large" name="secretPPsize" value="large" <?php if ($devOptions['button_size'] == "large") { _e('checked="checked"', "SecretPicturePinterestPlugin"); }?> /> Large </label>&nbsp;&nbsp;&nbsp;&nbsp;<label for="secretPPsize_small"><input type="radio" id="secretPPsize_small" name="secretPPsize" value="small" <?php if ($devOptions['button_size'] == "small") { _e('checked="checked"', "SecretPicturePinterestPlugin"); }?>/> Small </label></p>
<br />
<h3>What shape Pin It button would you like?</h3>
<p>If you choose Circular shape, color, language and pin count selectors will not apply. </p>
<p><label for="secretPPshape_rect"><input type="radio" id="secretPPshape_rect" name="secretPPshape" value="rect" <?php if ($devOptions['button_shape'] == "rect") { _e('checked="checked"', "SecretPicturePinterestPlugin"); }?> /> Rectangle </label>&nbsp;&nbsp;&nbsp;&nbsp;<label for="secretPPshape_round"><input type="radio" id="secretPPshape_round" name="secretPPshape" value="round" <?php if ($devOptions['button_shape'] == "round") { _e('checked="checked"', "SecretPicturePinterestPlugin"); }?>/> Circular </label></p>
<br />
<h3>What color Pin It button would you like?</h3>
<p><label for="secretPPcolor_red"><input type="radio" id="secretPPcolor_red" name="secretPPcolor" value="red" <?php if ($devOptions['button_color'] == "red") { _e('checked="checked"', "SecretPicturePinterestPlugin"); }?> /> Red </label>&nbsp;&nbsp;&nbsp;&nbsp;<label for="secretPPcolor_gray"><input type="radio" id="secretPPcolor_gray" name="secretPPcolor" value="gray" <?php if ($devOptions['button_color'] == "gray") { _e('checked="checked"', "SecretPicturePinterestPlugin"); }?>/> Gray </label></label>&nbsp;&nbsp;&nbsp;&nbsp;<label for="secretPPcolor_white"><input type="radio" id="secretPPcolor_white" name="secretPPcolor" value="white" <?php if ($devOptions['button_color'] == "white") { _e('checked="checked"', "SecretPicturePinterestPlugin"); }?>/> White </label></p>
<br />
<h3>What language do you want on your Pin It button?</h3>
<p><label for="secretPPlang_en"><input type="radio" id="secretPPlang_en" name="secretPPlang" value="en" <?php if ($devOptions['button_lang'] == "en") { _e('checked="checked"', "SecretPicturePinterestPlugin"); }?> /> English </label>&nbsp;&nbsp;&nbsp;&nbsp;<label for="secretPPlang_ja"><input type="radio" id="secretPPlang_ja" name="secretPPlang" value="ja" <?php if ($devOptions['button_lang'] == "ja") { _e('checked="checked"', "SecretPicturePinterestPlugin"); }?>/> Japanese </label></p>
<br />
<h3>Where would you like the Pin Count shown?</h3>
<p><label for="secretPPshowPC_above"><input type="radio" id="secretPPshowPC_above" name="secretPPshowPC" value="above" <?php if ($devOptions['show_pin_count'] == "above") { _e('checked="checked"', "SecretPicturePinterestPlugin"); }?> /> Above the Button </label>&nbsp;&nbsp;&nbsp;&nbsp;<label for="secretPPshowPC_beside"><input type="radio" id="secretPPshowPC_beside" name="secretPPshowPC" value="beside" <?php if ($devOptions['show_pin_count'] == "beside") { _e('checked="checked"', "SecretPicturePinterestPlugin"); }?>/> Beside the Button </label></label>&nbsp;&nbsp;&nbsp;&nbsp;<label for="secretPPshowPC_none"><input type="radio" id="secretPPshowPC_none" name="secretPPshowPC" value="none" <?php if ($devOptions['show_pin_count'] == "none") { _e('checked="checked"', "SecretPicturePinterestPlugin"); }?>/> Not Shown </label></p>

</div>

<?php endif; ?>

<div class="submit">
<input type="submit" name="update_secretPicturePinterestSeriesSettings" value="<?php _e('Update Settings', 'SecretPicturePinterstPlugin') ?>" /></div>
</form>
</div>

		<?php }


		// Enqueues the pinterest javascript
		function enqueue_these_scripts() {
			$devOptions = $this->getAdminOptions();
			if ( $devOptions['include_js'] == 'true' ) {
				wp_enqueue_script( 'pinterest', 'http://assets.pinterest.com/js/pinit.js' );
			}
		}


		// Main workhorse will add pinterest image and meta data to content
		// data-pin-media & blank.gif were suggested by Jesse Gardner
		function test_content_addition( $content='' ) {
			global $post;
			$devOptions = $this->getAdminOptions();

			if ( is_single() ) {

				$blank_gif_url = plugins_url( 'images/blank.gif', __FILE__ );
				$site_link_url = get_site_url();

				// This ads pinterest pin it button to top of post if enabled by user settings
				if ( $devOptions['use_button'] == 'true' ) {
					$new_content = '<a href="//www.pinterest.com/pin/create/button/" data-pin-do="buttonBookmark" ';

					// Is pin round or rectangle
					if ( $devOptions['button_shape'] == 'round' ) {
						//determine size of pin
						if( $devOptions['button_size'] == 'large' ) {
							$new_content .= 'data-pin-shape="round" data-pin-height="32"';
							$new_src = 'pinit_fg_en_round_red_32.png';
						} else {
							$new_content .= 'data-pin-shape="round"';
							$new_src = 'pinit_fg_en_round_red_16.png';
						}

					// Pin is rectangle
					} else {
						$rectangle_height = ($devOptions['button_size'] == 'large' ) ? '28' : '20';
						$new_content .= 'data-pin-config="'.$devOptions['show_pin_count'].'" ';
						$new_content .= 'data-pin-color="'.$devOptions['button_color'].'" ';
						$new_content .= 'data-pin-lang="'.$devOptions['button_lang'].'" ';
						$new_content .= 'data-pin-height="'.$rectangle_height.'"';
						$new_src = 'pinit_fg_'.$devOptions['button_lang'].'_rect_'.$devOptions['button_color'].'_'.$rectangle_height.'.png';
					}

					$new_content .= '><img src="//assets.pinterest.com/images/pidgets/'.$new_src.'" /></a>';
				}

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


// Initialize the admin panel
if (!function_exists("SecretPicturePinterestPlugin_ap")) {
	function SecretPicturePinterestPlugin_ap() {
		global $secretpp;
		if (!isset($secretpp)) {
			return;
		}
		if (function_exists('add_options_page')) {
			add_options_page('Secret Pinterest Picture', 'Secret Pinterest Picture', 'manage_options', 'secret-pinterest-picture', array(&$secretpp, 'printAdminPage'));
		}
	}
}


//Actions and Filters
if (isset($secretpp)) {
	// Activation Hook
	register_activation_hook(__FILE__, array(&$secretpp, 'init'));					// On activation - initialize Custom Fields

	// Actions
	add_action('wp_enqueue_scripts', array(&$secretpp, 'enqueue_these_scripts'));	// Enqueues the pinterest script
	add_action('admin_menu', 'SecretPicturePinterestPlugin_ap');					// Ads the Admin Panel

	// Filters
	add_filter('the_content', array(&$secretpp, 'test_content_addition'));			// Main work horse of plugin adds appropriate content

}

?>
