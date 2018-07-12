<?php get_header(); ?>
<?php get_sidebar(); ?>

<div class="contentMiddle" id="SpanRight">
    <?php
        if( have_posts() ):
            while ( have_posts() ): the_post(); ?>
                <h3><?php the_title();?></h3>
                <div class="thumbnail-image"><?php the_post_thumbnail( 'thumbnail' ); ?></div>
                <h3><?php the_content();?></h3>
                <small><?php the_category();?></small>
                <hr>
            <?php
            endwhile;
        endif;
    ?>
</div>

<?php get_footer(); ?>