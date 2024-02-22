<?php


// update prices
include_once get_stylesheet_directory() . '/inc/update-prices/admin.php';

// update stock
include_once get_stylesheet_directory() . '/inc/update-stock/admin.php';

// Remove the original action
remove_action('neve_after_slot_component', ['HFG\Core\Builder\Footer', 'add_footer_component'], 10, 3);

// general functions
include_once get_stylesheet_directory() . '/inc/funciones_generales.php';
