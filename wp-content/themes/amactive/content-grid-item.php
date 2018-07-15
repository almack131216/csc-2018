<div class="li">
    <div class="imgArea">
        <a href="<?php echo esc_url( get_permalink() ) ?>" title="Link to <?php the_title();?>">
            <?php
                if( has_post_thumbnail() ):
                    the_post_thumbnail( 'post-thumbnail' );
                else:
                    echo 'xxx';
                endif;
            ?>
        </a>
    </div>
    <div class="textArea">
        <h2><a href="<?php echo esc_url( get_permalink() ) ?>" title="Link to <?php the_title();?>"><?php the_title();?></a></h2>
        <p><?php echo amactive_my_custom_price_format(get_post_meta( $post->ID, 'csc_car_price', true)); ?></p>
    </div>
</div>