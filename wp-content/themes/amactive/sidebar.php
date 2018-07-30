<?php

    dynamic_sidebar( 'sidebar-1' );
    // wp_nav_menu( array('theme_location' => 'primary') );
    amactive_debug('FILE: sidebar.php');
    amactive_debug('GV pageType: '.$GLOBALS['pageType']);
    amactive_debug('GV postPageCategoryId: '.$GLOBALS['postPageCategoryId']);
    amactive_debug('GV postPageSubCategoryId: '.$GLOBALS['postPageSubCategoryId']);
        
?>

<?php

    if ($GLOBALS['showProductCats']) {
        /* base args */
        $showCategoryCount = true;
        $totalCount = 0;
        $args = array(
            'orderby'   => 'name', 
            'order'     => 'ASC',
            'child_of' => 2,
        );
        /* [?] for sale / sold */
        if ( $GLOBALS['postPageCategoryId'] == DV_category_IsForSale_id) {            
            $args_sale_or_sold = array('category__not_in' => DV_category_IsSold_id);
            // dynamic_sidebar( 'custom-side-bar' );
        } else if ( $GLOBALS['postPageCategoryId'] == DV_category_IsSold_id ) {
            // $args_sale_or_sold = array('category__in' => DV_category_IsSold_id);
            $args_sale_or_sold = array('category__in' => DV_category_IsSold_id);
            // dynamic_sidebar( 'custom-side-bar-sold' );
        }

        
        /* [?] on subcategory page? */
        if ( $GLOBALS['postPageSubCategoryId'] ) {
            $GLOBALS['sidebarCategoryListTitle'] = '';

            $args = array(
                'cat' => $GLOBALS['postPageSubCategoryId'],
                'category__not_in' => DV_category_IsSold_id
            );
            $the_query = new WP_Query( $args );
            $count_IsForSale = $the_query->found_posts;

            $args = array(
                'cat' => $GLOBALS['postPageSubCategoryId'],
                'category__in' => DV_category_IsSold_id
            );
            $the_query = new WP_Query( $args );
            $count_IsSold = $the_query->found_posts;

            echo '<aside id="product-categories" class="widget widget_product-categories">';        
            echo '<div class="list-group">';

            if ($count_IsForSale) {
                $categoryLink = get_category_link( $GLOBALS['postPageSubCategoryId'] );
                echo '<a href="' . $categoryLink . '"';
                echo ' title="XXX"';
                echo ' class="list-group-item">';
                echo $GLOBALS['postPageSubCategoryName'].' For Sale';            
                echo ' ('.$count_IsForSale.')';
                echo '</a>';
            }

            if ($count_IsSold) {
                // var_dump($the_query);
                $categoryLink = get_category_link( $GLOBALS['postPageSubCategoryId'] );
                $categoryLink = str_replace(DV_category_IsForSale_slug, DV_category_IsSold_id, $categoryLink);
                // $categoryLink = get_category_link( $category->term_id );

                        // if( $GLOBALS['postPageCategoryId'] == DV_category_IsSold_id ) {
                            // $categoryLink = bloginfo('url').'classicandsportscar.ltd.uk/category/classic-cars-sold/'. $GLOBALS['postPageSubCategorySlug'];
                            // $categoryLink = $the_query->parse_tax_query.'/category/classic-cars-sold/'. $GLOBALS['postPageSubCategorySlug'];
                            // $categoryLink = $category->slug;
                        // }

                echo '<a href="' . $categoryLink . '"';
                echo ' title="XXX"';
                echo ' class="list-group-item">';
                echo $GLOBALS['postPageSubCategoryName'].' SOLD';            
                echo ' ('.$count_IsForSale.')';
                echo '</a>';
            }
            

            echo '</div>';
            echo '</aside>';
            echo '<hr>';
            wp_reset_postdata();

        } else {
            $categories = get_categories( $args );

            echo '<h1>'.$GLOBALS['sidebarCategoryListTitle'].'</h1>';

            if($categories) {
                echo '<aside id="product-categories" class="widget widget_product-categories">';        
                echo '<div class="list-group">';

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
                        $categoryLink = get_category_link( $category->term_id );

                        if( $GLOBALS['postPageCategoryId'] == DV_category_IsSold_id ) {
                            // $categoryLink = './category/classic-cars-sold/'. $category->slug;
                            $categoryLink = $category->slug;
                        }
                        echo '<a href="' . $categoryLink . '"';
                        echo ' title="' . sprintf( __( "View all posts in %s" ), $category->name ) . '"';
                        echo ' class="list-group-item">';
                        echo $category->name;
                        
                        if( $showCategoryCount ) echo ' ('.$count.')';
                        echo '</a>';

                        // if($category->name == 'Ferrari') {
                        //     var_dump($category);
                        // }
                    }                
                }
                echo '</div>';
                echo '</aside>';
                echo '<hr>';

                $args = [
                    'post_type'   => 'post',
                    'cat' => 2,
                    'category__not_in' => DV_category_IsSold_id
                ];
                $count = get_term_post_count( 'category', 'all', $args );
                echo $count.' / '.$totalCount;
            }
        }
        /* (END) if on subcategory page */        
    }
?>