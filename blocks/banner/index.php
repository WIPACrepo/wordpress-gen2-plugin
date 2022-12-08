<?php
/**
 * Server-side rendering of the `gen2/banner` block.
 *
 */

/**
 * Renders the `gen2/banner` block on the server.
 *
 * @param array    $attributes Block attributes.
 * @param string   $content    Block default content.
 * @param WP_Block $block      Block instance.
 * @return string Returns the featured image for the current post.
 */
function render_block_gen2_banner( $attributes, $content, $block ) {
    if ( ! isset( $block->context['postId'] ) ) {
        return '';
    }
    $post_ID = $block->context['postId'];

    $is_link        = isset( $attributes['isLink'] ) && $attributes['isLink'];
    $size_slug      = isset( $attributes['sizeSlug'] ) ? $attributes['sizeSlug'] : 'post-thumbnail';
    $post_title     = trim( strip_tags( get_the_title( $post_ID ) ) );
    $attr           = get_block_gen2_banner_border_attributes( $attributes );

    if ( $is_link ) {
        $attr['alt'] = $post_title;
    }

    if ( ! empty( $attributes['height'] ) ) {
        $extra_styles = "height:{$attributes['height']};";
        if ( ! empty( $attributes['scale'] ) ) {
            $extra_styles .= "object-fit:{$attributes['scale']};";
        }
        $attr['style'] = empty( $attr['style'] ) ? $extra_styles : $attr['style'] . $extra_styles;
    }

    $featured_image = get_the_post_thumbnail( $post_ID, $size_slug, $attr );
    if ( ! $featured_image ) {
        return '';
    }
    if ( $is_link ) {
        $link_target    = $attributes['linkTarget'];
        $rel            = ! empty( $attributes['rel'] ) ? 'rel="' . esc_attr( $attributes['rel'] ) . '"' : '';
        $featured_image = sprintf(
            '<a href="%1$s" target="%2$s" %3$s>%4$s</a>',
            get_the_permalink( $post_ID ),
            esc_attr( $link_target ),
            $rel,
            $featured_image
        );
    }

    $wrapper_attributes = empty( $attributes['width'] )
        ? get_block_wrapper_attributes()
        : get_block_wrapper_attributes( array( 'style' => "width:{$attributes['width']};" ) );

    return "<figure {$wrapper_attributes}>{$featured_image}</figure>";
}

/**
 * Selects the image based on the parent page or category.
 *
 * @param int   $post_ID    The currenty post.
 * @return string Returns the image for this page.
 */
function get_post_image( $post_ID ) {

}


/**
 * Generates class names and styles to apply the border support styles for
 * the Post Featured Image block.
 *
 * @param array $attributes The block attributes.
 * @return array The border-related classnames and styles for the block.
 */
function get_block_gen2_banner_border_attributes( $attributes ) {
    $border_styles = array();
    $sides         = array( 'top', 'right', 'bottom', 'left' );

    // Border radius.
    if ( isset( $attributes['style']['border']['radius'] ) ) {
        $border_styles['radius'] = $attributes['style']['border']['radius'];
    }

    // Border style.
    if ( isset( $attributes['style']['border']['style'] ) ) {
        $border_styles['style'] = $attributes['style']['border']['style'];
    }

    // Border width.
    if ( isset( $attributes['style']['border']['width'] ) ) {
        $border_styles['width'] = $attributes['style']['border']['width'];
    }

    // Border color.
    $preset_color           = array_key_exists( 'borderColor', $attributes ) ? "var:preset|color|{$attributes['borderColor']}" : null;
    $custom_color           = _wp_array_get( $attributes, array( 'style', 'border', 'color' ), null );
    $border_styles['color'] = $preset_color ? $preset_color : $custom_color;

    // Individual border styles e.g. top, left etc.
    foreach ( $sides as $side ) {
        $border                 = _wp_array_get( $attributes, array( 'style', 'border', $side ), null );
        $border_styles[ $side ] = array(
            'color' => isset( $border['color'] ) ? $border['color'] : null,
            'style' => isset( $border['style'] ) ? $border['style'] : null,
            'width' => isset( $border['width'] ) ? $border['width'] : null,
        );
    }

    $styles     = wp_style_engine_get_styles( array( 'border' => $border_styles ) );
    $attributes = array();
    if ( ! empty( $styles['classnames'] ) ) {
        $attributes['class'] = $styles['classnames'];
    }
    if ( ! empty( $styles['css'] ) ) {
        $attributes['style'] = $styles['css'];
    }
    return $attributes;
}

/**
 * Registers the `gen2/banner` block on the server.
 */
function register_block_gen2_banner() {
    register_block_type_from_metadata(
        plugin_dir_path( __FILE__ ),
        array(
            'render_callback' => 'render_block_gen2_banner',
        )
    );
}
add_action( 'init', 'register_block_gen2_banner' );
