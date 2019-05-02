<?php

function amactive_is_localhost() {    
    if($_SERVER['HTTP_HOST']=="localhost:8080") return true;
    return false;   
}
/*
    ====================================
    Include scripts
    ====================================
*/
/*
    ====================================
    Include BOOTSTRAP scripts
    ====================================
*/
function amactive_add_bootstrap_js() {
    //REF: https://startbootstrap.com/template-overviews/blog-home/
    //REF: https://code.tutsplus.com/tutorials/how-to-integrate-a-bootstrap-navbar-into-a-wordpress-theme--wp-33410
    wp_register_script('jquery.bootstrap.min', get_template_directory_uri() . '/bootstrap/dist/js/bootstrap.min.js', 'jquery');
    wp_enqueue_script('jquery.bootstrap.min');
}
add_action( 'init', 'amactive_add_bootstrap_js' );

function amactive_add_bootstrap_css() {
    wp_register_style( 'bootstrap.min', get_template_directory_uri() . '/bootstrap/dist/css/bootstrap.min.css' );
    wp_enqueue_style( 'bootstrap.min' );
}
add_action( 'wp_enqueue_scripts', 'amactive_add_bootstrap_css' );

function amactive_script_enqueue() {
    global $offline;
    // wp_enqueue_style( 'style_core', get_template_directory_uri().'/style.css' , array(), 'all' );

    // wp_enqueue_style( 'style_base', get_template_directory_uri().'/css/4-col-portfolio.css' , array(), 'all' );
    
    // wp_enqueue_style( 'style_base', get_template_directory_uri().'/css/amactive.css' , array(), 'all' );
    // wp_enqueue_style( 'style_menu', get_template_directory_uri().'/css/menu.css' , array(), 'all' );
    // wp_enqueue_style( 'style_catalogue', get_template_directory_uri().'/css/catalogue2.css' , array(), 'all' );
    // wp_enqueue_style( 'style_slideshow', get_template_directory_uri().'/css/slideshow2.css' , array(), 'all' );
    // wp_enqueue_style( 'style_boxoffers', get_template_directory_uri().'/css/box-offers.css' , array(), 'all' );
    // wp_enqueue_style( 'style_featurebox', get_template_directory_uri().'/css/featurebox2.css' , array(), 'all' );

    // wp_enqueue_script( 'js_base', get_template_directory_uri().'/js/amactive.js', array(), true );
    wp_enqueue_script( 'js_base', get_template_directory_uri().'/js/amactive.js', array('jquery'), false, true );
    

    //REF: https://www.sitepoint.com/using-font-awesome-with-wordpress/
    if($offline){
        wp_enqueue_style('font-awesome', get_template_directory_uri().'/offline/font-awesome.min.css');        
    }else{
        wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    }
}
add_action( 'wp_enqueue_scripts', 'amactive_script_enqueue' );

// Register Custom Navigation Walker
require_once get_template_directory() . '/class-wp-bootstrap-navwalker.php';

// register_nav_menus( array(
// 	'primary_menu' => __( 'Primary Menu', 'amactive' ),
//     'footer_menu_1' => __( 'Footer Menu 1', 'amactive' ),
// ));

// function prefix_modify_nav_menu_args( $args ) {
// 	return array_merge( $args, array(
// 		'walker' => WP_Bootstrap_Navwalker(),
// 	) );
// }
// add_filter( 'wp_nav_menu_args', 'prefix_modify_nav_menu_args' );

