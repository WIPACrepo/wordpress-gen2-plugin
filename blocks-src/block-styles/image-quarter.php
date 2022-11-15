<?php
/**
 * Register custom table style with lines.
 */
if ( function_exists( 'register_block_style' ) ) {
	function block_styles_register_image_quarter() {
		/**
		 * Register stylesheet
		 */
        $uri = trailingslashit( get_stylesheet_directory_uri() ) . 'block-styles/image-quarter.css';
		wp_register_style(
			'block-styles-image-quarter-stylesheet',
			$uri,
			array(),
			'1.0'
		);

		/**
		 * Register block style
		 */
		register_block_style(
			'core/image',
			array(
				'name'         => 'quarter',
				'label'        => '25% Width',
				'style_handle' => 'block-styles-image-quarter-stylesheet',
			)
		);
	}

	add_action( 'init', 'block_styles_register_image_quarter' );
}