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

            echo '<h3>CAT ID: '.DV_categoryIdIsForSale.' (index.php)</h3>';
            echo '<h4>CAT NAME: '.$GLOBALS['postPageCategoryName'].'</h4>';
            if ($GLOBALS['postPageSubCategoryId']) :
                echo '<h3>SUBCAT ID: '.$GLOBALS['postPageSubCategoryId'].'</h3>';
                echo '<h4>SUBCAT NAME: '.$GLOBALS['postPageSubCategoryName'].'</h4>';
            endif;
            echo '<h5>VAR_DUMP: '.var_dump($GLOBALS['page_object']).'</h5>';
        ?>


        <div class="search-container">
            <section id="primary" class="content-area">
                <main id="main" class="site-main" role="main">
                <div class="search-page-form" id="ss-search-page-form"><?php get_search_form(); ?></div>
        
                <?php if ( have_posts() ) : ?>
        
                    <header class="page-header">
                        <span class="search-page-title"><?php printf( esc_html__( 'Search Results for: %s', stackstar ), '<span>' . get_search_query() . '</span>' ); ?></span>
                    </header><!-- .page-header -->
        
                    <ul>
                    <?php /* Start the Loop */ ?>
                    <?php while ( have_posts() ) : the_post(); ?>
                        <li>
                            <span class="search-post-title"><?php the_title(); ?></span>
                            <span class="search-post-excerpt"><?php the_excerpt(); ?></span>
                            <span class="search-post-link"><a href="<?php the_permalink(); ?>"><?php the_permalink(); ?></a></span>
                        </li>
                    <?php endwhile; ?>
        
                    <?php //the_posts_navigation(); ?>
                    </ul>

                <?php else : ?>
        
                    <?php //get_template_part( 'template-parts/content', 'none' ); ?>
        
                <?php endif; ?>
        
                </main><!-- #main -->
            </section><!-- #primary -->
        </div>

    </div>
    <!--// (END) right //-->
</div>
<!--// (END) row //-->

<?php get_footer(); ?>