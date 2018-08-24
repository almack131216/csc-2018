<?php
//REF: https://wordpress.stackexchange.com/questions/107696/in-array-doesnt-recognize-category (not in use)

    if( is_front_page() ):
        $amactive_classes_body = array( 'amactive', 'page-home' );
    else:
        $amactive_classes_body = array( 'amactive' );
    endif;

    // define("DV_contact", [
    //     'address' => 'Corner Farm, West Knapton, Malton, North Yorkshire, UK, YO17 8JB',
    //     'email_address' => 'sales@classicandsportscar.ltd.uk',
    //     'telephone' => '01944 758000',
    //     'fax' => '01944 758963'
    // ]);

    define("DV_strapline", 'Selling classic cars worldwide for over 25 years');
    define("DV_contact_address", 'Corner Farm, West Knapton, Malton, North Yorkshire, UK, YO17 8JB');
    define("DV_contact_email_address", 'sales@classicandsportscar.ltd.uk');
    define("DV_contact_telephone", '01944 758000');
    define("DV_contact_fax", '01944 758963');

    define("DV_category_IsForSale_id", 2);// echo DV_category_IsForSale_id;
    define('DV_category_IsForSale_slug', 'classic-cars-for-sale');

    define("DV_category_IsSold_id", 38);
    define('DV_category_IsSold_slug', 'classic-cars-sold');

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

    // $GLOBALS['postPageIsForSale'] = null;
    // $GLOBALS['postPageIsSold'] = null;

    $GLOBALS['showProductCats'] = null;
    $GLOBALS['sidebarShowOpeningHours'] = null;
    $GLOBALS['sidebarShowContactDetails'] = null;
    $GLOBALS['postPageTitle'] = null;
    $GLOBALS['postPageCategoryId'] = null;
    $GLOBALS['postPageCategoryName'] = null;
    $GLOBALS['sidebarCategoryListTitle'] = null;



    if( have_posts() ):
        if ( is_page() ):
            $GLOBALS['pageType'] = 'page';
            // $GLOBALS['sidebarShowOpeningHours'] = true;
            // $GLOBALS['sidebarShowContactDetails'] = true;
        elseif( is_front_page() ):
            $GLOBALS['pageType'] = 'front_page';
            // $GLOBALS['sidebarShowOpeningHours'] = false;
            // $GLOBALS['sidebarShowContactDetails'] = false;
        elseif( is_single() ):
            $GLOBALS['pageType'] = 'single';
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

            // if post is for sale...
            if(in_array(DV_category_IsForSale_id, $category_ids)){
                array_push($amactive_classes_body, 'classic-cars-for-sale');
                $category_ids = array_diff($category_ids, array(DV_category_IsForSale_id));
                amactive_debug('YES! - DV_category_IsForSale_id');
                $GLOBALS['postPageCategoryId'] = $category->term_id;
                $GLOBALS['postPageCategoryName'] = $category->name;
                $GLOBALS['postPageCategorySlug'] = $category->slug;
            }

            // if post is sold...
            if(in_array(DV_category_IsSold_id, $category_ids)){
                $amactive_classes_body = array_diff($amactive_classes_body, array('classic-cars-for-sale') );
                array_push($amactive_classes_body, 'classic-cars-sold');
                $category_ids = array_diff($category_ids, array(DV_category_IsSold_id));
                amactive_debug('YES! - DV_category_IsSold_id');
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
        $GLOBALS['postPageCategoryCount'] = $GLOBALS['page_object']->category_count;
        $GLOBALS['postPageTitle'] = $GLOBALS['postPageCategoryName'];
        $GLOBALS['sidebarCategoryListTitle'] = $GLOBALS['postPageCategoryCount'].' '.$GLOBALS['postPageCategoryName'];

        if( $GLOBALS['page_object']->category_parent ){
            // echo '<h6>category_parent: '.$GLOBALS['page_object']->category_parent.'</h6>';
            $thisCat = get_category($GLOBALS['page_object']->category_parent);
            $GLOBALS['postPageCategoryId'] = $GLOBALS['page_object']->category_parent;
            $GLOBALS['postPageCategoryName'] = $thisCat->name;

            $GLOBALS['postPageSubCategoryId'] = $GLOBALS['page_object']->term_id;
            $GLOBALS['postPageSubCategoryName'] = $GLOBALS['page_object']->name;

            if(strpos($_SERVER['REQUEST_URI'], 'classic-cars-sold') !== false){
                $GLOBALS['postPageCategoryId'] = DV_category_IsSold_id;
                $GLOBALS['postPageCategoryName'] = 'XXX SOLD XXX';
            }
        }

        if ( $GLOBALS['postPageCategoryId'] == DV_category_IsForSale_id ) {
            $GLOBALS['postPageIsForSale'] = true;
            $GLOBALS['showProductCats'] = true;
            array_push($amactive_classes_body, 'classic-cars-for-sale');
            // dynamic_sidebar( 'custom-side-bar' );
        } else if ( $GLOBALS['postPageCategoryId'] == DV_category_IsSold_id ) {
            $GLOBALS['postPageIsSold'] = true;
            $GLOBALS['showProductCats'] = true;
            array_push($amactive_classes_body, 'classic-cars-sold');
            // dynamic_sidebar( 'custom-side-bar-sold' );
        }
    }
?>
