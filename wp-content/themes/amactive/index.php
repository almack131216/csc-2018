<?php

    if(!empty($cat)){
        $isPostsPage = true;
        $postPageCategoryId = get_query_var('cat');

        $page_object = get_queried_object();
        $postPageCategoryId = $page_object->term_id;
        $postPageCategoryName = $page_object->cat_name;
        if( $page_object->category_parent ){
            $thisCat = get_category($page_object->category_parent);
            $postPageCategoryId = $page_object->category_parent;
            $postPageCategoryName = $thisCat->name;
            $postPageSubCategoryId = $page_object->term_id;
            $postPageSubCategoryName = $page_object->name;

            if(strpos($_SERVER['REQUEST_URI'], 'classic-cars-sold') !== false){
                $postPageCategoryId = 38;
                $postPageCategoryName = 'XXX SOLD XXX';
            }

        }
    }

    get_header();
?>

<div class="row">
    <div class="col-md-3">
        <?php get_sidebar(); ?>
    </div>

    <div class="col-md-9">
        <?php

            echo '<h3>CAT ID: '.$postPageCategoryId.' (index.php)</h3>';
            echo '<h4>CAT NAME: '.$postPageCategoryName.'</h4>';
            if ($postPageSubCategoryId) {
                echo '<h3>SUBCAT ID: '.$postPageSubCategoryId.'</h3>';
                echo '<h4>SUBCAT NAME: '.$postPageSubCategoryName.'</h4>';
            }
            echo '<h5>VAR_DUMP: '.var_dump($page_object).'</h5>';            

            if( have_posts() ):
                if ( is_page() || is_single()):
                    the_post(); 
                    get_template_part('content', get_post_format());
                else:

                    if ( $postPageCategoryId != 38 ) {
                        //REF: https://wordpress.stackexchange.com/questions/273523/include-posts-from-some-categories-while-excluding-from-others
                        $args = array(
                            'tax_query' => array(
                                'relation' => 'AND', // logical relationship between taxonomy arrays
                                array( // subcategories to exclude
                                    'taxonomy'      => 'category',
                                    'field'         => 'term_id',
                                    'terms'         => array(38),
                                    'operator'      => 'NOT IN', // exclude
                                    'post_parent'   => 0 // top level only
                                ),
                                array( // categories to include
                                    'taxonomy'      => 'category',
                                    'field'         => 'term_id',
                                    'terms'         => array($postPageCategoryId),
                                )
                            )
                        );
                    } else if ( $postPageCategoryId == 38 ) {

                        $lookInCats = array($postPageCategoryId);

                        if($postPageSubCategoryId){
                            echo '??? Append to args > '.$postPageSubCategoryId;
                            $lookInCats = array($postPageCategoryId, $postPageSubCategoryId);                            
                        }

                        $args = array(
                            'tax_query' => array(                                
                                array( // categories to include
                                    'taxonomy'      => 'category',
                                    'field'         => 'term_id',
                                    'terms'         => $lookInCats,
                                    'operator' => 'AND'
                                )                                
                            )
                        );
                        
                    }

                    $args2 = array('posts_per_page' => 12);
                    $args += $args2;
                    
                    $query = new WP_Query( $args );

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