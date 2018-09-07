<?php

/*
    ====================================
    Include scripts
    ====================================
*/
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

    wp_enqueue_script( 'js_base', get_template_directory_uri().'/js/amactive.js', array(), true );

    

    //REF: https://www.sitepoint.com/using-font-awesome-with-wordpress/
    if($offline){
        wp_enqueue_style('font-awesome', get_template_directory_uri().'/offline/font-awesome.min.css');        
    }else{
        wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    }
}
add_action( 'wp_enqueue_scripts', 'amactive_script_enqueue' );

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
    register_sidebar(
        array (
            'name' => 'Sidebar Cars For Sale',
            'id' => 'custom-side-bar',
            'description' => 'Sidebar Cars For Sale',
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'after_widget'  => '</aside>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>'
        )
    );
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

///
function my_attachments( $attachments )
{
  $fields         = array(
    array(
      'name'      => 'title',                         // unique field name
      'type'      => 'text',                          // registered field type
      'label'     => __( 'Title', 'attachments' ),    // label to display
      'default'   => 'title',                         // default value upon selection
    ),
    array(
      'name'      => 'caption',                       // unique field name
      'type'      => 'textarea',                      // registered field type
      'label'     => __( 'Caption', 'attachments' ),  // label to display
      'default'   => 'caption',                       // default value upon selection
    ),
  );

  $args = array(

    // title of the meta box (string)
    'label'         => 'My Attachments',

    // all post types to utilize (string|array)
    'post_type'     => array( 'post', 'page' ),

    // meta box position (string) (normal, side or advanced)
    'position'      => 'normal',

    // meta box priority (string) (high, default, low, core)
    'priority'      => 'high',

    // allowed file type(s) (array) (image|video|text|audio|application)
    'filetype'      => null,  // no filetype limit

    // include a note within the meta box (string)
    'note'          => 'Attach files here!',

    // by default new Attachments will be appended to the list
    // but you can have then prepend if you set this to false
    'append'        => true,

    // text for 'Attach' button in meta box (string)
    'button_text'   => __( 'Attach Files', 'attachments' ),

    // text for modal 'Attach' button (string)
    'modal_text'    => __( 'Attach', 'attachments' ),

    // which tab should be the default in the modal (string) (browse|upload)
    'router'        => 'browse',

    // whether Attachments should set 'Uploaded to' (if not already set)
	'post_parent'   => false,

    // fields array
    'fields'        => $fields,

  );

  $attachments->register( 'my_attachments', $args ); // unique instance name
}
add_action( 'attachments_register', 'my_attachments' );

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
    // wp_enqueue_style( 'style_hacks', get_template_directory_uri().'/css/tmp-hacks.css' , array(), 'all' );
    wp_enqueue_style( 'style_theme', get_template_directory_uri().'/style.css?ver='. rand(111,999), array(), 'all' );
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
    if( $year && amactive_is_classified($postId) ) {
        $title = $year.' '.$title;
    }
    return $title;
}
// add_action( 'amactive_set_title', 'amactive_custom_title' );

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
        if($price):
            $itemPrice = '<span class="fmt-price">'.amactive_my_custom_price_format($price).'</span>';
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
        if($paged > 2 && $paged > $range+1 && $showitems < $pages) $myPagination .= "<li><a href='".get_pagenum_link(1)."'>&laquo; First</a></li>";
        if($paged > 1 && $showitems < $pages) $myPagination .= "<li><a href='".get_pagenum_link($paged - 1)."'>&lsaquo; Previous</a></li>";
 
        
        for ($i=1; $i <= $pages; $i++)
        {
            if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems ))
            {
                $myPagination .= ($paged == $i)? "<li><span class=\"current\">".$i."</span></li>":"<li><a href='".get_pagenum_link($i)."' class=\"inactive\">".$i."</a></li>";
            }
        }
        
 
        if ($paged < $pages && $showitems < $pages) $myPagination .= "<li><a href=\"".get_pagenum_link($paged + 1)."\">Next &rsaquo;</a></li>";
        if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) $myPagination .= "<li><a href='".get_pagenum_link($pages)."'>Last &raquo;</a></li>";
        $myPagination .= '</ul>';
        $myPagination .= "</div>\n";
    }

    return $myPagination;
}

function amactive_is_classified( $getPostId = 0 ){
    global $post;
    if ( !$getPostId ) $getPostId = $post->ID;
    if ( $getPostId && in_category( array(DV_category_IsForSale_id, DV_category_IsSold_id), $getPostId ) ) return true;
    return false;
}

function amactive_is_classified_sold( $getPostId = 0 ){
    global $post;
    if ( !$getPostId ) $getPostId = $post->ID;
    if ( $getPostId && in_category( array(DV_category_IsSold_id), $getPostId ) ) return true;
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

    $myCrumbs = '';
    $myCrumbs .= '<div class="crumbs-wrap">';
    $myCrumbs .= '<ul class="ul-breadcrumb">';
    $myCrumbs .= '<li class="home"><a href="'.get_option('home').'"><i class="fa fa-home"></i><span>home</span></a></li>';

    if($GLOBALS['postPageCategorySlug']){
        $myCrumbs .= '<li>';
        $myCrumbs .= '<a href="'.get_category_link($GLOBALS['postPageCategoryId']).'">'.$GLOBALS['postPageCategoryName'].'</a>';
        $myCrumbs .= '</li>'."\r\n";
    }

    if($GLOBALS['postPageSubCategorySlug']){
        $myCrumbs .= '<li>';
        $myCrumbs .= '<a href="'.get_category_link($GLOBALS['postPageCategoryId']).$GLOBALS['postPageSubCategorySlug'].'">'.$GLOBALS['postPageSubCategoryName'].'</a>';
        $myCrumbs .= '</li>'."\r\n";
    }

    if ( is_single() || is_page() ) {
        $myCrumbs .= '<li>';
        $myCrumbs .= get_the_title();
        $myCrumbs .= '</li>';
    }
    
    // elseif (is_tag()) {single_tag_title();}
    // elseif (is_day()) {$myCrumbs .= "<li>Archive for "; the_time('F jS, Y'); $myCrumbs .= '</li>';}
    // elseif (is_month()) {$myCrumbs .= "<li>Archive for "; the_time('F, Y'); $myCrumbs .= '</li>';}
    // elseif (is_year()) {$myCrumbs .= "<li>Archive for "; the_time('Y'); $myCrumbs .= '</li>';}
    // elseif (is_author()) {$myCrumbs .= "<li>Author Archive"; $myCrumbs .= '</li>';}
    // elseif (isset($_GET['paged']) && !empty($_GET['paged'])) {$myCrumbs .= "<li>Blog Archives"; $myCrumbs .= '</li>';}
    // elseif (is_search()) {$myCrumbs .= "<li>Search Results"; $myCrumbs .= '</li>';}
    
    $myCrumbs .= '</ul>';
    $myCrumbs .= '</div>';
    /* (END) crumbs-wrap */

    return $myCrumbs;
	
}

function amactive_set_category_globals( $category_ids, $categories ) {
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
/* (END) amactive_set_category_globals */

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