/*
    ====================================
    Activate Menus
    ====================================
*/
function amactive_theme_setup() {
    add_theme_support( 'menus' );

    register_nav_menu( 'primary_menu', 'primary menu' );
    register_nav_menu( 'footer_menu_1', 'footer menu 1' );
    register_nav_menu( 'footer_menu_2', 'footer menu 2' );
    register_nav_menu( 'social_menu', 'social menu' );
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
        array (
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
    // register_sidebar(
    //     array (
    //         'name' => 'Sidebar Cars For Sale',
    //         'id' => 'custom-side-bar',
    //         'description' => 'Sidebar Cars For Sale',
    //         'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    //         'after_widget'  => '</aside>',
    //         'before_title' => '<h3 class="widget-title">',
    //         'after_title' => '</h3>'
    //     )
    // );
    // register_sidebar(
    //     array (
    //         'name' => 'Sidebar Cars Sold',
    //         'id' => 'custom-side-bar-sold',
    //         'description' => 'Sidebar Cars Sold',
    //         'before_widget' => '<aside id="%1$s" class="widget %2$s">',
    //         'after_widget'  => '</aside>',
    //         'before_title' => '<h3 class="widget-title">',
    //         'after_title' => '</h3>'
    //     )
    // );
}
add_action( 'widgets_init', 'amactive_widget_setup' );

// function my_custom_sidebar() {
//     register_sidebar(
//         array (
//             'name' => __( 'Custom', 'your-theme-domain' ),
//             'id' => 'custom-side-bar',
//             'description' => __( 'Custom Sidebar', 'your-theme-domain' ),
//             'before_widget' => '<div class="widget-content">',
//             'after_widget' => "</div>",
//             'before_title' => '<h3 class="widget-title">',
//             'after_title' => '</h3>',
//         )
//     );
// }
// add_action( 'widgets_init', 'my_custom_sidebar' );

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


function my_custom_post_status(){

    register_post_status( 'assigned', array(
		'label'                     => _x( 'Assigned', 'post status label', 'plugin-domain' ),
		'public'                    => true,
		'label_count'               => _n_noop( 'Assigned <span class="count">(%s)</span>', 'Assigned <span class="count">(%s)</span>', 'plugin-domain' ),
		'show_in_admin_all_list'    => true,
		'show_in_admin_status_list' => true,
		'show_in_metabox_dropdown'  => true,
		'show_in_inline_dropdown'   => true,
		'dashicon'                  => 'dashicons-businessman',
	) );

}

add_action( 'init', 'my_custom_post_status' );


// https://wordpress.stackexchange.com/questions/207923/count-posts-in-category-including-child-categories
/**
 * Funtion to get post count from given term or terms and its/their children
 *
 * @param (string) $taxonomy
 * @param (int|array|string) $term Single integer value, or array of integers or "all"
 * @param (array) $args Array of arguments to pass to WP_Query
 * @return $q->found_posts
 *
 */
function get_term_post_count( $taxonomy = 'category', $term = '', $args = [] )
{
    // Lets first validate and sanitize our parameters, on failure, just return false
    if ( !$term )
        return false;

    if ( $term !== 'all' ) {
        if ( !is_array( $term ) ) {
            $term = filter_var(       $term, FILTER_VALIDATE_INT );
        } else {
            $term = filter_var_array( $term, FILTER_VALIDATE_INT );
        }
    }

    if ( $taxonomy !== 'category' ) {
        $taxonomy = filter_var( $taxonomy, FILTER_SANITIZE_STRING );
        if ( !taxonomy_exists( $taxonomy ) )
            return false;
    }

    if ( $args ) {
        if ( !is_array ) 
            return false;
    }

    // Now that we have come this far, lets continue and wrap it up
    // Set our default args
    $defaults = [
        'posts_per_page' => 1,
        'fields'         => 'ids'
    ];

    if ( $term !== 'all' ) {
        $defaults['tax_query'] = [
            [
                'taxonomy' => $taxonomy,
                'terms'    => $term
            ]
        ];
    }
    $combined_args = wp_parse_args( $args, $defaults );
    $q = new WP_Query( $combined_args );

    // Return the post count
    return $q->found_posts;
}



/* header navbar */
/* Theme setup */
// add_action( 'after_setup_theme', 'wpt_setup' );
// if ( ! function_exists( 'wpt_setup' ) ):
//     function wpt_setup() {  
//         register_nav_menu( 'primary_menu', __( 'Primary navigation', 'wptuts' ) );
//     }
// endif;

/*
    ====================================
    tmp hack css
    ====================================
*/
function amactive_hack_css() {    
    if( amactive_is_localhost() ){
        wp_enqueue_style( 'style_theme', get_template_directory_uri().'/style.css?ver='. rand(111,999), array(), 'all' );
    }else{
        wp_enqueue_style( 'style_theme', get_template_directory_uri().'/style-190416.css', array(), 'all' );
    }    
}
add_action( 'wp_enqueue_scripts', 'amactive_hack_css' );

function amactive_debug($getStr, $print = 'echo') {
    if(!$print || $print=='echo'){
        // echo '<br>??? '.$getStr;
    }
}

/*
post title
*/
function amactive_custom_title( $getTitle = '', $getPostId = 0 ) {
    global $post;

    // print_r($post);
    $postId = $getPostId ? $getPostId : $post->ID;
    $title = $getTitle ? $getTitle : $post->post_title;
    $year = get_post_meta( $postId, 'csc_car_year', true);
    // echo $year.' + '.$title;
    if( $year && amactive_post_is_classified($postId) ) {
        $title = $year.' '.$title;
    }
    return $title;
}
// add_action( 'amactive_set_title', 'amactive_custom_title' );

/*
post title
*/
function amactive_post_url( $getName = '', $getPostId = 0 ) {
    return str_ireplace('/photos','',esc_url( get_permalink() ));
}

// define function
function array_search_multidim($array, $column, $key){
    return (array_search($key, array_column($array, $column)));
}

// get_category with excludes
function exclude_post_categories($excl, $spacer=', ') {
  $categories = get_the_category($post->ID);
  if (!empty($categories)) {
    $exclude = $excl;
    // $exclude = explode(",", $exclude);
    $thecount = count(get_the_category()) - count($exclude);
    foreach ($categories as $cat) {
      $html = '';
      if (!in_array($cat->cat_ID, $exclude)) {
        $html .= '<a href="' . get_category_link($cat->cat_ID) . '" ';
        $html .= 'title="' . $cat->cat_name . '">' . $cat->cat_name . '</a>';
        if ($thecount > 1) {
          $html .= $spacer;
        }
        $thecount--;
        return $html;
      }
    }
  }
}

// print categry title (dividing title + hyperlink)
function amactive_return_title_splitter( $getArr ) {

    $title = 'Title Goes Here';
    $titleLink = '#';
    $seeAll = 'See All';
    $seeAllLink = '#';
    $wrapClass = '';

    if ($getArr['cat']) {
        $cat = get_category($getArr['cat']);

        $title = $cat->name;
        $titleLink = 'category/'.$cat->slug;
        $seeAllLink = $titleLink;
    }

    if ($getArr['class']) {
        $wrapClass = ' '.$getArr['class'];
    }

    $ts = '';
    $ts .= '<div class="title_splitter_wrap'.$wrapClass.'">';
    $ts .= '<div class="title">';
    $ts .= '<a href="'.$titleLink.'" class="a-title">';
    $ts .= '<h2>'.$title.'</h2>';
    $ts .= '</a>';
    $ts .= '</div>';
    $ts .= '<div class="see-all"><a href="'.$seeAllLink.'" class="a-all">'.$seeAll.'</a></div>';
    $ts .= '</div>';
    return $ts;
}

function get_first_paragraph(){
    global $post;
    $str = wpautop( get_the_content() );
    $str = substr( $str, 0, strpos( $str, '</p>' ) + 4 );
    $str = strip_tags($str, '<a><strong><em>');
    $str .= '&nbsp;<a href="'.esc_url( get_permalink() ).'" title="Link to '.get_the_title().'">';
    $str .= '[Read&nbsp;More]';
    $str .= '</a>';
    return '<p class="hidden-xs-down">' . $str . '</p>';
    // global $post, $posts;
    // $post_content = $post->post_content;
    // $post_content = apply_filters('the_content', $post_content);
    // $post_content = str_replace('</p>', '', $post_content);
    // $paras = explode('<p>', $post_content);
    // array_shift($paras);

    // return $paras[0];
}

function amactive_item_print_price( $getPostId ) {
    $itemPrice = '';

    if ( has_category(DV_category_IsSold_id) ):
        $itemPrice = '<span class="fmt-sold">SOLD</span>';
    else:
        $price = get_post_meta( $getPostId, 'csc_car_price', true);
        $priceDetails = get_post_meta( $getPostId, 'csc_car_price_details', true);
        if($price):
            $itemPrice = '<span class="fmt-price">'.amactive_my_custom_price_format($price).'</span>';
        endif;
        if($priceDetails):
            $itemPrice = '<span class="fmt-price detail">&pound;&nbsp;'.$priceDetails.'</span>';
        endif;
    endif;

    return $itemPrice;
}

/*
    ====================================
    PAGINATION
    ====================================
*/
function amactive_pagination($pages = '', $range = 4){

    $myPagination = '';
    $showitems = ($range * 2)+1;
 
    global $paged;
    if(empty($paged)) $paged = 1;
 
    if($pages == '')
    {
        global $wp_query;
        $pages = $wp_query->max_num_pages;
        if(!$pages)
        {
            $pages = 1;
        }
    }
 
    if(1 != $pages)
    {
        $myPagination .= '<div class="pagination-wrap">';
        // $myPagination .= '<span>Page '.$paged.' of '.$pages.'</span>';
        $myPagination .= '<ul class="ul-pagination">';
        if($paged > 2 && $paged > $range+1 && $showitems < $pages) $myPagination .= '<li class="li-first"><a href="'.get_pagenum_link(1).'">&laquo;</a></li>';
        if($paged > 1 && $showitems < $pages) $myPagination .= '<li class="li-prev"><a href="'.get_pagenum_link($paged - 1).'">&lsaquo;</a></li>';
 
        
        for ($i=1; $i <= $pages; $i++)
        {
            if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
            {
                $myPagination .= ($paged == $i)? '<li class="li-num"><span class="current">'.$i.'</span></li>' : '<li class="li-num"><a href="'.get_pagenum_link($i).'" class="inactive">'.$i.'</a></li>';
            }
        }
        
 
        if ($paged < $pages && $showitems < $pages) $myPagination .= '<li class="li-next"><a href="'.get_pagenum_link($paged + 1).'">&rsaquo;</a></li>';
        if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) $myPagination .= '<li class="li-last"><a href="'.get_pagenum_link($pages).'">&raquo;</a></li>';
        $myPagination .= '</ul>';
        $myPagination .= "</div>\n";
    }

    return $myPagination;
}

