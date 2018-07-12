<?php get_header(); ?>
<?php get_sidebar(); ?>

<div class="contentMiddle" id="SpanRight">
    <h1>ABOUT US</h1>
    <?php    
        if( have_posts() ):
            while ( have_posts() ): the_post(); ?>
                <h3><?php the_content();?></h3>
                <hr>
            <?php
            endwhile;
        endif;
    ?>
</div>

<?php get_footer(); ?>