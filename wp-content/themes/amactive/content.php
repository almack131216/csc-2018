<div class="card mt-4">
    <?php
        if( has_post_thumbnail() ):
            the_post_thumbnail( 'large', array(
                'class' => 'card-img-top img-fluid'
                )
            );
        else:
            echo 'xxx';
        endif;
    ?>
    <div class="card-body">
        <h3 class="card-title"><?php the_title();?></h3>
        <?php
            $price = get_post_meta( $post->ID, 'csc_car_price', true);
            if($price):
                echo '<h4>'.amactive_my_custom_price_format($price).'</h4>';
            endif;
        ?>
        <?php the_content();?>
        <?php the_content();?>
    </div>
</div> 