function amactive_posts_page_is_classified( $getCategoryId = 0 ){
    // if ( !$getCategoryId ) $getCategoryId = $GLOBALS['postPageCategoryId'];
    // if ( $getCategoryId == (DV_category_IsForSale_id || DV_category_IsSold_id) ) return true;
    if ( $getCategoryId == DV_category_IsForSale_id || $getCategoryId == DV_category_IsSold_id ) return true;
    return false;
}

function amactive_post_is_classified( $getPostId = 0 ){
    global $post;
    if ( !$getPostId ) $getPostId = $post->ID;
    if ( $getPostId && in_category( array(DV_category_IsForSale_id, DV_category_IsSold_id), $getPostId ) ) return true;
    return false;
}

function amactive_post_is_classified_sold( $getPostId = 0 ){
    global $post;
    if ( !$getPostId ) $getPostId = $post->ID;
    if ( $getPostId && in_category( array(DV_category_IsSold_id), $getPostId ) ) return true;
    return false;
}

function amactive_post_is_news( $getPostId = 0 ){
    global $post;
    if ( !$getPostId ) $getPostId = $post->ID;
    if ( $getPostId && in_category( array(DV_category_News_id, DV_category_Press_id), $getPostId ) ) return true;
    return false;
}

// REF: http://www.wprecipes.com/wordpress-trick-get-category-slug-using-category-id/
function get_cat_slug($cat_id) {
	$cat_id = (int) $cat_id;
	$category = &get_category($cat_id);
	return $category->slug;
}

