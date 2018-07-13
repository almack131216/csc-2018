<?php

/*
    ====================================
    Include scripts
    ====================================
*/
function amactive_script_enqueue() {
    wp_enqueue_style( 'style_core', get_template_directory_uri().'/style.css' , array(), 'all' );
    wp_enqueue_style( 'style_base', get_template_directory_uri().'/css/amactive.css' , array(), 'all' );
    wp_enqueue_style( 'style_menu', get_template_directory_uri().'/css/menu.css' , array(), 'all' );
    wp_enqueue_style( 'style_catalogue', get_template_directory_uri().'/css/catalogue2.css' , array(), 'all' );
    wp_enqueue_style( 'style_slideshow', get_template_directory_uri().'/css/slideshow2.css' , array(), 'all' );
    wp_enqueue_style( 'style_boxoffers', get_template_directory_uri().'/css/box-offers.css' , array(), 'all' );

    wp_enqueue_script( 'js_base', get_template_directory_uri().'/js/amactive.js', array(), true );
}
add_action( 'wp_enqueue_scripts', 'amactive_script_enqueue' );

/*
    ====================================
    Activate Menus
    ====================================
*/
function amactive_theme_setup() {
    add_theme_support( 'menus' );

    register_nav_menu( 'primary', 'primary navigation' );
    register_nav_menu( 'footer', 'footer navigation' );
}
add_action( 'init', 'amactive_theme_setup' );

/*
    ====================================
    Theme support function
    ====================================
*/
// WordPress 101 - Part 6: How to add Theme Features with add_theme_support
add_theme_support( 'custom-background' );
add_theme_support( 'custom-header' );
add_theme_support( 'post-thumbnails' );
add_theme_support( 'post-formats', array( 'aside','image','video' ) );

/*
    ====================================
    Header function
    ====================================
*/
function amactive_custom_header_setup() {
    $args = array(
        'default-image'      => get_template_directory_uri() . '/stat/logo.gif',
        'default-text-color' => '000',
        'width'              => 440,
        'height'             => 90,
        'flex-width'         => false,
        'flex-height'        => false
    );
    add_theme_support( 'custom-header', $args );
}
add_action( 'after_setup_theme', 'amactive_custom_header_setup' );

/*
    ====================================
    Sidebar function
    ====================================
*/
function amactive_widget_setup() {
    register_sidebar(
        array(
            'name'  => 'Sidebar',
            'id'    => 'sidebar-1',
            'class' => 'sidebar-main-nav-left',
            'description'   => 'Main sidebar navigation',
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'after_widget'  => '</aside>',
            'before_title'  => '<h1 class="widget-title">',
            'after_title'   => '</h1>'
        )
    );
}
add_action( 'widgets_init', 'amactive_widget_setup' );

/*
    ====================================
    Format price
    ====================================
*/
function amactive_my_custom_price_format($price = 0, $symbol = '£'){
    $pricestring = number_format($price,2);
    $pos = strpos($pricestring, "."); // retrieve position of dot by counting chars upto dot
    $len = strlen($pricestring);
    $pricestring_end = substr($pricestring, $pos, $len);
    
    //echo 'END='.$pricestring_end;
    if($pricestring_end == ".00"){
        $pricestring_stripped = substr($pricestring, 0, $pos);
        return '&pound;'.$pricestring_stripped;
    }else{
        if($pricestring<1){
            $pennies = str_split($pricestring,"2");
            return number_format($pennies[1],0).'p';
        }else{
            return '&pound;'.$pricestring;
        }		
    }
}
add_filter('amactive_print_formatted_price', 'amactive_my_custom_price_format');