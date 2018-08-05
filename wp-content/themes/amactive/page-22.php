<?php get_header(); ?>
<div class="row bg-accent">
    <div class="hidden-md-down col-lg-3">
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

<div class="row">
    <div class="col-md-12 bg-blue">
        <?php
            $args = array(
                'type' => 'post',
                'posts_per_page' => 4,
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
                echo '<h4>Latest Cars for Sale at Classic and Sportscar Centre, Malton, North Yorkshire</h4>';
                echo '<div class="row">';        
                
                while ( $carousel->have_posts() ): $carousel->the_post();
                    // get_template_part('content', get_post_format());
                    echo '<div class="col-md-3 col-sm-6 portfolio-item">';
                    get_template_part('content', 'grid-item');
                    echo '</div>';
                endwhile;
                wp_reset_postdata();
                
                echo '</div>';
            endif;
        ?>

    </div>
</div>

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
            echo '<h3>'.$category->description.'</h3>';
            while ( $featuredPosts->have_posts() ): $featuredPosts->the_post();
                get_template_part('content', 'list-item');
            endwhile;
            wp_reset_postdata();
        endif;

    endforeach;

    

    // echo do_shortcode(get_post_field('post_content', 342));
?>

<?php get_footer(); ?>