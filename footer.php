
	<pre>[FOOTER]</pre>

	<pre>	[MENU]</pre>
	<div style="margin-left: 8em;">
		<?php wp_nav_menu( array(
			'theme_location'  => 'footer_1',
			'menu'            => 'footer_1',
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


	<!-- ::[WPF]:: -->
	<?php wp_footer() ?>
	<!-- ::[/WPF]:: -->

</body>
</html>
