<?php

function my_bcn_template_tag($replacements, $type, $id)
{
    $replacements['%breadcrumb%'] = the_field('breadcrumb');
    return $replacements;
}
add_filter('bcn_template_tags', 'my_bcn_template_tag', 3, 1);
