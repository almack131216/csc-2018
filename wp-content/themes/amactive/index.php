<?php

    if(!empty($cat)){
        $isPostsPage = true;
        $postPageCategoryId = get_query_var('cat');      
    }

    get_header();
?>

<div class="row">
    <div class="col-md-3">
        <?php get_sidebar(); ?>
    </div>

    <div class="col-md-9">
        <?php

            echo '<h3>CAT: '.$postPageCategoryId.' (index.php)</h3>';

            //REF: https://wordpress.stackexchange.com/questions/273523/include-posts-from-some-categories-while-excluding-from-others
            $args = array(
                'tax_query' => array(
                    // 'relation' => 'AND', // logical relationship between taxonomy arrays
                    // array( // subcategories to exclude
                    //     'taxonomy'      => 'category',
                    //     'field'         => 'term_id',
                    //     'terms'         => array(38),
                    //     'operator'      => 'NOT IN', // exclude
                    //     'post_parent'   => 0 // top level only
                    // ),
                    array( // categories to include
                        'taxonomy'      => 'category',
                        'field'         => 'term_id',
                        'terms'         => array($postPageCategoryId),
                        // 'include_children' => false
                    )
                ),
                'posts_per_page' => 10,
                // more lines if needed
            );
            $query = new WP_Query( $args );

            if( $query->have_posts() ):
                if ( is_page() || is_single()):
                    the_post(); 
                    get_template_part('content', get_post_format());
                else:
                    echo '<div class="row xxx" style="padding-top:30px;">';
                    while ( $query->have_posts() ):
                        $query->the_post();                    
                        echo '<div class="col-lg-4 col-md-4 col-sm-6 portfolio-item">';
                        get_template_part('content-grid-item', get_post_format());
                        echo '</div>';               
                    endwhile;
                    echo '</div>';
                endif;

                // echo '<div class="row">';
                
                // echo '</div>';
            endif;
        ?>
    </div>
</div>

<?php get_footer(); ?>