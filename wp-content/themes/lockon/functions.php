<?php

function lockon_custom_styles() {
	$custom_css = "
		.site-title a {
			text-indent: -10000px;
			height: 1px;
			display: block;
		}
";
	wp_add_inline_style( 'ttfmake-main-style', $custom_css );
}
add_action( 'wp_enqueue_scripts', 'lockon_custom_styles', 21);
