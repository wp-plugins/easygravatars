<?php
/*
 * Plugin Name: Easy Gravatars
 * Plugin URI: http://dougal.gunters.org/
 * Description: Add Gravatars to your comments, without requiring any modifications to your theme files. Just activate, and you're done!
 * Version: 1.0
 * License: GPL2
 * Author: Dougal Campbell
 * Author URI: http://dougal.gunters.org/
 * Min WP Version: 2.0.4
 * Max WP Version: 2.3
 */

// Register our activation hook, so we can set our default options:
register_activation_hook(_FILE_,'eg_activate');

// Let WP know that we have an addition to the admin menus:
add_action('admin_menu', 'eg_opt_menu');
add_filter('get_comment_author_link', 'eg_gravatar');


// On activation, set our default options:
function eg_activate() {
	// ...but only if we don't already have options set...
	$eg_opt_size = get_option('eg_size');
	if ( empty($eg_opt_size) ) {
		$defaults = eg_defaults();
		foreach ($defaults as $key => $val) {
			update_option($key, $val);
		}
	}
}

function eg_defaults() {
	return array(
		'eg_size' => 80,
		'eg_rating' => 'G',
		'eg_defaulturl' => 'http://use.perl.org/images/pix.gif',
		'eg_style_span' => 'float:right; margin-left:10px; display:block',
		'eg_style_img' => '',
	);
}

// return array of plugin options, using defaults if necessary:
function eg_get_options() {
	$eg_size = get_option('eg_size');
	$eg_rating = get_option('eg_rating');
	$eg_defaulturl = get_option('eg_defaulturl');
	$eg_style_span = get_option('eg_style_span');
	$eg_style_img = get_option('eg_style_img');
	
	$defaults = eg_defaults();
	// Extra paranoia:
	if(empty($eg_size))
		$eg_size = $defaults['eg_size'];
	if(empty($eg_rating))
		$eg_rating = $defaults['eg_rating'];
	if(empty($eg_defaulturl))
		$eg_defaulturl = $defaults['eg_defaulturl'];
	if(empty($eg_style_span))
		$eg_style_span = $defaults['eg_style_span'];
	if(empty($eg_style_img))
		$eg_style_img = $defaults['eg_style_img'];
		
	return array(
		'eg_size' => $eg_size,
		'eg_rating' => $eg_rating,
		'eg_defaulturl' => $eg_defaulturl,
		'eg_style_span' => $eg_style_span,
		'eg_style_img' => $eg_style_img,
	);
}

// Filter to add a Gravatar image:
function eg_gravatar($text) {
	global $comment;
	if ( !empty( $comment->comment_author_email ) ) {
		$opts = eg_get_options();
	        // The Gravatar server normalizes email addresses to
	        // lowercase, so we should, too. Props to David Potter:
	        //   http://dpotter.net/Technical/index.php/2007/10/22/integrating-gravatar-support/
		$md5 = md5( strtolower( $comment->comment_author_email ) );
		// Need to break the default image and the inline style out 
		// into config options.
		$eg_size = $opts['eg_size'];
		$eg_rating = $opts['eg_rating'];
		$default = urlencode( $opts['eg_defaulturl'] );
		$eg_style_span = $opts['eg_style_span'];
		$text .= "<span class='eg-image'  style='$eg_style_span; width:{$eg_size}px' ><img src='http://www.gravatar.com/avatar.php?gravatar_id=$md5&amp;size=$eg_size&amp;rating=$eg_rating&amp;default=$default' width='$eg_size' height='$eg_size' alt='' /></span>";
	}

	return $text;
}


// Hook our config page into the Options menu:
function eg_opt_menu() {
	add_options_page('Easy Gravatars', 'Easy Gravatars', 'manage_options', 'testoptions', 'eg_options_page');
}

// Options update page:
function eg_options_page() {
	// defaults
	$eg_ratings = array('G', 'PG', 'R', 'X');
	$maxsize = 80;
	$minsize = 1;
	
	// See if the user has posted us some information
	// If they did, this hidden field will be set to 'Y'
	if( $_POST[ 'eg_submitted' ] == 'Y' ) {
		check_admin_referer('easy-gravatars-update-options');
		// Read their posted value
		$eg_rating = $_POST['eg_rating'];
		$eg_size = (int) $_POST['eg_size'];
		$eg_defaulturl = $_POST['eg_defaulturl'];
		$eg_style_span = $_POST['eg_style_span'];
		
		if ($eg_size > $maxsize) {
			$eg_size = $maxsize;
		}

		if ($eg_size < $minsize) {
			$eg_size = $minsize;
		}

		// Save the posted value in the database
		update_option( 'eg_rating', $eg_rating );
		update_option( 'eg_size', $eg_size );
		update_option( 'eg_defaulturl', $eg_defaulturl );
		update_option( 'eg_style_span', $eg_style_span );

		// Put an options updated message on the screen

?>
<div class="updated"><p><strong><?php _e('Options saved.', 'eg_trans_domain' ); ?></strong></p></div>
<?php
	} // endif
	
	$opts = eg_get_options();

	// Get existing option values:
	$eg_opt_rating = $opts['eg_rating'];
	$eg_opt_size = $opts['eg_size'];
	$eg_opt_defaulturl = $opts['eg_defaulturl'];
	$eg_opt_style_span = $opts['eg_style_span'];
	
// Main options form:
?>
<div class="wrap">
<h2><?php _e( 'Easy Gravatars Plugin Options', 'eg_trans_domain' ); ?></h2>

<form name="eg_opts" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
<p class="submit">
<input type="submit" name="Submit" value="<?php _e('Update Options', 'eg_trans_domain' ) ?>" />
</p>

<?php wp_nonce_field('easy-gravatars-update-options') ?>
<input type="hidden" name="eg_submitted" value="Y">

<p>
<?php _e("Size, in pixels (maximum: 80):", 'eg_trans_domain' ); ?> 
<input type="text" name="eg_size" value="<?php echo $eg_opt_size; ?>" size="4">
</p>
<p>
<?php _e("Allowed Rating:", 'eg_trans_domain' ); ?> 
<select name="eg_rating">
<?php
	foreach ($eg_ratings as $rating) {
		$selected = ($rating == $eg_opt_rating) ? 'selected="selected"' : '';
		echo "\t<option value='$rating' $selected>$rating</option>\n";
	}
?>
</select>
</p>
<p>
<div style="float:right;margin-left:10px;width=<?php echo $eg_opt_size ?>"><img src="<?php echo $eg_opt_defaulturl; ?>" width="<?php echo $eg_opt_size; ?>" height="<?php echo $eg_opt_size; ?>" /></div>
<?php _e("Default image url:", 'eg_trans_domain' ); ?> 
<input type="text" name="eg_defaulturl" value="<?php echo $eg_opt_defaulturl; ?>" size="40">
</p>

<p>
<?php _e("Span style:", 'eg_trans_domain' ); ?> 
<input type="text" name="eg_style_span" value="<?php echo $eg_opt_style_span; ?>" size="40">
</p>

<p class="submit">
<input type="submit" name="Submit" value="<?php _e('Update Options', 'eg_trans_domain' ) ?>" />
</p>

</form>
</div>

<?php
 
}

?>