// REF: https://www.isitwp.com/breadcrumbs-without-plugin/
function amactive_breadcrumb( ) {
    global $post;

    $myCrumbCount = 1;

    if( $GLOBALS['postPageCategorySlug'] ){
        $myCrumbCount++;
        $categoryCrumb = '<li class="li-category-'.$GLOBALS['postPageCategoryId'].'">';
        $categoryCrumb .= '<a href="'.get_category_link($GLOBALS['postPageCategoryId']).'">';
        $categoryCrumb .= '<span>'.$GLOBALS['postPageCategoryName'].'</span>';
        $categoryCrumb .= '</a>';
        $categoryCrumb .= '</li>'."\r\n";
    }

    if( amactive_posts_page_is_classified($GLOBALS['postPageCategoryId']) && $GLOBALS['sidebarSubCategoryJumpSelect'] ){
        $myCrumbCount++;
        $subcategoryCrumb = '<li class="li-jump-menu-wrap">';
        $subcategoryCrumb .= $GLOBALS['sidebarSubCategoryJumpSelect'];
        $subcategoryCrumb .= '</li>'."\r\n";
    } else {
        if( $GLOBALS['postPageSubCategorySlug'] ){
            $myCrumbCount++;
            $subcategoryCrumb = '<li>x';
            $subcategoryCrumb .= '<a href="'.get_category_link($GLOBALS['postPageCategoryId']).$GLOBALS['postPageSubCategorySlug'].'">'.$GLOBALS['postPageSubCategoryName'].'</a>';
            $subcategoryCrumb .= '</li>'."\r\n";
        }
    }

    if ( is_single() || is_page() ) {
        $myCrumbCount++;
        $pageCrumb = '<li>';
        $pageCrumb .= get_the_title();
        $pageCrumb .= '</li>';
    }
    
    // elseif (is_tag()) {single_tag_title();}
    // elseif (is_day()) {$myCrumbs .= "<li>Archive for "; the_time('F jS, Y'); $myCrumbs .= '</li>';}
    // elseif (is_month()) {$myCrumbs .= "<li>Archive for "; the_time('F, Y'); $myCrumbs .= '</li>';}
    // elseif (is_year()) {$myCrumbs .= "<li>Archive for "; the_time('Y'); $myCrumbs .= '</li>';}
    // elseif (is_author()) {$myCrumbs .= "<li>Author Archive"; $myCrumbs .= '</li>';}
    // elseif (isset($_GET['paged']) && !empty($_GET['paged'])) {$myCrumbs .= "<li>Blog Archives"; $myCrumbs .= '</li>';}
    if (is_search()) {
        $myCrumbCount++;
        $searchCrumb = '<li>Search Results for "'.get_search_query().'"</li>';
    }

    if (is_tag()) {
        $myCrumbCount++;
        $searchCrumb = '<li>Tag Results for "'.single_tag_title( '', false ).'"</li>';
    }

    $myCrumbs = '';
    $myCrumbs .= '<div class="row row-breadcrumb">';
    $myCrumbs .= '<div class="col-xs-12 col-post-breadcrumb">';
            
    $myCrumbs .= '<div class="crumbs-wrap">';
    $myCrumbs .= '<ul class="ul-breadcrumb has-'.$myCrumbCount.'-crumbs">';
    $myCrumbs .= '<li class="home"><a href="'.get_option('home').'"><i class="fa fa-home"></i><span>Home</span></a></li>';

    if( $categoryCrumb ) $myCrumbs .= $categoryCrumb;
    if( $subcategoryCrumb ) $myCrumbs .= $subcategoryCrumb;
    if( $pageCrumb ) $myCrumbs .= $pageCrumb;
    if( $searchCrumb ) $myCrumbs .= $searchCrumb;
    
    $myCrumbs .= '</ul>';
    $myCrumbs .= '</div>';
    /* (END) crumbs-wrap */

    $myCrumbs .= '</div>'."\r\n";// col
    $myCrumbs .= '</div>'."\r\n";// row

    return $myCrumbs;
	
}

