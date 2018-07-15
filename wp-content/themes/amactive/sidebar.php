<?php
    dynamic_sidebar( 'sidebar-1' );
    // wp_nav_menu( array('theme_location' => 'primary') );
?>

<?php
    $showCategoryCount = false;
    $args = array(
        'child_of' => 2,
        'exclude' => 38
    );
    $categories = get_categories( $args );
    $totalCount = 0;

    if($categories) {
        echo '<div class="list-group">';

        foreach($categories as $category) {
            $args = [
                'post_type' => 'post',
                'meta_key' => 'csc_car_sale_status',
                'meta_value' => 1,
                'cat' => $category->term_id
            ];
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

    echo '<hr>';

    $args = [
        'post_type'   => 'post',
        'cat' => 2,
        'meta_key' => 'csc_car_sale_status',
        'meta_value' => 1
    ];
    $count = get_term_post_count( 'category', 'all', $args );
    echo $count.' / '.$totalCount;
?>