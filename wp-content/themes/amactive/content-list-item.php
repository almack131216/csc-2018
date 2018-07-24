<div class="row">
    <div class="col-md-3">
        <a href="<?php echo esc_url( get_permalink() ) ?>" title="Link to <?php the_title();?>">
        <?php
            if( has_post_thumbnail() ):
                the_post_thumbnail( 'small', array(
                    'class' => 'img-fluid rounded mb-3 mb-md-0'
                    )
                );
            else:
                echo 'xxx';
            endif;
        ?>
    </a>
    </div>
    <div class="col-md-9">
        <h3>
            <a href="<?php echo esc_url( get_permalink() ) ?>" title="Link to <?php the_title();?>">
                <?php the_title();?>
            </a>
        </h3>
        <?php
            $price = get_post_meta( $post->ID, 'csc_car_price', true);
            if($price):
                echo '<h4>'.amactive_my_custom_price_format($price).'</h4>';
            endif;
        ?>
        <?php
            the_category();
        ?>
    </div>
</div>