function amactive_set_category_globals( $category_ids, $categories ) {


    // If post is News...
    if(in_array(DV_category_News_id, $category_ids)){
        $category = get_category(DV_category_News_id);
        array_push($GLOBALS['pageBodyClass'], 'classic-cars-for-sale');
        $category_ids = array_diff($category_ids, array(DV_category_News_id));
        amactive_debug('CAT: DV_category_News_id - '.$category->term_id.' = '.amactive_posts_page_is_classified($category->term_id));
        $GLOBALS['postPageCategoryId'] = $category->term_id;
        $GLOBALS['postPageCategoryName'] = $category->name;
        $GLOBALS['postPageCategorySlug'] = $category->slug;
    }


    // If post is for sale...
    if(in_array(DV_category_IsForSale_id, $category_ids)){
        $category = get_category(DV_category_IsForSale_id);
        array_push($GLOBALS['pageBodyClass'], 'classic-cars-for-sale');
        $category_ids = array_diff($category_ids, array(DV_category_IsForSale_id));
        amactive_debug('CAT: DV_category_IsForSale_id');
        $GLOBALS['postPageCategoryId'] = $category->term_id;
        $GLOBALS['postPageCategoryName'] = $category->name;
        $GLOBALS['postPageCategorySlug'] = $category->slug;
    }

    // if post is sold...
    if(in_array(DV_category_IsSold_id, $category_ids)){
        $category = get_category(DV_category_IsSold_id);
        $GLOBALS['pageBodyClass'] = array_diff($GLOBALS['pageBodyClass'], array('classic-cars-for-sale') );
        array_push($GLOBALS['pageBodyClass'], DV_category_IsSold_slug);
        $category_ids = array_diff($category_ids, array(DV_category_IsSold_id));
        amactive_debug('CAT: --> [switched to] --> DV_category_IsSold_id');
        $GLOBALS['postPageCategoryId'] = $category->term_id;
        $GLOBALS['postPageCategoryName'] = $category->name;
        $GLOBALS['postPageCategorySlug'] = $category->slug;
    }

    // if post has subcategory...
    $GLOBALS['showProductCats'] = false;
    if(amactive_posts_page_is_classified($GLOBALS['postPageCategoryId'])){
        foreach($categories as $category) {
            if($GLOBALS['postPageCategoryId'] && ($category->term_id != DV_category_IsForSale_id && $category->term_id != DV_category_IsSold_id)) {
                $GLOBALS['showProductCats'] = true;
                $GLOBALS['postPageSubCategoryId'] = $category->term_id;
                $GLOBALS['postPageSubCategoryName'] = $category->name;
                $GLOBALS['postPageSubCategorySlug'] = $category->slug;
                break;
            }
        } 
    }
    
}
/* (END) amactive_set_category_globals */

