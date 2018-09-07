<?php
    //REF: https://www.shift8web.ca/2016/03/customize-wordpress-search-results-page/
    get_header();

    if($GLOBALS['pageType']){
        amactive_debug('pageType: '.$GLOBALS['pageType']);
    }
?>

<div class="row bg-posts">
    <div class="hidden-md-down col-md-3 col-sidebar">
        <?php get_sidebar(); ?>
    </div>

    <div class="col-sm-12 col-md-9">
        <?php
            echo amactive_breadcrumb();

            amactive_debug('CAT ID: '.DV_category_IsForSale_id.' (index.php)');
            amactive_debug('CAT NAME: '.$GLOBALS['postPageCategoryName']);
            if ($GLOBALS['postPageSubCategoryId']) :
                amactive_debug('SUBCAT ID: '.$GLOBALS['postPageSubCategoryId']);
                amactive_debug('SUBCAT NAME: '.$GLOBALS['postPageSubCategoryName']);
            endif;
            // amactive_debug('VAR_DUMP: '.var_dump($GLOBALS['page_object']));
        ?>

        <div class="row row-portfolio-wrap row-portfolio-list">
            <h1 class="page-header">
                <span class="search-page-title"><?php $wp_query->post_count.printf( esc_html__( 'Results for "%s', stackstar ), '<span>' . get_search_query() . '</span>"' ); ?></span>
            </h1><!-- .page-header -->

            <!--<div class="search-form-wrap" id="ss-search-page-form">
                <?php get_search_form(); ?>
            </div>-->
        </div>


        <?php if ( have_posts() ) : ?>
            <div class="row row-portfolio-wrap row-portfolio-list has-posts">
            <?php /* Start the Loop */ ?>
            <?php while ( have_posts() ) : the_post(); ?>
                
            <?php
                echo '<div class="col-md-12 col-portfolio-item is-light item-is-row">';
                // get_template_part('content-grid-item', get_post_format());
                get_template_part('content', 'grid-item');
                // if( has_post_thumbnail() ):                                    
                //     the_post_thumbnail( 'post-thumbnail', array(
                //         'class' => 'card-img-top img-fluid'
                //         )
                //     );
                // else:
                //     echo 'xxx';
                // endif;
                echo '</div>';
            ?>
            <!--<div class="col-md-9">
                <span class="search-post-title"><?php the_title(); ?></span>
                <span class="search-post-excerpt"><?php the_excerpt(); ?></span>
                <span class="search-post-link"><a href="<?php the_permalink(); ?>"><?php the_permalink(); ?></a></span>
            </div>-->
                
            <?php endwhile; ?>
            </div>
            <?php //the_posts_navigation(); ?>

        <?php else : ?>

            <?php //get_template_part( 'template-parts/content', 'none' ); ?>

        <?php endif; ?>

    </div>
    <!--// (END) right //-->
</div>
<!--// (END) row //-->

<?php get_footer(); ?>