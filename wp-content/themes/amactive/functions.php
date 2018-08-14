<?php

/*
    ====================================
    Include scripts
    ====================================
*/
function amactive_script_enqueue() {
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
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
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
function amactive_custom_title($title, $getPostId) {
    // if( is_single() ){
    //     $categories = get_the_category();
    //     $title      = $categories[0]->name;
    // }

    // return $title;
    $year = get_post_meta( $getPostId, 'csc_car_year', true);
    if( $year && ($GLOBALS['postPageCategoryId'] == DV_category_IsForSale_id || $GLOBALS['postPageCategoryId'] == DV_category_IsSold_id) ) {
        $title = $year.' '.get_the_title();
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
        $seeAllLink = $cat->slug;
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
    $ts .= '<a href="'.$seeAllLink.'" class="a-all">'.$seeAll.'</a>';
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

function amactive_wp_set_post_lock( $post_id ) {
	if ( ! $post = get_post( $post_id ) ) {
		return false;
	}

	if ( 0 == ( $user_id = get_current_user_id() ) ) {
		return false;
	}

	$now = time();
	$lock = "$now:$user_id";

	update_post_meta( $post->ID, '_edit_lock', $lock );

	return array( $now, $user_id );
}

function amactive_batch_insert_postmeta( $getArr ) {
    global $wpdb;

    if($getArr){
        $my_post_id = $getArr['post_id'];
        $my_item_arr = $getArr['item_arr'];

        $wpdb->insert('wp_postmeta', array(
            'post_id' => $my_post_id,
            'meta_key' => 'csc_car_sale_status',
            'meta_value' => $my_item_arr->status
        ));
        $wpdb->insert('wp_postmeta', array(
            'my_post_id' => $my_post_id,
            'meta_key' => '_csc_car_sale_status',
            'meta_value' => 'field_5b47617c80afd'
        ));
        $wpdb->insert('wp_postmeta', array(
            'post_id' => $my_post_id,
            'meta_key' => 'csc_car_year',
            'meta_value' => $my_item_arr->year
        ));
        $wpdb->insert('wp_postmeta', array(
            'post_id' => $my_post_id,
            'meta_key' => '_csc_car_year',
            'meta_value' => 'field_5b0d704a3289e'
        ));
        $wpdb->insert('wp_postmeta', array(
            'post_id' => $my_post_id,
            'meta_key' => 'csc_car_price',
            'meta_value' => $my_item_arr->price
        ));
        $wpdb->insert('wp_postmeta', array(
            'post_id' => $my_post_id,
            'meta_key' => '_csc_car_price',
            'meta_value' => 'field_5b0d70b73289f'
        ));                    
        $wpdb->insert('wp_postmeta', array(
            'post_id' => $my_post_id,
            'meta_key' => 'csc_car_price_details',
            'meta_value' => $my_item_arr->price_details
        ));
        $wpdb->insert('wp_postmeta', array(
            'post_id' => $my_post_id,
            'meta_key' => '_csc_car_price_details',
            'meta_value' => 'field_5b0d70fd328a0'
        ));
    }
}

function amactive_batch_delete_all( $getQuery ) {
    global $wpdb;

    $result = $wpdb->get_results( $getQuery );// LIMIT 3

    if($result) {
        echo '<h1>DELETE...</h1>';
        echo '<span class="sql_info">'.$wpdb->last_query.'</span>';
        foreach($result as $wp_migrated_items){
            $item_id = $wp_migrated_items->id;

            $result_migrated = $wpdb->get_results("SELECT * FROM amactive_migrated WHERE id_before=$item_id");//LIMIT 1
            if($result_migrated) {
                echo '???';
                echo '<span class="sql_info">'.$wpdb->last_query.'</span>';
                foreach($result_migrated as $wp_migrated_posts){
                    $item_id_before = $wp_migrated_posts->id_before;
                    $post_id_to_delete = $wp_migrated_posts->id_after;
                    $post_id_to_delete_attachment = $wp_migrated_posts->id_after_attachment;
                    $post_id_to_delete_revision = $wp_migrated_posts->id_after_revision;
                    
                    echo '<span class="sql_step">DELETE > wp_posts > WHERE ID = '.$post_id_to_delete.'</span>';
                    $deletePost = $wpdb->delete( 'wp_posts', array( 'ID' => $post_id_to_delete ) );
                    if($deletePost) echo '<span class="sql_success">'.$deletePost.' DELETED > wp_posts > WHERE ID='.$post_id_to_delete.'</span>';
                    if($wpdb->last_error) echo '<span class="sql_error">DELETE FAILED: '.$wpdb->last_error.'</span>';

                    echo '<span class="sql_step">DELETE > wp_posts > WHERE post_parent = '.$post_id_to_delete.'</span>';
                    $deletePostParent = $wpdb->delete( 'wp_posts', array( 'post_parent' => $post_id_to_delete ) );
                    if($deletePostParent) echo '<span class="sql_success">'.$deletePostParent.' DELETED > wp_posts > WHERE post_parent='.$post_id_to_delete.'</span>';
                    if($wpdb->last_error) echo '<span class="sql_error">DELETE FAILED: '.$wpdb->last_error.'</span>';


                    /* POSTMETA has 3 records... */
                    echo '<span class="sql_step">DELETE > wp_postmeta > WHERE post_id = '.$post_id_to_delete.'</span>';
                    $deletePostMeta = $wpdb->delete( 'wp_postmeta', array( 'post_id' => $post_id_to_delete ) );
                    if($deletePostMeta) echo '<span class="sql_success">'.$deletePostMeta.' DELETED > wp_postmeta > WHERE post_id='.$post_id_to_delete.'</span>';
                    if($wpdb->last_error) echo '<span class="sql_error">DELETE FAILED: '.$wpdb->last_error.'</span>';

                    echo '<span class="sql_step">DELETE > wp_postmeta > WHERE post_id = '.$post_id_to_delete_attachment.'</span>';
                    $deletePostMetaAttachment = $wpdb->delete( 'wp_postmeta', array( 'post_id' => $post_id_to_delete_attachment ) );
                    if($deletePostMetaAttachment) echo '<span class="sql_success">'.$deletePostMetaAttachment.' DELETED > wp_postmeta > WHERE post_id='.$post_id_to_delete_attachment.'</span>';
                    if($wpdb->last_error) echo '<span class="sql_error">DELETE FAILED: '.$wpdb->last_error.'</span>';

                    echo '<span class="sql_step">DELETE > wp_postmeta > WHERE post_id = '.$post_id_to_delete_revision.'</span>';
                    $deletePostMetaRevision = $wpdb->delete( 'wp_postmeta', array( 'post_id' => $post_id_to_delete_revision ) );
                    if($deletePostMetaRevision) echo '<span class="sql_success">'.$deletePostMetaRevision.' DELETED > wp_postmeta > WHERE post_id='.$post_id_to_delete_revision.'</span>';
                    if($wpdb->last_error) echo '<span class="sql_error">DELETE FAILED: '.$wpdb->last_error.'</span>';

                    echo '<span class="sql_step">DELETE > amactive_migrated > WHERE id_after = '.$post_id_to_delete.'</span>';
                    $deletePostMigrated = $wpdb->delete( 'amactive_migrated', array( 'id_after' => $post_id_to_delete ) );
                    if($deletePostMigrated) echo '<span class="sql_success">'.$deletePostMigrated.' DELETED > amactive_migrated > WHERE id_after='.$post_id_to_delete.'</span>';
                    if($wpdb->last_error) echo '<span class="sql_error">DELETE FAILED: '.$wpdb->last_error.'</span>';

                    echo '<span class="sql_step">UPDATE > catalogue > migrate = 1</span>';
                    $updateCatalogue = $wpdb->update(
                        'catalogue',
                            array(
                                'migrated' => 0
                            ),
                            array('id' => $item_id_before)
                    );
                    if($wpdb->last_error) echo '<span class="sql_error">'.$wpdb->last_error.'</span>';
                    if ($updateCatalogue){
                        echo '<span class="sql_success">UPDATE > catalogue > migrated=1</span>';
                    }                                 
                
                }              
            } else {
                echo '<span class="sql_info">(NOT FOUND) '.$wpdb->last_query.'</span>';
                if($wpdb->last_error) echo '<span class="sql_error">'.$wpdb->last_error.'</span>';
            }
            
        }               
    } else {
        echo '<span class="sql_info">'.$wpdb->last_query.'</span>';
        if($wpdb->last_error) echo '<span class="sql_error">'.$wpdb->last_error.'</span>';
    }
}

function amactive_batch_print_post( $getArr ){

    $tableSuccess = "<table border='1'>";
    $tableSuccess .= "<tr><th>Id</th><th>Img</th><th>Name</th><th>Category</th><th>Subcategory</th><th>Date</th></tr>";
    
    $tableSuccess .= '<tr>';
    $tableSuccess .= '<td>';
        $tableSuccess .= $getArr['item_arr']->id;
        $tableSuccess .= '<br>'.$getArr['post_arr']->id;
    $tableSuccess .= '</td>';
    $tableSuccess .= '<td>';
        $tableSuccess .= '<img src="http://www.classicandsportscar.ltd.uk/images_catalogue/thumbs/'.$getArr['item_arr']->image_large.'">';
        $tableSuccess .= '<br><img src="http://localhost:8080/classicandsportscar.ltd.uk/'.$getArr['post_arr']->filename.'">';
    $tableSuccess .= '</td>';
    $tableSuccess .= '<td>';
        $tableSuccess .= $getArr['item_arr']->name;
        $tableSuccess .= '<br>'.$getArr['post_arr']->name;
    $tableSuccess .= '</td>';
    $tableSuccess .= '<td>';
        $tableSuccess .= $getArr['item_arr']->category;
        $tableSuccess .= '<br>'.$getArr['post_arr']->category;
    $tableSuccess .= '</td>';
    $tableSuccess .= '<td>';
        $tableSuccess .= $getArr['item_arr']->subcategory;
        $tableSuccess .= '<br>'.$getArr['post_arr']->subcategory;
    $tableSuccess .= '</td>';
    $tableSuccess .= '<td>';
        $tableSuccess .= $getArr['item_arr']->upload_date;
        $tableSuccess .= '<br>'.$getArr['post_arr']->date;
    $tableSuccess .= '</td>';
    $tableSuccess .= '</tr>';

    $tableSuccess .= "</table>";

    return $tableSuccess;
}