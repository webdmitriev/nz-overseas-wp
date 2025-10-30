<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package webdmitriev
 */

$url = get_template_directory_uri();

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class('is-animation'); ?>>

	<div id="app">

		<div class="nz-background">
			<div class="line-horizontals">
				<span class="line-horizontal"></span>
				<span class="line-horizontal"></span>
				<span class="line-horizontal"></span>
				<span class="line-horizontal"></span>
				<span class="line-horizontal"></span>
			</div>
			<div class="line-verticals">
				<span class="line-vertical"></span>
				<span class="line-vertical"></span>
				<span class="line-vertical"></span>
				<span class="line-vertical"></span>
				<span class="line-vertical"></span>
				<span class="line-vertical"></span>
				<span class="line-vertical"></span>
				<span class="line-vertical"></span>
				<span class="line-vertical"></span>
				<span class="line-vertical"></span>
				<span class="line-vertical"></span>
				<span class="line-vertical"></span>
			</div>
		</div>

		<header class="header">
			<div class="container">
				<div class="line-wrap">
					<a href="<?php echo get_home_url( null, '/' ); ?>" class="header-logotype"><img src="<?= esc_url($url); ?>/assets/img/header/logotype.svg" alt="alto" /></a>

					<ul class="header-menu" style="display: none;">
						<li class="menu-item"><a href="#link">Services</a></li>
						<li class="menu-item"><a href="#link">About Us</a></li>
						<li class="menu-item"><a href="#link">Compliance</a></li>
						<li class="menu-item"><a href="#link">FAQ</a></li>
						<li class="menu-item"><a href="#link">Contact Us</a></li>
					</ul>

					<?php
						wp_nav_menu( [
							'theme_location'  => 'lang-menu',
							'menu'            => '',
							'container'       => '',
							'container_class' => '',
							'container_id'    => '',
							'menu_class'      => 'header-lang',
							'menu_id'         => '',
							'echo'            => true,
							'fallback_cb'     => 'wp_page_menu',
							'before'          => '',
							'after'           => '',
							'link_before'     => '',
							'link_after'      => '',
							'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
							'depth'           => 0,
							'walker'          => '',
						] );
					?>

					<button class="burger" style="display: none;"><span></span></button>
				</div>
			</div>
		</header>
