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
		<li>
            <a href="#main-content" class="screen-reader-shortcut"><?php _e( 'Skip to main content', 'fct1' ) ?></a>
        </li>
		<li>
            <a href="#footer" class="screen-reader-shortcut"><?php _e( 'Skip to footer', 'fct1' ) ?></a>
        </li>
	</ul>

    <input type="checkbox" id="nav-primary-toggle" aria-hidden="true">
	<header class="site-header">
		<div class="header-wrap">

            <?php if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) { the_custom_logo(); } else { ?>
            <a href="<?php echo home_url() ?>" class="custom-logo-link" rel="home">
                <img src="<?php echo get_stylesheet_directory_uri() . '/imgs/klinikerfahrungen-logo-4.png' ?>" alt="<?php echo get_bloginfo( 'name' ) ?>"/>
            </a>
            <?php } ?>

            <?php if ( has_nav_menu( 'main' ) ) : ?>
            <nav class="nav-primary" id="nav-primary" aria-label="Main menu">
                <label for="nav-primary-toggle" class="toggle-label"></label>
                <?php
                    wp_nav_menu( [
                        'theme_location'  => is_user_logged_in() ? 'logged' : 'main',
                        'menu_class'      => 'menu menu-primary',
                        'menu_id'         => 'menu-primary'
                    ] );
                ?>
            </nav>
            <?php endif ?>

		</div>
	</header>

	<main id="main-content">
