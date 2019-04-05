<?php
    get_header();
?>

<div class="row bg-posts">
    <div class="hidden-md-down col-md-3 col-sidebar">
        <?php get_sidebar(); ?>
    </div>

    <div class="col-sm-12 col-md-9 col-posts-parent">
        <?php
            echo amactive_breadcrumb();

            amactive_debug('FILE: index.php');
            amactive_debug('GV pageType: '.$GLOBALS['pageType']);
            amactive_debug('GV postPageCategoryId: '.$GLOBALS['postPageCategoryId']);
            amactive_debug('GV postPageCategoryName: '.$GLOBALS['postPageCategoryName']);
            amactive_debug('GV postPageCategorySlug: '.$GLOBALS['postPageCategorySlug']);

            if ($GLOBALS['postPageSubCategoryId']) :
                amactive_debug('GV postPageSubCategoryId: '.$GLOBALS['postPageSubCategoryId']);
                amactive_debug('GV postPageSubCategoryName: '.$GLOBALS['postPageSubCategoryName']);
                amactive_debug('GV postPageSubCategorySlug: '.$GLOBALS['postPageSubCategorySlug']);
            endif;
            // amactive_debug('VAR_DUMP: '.var_dump($GLOBALS['page_object']));            

            //echo '?? > switch cat: '.$GLOBALS['postPageCategoryId'].' -> '.DV_category_IsSold_id;
            if( have_posts() ):
                // if ( is_page() || is_single() ):
                if ( $GLOBALS['pageType'] == 'page' || $GLOBALS['pageType'] == 'single' ):
                    // var_dump( get_post() );
                    the_post();

                    if( amactive_post_is_classified($post->ID) || amactive_post_is_news($post->ID)) {
                        get_template_part('content', get_post_format());
                    } else{

                        // $bodyContent .= '<div class="row row-header-wrap">';
                        //     $bodyContent .= '<div class="col-xs-12">';
                        //         $bodyContent .= '<h1 class="page-header">';
                        //             $bodyContent .= $post->post_title;
                        //             // $bodyContent .= '<span class="search-page-title">';
                        //             // $bodyContent .= 'Search for "<span>'. get_search_query() . '</span>"...';
                        //             // $bodyContent .= '</span>';
                        //         $bodyContent .= '</h1>';
                        //     $bodyContent .= '</div>'."\r\n";
                        // $bodyContent .= '</div>'."\r\n";

                        $bodyContent .= '<div class="row row-page-text">';
                            $bodyContent .= '<div class="col-xs-12 col-page-text">';
                                $bodyContent .= '<h1 class="page-header">';
                                    $bodyContent .= $post->post_title;
                                    // $bodyContent .= '<span class="search-page-title">';
                                    // $bodyContent .= 'Search for "<span>'. get_search_query() . '</span>"...';
                                    // $bodyContent .= '</span>';
                                $bodyContent .= '</h1>';
                                // REF: https://stackoverflow.com/questions/22270147/wordpress-shortcode-doesnt-work-when-getting-post-content-using-function-get-p
                                $bodyContent .= apply_filters( 'the_content', get_post_field('post_content', $post->ID) );
                            $bodyContent .= '</div>'."\r\n";
                        $bodyContent .= '</div>'."\r\n";

                        echo $bodyContent;

                    }
                    
                else:

                    $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 0;
                    $postsPerPage = 12;// see WP settings>reading
                    $postOffset = $paged * $postsPerPage;
                    amactive_debug('<strong>PAGED:</strong>'.$paged);

                    $excludeAllCats = array(DV_category_IsForSale_id, DV_category_IsSold_id, DV_category_News_id, DV_category_Testimonials_id, DV_category_Press_id);

                    $itemStyle = 'grid';
                    if( $GLOBALS['postPageCategoryId'] == DV_category_IsForSale_id ){                        
                        $excludeCats = array_diff($excludeAllCats, array(DV_category_IsForSale_id));
                    }elseif( $GLOBALS['postPageCategoryId'] == DV_category_IsSold_id ){
                        $excludeCats = array_diff($excludeAllCats, array(DV_category_IsForSale_id, DV_category_IsSold_id));
                    }elseif( $GLOBALS['postPageCategoryId'] == DV_category_News_id ){
                        $itemStyle = 'row';
                        $excludeCats = array_diff($excludeAllCats, array(DV_category_News_id));
                    }elseif( $GLOBALS['postPageCategoryId'] == DV_category_Press_id ){
                        $itemStyle = 'row';
                        $excludeCats = array_diff($excludeAllCats, array(DV_category_Press_id));
                    }elseif( $GLOBALS['postPageCategoryId'] == DV_category_Testimonials_id ){
                        $itemStyle = 'row';
                        $excludeCats = array_diff($excludeAllCats, array(DV_category_Testimonials_id));
                    }

                    $lookInCats = array($GLOBALS['postPageCategoryId']);

                    /* IF subcategory is set... */
                    // if($GLOBALS['postPageSubCategoryId']){                            
                    //     $lookInCats = array($GLOBALS['postPageCategoryId'], $GLOBALS['postPageSubCategoryId']);                            
                    // }

                    /* IF not on SOLD category... */
                    // if ( $GLOBALS['postPageCategoryId'] != DV_category_IsSold_id ) {
                        $lookInCats = array( $GLOBALS['postPageCategoryId'] );
                        amactive_debug('FOR SALE: '.$GLOBALS['postPageCategoryId'].' -> '.$GLOBALS['postPageSubCategoryId']);

                        //REF: https://wordpress.stackexchange.com/questions/273523/include-posts-from-some-categories-while-excluding-from-others
                        $args = array(
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

                        // $args = array(                             
                        //     'category'   => $lookInCats,
                        //     // 'category__in'   => array(2, 38),
                        //     'category__not_in'   => $excludeCats                           

                        // );

                        if($GLOBALS['postPageSubCategoryId']){
                            $args_subcategory = array( // subcategories to exclude
                                'taxonomy'      => 'category',
                                'field'         => 'term_id',
                                'terms'         => $GLOBALS['postPageSubCategoryId'],
                            );

                            array_push($args['tax_query'], $args_subcategory);
                        }

                    // amactive_debug('LOOKINCATS: '.print_r($lookInCats));
                    // amactive_debug('EXCLUDECATS: '.print_r($excludeCats));

                    $args2 = array(
                        // 'ignore_sticky_posts' => 1,
                        'paged' => $paged,    
                        // 'posts_per_page' => $postsPerPage,                    
                        // 'offset'          => $postOffset,
                        'post_type' => 'post',
                        'post_status' => array('publish'),
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
                    // remove_all_filters( 'pre_get_posts' );                  
                    $query = new WP_Query( $args );
                    $count = $query->post_count;
                    amactive_debug('COUNT: '.$count.' / '.$GLOBALS['wp_query']->post_count.' / '.$query->max_num_pages);
                    // $query->set('posts_per_page', 12);
                    // echo '<br>'.$GLOBALS['wp_query']->request;

                    if( $query->have_posts() ){
                        // echo '???';
                        echo '<div class="row row-portfolio-wrap has-posts';
                        echo $itemStyle == 'row' ? '' : ' row-flex';                        
                        echo '">';

                        // $showcategoryTitle = true;
                        // if( $showcategoryTitle ){
                        //     $categoryTitle = '<div class="col-xs-12">';
                        //         $categoryTitle .= '<h1 class="page-header">';
                        //             $categoryTitle .= '<span class="search-page-title">';
                        //             $categoryTitle .= 'Category Title';
                        //             $categoryTitle .= '</span>';
                        //         $categoryTitle .= '</h1>';
                        //     $categoryTitle .= '</div>'."\r\n";
                        //     echo $categoryTitle;
                        // }
                        

                        // $debugCount = 0;
                        // echo '$debugCount: '.$debugCount;
                        while ( $query->have_posts() ):
                            // echo '<br>$debugCount: '.$debugCount;
                            $query->the_post();    
                            // echo get_the_title();                
                            echo '<div class="col-xs-12 col-portfolio-item';
                            echo $itemStyle == 'row' ? ' is-light item-is-row' : ' item-is-grid col-lg-4 col-md-4 col-sm-6';                            
                            echo '">';
                                get_template_part('content-grid-item', get_post_format());
                            echo '</div>';               
                        endwhile;
                        // REF: https://developer.wordpress.org/themes/functionality/pagination/
                        // echo wpbeginner_numeric_posts_nav();
                        
                        $hasPagination = amactive_pagination( $query->max_num_pages );
                        if( $hasPagination ){
                            echo '<div class="col-xs-12 col-pagination">';
                            echo $hasPagination;
                            echo '</div>'."\r\n";
                        }                         
                        // wp_pagenavi();

                        echo '</div>';


                    } else {
                        echo '<div class="row row-portfolio-wrap no-posts">';
                        echo '<h3>We currently have no '.$GLOBALS['postPageSubCategoryName'].' for sale</h3>';
                        if($GLOBALS['sidebarSubCategoryLinks']) echo $GLOBALS['sidebarSubCategoryLinks'];
                        echo '</div>';
                    }
                    
                endif;
            endif;
        ?>
    </div>
    <!--// (END) right //-->
</div>
<!--// (END) row //-->

<?php get_footer(); ?>