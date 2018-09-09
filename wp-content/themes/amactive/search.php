<?php
    //REF: https://www.shift8web.ca/2016/03/customize-wordpress-search-results-page/
    get_header();
    amactive_debug('pageType: '.$GLOBALS['pageType']);
?>

<div class="row bg-posts">
    <div class="hidden-md-down col-md-3 col-sidebar">
        <?php get_sidebar(); ?>
    </div>

    <div class="col-sm-12 col-md-9 col-posts-parent">
        <?php

            $bodyContent = '';
            $bodyContent .= amactive_breadcrumb();

            amactive_debug('CAT ID: '.DV_category_IsForSale_id.' (index.php)');
            amactive_debug('CAT NAME: '.$GLOBALS['postPageCategoryName']);
            if ($GLOBALS['postPageSubCategoryId']) :
                amactive_debug('SUBCAT ID: '.$GLOBALS['postPageSubCategoryId']);
                amactive_debug('SUBCAT NAME: '.$GLOBALS['postPageSubCategoryName']);
            endif;
            // amactive_debug('VAR_DUMP: '.var_dump($GLOBALS['page_object']));

            // $bodyContent .= '<div class="row row-header-wrap">';
            //     $bodyContent .= '<div class="col-xs-12">';
            //         $bodyContent .= '<h1 class="page-header">';
            //             $bodyContent .= '<span class="search-page-title">';
            //             $bodyContent .= 'Search for "<span>'. get_search_query() . '</span>"...';
            //             $bodyContent .= '</span>';
            //         $bodyContent .= '</h1>';
            //     $bodyContent .= '</div>'."\r\n";
            // $bodyContent .= '</div>'."\r\n";


            $bodyContent .= '<div class="row row-portfolio-wrap has-title has-posts">';
                $bodyContent .= '<div class="col-xs-12">';
                    $bodyContent .= '<h1 class="page-header">';
                        $bodyContent .= '<span class="search-page-title">';
                        $bodyContent .= 'Search for "<span>'. get_search_query() . '</span>"...';
                        $bodyContent .= '</span>';
                    $bodyContent .= '</h1>';
                $bodyContent .= '</div>'."\r\n";
            echo $bodyContent;

            // $bodyContent .= '<div class="search-form-wrap" id="ss-search-page-form">';
            //     $bodyContent .= get_search_form();
            // $bodyContent .= '</div>';
            

            if ( have_posts() ) :                
                /* Start the Loop */
                while ( have_posts() ) : the_post();
                    
                    echo '<div class="col-portfolio-item is-light item-is-row col-xs-12 col-sm-12 col-md-12 col-lg-12">';
                    get_template_part('content-grid-item', get_post_format());
                    echo '</div>'."\r\n";

                endwhile;
                //the_posts_navigation();
            else :
                //get_template_part( 'template-parts/content', 'none' );
            endif;

            echo '</div>'."\r\n";
        ?>

    </div>
    <!--// (END) right //-->
</div>
<!--// (END) row //-->

<?php get_footer(); ?>