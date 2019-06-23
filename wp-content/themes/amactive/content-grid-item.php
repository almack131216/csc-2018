<?php
    $css_itemStatus = '';

    $itemDate = get_the_time('Y\-m\-d', $post->ID);
    // echo $itemDate;

    if (DV_date_today == $itemDate){
        $itemPublishedToday = true;
        $css_itemStatus .= ' corner-ribbon-wrap';
        // echo '???';
    }

    if (in_category( DV_category_IsSold_id )){
        $itemIsSold = true;
        $css_itemStatus .= ' corner-ribbon-wrap';
    }

    $css_itemStatus .= ' category-ribbon-wrap';
    $tmpExclude = array(DV_category_IsForSale_id, DV_category_IsSold_id);
    $tmpCat = exclude_post_categories( $tmpExclude );
?>
<div class="card <?php echo $css_itemStatus; ?>">
    <div class="card-img">
        <a href="<?php echo amactive_post_url(); ?>" title="Link to <?php the_title();?>">
            <?php
                if( has_post_thumbnail() ):
                    the_post_thumbnail( 'medium', array(
                        'class' => 'card-img-top'
                        )
                    );
                else:
                    echo 'xxx';
                endif;

                if( $itemPublishedToday ){
                    echo '<div class="corner-ribbon top-right green">TODAY</div>';
                }
                if ( $itemIsSold ) {
                    echo '<div class="corner-ribbon top-right red">SOLD</div>';
                }            
            ?>
        </a>
        <?php
            echo '<div class="category-ribbon">'.$tmpCat.'</div>';
        ?>
    </div>
    <div class="card-body">
        <h5 class="card-title">
            <a href="<?php echo amactive_post_url(); ?>" title="Link to <?php the_title();?>">
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
                    echo get_first_paragraph( array('readmore' => false) );
                }
                if($tmpCat && in_category( array(DV_category_IsForSale_id, DV_category_IsSold_id) )){
                    echo amactive_item_print_price( $post->ID );
                }
                // $bodyContent .= '<div class="col-md-9">';
                //     $bodyContent .= '<span class="search-post-title">'.the_title().'</span>';
                //     $bodyContent .= '<span class="search-post-excerpt">'.the_excerpt().'</span>';
                //     $bodyContent .= '<span class="search-post-link">';
                //     $bodyContent .= '<a href="'.the_permalink().'">'.the_permalink().'</a>';
                //     $bodyContent .= '</span>';
                // $bodyContent .= '</div>';
            ?>
        </div>
        
    </div>
</div>