<?php get_header(); ?>

<div class="row">
    <div class="col-md-3">
        <?php get_sidebar(); ?>
    </div>

    <div class="col-md-9">
        <?php
            if( have_posts() ):
                if ( is_page() || is_single()):
                    the_post(); 
                   get_template_part('content', get_post_format());
                else:
                    echo '<div class="row xxx" style="padding-top:30px;">';
                    while ( have_posts() ): the_post();                    
                        echo '<div class="col-lg-4 col-md-4 col-sm-6 portfolio-item">';
                        get_template_part('content-grid-item', get_post_format());
                        echo '</div>';               
                    endwhile;
                    echo '</div>';
                endif;

                // echo '<div class="row">';
                
                // echo '</div>';
            endif;
        ?>
    </div>
</div>

<?php get_footer(); ?>