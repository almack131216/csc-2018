<div class="card h-100">
    <a href="<?php echo esc_url( get_permalink() ) ?>" title="Link to <?php the_title();?>">
        <?php
            if( has_post_thumbnail() ):
                the_post_thumbnail( 'medium', array(
                    'class' => 'card-img-top'
                    )
                );
            else:
                echo 'xxx';
            endif;
        ?>
    </a>
    <div class="card-body">
        <h5 class="card-title">
            <a href="<?php echo esc_url( get_permalink() ) ?>" title="Link to <?php the_title();?>">
                <?php the_title();?>
            </a>
        </h5>
        <p class="card-text">
            <?php
                $price = get_post_meta( $post->ID, 'csc_car_price', true);
                if($price):
                    echo amactive_my_custom_price_format($price);
                endif;
            ?>
        </p>
        <?php
            $tmpExclude = array(DV_category_IsForSale_id, DV_category_IsSold_id);
            $tmpCat = exclude_post_categories( $tmpExclude );
            if($tmpCat){
                echo '<span class="category">'.$tmpCat.'</span>';
            }
            // the_category();
        ?>
    </div>
</div>