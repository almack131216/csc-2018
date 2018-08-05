<?php
    //REF: https://www.shift8web.ca/2016/03/customize-wordpress-search-results-page/
    get_header();

    if($GLOBALS['pageType']){
        amactive_debug('pageType: '.$GLOBALS['pageType']);
    }
?>

<div class="row">
    <div class="col-md-3 col-no-padding">
        <?php get_sidebar(); ?>
    </div>

    <div class="col-md-9">
        <?php
            amactive_debug('CAT ID: '.DV_category_IsForSale_id.' (index.php)');
            amactive_debug('CAT NAME: '.$GLOBALS['postPageCategoryName']);
            if ($GLOBALS['postPageSubCategoryId']) :
                amactive_debug('SUBCAT ID: '.$GLOBALS['postPageSubCategoryId']);
                amactive_debug('SUBCAT NAME: '.$GLOBALS['postPageSubCategoryName']);
            endif;
            // amactive_debug('VAR_DUMP: '.var_dump($GLOBALS['page_object']));
        ?>


        <div class="search-container">
            <h1 class="page-header">
                        <span class="search-page-title"><?php printf( esc_html__( 'Search Results for: %s', stackstar ), '<span>' . get_search_query() . '</span>' ); ?></span>
                    </h1><!-- .page-header -->

                <div class="search-page-form" id="ss-search-page-form"><?php get_search_form(); ?></div>
        
                <?php if ( have_posts() ) : ?>
        
                    
        
                    <?php /* Start the Loop */ ?>
                    <?php while ( have_posts() ) : the_post(); ?>
                        <div class="row">
                            <?php
                                echo '<div class="col-md-3">';
                                if( has_post_thumbnail() ):                                    
                                    the_post_thumbnail( 'post-thumbnail', array(
                                        'class' => 'card-img-top img-fluid'
                                        )
                                    );
                                else:
                                    echo 'xxx';
                                endif;
                                echo '</div>';
                            ?>
                            <div class="col-md-9">
                                <span class="search-post-title"><?php the_title(); ?></span>
                                <span class="search-post-excerpt"><?php the_excerpt(); ?></span>
                                <span class="search-post-link"><a href="<?php the_permalink(); ?>"><?php the_permalink(); ?></a></span>
                            </div>
                        </div>
                    <?php endwhile; ?>
        
                    <?php //the_posts_navigation(); ?>

                <?php else : ?>
        
                    <?php //get_template_part( 'template-parts/content', 'none' ); ?>
        
                <?php endif; ?>
        </div>

    </div>
    <!--// (END) right //-->
</div>
<!--// (END) row //-->

<?php get_footer(); ?>