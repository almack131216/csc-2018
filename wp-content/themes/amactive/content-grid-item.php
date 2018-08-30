<?php
    $tmpExclude = array(DV_category_IsForSale_id, DV_category_IsSold_id);
    $tmpCat = exclude_post_categories( $tmpExclude );
?>
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
        <?php
            if($tmpCat){
                echo '<h4 class="category">'.$tmpCat.'</h4>';
            }
            // the_category();
        ?>
        <div class="card-text">
            <?php
                if ( in_category( array(DV_category_News_id, DV_category_Press_id, DV_category_Testimonials_id) ) ) {
                    // print_r( get_the_category() );
                    // echo '<p>'.$post->ID.'</p>';
                    echo get_first_paragraph();
                }
                echo amactive_item_print_price( $post->ID );
            ?>
        </div>
        
    </div>
</div>