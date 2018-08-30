<div class="card">
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
                echo amactive_item_print_price( $post->ID );
            ?>
        </p>
        <?php
            $tmpExclude = array(DV_category_IsForSale_id, DV_category_IsSold_id);
            $tmpCat = exclude_post_categories( $tmpExclude );
            if($tmpCat){
                echo '<h4 class="category">'.$tmpCat.'</h4>';
            }
            // the_category();
        ?>
    </div>
</div>