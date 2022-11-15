<?php
/**
 * Register custom table style with lines.
 */
if ( function_exists( 'register_block_style' ) ) {
	function block_styles_register_image_half() {
		/**
		 * Register stylesheet
		 */
        $uri = trailingslashit( get_stylesheet_directory_uri() ) . 'block-styles/image-half.css';
		wp_register_style(
			'block-styles-image-half-stylesheet',
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
				'name'         => 'half',
				'label'        => '50% Width',
				'style_handle' => 'block-styles-image-half-stylesheet',
			)
		);
	}

	add_action( 'init', 'block_styles_register_image_half' );
}
