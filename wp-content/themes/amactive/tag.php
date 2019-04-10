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



                

        if ( have_posts() ) :

            $bodyContent .= '<div class="row row-portfolio-wrap has-title has-posts">';            

            $bodyContent .= '<div class="col-xs-12">';
            $bodyContent .= '<h1 class="page-header">';
                $bodyContent .= '<span class="search-page-title">';
                $bodyContent .= 'Tagged: "<span>'.single_tag_title( '', false ).'</span>"';
                $bodyContent .= '</span>';
            $bodyContent .= '</h1>';
            $bodyContent .= '</div>'."\r\n";
                
            // Show an optional term description.
            // $term_description = term_description();
            // if ( ! empty( $term_description ) ) :
            //     printf( '<div class="taxonomy-description">%s</div>', $term_description );
            // endif;
            
            echo $bodyContent;
        
            $counter = 1; //Starts counter for post column lay out

            // Start the Loop.
            while ( have_posts() ) : the_post();
                // get_template_part( 'content', get_post_format() );
                echo '<div class="col-portfolio-item is-light item-is-row col-xs-12 col-sm-12 col-md-12 col-lg-12">';
                get_template_part('content-grid-item', get_post_format());
                echo '</div>'."\r\n"; 

                $counter++; //Update the counter
            endwhile;

        else :        
            // If no content, include the "No posts found" template.
            get_template_part( 'content', 'none' );
        endif;    

        echo '</div>'."\r\n";
    ?>

    </div>
    <!--// (END) right //-->
</div>
<!--// (END) row //-->
<?php get_footer(); ?>