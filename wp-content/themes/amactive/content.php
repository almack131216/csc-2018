<?php
    // REF: https://github.com/jchristopher/attachments/blob/master/docs/usage.md
    // retrieve all Attachments for the 'attachments' instance of post 123
    $attachments = new Attachments( 'attachments', $post->ID );
    amactive_debug('POST ID: '.$post->ID);

    if( $attachments->exist() ):
        $attachmentCount = $attachments->total();

        $attachmentGrid = '';
        $attachmentGrid .= '<div class="row">';
        while( $attachments->get() ) :
            $attachmentGrid .= '<div class="col-md-3">';
            $attachmentGrid .= '<a href="'. $attachments->src( 'full' ) .'" title="'. $attachments->field( 'title' ) .'" class="foobox" rel="gallery">';
            $attachmentGrid .= $attachments->image( 'thumbnail' );
            $attachmentGrid .= '</a>';
            $attachmentGrid .= '</div>';
        endwhile;
        $attachmentGrid .= '</div>';
    else:

    endif;

?>

<?php
    $img_url_thumb = wp_get_attachment_url( get_post_thumbnail_id($post->ID), 'thumbnail' );
    $img_url_large = wp_get_attachment_url( get_post_thumbnail_id($post->ID), 'large' );

    if( has_post_thumbnail() ):
        if ( $attachmentGrid ) :
?>
<div class="row post-img-row">
    <div class="col-md-6 padding-right-0">
        <div class="post-img-featured">
        <?php
            echo '<a href="'.$img_url_large.'" rel="gallery"><img src="'.$img_url_thumb.'"></a>';
        ?>
        </div>
    </div>
    <div class="col-md-6">
        <div class="post-img-grid">
            <?php echo $attachmentGrid; ?>
        </div>
    </div>
</div>
<?php
        else:
            ?>
<div class="row post-img-row">
    <div class="col-md-12">
        <div class="post-img-featured">
        <?php
            echo '<a href="'.$img_url_large.'" rel="gallery"><img src="'.$img_url_thumb.'"></a>';
        ?>
        </div>
    </div>
</div>
<?php
        endif;
    endif;
?>
<div class="row">
    <div class="col-xs-12 post-text">
        <h3>
        <?php
            $postTitle = get_the_title();
            $year = get_post_meta( $post->ID, 'csc_car_year', true);
            if( $year && ($GLOBALS['postPageCategoryId'] == DV_category_IsForSale_id || $GLOBALS['postPageCategoryId'] == DV_category_IsSold_id) ) {
                $postTitle = $year.' '.get_the_title();
            }
            echo $postTitle;
            // echo '???'.amactive_custom_title($postTitle, $post->ID);
        ?>
        </h3>
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