function show_more( $atts, $content = null ) {
	$a = shortcode_atts( array(
	    'text' => 'Show more',
	    'text_more' => 'Show more',
	    'text_less' => 'Show less',
	    'style' => 'normal',
	    'section' => 'no-specific-section'
	), $atts );
	// return '<a href="#" onclick="toggleMore(event)" class="show-more-handle show-more-handle-' . $a['style'] . ' ' . $a['section'] . '" data-more="'. $a['text_more'] .'" data-less="'. $a['text_less'] .'">' . $a['text_more'] . '</a>' . '<div class="expandable">' . do_shortcode($content) . '</div>';
	return '<a href="#" onclick="toggleMore(event)" class="show-more-handle show-more-handle-' . $a['style'] . ' ' . $a['section'] . '" data-more="'. $a['text_more'] .'" data-less="'. $a['text_less'] .'">' . $a['text_more'] . '</a>' . '<div class="expandable">' . do_shortcode($content) . '</div>';
}
add_shortcode( 'show_more', 'show_more' );


function amactive_widget_get_directions($content = null)
{
    // do something to $content 
    // run shortcode parser recursively
    // $contentBuild = '<div class="circle-container-wrap">';
    // if($atts['title']) $contentBuild .= '<h2>'.$atts['title'].'</h2>';
    // $contentBuild .= '<ul class="circle-container">'.do_shortcode($content).'</ul></div>';

    $contentBuild = '';
    $contentBuild .= '<label for="saddr">Enter your town / postcode and hit GO!</label><br>';
    $contentBuild .= '<form action="http://maps.google.co.uk/maps" method="get" target="_blank">';
    $contentBuild .= '<div class="input-group mb-3">';
        
            $contentBuild .= '<input type="hidden" name="daddr" value="YO17 8JB">';
            $contentBuild .= '<input type="hidden" name="hl" value="en">';
            
            $contentBuild .= '<input type="text" class="form-control" name="saddr" id="saddr" placeholder="Town / Postcode" aria-label="Town / Postcode" aria-describedby="Get Directions">';
            $contentBuild .= '<div class="input-group-append">';
                $contentBuild .= '<button class="btn btn-outline-secondary" type="submit">GO!</button>';
            $contentBuild .= '</div>';
        
    $contentBuild .= '</div>';
    $contentBuild .= '</form>';
    $contentBuildXXX = '<input type="text"  value=""><input type="submit" value="GO!" class="gmapGo"></form></div>';

    return $contentBuild;
}
add_shortcode( 'widget_get_directions', 'amactive_widget_get_directions' );

