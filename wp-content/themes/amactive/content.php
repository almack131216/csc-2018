<?php
    // REF: https://github.com/jchristopher/attachments/blob/master/docs/usage.md
    // retrieve all Attachments for the 'attachments' instance of post 123
    $attachments = new Attachments( 'attachments', $post->ID );
    amactive_debug('POST ID: '.$post->ID);

    if( $attachments->exist() ):
        $attachmentCount = $attachments->total();

        $attachmentGrid = '';
        $attachmentGrid .= '<ul>';
        while( $attachments->get() ) :
            $attachmentGrid .= '<li>';
            $attachmentGrid .= '<a href="'. $attachments->src( 'full' ) .'" title="'. $attachments->field( 'title' ) .'" class="foobox" rel="gallery">';
            $attachmentGrid .= $attachments->image( 'thumbnail' );
            $attachmentGrid .= '</a>';
            $attachmentGrid .= '</li>';
        endwhile;
        $attachmentGrid .= '</ul>';
    else:

    endif;

?>

<div class="row">
    <div class="col-md-6">
    <?php
        $img_url_thumb = wp_get_attachment_url( get_post_thumbnail_id($post->ID), 'thumbnail' );
        $img_url_large = wp_get_attachment_url( get_post_thumbnail_id($post->ID), 'large' );
    ?>

    <?php
        if( has_post_thumbnail() ):
            echo '<a href="'.$img_url_large.'" rel="gallery"><img src="'.$img_url_thumb.'"></a>';
        else:
            echo 'xxx';
        endif;
    ?>
    </div>
    <div class="col-md-6">
        <?php echo $attachmentGrid; ?>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <h3><?php the_title();?></h3>
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