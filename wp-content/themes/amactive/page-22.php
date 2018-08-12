<?php get_header(); ?>
<div class="row bg-accent">
    <div class="hidden-md-down col-lg-3 col-no-padding">
        <?php get_sidebar(); ?>
    </div>
    <div class="col-md-12 col-lg-9 padding-x-0">
        <?php
            if( have_posts() ):
                while ( have_posts() ): the_post();
                    // get_template_part('content', get_post_format());
                    ?>
                    <?php the_content();?>
                    <?php
                endwhile;
            endif;
        ?>
    </div>
</div>

<div class="pad-me">
    <?php
        $args = array(
            'type' => 'post',
            'posts_per_page' => 4,
            'orderby' => 'post_date',
            'order' => 'DESC',
            'cat' => 2,
            'category__not_in' => 38,
            'meta_query' => array(
                array(
                    'key' => '_thumbnail_id',
                    'compare' => 'EXISTS'
                ),
            )
        );
        $carousel = new WP_Query( $args );
        if( $carousel->have_posts() ):

            echo '<div class="row">';
                echo '<div class="col-md-12">';
                echo amactive_return_title_splitter( array('cat' => 2, 'class' => 'margin-x-g2') );
                echo '</div>';
            echo '</div>';

            echo '<div class="row padding-x-g1XXX post-img-rowXXX">';
                // echo '<div class="col-md-12">';
            // echo '<div class="col-md-12">';
            // echo '<h4>Latest Cars for Sale at Classic and Sportscar Centre, Malton, North Yorkshire</h4></div>';
            // echo '<div class="row">';

            while ( $carousel->have_posts() ): $carousel->the_post();
                // get_template_part('content', get_post_format());
                echo '<div class="col-md-3 col-sm-6 portfolio-item bg-white">';
                get_template_part('content', 'grid-item');
                echo '</div>';
            endwhile;
            wp_reset_postdata();

            echo '</div>';
        endif;
    ?>



    <?php
        echo '<div class="row">';
        echo '<div class="col-md-12">';
        echo amactive_return_title_splitter( array('cat' => 44) );
        echo do_shortcode( "[insert page='562' display='content']", false );
        echo '</div>';
        echo '</div>';
    ?>

    <?php

        // 40 - news
        // 4 - press
        // 3 testimonials

        $args_cat = array(
            'include' => array(DV_category_News_id, DV_category_Press_id, DV_category_Testimonials_id)
        );

        $categories = get_categories( $args_cat );
        //var_dump($categories);
        foreach($categories as $category):

            $args = array(
                'post_type'  => 'post',
                'posts_per_page' => 1,
                'category__in' => $category->term_id,
                'meta_query' => array(
                    array(
                        'key' => '_thumbnail_id',
                        'compare' => 'EXISTS'
                    ),
                )
            );
            $featuredPosts = new WP_Query( $args );//'type=post&posts_per_page=5'
            if( $featuredPosts->have_posts() ):
                echo '<div class="row">';
                echo '<div class="col-md-12">';
                // echo '<h3>'.$category->description.'</h3>';
                while ( $featuredPosts->have_posts() ): $featuredPosts->the_post();
                    // echo '<div class="row">';
                    // echo '<div class="col-xs-12">';
                    echo amactive_return_title_splitter( array('cat' => $category->term_id) );
                    get_template_part('content', 'list-item');
                    // echo '</div>';
                    // echo '</div>';
                endwhile;
                wp_reset_postdata();
                echo '</div>';
                echo '</div>';
            endif;

        endforeach;



        // echo do_shortcode(get_post_field('post_content', 342));
    ?>

</div>

<?php get_footer(); ?>