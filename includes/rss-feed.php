<?php

// add thumbnail to rss feed
function add_post_featured_image_as_rss_item_enclosure() {
    if ( ! has_post_thumbnail() ) {
        return;
    }

    $thumbnail_id = get_post_thumbnail_id( $post->ID );
    $thumbnail_src = wp_get_attachment_image_src( $thumbnail_id, 'medium' )[0];

    printf(
        '<enclosure url="%s" type="%s" />',
        esc_url($thumbnail_src),
        esc_attr(get_post_mime_type( $thumbnail_id ))
    );
}
add_action( 'rss2_item', 'add_post_featured_image_as_rss_item_enclosure' );
