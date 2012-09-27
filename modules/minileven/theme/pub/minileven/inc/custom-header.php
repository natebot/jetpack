<?php
/**
 * @package Minileven
 * @since Minileven 2.0
 */

/* This function grabs the custom header from the current theme so that Minileven can display it. */
function minileven_get_header_image() {
	$theme_slug = minileven_actual_current_theme();
	$mods = get_option( "theme_mods_{$theme_slug}" );

	if ( isset( $mods['header_image'] ) && 'remove-header' != $mods['header_image'] && 'random-default-image' != $mods['header_image'] && 'random-uploaded-image' != $mods['header_image'] )
		return $mods['header_image'];

	return false;
}

/* This function determines whether or not the user is displaying the header on the current theme */
function minileven_header_text_display() {
	$theme_slug = minileven_actual_current_theme();
	$mods = get_option( "theme_mods_{$theme_slug}" );

	if ( isset( $mods['header_textcolor'] ) )
		return $mods['header_textcolor'];

	return false;
}

/* This function determines how the header should be displayed. */
function minileven_header() {
	$header_image = minileven_get_header_image();
	$header_text = minileven_header_text_display();

	if  ( minileven_get_header_image() )
		list( $width ) = getimagesize( $header_image );
	?>
	<?php // Display small header image floated to the left of the site title
	if ( false !== $header_image && $width < 115) : ?>
		<div id="header-logo">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<img src="<?php echo $header_image; ?>" alt="" />
			</a>
		</div><!-- #header-logo -->
	<?php endif; // end check for header images that are less than 115px wide ?>

	<?php if ( 'blank' != $header_text ) : ?>
		<hgroup>
			<h1 id="site-title"><span><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></span></h1>
			<h2 id="site-description"><?php bloginfo( 'description' ); ?></h2>
		</hgroup>
	<?php endif; ?>

	<?php // Display standard size header images below the site title
		if ( false !== $header_image && $width >= 115) : ?>
			<div id="header-img">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<img src="<?php echo $header_image; ?>" alt="" />
				</a>
			</div><!-- #header-img -->
	<?php endif; // end check for header images that are at least 115px wide
}

/* This function displays the custom background image or color, and custom text color */
function minileven_show_background_and_header_color() {
	$background = minileven_get_background();
	$header_text = minileven_header_text_display();

	$style = '';

	if ( $background['color'] || $background['image'] ) :
		$style = $background['color'] ? "background-color: #$background[color];" : '';

		if ( $background['image'] ) :
			$image = " background-image: url('$background[image]');";

			if ( ! in_array( $background['repeat'], array( 'no-repeat', 'repeat-x', 'repeat-y', 'repeat' ) ) )
				$background['repeat'] = 'repeat';
			$repeat = " background-repeat: $background[repeat];";

			if ( ! in_array( $background['position'], array( 'center', 'right', 'left' ) ) )
				$background['position'] = 'left';
			$position = " background-position: top $background[position];";

			if ( ! in_array( $background['attachment'], array( 'fixed', 'scroll' ) ) )
				$background['attachment'] = 'scroll';
			$attachment = " background-attachment: $background[attachment];";

			$style .= $image . $repeat . $position . $attachment;
		endif;
	endif;
?>
	<style type="text/css">
		<?php if ( $style ) { ?>
			body {
				<?php echo trim( $style ); ?>
			}
		<?php } ?>
		#page {
			margin: 0.6em 0.6em 0.8em;
		}
		#site-generator {
			border: 0;
		}
	<?php if ( 'blank' != $header_text ) : ?>
		/* If The user has set a header text color, use that */
		#site-title,
		#site-title a {
			color: #<?php echo $header_text; ?>;
	<?php endif; ?>
		}
	</style>
<?php
}
add_action( 'wp_head', 'minileven_show_background_and_header_color' );