<?php
    if(!empty($cat)){
        $postPageCategoryId = get_query_var('cat');      
    }

    dynamic_sidebar( 'sidebar-1' );
    // wp_nav_menu( array('theme_location' => 'primary') );
?>

<?php

    echo '<h3>CAT: '.$postPageCategoryId.' (sidebar.php)</h3>';
    
    if ( $postPageCategoryId == 2 ) {
        $args = array(
            'orderby'   => 'name', 
            'order'     => 'ASC',
            'child_of' => 2,
            'exclude' => 38
        );
        $args2 = array('category__not_in' => 38);
        $saleStatus = 1;
        $showProductCats = true;
        $titleProductCats = 'Classic Cars For Sale';
        // dynamic_sidebar( 'custom-side-bar' );
    } else if ( $postPageCategoryId == 38 ) {
        $args = array(
            'child_of' => 2,
            'category' => 38
        );
        $args2 = array('category__in' => 38);
        $saleStatus = 1;
        $showProductCats = true;
        $titleProductCats = 'Classic Cars Sold';
        // dynamic_sidebar( 'custom-side-bar-sold' );
    }
        
?>

<?php

    if ($showProductCats) {
        $categories = get_categories( $args );
    }

    $showCategoryCount = false;
    $totalCount = 0;
echo '<h1>'.$titleProductCats.'</h1>';
    if($categories) {
        echo '<aside id="product-categories" class="widget widget_product-categories">';
        
        echo '<div class="list-group">';

        foreach($categories as $category) {
            $args = [
                'post_type' => 'post',
                'cat' => $category->term_id
            ];
            $args += $args2;
            $count = get_term_post_count( 'category', $category->term_id, $args );
            $totalCount += $count;

            if ( $count ) {
                echo '<a href="' . get_category_link( $category->term_id ) . '"';
                echo ' title="' . sprintf( __( "View all posts in %s" ), $category->name ) . '"';
                echo ' class="list-group-item">';
                echo $category->name;
                echo '</a>';
                //if( $showCategoryCount ) echo ' ('.$count.')';
            }                
        }
        echo '</div>';
    }
    echo '</aside>';
    echo '<hr>';

    $args = [
        'post_type'   => 'post',
        'cat' => 2,
        'category__not_in' => 38
    ];
    $count = get_term_post_count( 'category', 'all', $args );
    echo $count.' / '.$totalCount;
?>