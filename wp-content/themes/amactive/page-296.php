<?php get_header(); ?>

<div class="row">
    <div class="col-md-3">
        <?php get_sidebar(); ?>
    </div>

    <div class="col-md-9">

    <h1>PAGE-296</h1>
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
</div>

<?php get_footer(); ?>