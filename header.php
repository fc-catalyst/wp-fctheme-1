<!doctype html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=yes">
		<meta name="format-detection" content="telephone=no">
		<?php wp_head(); ?>
	</head>

<body <?php body_class(); ?> id="document-top">

	<ul class="skip-links">
		<li><a href="#main-content" class="screen-reader-shortcut">Skip to main content</a></li>
		<li><a href="#footer" class="screen-reader-shortcut">Skip to footer</a></li>
	</ul>

	<header class="site-header">
		<div class="header-wrap">

            <a href="<?php echo home_url() ?>" class="site-logo" title="Startseite">
                <img src="<?php echo get_stylesheet_directory_uri() . '/imgs/klinikerfahrungen-logo.png' ?>" alt="Klinikerfahrungen Logo"/>
            </a>

            <?php if ( has_nav_menu( 'main' ) ) : ?>
            <nav class="nav-primary" id="nav-primary" aria-label="Main menu">
                <input type="checkbox" id="nav-primary-toggle" aria-hidden="true">
                <label for="nav-primary-toggle"></label>
                <?php
                    wp_nav_menu( [
                        'theme_location'  => 'main',
                        'menu_class'      => 'menu menu-primary',
                        'menu_id'         => 'menu-primary'
                    ] );
                ?>
            </nav>
            <?php endif ?>

		</div>
	</header>

	<main id="main-content">