function btn_print_img( $atts ) {
	$a = shortcode_atts( array(
	    'btn-text' => 'Print Photo',
        'img-title' => 'Post Photo',
        'img-src' => null,
        'img-id' => null,
	    'style' => 'normal'
	), $atts );

	return '<a title="'.$a['img-title'].'" onclick="printme(\''.$a['img-title'].'\',\''.$a['img-id'].'\')" class="'.$a['style'].'">'.$a['btn-text'].'</a>';
}
add_shortcode( 'btn_print_img', 'btn_print_img' );

function btn_download_img( $atts ) {
	$a = shortcode_atts( array(
	    'btn-text' => 'Download Photo',
	    'img-src' => null,
	    'img-title' => 'Download this image',
	    'style' => ''
	), $atts );
	// return '<a href="#" onclick="toggleMore(event)" class="show-more-handle show-more-handle-' . $a['style'] . ' ' . $a['section'] . '" data-more="'. $a['text_more'] .'" data-less="'. $a['text_less'] .'">' . $a['text_more'] . '</a>' . '<div class="expandable">' . do_shortcode($content) . '</div>';
    //$ulLinks .= '<li>'.do_shortcode('[btn_download_img img-title="'.get_the_title().'" img-src="'.$attachments->src( 'full' ).'" btn-text="Download Photo ZZZ"]').'</li>';
	$btnDownload = do_shortcode('[easy_media_download url="'.$a['img-src'].'" text="'.$a['btn-text'].'" title="'.$a['btn-text'].'" class="'.$a['style'].'" force_dl="1" rel="nofollow"]');

    return $btnDownload;
}
add_shortcode( 'btn_download_img', 'btn_download_img' );

function attachment_options( $atts, $content = null ) {
	$a = shortcode_atts( array(
        'img-id' => null,
	    'btn-text' => null,
	    'img-src' => null,
	    'img-title' => null,
	    'style' => ''
	), $atts );
	// return '<a href="#" onclick="toggleMore(event)" class="show-more-handle show-more-handle-' . $a['style'] . ' ' . $a['section'] . '" data-more="'. $a['text_more'] .'" data-less="'. $a['text_less'] .'">' . $a['text_more'] . '</a>' . '<div class="expandable">' . do_shortcode($content) . '</div>';
    //$ulLinks .= '<li>'.do_shortcode('[btn_download_img img-title="'.get_the_title().'" img-src="'.$attachments->src( 'full' ).'" btn-text="Download Photo ZZZ"]').'</li>';
    $btnPrint = do_shortcode('[btn_print_img img-title="'.$a['img-title'].'" img-id="'.$a['img-id'].'" img-src="'.$a['img-src'].'"]');
	$btnDownload = do_shortcode('[btn_download_img img-title="'.$a['img-title'].'" img-src="'.$a['img-src'].'"]');

    $imgTitle = $a['img-title'] ? $a['img-title'] : get_the_title();

    $ulLinks = '<ul class="ul-photo-btns">';
    $ulLinks .= '<li class="li-print">'.$btnPrint.'</li>';
    $ulLinks .= '<li class="li-download">'.$btnDownload.'</li>';                    
    $ulLinks .= '</ul>';
    return $imgTitle .$ulLinks;
}
add_shortcode( 'attachment_options', 'attachment_options' );


//////////////////////////// CheckUrlFor
/**
* https://www.intechgrity.com/correct-way-get-url-parameter-values-wordpress/#
* Gets the request parameter.
*
* @param      string  $key      The query parameter
* @param      string  $default  The default value to return if not found
*
* @return     string  The request parameter.
*/
function get_request_parameter( $key ) {
    // global $wp_query;
    
    // var_dump($wp_query->query_vars);
    // echo $_SERVER['REQUEST_URI'].$key;
    // If not request set
    if(strpos($_SERVER['REQUEST_URI'], $key) !== false){        
        // $wp_query->set( $key, true );   
        // var_dump($wp_query->query_vars);     
        return true;
    // }else{
    //     $wp_query->set( $key, false );
        // var_dump($wp_query->query_vars);
    }

    return false;
}

