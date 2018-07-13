<?php get_header(); ?>
<div class="contentbox">
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
        echo '<div class="contentbox" id="featuredRow">';
        echo '<h1>Latest Cars for Sale at Classic and Sportscar Centre, Malton, North Yorkshire</h1>';
        echo '<ul class="itemBox">';
        while ( $featuredPosts->have_posts() ): $featuredPosts->the_post();
            // get_template_part('content', get_post_format());
            get_template_part('content-grid-item', get_post_format());
        endwhile;
        wp_reset_postdata();
        echo '</ul>';
        echo '</div>';
    endif;

    // echo do_shortcode(get_post_field('post_content', 342));
?>
<div class="contentbox">
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