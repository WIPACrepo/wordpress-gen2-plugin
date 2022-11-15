<?php
/**
 * Scan all subdirs and import the index.php file in them.
 **/

$dirpath = plugin_dir_path( __FILE__ );
$scanned_directory = array_diff( scandir( $dirpath ), array( '..', '.', 'index.php' ) );

foreach ( $scanned_directory as $file ) {
    $filepath = $dirpath . trailingslashit($file) . 'index.php';
    if ( is_file( $filepath ) ) {
        require_once $filepath;
    }
}
