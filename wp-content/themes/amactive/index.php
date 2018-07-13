<?php get_header(); ?>
<?php get_sidebar(); ?>

<div class="contentMiddle" id="SpanRight">
    <?php
        if( have_posts() ):
            echo '<ul class="itemBox">';
            while ( have_posts() ): the_post();
                if ( is_page() || is_single()) {
                    get_template_part('content', get_post_format());
                } else {
                    get_template_part('content-grid-item', get_post_format());
                }                
            endwhile;
            echo '</ul>';
        endif;
    ?>
</div>

<?php get_footer(); ?>