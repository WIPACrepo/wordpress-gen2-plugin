<?php
/**
 * Scan all php files in this dir and import them.
 **/

$dirpath = plugin_dir_path( __FILE__ );
$scanned_directory = array_diff( scandir( $dirpath ), array( '..', '.', 'index.php' ) );

foreach ( $scanned_directory as $file ) {
    $filepath = $dirpath . $file;
    if ( is_file( $filepath ) ) {
        require_once $filepath;
    }
}
