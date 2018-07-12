<?php

function amactive_script_enqueue() {
    wp_enqueue_style( 'style_base', get_template_directory_uri().'/css/amactive.css' , array(), 'all' );
    wp_enqueue_style( 'style_menu', get_template_directory_uri().'/css/menu.css' , array(), 'all' );
    wp_enqueue_style( 'style_catalogue', get_template_directory_uri().'/css/catalogue2.css' , array(), 'all' );
    wp_enqueue_style( 'style_boxoffers', get_template_directory_uri().'/css/box-offers.css' , array(), 'all' );

    wp_enqueue_script( 'js_base', get_template_directory_uri().'/js/amactive.js', array(), true );
}
add_action( 'wp_enqueue_scripts', 'amactive_script_enqueue' );

function amactive_theme_setup() {
    add_theme_support( 'menus' );

    register_nav_menu( 'primary', 'primary navigation' );
    register_nav_menu( 'footer', 'footer navigation' );
}
add_action( 'init', 'amactive_theme_setup' );

// WordPress 101 - Part 6: How to add Theme Features with add_theme_support
add_theme_support( 'custom-background' );
add_theme_support( 'custom-header' );
add_theme_support( 'post-thumbnails' );
add_theme_support( 'post-formats' );