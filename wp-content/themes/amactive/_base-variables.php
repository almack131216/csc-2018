<?php
//REF: https://wordpress.stackexchange.com/questions/107696/in-array-doesnt-recognize-category (not in use)

    $offline = false;
    
    // define("DV_contact", [
    //     'address' => 'Corner Farm, West Knapton, Malton, North Yorkshire, UK, YO17 8JB',
    //     'email_address' => 'sales@classicandsportscar.ltd.uk',
    //     'telephone' => '01944 758000',
    //     'fax' => '01944 758963'
    // ]);

    define('DV_date_today', date('Y\-m\-d'));

    if( amactive_is_localhost() ){
        define('DV_base', 'http://localhost:8080/classicandsportscar.ltd.uk/');
    }else{
        define('DV_base', 'http://www.classicandsportscar.ltd.uk/_wp190503/');
    }

    define("DV_strapline", 'Selling classic cars worldwide for over 25 years');
    define("DV_contact_address", 'Corner Farm, West Knapton, Malton, North Yorkshire, UK, YO17 8JB');
    define("DV_contact_email_address", 'sales@classicandsportscar.ltd.uk');
    define("DV_contact_telephone", '01944 758000');
    define("DV_contact_fax", '01944 758963');

    define("DV_category_IsForSale_id", 2);// echo DV_category_IsForSale_id;
    define('DV_category_IsForSale_slug', 'classic-cars-for-sale');
    define('DV_category_IsForSale_name', 'Classic Cars For Sale');

    define("DV_category_IsSold_id", 38);
    define('DV_category_IsSold_slug', 'classic-cars-sold');
    define('DV_category_IsSold_name', 'Classic Cars Sold');

    define("DV_category_News_id", 40);
    define('DV_category_News_slug', 'news');

    define("DV_category_Press_id", 4);
    define('DV_category_Press_slug', 'press');

    define("DV_category_Testimonials_id", 3);
    define('DV_category_Testimonials_slug', 'testimonials');

    define("DV_category_PageText_id", 7);
    define('DV_category_PageText_slug', 'page-text');

    /* nullify all global variables */
    /* keep together so we know ALL globals */
    $GLOBALS['pageType'] = null;
    $GLOBALS['pageBodyClass'] = array();
    if( is_front_page() ):
        $amactive_classes_body = array( 'amactive', 'page-home' );
        $GLOBALS['pageBodyClass'] = array( 'amactive', 'page-home' );
    else:
        $amactive_classes_body = array( 'amactive' );
        $GLOBALS['pageBodyClass'] = array( 'amactive' );
    endif;

    // $GLOBALS['postPageIsForSale'] = null;
    // $GLOBALS['postPageIsSold'] = null;

    $GLOBALS['showProductCats'] = null;
    $GLOBALS['sidebarShowOpeningHours'] = null;
    $GLOBALS['sidebarShowContactDetails'] = null;
    $GLOBALS['postPageSlug'] = null;
    $GLOBALS['postPageTitle'] = null;
    $GLOBALS['postPageCategoryId'] = null;
    $GLOBALS['postPageCategoryName'] = null;
    $GLOBALS['postPageSubCategoryId'] = null;
    $GLOBALS['postPageSubCategoryName'] = null;
    $GLOBALS['sidebarCategoryListTitle'] = null;
    $GLOBALS['sidebarSubCategoryLinks'] = null;
    $GLOBALS['sidebarSubCategoryJumpSelect'] = null;
    $GLOBALS['dateToday'] = date( 'Y-m-d' );
    $GLOBALS['dateLaunch'] = date( '2018-04-01' );

    $GLOBALS['pagePostDetailsProps'] = array( );
    $GLOBALS['pagePostPhotosProps'] = array(
        'canZoom' => false,
        'canPrintImg' => true,
        'canDownloadImg' => true,
        'imgSize' => 'large',
        'excerptWordCount' => 60
    );

    if( have_posts() ):
        if ( is_page() ):
            $GLOBALS['pageType'] = 'page';
            // $GLOBALS['sidebarShowOpeningHours'] = true;
            switch( $post->ID ){
                case 22:
                    break;

                default:
                    $GLOBALS['sidebarShowContactDetails'] = true;
                    $GLOBALS['sidebarShowOpeningHours'] = true;
                    break;
            }
            
        elseif( is_front_page() ):
            $GLOBALS['pageType'] = 'front_page';
            // $GLOBALS['sidebarShowOpeningHours'] = false;
            // $GLOBALS['sidebarShowContactDetails'] = false;
        elseif( is_single() ):
            $GLOBALS['pageType'] = 'single';
            // var_dump($post);
            // $GLOBALS['postPageSlug'] = $post->post_name;//amactive_post_url();//str_ireplace('/photos','',esc_url( get_permalink() ));
            $GLOBALS['postPageSlug'] = amactive_post_url();//str_ireplace('/photos','',esc_url( get_permalink() ));
            $GLOBALS['postPageTitle'] = amactive_custom_title();
            $GLOBALS['showProductCats'] = true;
            $GLOBALS['sidebarShowOpeningHours'] = true;
            $GLOBALS['sidebarShowContactDetails'] = true;

            //REF: https://wordpress.stackexchange.com/questions/107696/in-array-doesnt-recognize-category
            $categories = $category_ids = array();
            

            //REF: https://stackoverflow.com/questions/45417125/how-to-exclude-specific-category-and-show-only-one-from-the-get-the-category
            // if ($categoryArr) :
            // get all categories for this post
            foreach ( (get_the_category()) as $category ) {
                if ( ! in_array( $category->term_id, $category_ids ) ) {
                    $category_ids[] = $category->term_id;
                    $categories[] = $category;
                }
            }
            // print_r( $categories );
            amactive_set_category_globals( $category_ids, $categories );     

            // print_r($amactive_classes_body);
            // echo '<h5>??? csc_car_sale_status: '.get_post_meta( $post->ID, 'csc_car_sale_status', true).'</h6>';
            // array_push($amactive_classes_body, $GLOBALS['postPageCategorySlug']);

        else:
            // posts page
            $GLOBALS['pageType'] = 'posts';
            $GLOBALS['sidebarShowOpeningHours'] = true;
            $GLOBALS['sidebarShowContactDetails'] = true;
        endif;
    endif;

    // if posts page...
    if(!empty($cat)){
        $GLOBALS['page_object'] = get_queried_object();
        // var_dump($GLOBALS['page_object']);
        // $GLOBALS['postPageCategoryId'] = get_query_var('cat');
        $GLOBALS['postPageCategoryId'] = $GLOBALS['page_object']->term_id;
        $GLOBALS['postPageCategoryName'] = $GLOBALS['page_object']->cat_name;
        $GLOBALS['postPageCategorySlug'] = $GLOBALS['page_object']->slug;
        $GLOBALS['postPageCategoryCount'] = $GLOBALS['page_object']->category_count;
        $GLOBALS['sidebarCategoryListTitle'] = $GLOBALS['postPageCategoryName'];

        if( $GLOBALS['page_object']->category_parent ){
            // echo '<h6>category_parent: '.$GLOBALS['page_object']->category_parent.'</h6>';
            $thisCat = get_category($GLOBALS['page_object']->category_parent);
            // print_r($thisCat);
            $GLOBALS['postPageCategoryId'] = $thisCat->term_id;
            $GLOBALS['postPageCategoryName'] = $thisCat->name;
            $GLOBALS['postPageCategorySlug'] = $thisCat->slug;

            $GLOBALS['postPageSubCategoryId'] = $GLOBALS['page_object']->term_id;
            $GLOBALS['postPageSubCategoryName'] = $GLOBALS['page_object']->name;
            $GLOBALS['postPageSubCategorySlug'] = $GLOBALS['page_object']->slug;

            if(strpos($_SERVER['REQUEST_URI'], DV_category_IsSold_slug) !== false){
                $GLOBALS['postPageCategoryId'] = DV_category_IsSold_id;
                $GLOBALS['postPageCategoryName'] = DV_category_IsSold_name;
                $GLOBALS['postPageCategorySlug'] = DV_category_IsSold_slug;
            }
        }

        if ( $GLOBALS['postPageCategoryId'] == DV_category_IsForSale_id ) {
            $GLOBALS['postPageIsForSale'] = true;
            $GLOBALS['showProductCats'] = true;
            array_push($GLOBALS['pageBodyClass'], 'classic-cars-for-sale');
            // dynamic_sidebar( 'custom-side-bar' );
        } else if ( $GLOBALS['postPageCategoryId'] == DV_category_IsSold_id ) {
            $GLOBALS['postPageIsSold'] = true;
            $GLOBALS['showProductCats'] = true;
            array_push($GLOBALS['pageBodyClass'], DV_category_IsSold_slug);
            // dynamic_sidebar( 'custom-side-bar-sold' );
        }
    }
?>
