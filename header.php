<!DOCTYPE html>
<html <?php language_attributes() ?>>
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0 shrink-to-fit=no">
	<title><?php wp_title('') ?></title>

	<!-- ::[WPH]:: -->
	<?php wp_head() ?>
	<!-- ::[/WPH]:: -->
</head>
<body <?php body_class() ?>>

	<pre>[HEADER]</pre>
	<pre>	[MENU]</pre>
	<div style="margin-left: 8em;">
		<?php wp_nav_menu( array(
			'theme_location'  => 'main_1',
			'menu'            => 'main_1',
			'container'       => 'div',
			'container_class' => 'menu-{menu-slug}-container',
			'container_id'    => '',
			'menu_class'      => 'menu',
			'menu_id'         => '',
			'echo'            => true,
			'fallback_cb'     => 'wp_page_menu',
			'before'          => '',
			'after'           => '',
			'link_before'     => '',
			'link_after'      => '',
			'items_wrap'      => '<ul id = "%1$s" class = "%2$s">%3$s</ul>',
			'depth'           => 0,
			'walker'          => '',
		) ); ?>
	</div>

