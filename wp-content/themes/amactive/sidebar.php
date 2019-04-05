<?php
    
    echo '<div class="nav-primary-wrap">';
    dynamic_sidebar( 'sidebar-1' );
    echo '</div>';
    // wp_nav_menu( array('theme_location' => 'primary') );
    amactive_debug('FILE: sidebar.php');
    amactive_debug('GV pageType: '.$GLOBALS['pageType']);
    amactive_debug('GV postPageCategoryId: '.$GLOBALS['postPageCategoryId']);
    amactive_debug('GV postPageSubCategoryId: '.$GLOBALS['postPageSubCategoryId']);        
    // dynamic_sidebar( 'custom-side-bar' );

    // REF: https://developer.wordpress.org/reference/functions/wp_dropdown_categories/
    // $args_cats = array(
    //     'child_of' => DV_category_IsForSale_id,
    //     'show_option_all'   => 'Show All Makes',
    //     'show_option_none'  => '',
    //     'show_count' => 1,
    //     // 'exclude'   => array(DV_category_IsForSale_id,DV_category_IsSold_id,DV_category_News_id, DV_category_Testimonials_id, DV_category_Press_id, DV_category_PageText_id),
    //     'orderby'   => 'name',
    //     'order'     => 'ASC'
    // );
    // $args_cats['exclude'] = array(DV_category_IsSold_id);
    // // $args_cats['category__not_in'] = DV_category_IsSold_id;
    // wp_dropdown_categories( $args_cats );

    if ($GLOBALS['showProductCats']) {
        /* base args */
        $subcatLinks = '';
        $showCategoryCount = true;
        $totalCount = 0;
        $args_productCats = array(
            'orderby'   => 'name', 
            'order'     => 'ASC',
            'child_of' => 2,
            'category__not_in' => array( DV_category_News_id )
        );
        /* [?] for sale / sold */
        if ( $GLOBALS['postPageCategoryId'] == DV_category_IsForSale_id) {            
            $args_sale_or_sold = array('category__not_in' => DV_category_IsSold_id);
            // dynamic_sidebar( 'custom-side-bar' );
        } else if ( $GLOBALS['postPageCategoryId'] == DV_category_IsSold_id ) {
            // $args_sale_or_sold = array('category__in' => DV_category_IsSold_id);
            $args_sale_or_sold = array(
                'category__in' => DV_category_IsSold_id
            );
            // dynamic_sidebar( 'custom-side-bar-sold' );
        }

        
        /* [?] on subcategory page? */
        if ( $GLOBALS['postPageSubCategoryId'] ) {

            $args_forSale = array(
                'cat' => $GLOBALS['postPageSubCategoryId'],
                'category__not_in' => DV_category_IsSold_id,
            );
            $the_query = new WP_Query( $args_forSale );
            $count_IsForSale = $the_query->found_posts;

            $args_isSold = array(
                'cat' => $GLOBALS['postPageSubCategoryId'],
                'category__in' => DV_category_IsSold_id
            );
            $the_query = new WP_Query( $args_isSold );
            $count_IsSold = $the_query->found_posts;
                
            $subcatLinks .= '<aside id="product-subcategory-selected" class="widget widget_product-subcategory-selected">';        
            $subcatLinks .= '<div class="widget_basic category-list">';
            $subcatLinks .= '<h5 class="title">'.$GLOBALS['postPageSubCategoryName'].'</h5>';
            $subcatLinks .= '<ul>';

            if ($count_IsForSale) {
                $categoryLink = get_category_link( $GLOBALS['postPageSubCategoryId'] );
                $subcatLinks .= '<li><a href="' . $categoryLink . '"';
                // $subcatLinks .= ' title="XXX"';
                $subcatLinks .= ' class="">';
                $subcatLinks .= $GLOBALS['postPageSubCategoryName'].' For Sale';            
                $subcatLinks .= ' ('.$count_IsForSale.')';
                $subcatLinks .= '</a></li>';
            }

            if ($count_IsSold) {
                // var_dump($the_query);
                $categoryLink = get_category_link( $GLOBALS['postPageSubCategoryId'] );
                $categoryLink = str_replace(DV_category_IsForSale_slug, DV_category_IsSold_slug, $categoryLink);
                // $categoryLink = get_category_link( $category->term_id );

                        // if( $GLOBALS['postPageCategoryId'] == DV_category_IsSold_id ) {
                            // $categoryLink = bloginfo('url').'classicandsportscar.ltd.uk/category/classic-cars-sold/'. $GLOBALS['postPageSubCategorySlug'];
                            // $categoryLink = $the_query->parse_tax_query.'/category/classic-cars-sold/'. $GLOBALS['postPageSubCategorySlug'];
                            // $categoryLink = $category->slug;
                        // }

                $subcatLinks .= '<li><a href="' . $categoryLink . '"';
                // $subcatLinks .= ' title="XXX"';
                $subcatLinks .= ' class="">';
                $subcatLinks .= $GLOBALS['postPageSubCategoryName'].' SOLD';            
                $subcatLinks .= ' ('.$count_IsSold.')';
                $subcatLinks .= '</a></li>';
            }          

            $subcatLinks .= '</ul>';
            $subcatLinks .= '</div>';
            $subcatLinks .= '</aside>';
            echo $subcatLinks;
            $GLOBALS['sidebarSubCategoryLinks'] = $subcatLinks;
            wp_reset_postdata();
        }

        // print_r( $args_productCats );
        $categories = get_categories( $args_productCats );            

        if( $categories ) {
            $baseCategoryLink = get_category_link($GLOBALS['postPageCategoryId']);
            $myCategories = '';
            $myCategoriesSelect = '';
            
            $myCategories .= '<ul>';

            $myCategoriesSelect .= '<form name="category_jump" id="category_jump">';
            $myCategoriesSelect .= '<select name="jumpMenu" id="jumpMenu" onChange="MM_jumpMenu(\'parent\',this,0)">';
            $myCategoriesSelect .= '<option value="'.$baseCategoryLink.'">';                        
            $myCategoriesSelect .= 'All';
            $myCategoriesSelect .= '</option>';

            foreach($categories as $category) {
                $args = [
                    'post_type' => 'post',
                    'cat' => $category->term_id,
                    // 'category__not_in' => DV_category_IsSold_id
                ];
                if($args_sale_or_sold) $args += $args_sale_or_sold;
                $count = get_term_post_count( 'category', $category->term_id, $args );
                $totalCount += $count;

                if ( $count ) {
                    $myCategoryName = $category->name;                        
                    if( $showCategoryCount ){
                        $myCategoryName .= ' ('.$count.')';
                    }

                    $categoryLink = $baseCategoryLink.$category->slug;
                    $categoryLinkJump = $categoryLink;
                    // if( $GLOBALS['postPageCategoryId'] == DV_category_IsSold_id ){
                    //     $categoryLink = get_category_link( DV_category_IsSold_id );
                    //     echo '<br>'.$categoryLink;
                    // }

                    $myCategories .= '<li><a href="'.$categoryLink.'"';
                    $myCategories .= ' title="' . sprintf( __( "View all posts in %s" ), $category->name ) . '"';
                    $myCategories .= ' class="">';
                    $myCategories .= $myCategoryName;
                    $myCategories .= '</a></li>';

                    $myCategoriesSelect .= '<option value="'.$categoryLinkJump.'"';
                    if( $GLOBALS['postPageSubCategoryId'] == $category->term_id ) $myCategoriesSelect .= ' selected';
                    $myCategoriesSelect .= '>';                        
                    $myCategoriesSelect .= $myCategoryName;
                    $myCategoriesSelect .= '</option>';

                    // if($category->name == 'Ferrari') {
                    //     var_dump($category);
                    // }
                }                
            }
            // $myCategories .= '<li>??? '.$totalCount.'</li>';
            $myCategories .= '</ul>';
            $myCategoriesSelect .= '</select>';
            $myCategoriesSelect .= '</form>';

            $GLOBALS['postPageCategoryCount'] = $totalCount;
            $GLOBALS['sidebarCategoryListTitle'] = $totalCount.' '.$GLOBALS['postPageCategoryName'];
            if( $GLOBALS['postPageCategoryId']==DV_category_IsSold_id ) $GLOBALS['sidebarCategoryListTitle'] = $GLOBALS['postPageCategoryName'];

            $myCategoriesWrap = '<aside id="product-categories" class="widget widget_product-categories">';        
            $myCategoriesWrap .= '<div class="widget_basic category-list">';
            $myCategoriesWrap .= '<h5 class="title">'.$GLOBALS['sidebarCategoryListTitle'].'</h5>';
            $myCategoriesWrap .= $myCategories;
            $myCategoriesWrap .= '</div>';
            $myCategoriesWrap .= '</aside>';
            
            // echo $GLOBALS['sidebarSubCategoryJumpSelect'];
            // echo '<hr>';

            /*
            $args = [
                'post_type'   => 'post',
                'cat' => 2,
                'category__not_in' => DV_category_IsSold_id
            ];
            $count = get_term_post_count( 'category', 'all', $args );
            echo $count.' / '.$totalCount;
            */
        }
        /* (END) if categories */        
    }
    
    if( amactive_posts_page_is_classified($GLOBALS['postPageCategoryId'] ) ) $GLOBALS['sidebarSubCategoryJumpSelect'] = $myCategoriesSelect;
    if( !$GLOBALS['postPageSubCategoryId'] ) echo $myCategoriesWrap;

    /* WIDGET - Address */
    if( $GLOBALS['sidebarShowContactDetails'] ){
        echo '<div class="widget_basic contact-details">';
        echo do_shortcode( "[insert page='44' display='all']", false );
        echo '</div>';
    }

    /* WIDGET - Opening Hours */
    if( $GLOBALS['sidebarShowOpeningHours'] ){
        echo '<div class="widget_basic opening-hours">';
        echo do_shortcode( "[insert page='262' display='all']", false );
        echo '</div>';
    }

    /*
    username: stemmvogcscuser
    password: eKUm@b%(a@I4qi@0e6
    email: amactive17@gmail.com
    */
?>