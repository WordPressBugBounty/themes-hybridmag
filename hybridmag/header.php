<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package HybridMag
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">

	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'hybridmag' ); ?></a>

	<?php do_action( 'hybridmag_before_header' ); ?>

	<?php do_action( 'hybridmag_header' ); ?>

	<?php do_action( 'hybridmag_after_header' ); ?>

	<div id="content" class="site-content">

		<?php do_action( 'hybridmag_inside_site_content_top' ); ?>
		
		<div class="content-area hm-container">

			<?php do_action( 'hybridmag_inside_container' ); ?>