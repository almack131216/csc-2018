<div class="card h-100">
    <a href="<?php echo esc_url( get_permalink() ) ?>" title="Link to <?php the_title();?>">
        <?php
            if( has_post_thumbnail() ):
                the_post_thumbnail( 'small', array(
                    'class' => 'card-img-top',
                    'width' => '100%',
                    'height'    => 'auto'
                    )
                );
            else:
                echo 'xxx';
            endif;
        ?>
    </a>
    <div class="card-body">
        <h4 class="card-title">
            <a href="<?php echo esc_url( get_permalink() ) ?>" title="Link to <?php the_title();?>">
                <?php the_title();?>
            </a>
        </h4>
        <p class="card-text">
            <?php
                $price = get_post_meta( $post->ID, 'csc_car_price', true);
                if($price):
                    echo amactive_my_custom_price_format($price);
                endif;
            ?>

            <?php
                the_category();
            ?>
        </p>
    </div>
</div>