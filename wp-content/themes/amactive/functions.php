<?php

function amactive_script_enqueue() {
    wp_enqueue_style( 'style_base', get_template_directory_uri().'/css/amactive.css' , array(), 'all' );
    wp_enqueue_style( 'style_menu', get_template_directory_uri().'/css/menu.css' , array(), 'all' );
    wp_enqueue_style( 'style_catalogue', get_template_directory_uri().'/css/catalogue2.css' , array(), 'all' );
    wp_enqueue_style( 'style_boxoffers', get_template_directory_uri().'/css/box-offers.css' , array(), 'all' );

    wp_enqueue_script( 'js_base', get_template_directory_uri().'/js/amactive.js', array(), true );
}
add_action( 'wp_enqueue_scripts', 'amactive_script_enqueue' );

?>