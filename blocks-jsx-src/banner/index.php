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

    $banner_image = get_block_gen2_banner_post_image( $post_ID );
    if ( ! $banner_image ) {
        $attr = get_block_gen2_banner_border_attributes( $attributes );
        $banner_image = get_the_post_thumbnail( $post_ID, 'full', $attr );
    }
    if ( ! $banner_image ) {
        return '';
    }

    $wrapper_attributes = get_block_wrapper_attributes();

    return "<figure {$wrapper_attributes}>{$banner_image}</figure>";
}

/**
 * Selects the image based on the parent page or category.
 *
 * @param int   $post_ID    The currenty post.
 * @return string Returns the image for this page.
 */
function get_block_gen2_banner_post_image( $post_ID ) {
    $page_categories = array(
        'About' => 'https://res.cloudinary.com/icecube/images/f_auto,q_auto/v1669753817/IceCube-Gen2/IceCube-Gen2_Web-Headers_5_About_A_2250x500/IceCube-Gen2_Web-Headers_5_About_A_2250x500.jpg',
        'Facility' => 'https://res.cloudinary.com/icecube/images/f_auto,q_auto/v1669836353/IceCube-Gen2/IceCube-Gen2_Web-Headers_4_Facility_A_2250x500-copy/IceCube-Gen2_Web-Headers_4_Facility_A_2250x500-copy.png',
        'Science' => 'https://res.cloudinary.com/icecube/images/f_auto,q_auto/v1669754392/IceCube-Gen2/IceCube-Gen2_Web-Headers_5_Science_2250x500_Science_2250x500/IceCube-Gen2_Web-Headers_5_Science_2250x500_Science_2250x500.jpg',
        'Collaboration' => 'https://res.cloudinary.com/icecube/images/f_auto,q_auto/v1669750829/IceCube-Gen2/IceCube-Gen2_Web-Headers_4_Collaboration_A_2250x500/IceCube-Gen2_Web-Headers_4_Collaboration_A_2250x500.jpg',
    );

    $image = '';
    $attrs = array(
        'class' => 'wp-post-image',
        'width' => '2250',
        'height' => '500',
    );

    $nav_items_raw = wp_get_nav_menu_items( 'Main' );
    $nav_items = array();
    foreach ( $nav_items_raw as $item ) {
        $nav_items[ $item->object_id ] = $item;
    }

    if ( array_key_exists( $post_ID, $nav_items ) ) {
        $parent_id = $nav_items[ $post_ID ]->menu_item_parent;
        if ( 0 == $parent_id) {
            $parent_id = $post_ID;
        } elseif ( 0 != $nav_items[ $parent_id ]->menu_item_parent ) {
            $parent_id = $nav_items[ $parent_id ]->menu_item_parent;
        }

        $parent_title = $nav_items[ $parent_id ]->title;
        if ( array_key_exists( $parent_title, $page_categories ) ) {
            $image = $page_categories[ $parent_title ];
            $attrs['class'] .= ' ' . sanitize_key( $parent_title );
        }
    }

    if ( ! $image ) {
        return '';
    }

    $normalized_attributes = array();
    foreach ( $attrs as $key => $value ) {
        $normalized_attributes[] = $key . '="' . esc_attr( $value ) . '"';
    }
    $attr_str = implode( ' ', $normalized_attributes );

    $image_html = "<img {$attr_str} src='{$image}' />";
    return $image_html;
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
    if ( ! function_exists( 'register_block_type' ) ) {
        // Block editor is not available.
        return;
    }
    $ret = register_block_type(
        plugin_dir_path( __FILE__ ),
        array(
            'render_callback' => 'render_block_gen2_banner',
        )
    );
}
add_action( 'init', 'register_block_gen2_banner' );
