<?php

// remove max image size limit
add_filter( 'big_image_size_threshold', '__return_false' );
