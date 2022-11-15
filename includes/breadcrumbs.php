<?php

function my_bcn_template_tag( $replacements, $type, $id ) {
    $breadcrumb = the_field( 'breadcrumb' );
    if ( $breadcrumb == '' ) {
        $breadcrumb = isset( $post->post_title ) ? $post->post_title : '';
    }

    $replacements['%breadcrumb%'] = $breadcrumb;
    return $replacements;
}
add_filter( 'bcn_template_tags', 'my_bcn_template_tag', 3, 1 );
