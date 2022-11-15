<?php

// remove password-protected posts from all listings if you're not an admin
function wpb_password_post_filter( $where = '' ) {
    if ( ! is_single() && ! is_admin() ) {
        $where .= " AND post_password = ''";
    }
    return $where;
}
add_filter( 'posts_where', 'wpb_password_post_filter' );
