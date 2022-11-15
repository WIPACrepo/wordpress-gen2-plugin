<?php
/**
 * Register custom table style with lines.
 */
if ( function_exists( 'register_block_style' ) ) {
	function block_styles_register_table_lines() {
		/**
		 * Register stylesheet
		 */
        $uri = trailingslashit( get_stylesheet_directory_uri() ) . 'block-styles/table-lines.css';
		wp_register_style(
			'block-styles-table-lines-stylesheet',
			$uri,
			array(),
			'1.0'
		);

		/**
		 * Register block style
		 */
		register_block_style(
			'core/table',
			array(
				'name'         => 'lines',
				'label'        => 'Lined',
				'style_handle' => 'block-styles-table-lines-stylesheet',
			)
		);
	}

	add_action( 'init', 'block_styles_register_table_lines' );
}