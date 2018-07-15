<?php get_header(); ?>
<div class="row">
    <?php get_sidebar(); ?>

    <div class="contentMiddle" id="SpanRight">
        <?php
        echo do_shortcode(get_post_field('post_content', 342));
        ?>
    </div>
</div>
<?php

    $args = array(
        'post_type'  => 'post',
        'posts_per_page' => 5,
        'category__in' => 2,
        'category__not_in' => 38,
        'cat' => 2,
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
        echo '<h1>Latest Cars for Sale at Classic and Sportscar Centre, Malton, North Yorkshire</h1>';
        echo '<div class="col-lg-3 col-md-4 col-sm-6 portfolio-item">';
        while ( $featuredPosts->have_posts() ): $featuredPosts->the_post();
            // get_template_part('content', get_post_format());
            get_template_part('content', 'grid-item');
        endwhile;
        wp_reset_postdata();
        echo '</div>';
        echo '</div>';
    endif;

    // echo do_shortcode(get_post_field('post_content', 342));
?>
<?php

    // 40 - news
    // 4 - press
    // 3 testimonials

    $args_cat = array(
        'include' => '4, 40, 3'
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
            echo '<h1>'.$category->description.'</h1>';
            echo '<div class="itemRow">';
            while ( $featuredPosts->have_posts() ): $featuredPosts->the_post();
                get_template_part('content', 'list-item');
            endwhile;
            wp_reset_postdata();
            echo '</div>';
            echo '</div>';
        endif;

    endforeach;

    

    // echo do_shortcode(get_post_field('post_content', 342));
?>
<div class="row">
    <div class="mainPad">
        <?php
            if( have_posts() ):
                while ( have_posts() ): the_post();
                    // get_template_part('content', get_post_format());
                    ?>
                    <div class="thumbnail-image"><?php the_post_thumbnail( 'thumbnail' ); ?></div>
                    <small><?php the_category();?></small>
                    <h3><?php the_content();?></h3>
                    <?php
                endwhile;
            endif;

        ?>
    </div>
</div>

<?php get_footer(); ?>