<?php get_header(); ?>
<div class="row">
    <div class="col-md-3">
        <?php get_sidebar(); ?>
    </div>
    <div class="col-md-9">
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
    

    <div class="col-md-9">
        <?php
            $carousel = new WP_Query('type=post&posts_per_page=5');
            if( $carousel->have_posts() ):
                echo '<h1>Latest Cars for Sale at Classic and Sportscar Centre, Malton, North Yorkshire</h1>';
                echo '<div class="row">';        
                
                while ( $carousel->have_posts() ): $carousel->the_post();
                    // get_template_part('content', get_post_format());
                    echo '<div class="col-lg-3 col-md-4 col-sm-6 portfolio-item">';
                    get_template_part('content', 'grid-item');
                    echo '</div>';
                endwhile;
                wp_reset_postdata();
                
                echo '</div>';
            endif;
        ?>

        <!--// START //-->
        <div id="myCarousel" class="carousel slide" data-ride="carousel">
      <!-- Indicators -->
      <ol class="carousel-indicators">
        <li data-target="#myCarousel" data-slide-to="0" class=""></li>
        <li class="active" data-target="#myCarousel" data-slide-to="1"></li>
        <li class="" data-target="#myCarousel" data-slide-to="2"></li>
      </ol>
<div class="carousel-inner">
        <div class="item">
          <img src="http://binarynote.com/1stimage.jpg" alt="First slide">
          <div class="container">
            <div class="carousel-caption">
              <h1>Example headline.</h1>
              <p>Note: If you're viewing this page via a <code>file://</code> URL, the "next" and "previous" Glyphicon buttons on the left and right might not load/display properly due to web browser security rules.</p>
              <p><a class="btn btn-lg btn-primary" href="#" role="button">Sign up today</a></p>
            </div>
          </div>
        </div>
        <div class="item active">
          <img src="http://binarynote.com/Second slide" alt="Second slide">
          <div class="container">
            <div class="carousel-caption">
              <h1>Another example headline.</h1>
              <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
              <p><a class="btn btn-lg btn-primary" href="#" role="button">Learn more</a></p>
            </div>
          </div>
        </div>
        <div class="item">
          <img src="http://binarynote.com/3rdimage.jpg" alt="Third slide">
          <div class="container">
            <div class="carousel-caption">
              <h1>One more for good measure.</h1>
              <p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
              <p><a class="btn btn-lg btn-primary" href="#" role="button">Browse gallery</a></p>
            </div>
          </div>
        </div>
      </div>
      <a class="left carousel-control" href="#myCarousel" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span></a>
      <a class="right carousel-control" href="#myCarousel" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span></a>
</div><!-- /.carousel -->
<script type=”text/javascript”>
$(document).ready (function($) {
$(‘.carousel’).carousel({
interval: 1000
})
});
</script>
        <!--// END //-->
    </div>
</div>

<?php

    $args = array(
        'post_type'  => 'post',
        'posts_per_page' => 4,
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
        echo '<h1>Latest Cars for Sale at Classic and Sportscar Centre, Malton, North Yorkshire</h1>';
        echo '<div class="row">';        
        
        while ( $featuredPosts->have_posts() ): $featuredPosts->the_post();
            // get_template_part('content', get_post_format());
            echo '<div class="col-lg-3 col-md-4 col-sm-6 portfolio-item">';
            get_template_part('content', 'grid-item');
            echo '</div>';
        endwhile;
        wp_reset_postdata();
        
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
            echo '<h1>'.$category->description.'</h1>';
            while ( $featuredPosts->have_posts() ): $featuredPosts->the_post();
                get_template_part('content', 'list-item');
            endwhile;
            wp_reset_postdata();
        endif;

    endforeach;

    

    // echo do_shortcode(get_post_field('post_content', 342));
?>

<?php get_footer(); ?>