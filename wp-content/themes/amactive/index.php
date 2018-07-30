<?php
    get_header();

    if($GLOBALS['pageType']){
        echo '<h7>pageType: '.$GLOBALS['pageType'].'</h7>';
    }
?>

<div class="row">
    <div class="col-md-3">
        <?php get_sidebar(); ?>
    </div>

    <div class="col-md-9">
        <?php

            echo '<h3>CAT ID: '.DV_category_IsForSale_id.' (index.php)</h3>';
            echo '<h4>CAT NAME: '.$GLOBALS['postPageCategoryName'].'</h4>';
            if ($GLOBALS['postPageSubCategoryId']) :
                echo '<h3>SUBCAT ID: '.$GLOBALS['postPageSubCategoryId'].'</h3>';
                echo '<h4>SUBCAT NAME: '.$GLOBALS['postPageSubCategoryName'].'</h4>';
            endif;
            echo '<h5>VAR_DUMP: '.var_dump($GLOBALS['page_object']).'</h5>';            

            if( have_posts() ):
                // if ( is_page() || is_single() ):
                if ( $GLOBALS['pageType'] == 'page' || $GLOBALS['pageType'] == 'single' ):
                    // var_dump( get_post() );
                    
                    

                    the_post();
                    get_template_part('content', get_post_format());

                    
                else:

                    $lookInCats = array($GLOBALS['postPageCategoryId']);
                    if($GLOBALS['postPageSubCategoryId']){                            
                        $lookInCats = array($GLOBALS['postPageCategoryId'], $GLOBALS['postPageSubCategoryId']);                            
                    }

                    if ( $GLOBALS['postPageCategoryId'] != DV_category_IsSold_id ) {

                        echo '!!! FOR SALE | '.$GLOBALS['postPageCategoryId'].' -> '.$GLOBALS['postPageSubCategoryId'];

                        //REF: https://wordpress.stackexchange.com/questions/273523/include-posts-from-some-categories-while-excluding-from-others
                        $args = array(
                            'tax_query' => array(
                                'relation' => 'AND', // logical relationship between taxonomy arrays
                                array( // subcategories to exclude
                                    'taxonomy'      => 'category',
                                    'field'         => 'term_id',
                                    'terms'         => DV_category_IsSold_id,
                                    'operator'      => 'NOT IN', // exclude
                                    'post_parent'   => 0 // top level only
                                ),
                                array( // categories to include
                                    'taxonomy'      => 'category',
                                    'field'         => 'term_id',
                                    'terms'         => $lookInCats,
                                )
                            )
                        );

                        if($GLOBALS['postPageSubCategoryId']){
                            $args2 = array( // subcategories to exclude
                                        'taxonomy'      => 'category',
                                        'field'         => 'term_id',
                                        'terms'         => $GLOBALS['postPageSubCategoryId'],
                                    );

                            array_push($args['tax_query'], $args2);
                        }

                    } else if ( $GLOBALS['postPageCategoryId'] == DV_category_IsSold_id ) {
                        
                        echo '??? SOLD | '.$GLOBALS['postPageCategoryId'].' > '.$GLOBALS['postPageSubCategoryId'];

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
    <!--// (END) right //-->
</div>
<!--// (END) row //-->

<?php get_footer(); ?>