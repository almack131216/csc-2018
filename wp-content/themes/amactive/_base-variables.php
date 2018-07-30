<?php
    if( is_front_page() ):
        $amactive_classes_body = array( 'amactive-class', 'my-class' );
    else:
        $amactive_classes_body = array( 'not-amactive-class' );
    endif;


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

    /* nullify all global variables */
    /* keep together so we know ALL globals */
    $GLOBALS['pageType'] = null;

    // $GLOBALS['postPageIsForSale'] = null;
    // $GLOBALS['postPageIsSold'] = null;

    $GLOBALS['showProductCats'] = null;
    $GLOBALS['postPageTitle'] = null;
    $GLOBALS['postPageCategoryId'] = null;
    $GLOBALS['postPageCategoryName'] = null;
    $GLOBALS['sidebarCategoryListTitle'] = null;

    

    if( have_posts() ):
        if ( is_page() ):
            $GLOBALS['pageType'] = 'page';
        elseif( is_front_page() ):
            $GLOBALS['pageType'] = 'front_page'; 
        elseif( is_single() ):
            $GLOBALS['pageType'] = 'single';
            $GLOBALS['showProductCats'] = true;

            $categoryArr = get_the_category();
            // if ( $GLOBALS['postPageCategoryId'] == DV_category_IsForSale_id || $GLOBALS['postPageCategoryId'] ==  DV_category_IsSold_id ) {                        
            //REF: https://stackoverflow.com/questions/45417125/how-to-exclude-specific-category-and-show-only-one-from-the-get-the-category
            if ($categoryArr) :
                foreach($categoryArr as $category) {
                    if(!$GLOBALS['postPageCategoryId'] && ($category->term_id == DV_category_IsForSale_id || $category->term_id == DV_category_IsSold_id)) {
                        $GLOBALS['postPageCategoryId'] = $category->term_id;
                        $GLOBALS['postPageCategoryName'] = $category->name;
                        $GLOBALS['postPageSubCategorySlug'] = $category->slug;
                        // echo '<h5>??? categoryId: '.$GLOBALS['postPageCategoryId'].'</h6>';
                        // echo '<h5>??? categoryName: '.$GLOBALS['postPageCategoryName'].'</h6>';
                        continue;
                    }
                    if($GLOBALS['postPageCategoryId'] == DV_category_IsForSale_id && ($category->term_id == DV_category_IsSold_id)) {
                        $GLOBALS['postPageCategoryId'] = $category->term_id;
                        $GLOBALS['postPageCategoryName'] = $category->name;
                        $GLOBALS['postPageCategorySlug'] = $category->slug;
                        // echo '<h5>???? categoryId: '.$GLOBALS['postPageCategoryId'].'</h6>';
                        // echo '<h5>???? categoryName: '.$GLOBALS['postPageCategoryName'].'</h6>';
                        continue;
                    }
                    if($GLOBALS['postPageCategoryId'] && ($category->term_id != DV_category_IsSold_id)) {
                        $GLOBALS['showProductCats'] = true;
                        $GLOBALS['postPageSubCategoryId'] = $category->term_id;
                        $GLOBALS['postPageSubCategoryName'] = $category->name;
                        $GLOBALS['postPageSubCategorySlug'] = $category->slug;
                        // echo '<h5>????? subcategoryId: '.$GLOBALS['postPageSubCategoryId'].'</h6>';
                        // echo '<h5>????? subcategoryName: '.$GLOBALS['postPageSubCategoryName'].'</h6>';
                        break;
                    }
                }
            endif;
            // }
                                
            // echo '<h5>??? csc_car_sale_status: '.get_post_meta( $post->ID, 'csc_car_sale_status', true).'</h6>';
      
        else:
            $GLOBALS['pageType'] = 'posts';
        endif;
    endif;

    // [?] if posts page */
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
            // dynamic_sidebar( 'custom-side-bar' );
        } else if ( $GLOBALS['postPageCategoryId'] == DV_category_IsSold_id ) {
            $GLOBALS['postPageIsSold'] = true;
            $GLOBALS['showProductCats'] = true;
            // dynamic_sidebar( 'custom-side-bar-sold' );
        }
    }
?>
            