function set_request_parameter( $key, $boolean ) {
    global $wp_query;
    
    if(strpos($_SERVER['REQUEST_URI'], $key) !== false){        
        $wp_query->set( $key, true );   
    }else{
        $wp_query->set( $key, false );
    }
}


//////////////////////////// FORCE File Download
/// File Download
function FileDownloadLink($getFilePath){
    $FileLink = 'force-download.php?file='.$getFilePath;
    return $FileLink;	
}
/// END ///

/**
 * Get the link of the attachment for download
 * REF: https://www.ibenic.com/programmatically-download-a-file-from-wordpress/
 */
function ibenic_the_file_link( $attachment_id ){
    // This must be improved by using wp_nonce!
    $tmpLink = '<a href="' . get_permalink( $attachment_id ) . '?attachment_id='. $attachment_id.'&download_file=1" class="a-download">';
    //   $tmpLink .= get_the_title( $attachment_id );
    $tmpLink .= 'Save Photo';
    $tmpLink .= '</a>';

    return $tmpLink;
}

// Start the download if there is a request for that
function ibenic_download_file(){
   
  if( isset( $_GET["attachment_id"] ) && isset( $_GET['download_file'] ) ) {
		ibenic_send_file();
	}
}
add_action('init','ibenic_download_file');

// Send the file to download
function ibenic_send_file(){
	//get filedata
  $attID = $_GET['attachment_id'];
  $theFile = wp_get_attachment_url( $attID );
  
  if( ! $theFile ) {
    return;
  }
  //clean the fileurl
  $file_url  = stripslashes( trim( $theFile ) );
  //get filename
  $file_name = basename( $theFile );
  //get fileextension
 
  $file_extension = pathinfo($file_name);
  //security check
  $fileName = strtolower($file_url);
  
  $whitelist = apply_filters( "ibenic_allowed_file_types", array('png', 'gif', 'tiff', 'jpeg', 'jpg','bmp','svg') );
  
  if(!in_array(end(explode('.', $fileName)), $whitelist))
  {
	  exit('Invalid file!');
  }
  if(strpos( $file_url , '.php' ) == true)
  {
	  die("Invalid file!");
  }
 
	$file_new_name = $file_name;
  $content_type = "";
  //check filetype
  switch( $file_extension['extension'] ) {
		case "png": 
		  $content_type="image/png"; 
		  break;
		case "gif": 
		  $content_type="image/gif"; 
		  break;
		case "tiff": 
		  $content_type="image/tiff"; 
		  break;
		case "jpeg":
		case "jpg": 
		  $content_type="image/jpg"; 
		  break;
		default: 
		  $content_type="application/force-download";
  }
  
  $content_type = apply_filters( "ibenic_content_type", $content_type, $file_extension['extension'] );
  
  header("Expires: 0");
  header("Cache-Control: no-cache, no-store, must-revalidate"); 
  header('Cache-Control: pre-check=0, post-check=0, max-age=0', false); 
  header("Pragma: no-cache");	
  header("Content-type: {$content_type}");
  header("Content-Disposition:attachment; filename={$file_new_name}");
  header("Content-Type: application/force-download");
   
  readfile("{$file_url}");
  exit();
}


// function add_taxonomies_to_pages() {
//     register_taxonomy_for_object_type( 'post_tag', 'page' );
//     register_taxonomy_for_object_type( 'category', 'page' );
// }
// add_action( 'init', 'add_taxonomies_to_pages' );

// if ( ! is_admin() ) {
//  add_action( 'pre_get_posts', 'category_and_tag_archives' );
 
//  }
// function category_and_tag_archives( $wp_query ) {
// $my_post_array = array('post','page');
 
//  if ( $wp_query->get( 'category_name' ) || $wp_query->get( 'cat' ) )
//  $wp_query->set( 'post_type', $my_post_array );
 
//  if ( $wp_query->get( 'tag' ) )
//  $wp_query->set( 'post_type', $my_post_array );
// }

