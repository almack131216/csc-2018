<?php
    $wp_query->set( 'posts_per_page', 9 );
    get_header();
?>

<div class="row">
    <div class="hidden-md-down col-md-4 col-lg-3 col-no-padding">
        <?php get_sidebar(); ?>
    </div>

    <div class="col-md-8 col-lg-9">
        <?php

            amactive_debug('FILE: index.php');
            amactive_debug('GV pageType: '.$GLOBALS['pageType']);
            amactive_debug('GV postPageCategoryId: '.$GLOBALS['postPageCategoryId']);
            amactive_debug('GV postPageCategoryName: '.$GLOBALS['postPageCategoryName']);

            if ($GLOBALS['postPageSubCategoryId']) :
                amactive_debug('GV postPageSubCategoryId: '.$GLOBALS['postPageSubCategoryId']);
                amactive_debug('GV postPageSubCategoryName: '.$GLOBALS['postPageSubCategoryName']);
            endif;
            // amactive_debug('VAR_DUMP: '.var_dump($GLOBALS['page_object']));            

            //echo '?? > switch cat: '.$GLOBALS['postPageCategoryId'].' -> '.DV_category_IsSold_id;
            if( have_posts() ):
                // if ( is_page() || is_single() ):
                if ( $GLOBALS['pageType'] == 'page' || $GLOBALS['pageType'] == 'single' ):
                    // var_dump( get_post() );

                    the_post();
                    get_template_part('content', get_post_format());
                    
                    
                    // $debugCount = 0;
                    // while ( have_posts() ):
                    // $debugCount++;
                    // amactive_debug('$debugCount: '.$debugCount);
                    //     the_post();                    
                        
                    // endwhile;
                    // get_template_part('content', get_post_format());

                else:

                    // $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
                    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
                    echo '<br><strong>PAGED:</strong>'.$paged;

                    $cpt_sale_status = 1;
                    $excludeCats = array(DV_category_IsSold_id, DV_category_News_id, DV_category_Testimonials_id, DV_category_Press_id);

                    // echo '??? > switch cat: '.$GLOBALS['postPageCategoryId'].' -> '.DV_category_IsSold_id;

                    if($GLOBALS['postPageCategoryId'] == DV_category_IsSold_id) {
                        //echo '?????? > switch cat: '.$GLOBALS['postPageCategoryId'].' -> '.DV_category_IsSold_id;
                        $excludeCats = array(DV_category_News_id, DV_category_Testimonials_id, DV_category_Press_id);                      
                        $GLOBALS['postPageCategoryId'] = DV_category_IsForSale_id;
                        $cpt_sale_status = 2;
                    }

                    $lookInCats = array($GLOBALS['postPageCategoryId']);
                    if($GLOBALS['postPageSubCategoryId']){                            
                        $lookInCats = array($GLOBALS['postPageCategoryId'], $GLOBALS['postPageSubCategoryId']);                            
                    }

                    if ( $GLOBALS['postPageCategoryId'] != DV_category_IsSold_id ) {
                        $lookInCats = array(2, 38);
                        amactive_debug('FOR SALE: '.$GLOBALS['postPageCategoryId'].' -> '.$GLOBALS['postPageSubCategoryId']);

                        //REF: https://wordpress.stackexchange.com/questions/273523/include-posts-from-some-categories-while-excluding-from-others
                        $argsXXX = array(
                            'tax_query' => array(
                                'relation' => 'AND', // logical relationship between taxonomy arrays
                                array( // subcategories to exclude
                                    'taxonomy'      => 'category',
                                    'field'         => 'term_id',
                                    'terms'         => $excludeCats,
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

                        $args = array(                             
                            'category'   => $lookInCats,
                            // 'category__in'   => array(2, 38),
                            'category__not_in'   => $excludeCats                           

                        );

                        if($GLOBALS['postPageSubCategoryId']){
                            $args2 = array( // subcategories to exclude
                                'taxonomy'      => 'category',
                                'field'         => 'term_id',
                                'terms'         => $GLOBALS['postPageSubCategoryId'],
                            );

                            array_push($argsXXX['tax_query'], $args2);
                        }

                    } else if ( $GLOBALS['postPageCategoryId'] == DV_category_IsSold_id ) {
                        
                        amactive_debug('SOLD: '.$GLOBALS['postPageCategoryId'].' > '.$GLOBALS['postPageSubCategoryId']);

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

                    $args2 = array(
                        'paged' => $paged,
                        'post_type' => 'post',
                        'posts_per_page' => 12,
                        'orderby' => 'post_date',
                        'order' => 'DESC',
                        'meta_query' => array(
                            'relation' => 'AND',
                            array(
                                'key' => '_thumbnail_id',
                                'compare' => 'EXISTS'
                            )
                        )
                    );
                    $args += $args2;

                    
                    
                    $query = new WP_Query( $args );

                    if( $query->have_posts() ){
                        echo '<div class="row portfolio-wrap">';
                        while ( $query->have_posts() ):
                            $query->the_post();                    
                            echo '<div class="col-lg-4 col-md-4 col-sm-6 portfolio-item">';
                            get_template_part('content-grid-item', get_post_format());
                            echo '</div>';               
                        endwhile;
                        // REF: https://developer.wordpress.org/themes/functionality/pagination/
                        echo '<p>???'.wpbeginner_numeric_posts_nav().'</p>';
                        wp_pagenavi();
                        echo '</div>';


                    } else {
                        // no posts found
                    }
                    
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