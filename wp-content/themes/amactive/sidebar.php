<?php

    dynamic_sidebar( 'sidebar-1' );
    // wp_nav_menu( array('theme_location' => 'primary') );
    echo '<h7>pageType: '.$GLOBALS['pageType'].'</h7>';
    echo '<h3>CAT: '.$GLOBALS['postPageCategoryId'].' (sidebar.php)</h3>';
        
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
        if ( $GLOBALS['postPageCategoryId'] == DV_categoryIdIsForSale) {            
            $args_sale_or_sold = array('category__not_in' => DV_categoryIdIsSold);
            // dynamic_sidebar( 'custom-side-bar' );
        } else if ( $GLOBALS['postPageCategoryId'] == DV_categoryIdIsSold ) {
            // $args_sale_or_sold = array('category__in' => DV_categoryIdIsSold);
            $args_sale_or_sold = array('category__in' => DV_categoryIdIsSold);
            // dynamic_sidebar( 'custom-side-bar-sold' );
        }

        
        /* [?] on subcategory page? */
        if ( $GLOBALS['postPageSubCategoryId'] ) {
            $GLOBALS['sidebarCategoryListTitle'] = '';

            $args = array(
                'cat' => $GLOBALS['postPageSubCategoryId'],
                'category__not_in' => DV_categoryIdIsSold
            );
            $the_query = new WP_Query( $args );
            $count_IsForSale = $the_query->found_posts;

            $args = array(
                'cat' => $GLOBALS['postPageSubCategoryId'],
                'category__in' => DV_categoryIdIsSold
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
                $categoryLink = get_category_link( $GLOBALS['postPageSubCategoryId'] );
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
                        // 'category__not_in' => DV_categoryIdIsSold
                    ];
                    if($args_sale_or_sold) $args += $args_sale_or_sold;
                    $count = get_term_post_count( 'category', $category->term_id, $args );
                    $totalCount += $count;

                    if ( $count ) {
                        $categoryLink = get_category_link( $category->term_id );

                        if( $GLOBALS['postPageCategoryId'] == DV_categoryIdIsSold ) {
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
                    'category__not_in' => DV_categoryIdIsSold
                ];
                $count = get_term_post_count( 'category', 'all', $args );
                echo $count.' / '.$totalCount;
            }
        }
        /* (END) if on subcategory page */        
    }
?>