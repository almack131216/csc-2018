<?php

    dynamic_sidebar( 'sidebar-1' );
    // wp_nav_menu( array('theme_location' => 'primary') );
?>

<?php

    echo '<h3>CAT: '.$GLOBALS['postPageCategoryId'].' (sidebar.php)</h3>';
    
    if ( $GLOBALS['postPageCategoryId'] == $GLOBALS['categoryIdIsForSale']) {
        $args = array(
            'orderby'   => 'name', 
            'order'     => 'ASC',
            'child_of' => 2,
            'exclude' => $GLOBALS['categoryIdIsSold']
        );
        $args2 = array('category__not_in' => $GLOBALS['categoryIdIsSold']);
        // dynamic_sidebar( 'custom-side-bar' );
    } else if ( $GLOBALS['postPageCategoryId'] == $GLOBALS['categoryIdIsSold'] ) {
        $args = array(
            'child_of' => 2
        );
        $args2 = array('category__in' => $GLOBALS['categoryIdIsSold']);
        // dynamic_sidebar( 'custom-side-bar-sold' );
    }
        
?>

<?php

    if ($GLOBALS['showProductCats']) {
        $categories = get_categories( $args );

        $showCategoryCount = true;
        $totalCount = 0;
        echo '<h1>'.$GLOBALS['postPageTitle'].'</h1>';

        if($categories) {
            echo '<aside id="product-categories" class="widget widget_product-categories">';        
            echo '<div class="list-group">';

            foreach($categories as $category) {
                $args = [
                    'post_type' => 'post',
                    'cat' => $category->term_id,
                    // 'category__not_in' => $GLOBALS['categoryIdIsSold']
                ];
                $args += $args2;
                $count = get_term_post_count( 'category', $category->term_id, $args );
                $totalCount += $count;

                if ( $count ) {
                    $categoryLink = get_category_link( $category->term_id );

                    if( $GLOBALS['postPageCategoryId'] == $GLOBALS['categoryIdIsSold'] ) {
                        // $categoryLink = './category/classic-cars-sold/'. $category->slug;
                        $categoryLink = $category->slug;
                    }
                    echo '<a href="' . $categoryLink . '"';
                    echo ' title="' . sprintf( __( "View all posts in %s" ), $category->name ) . '"';
                    echo ' class="list-group-item">';
                    echo $category->name;
                    
                    if( $showCategoryCount ) echo ' ('.$count.')';
                    echo '</a>';

                    if($category->name == 'Ferrari') {
                        var_dump($category);
                    }
                }                
            }
            echo '</div>';
            echo '</aside>';
            echo '<hr>';
        }
        

        $args = [
            'post_type'   => 'post',
            'cat' => 2,
            'category__not_in' => $GLOBALS['categoryIdIsSold']
        ];
        $count = get_term_post_count( 'category', 'all', $args );
        echo $count.' / '.$totalCount;
    }
?>