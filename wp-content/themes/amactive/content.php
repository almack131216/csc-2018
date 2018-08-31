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
            $attachmentGrid .= '<div class="col-xs-2 col-md-3">';
            $attachmentGrid .= '<a href="'. $attachments->src( 'full' ) .'" title="'. $attachments->field( 'title' ) .'" class="foobox" rel="gallery">';
            $attachmentGrid .= $attachments->image( 'thumbnail' );
            $attachmentGrid .= '</a>';
            $attachmentGrid .= '</div>';
        endwhile;
        $attachmentGrid .= '</div>';
    endif;

?>

<?php
    $img_url_thumb = wp_get_attachment_url( get_post_thumbnail_id($post->ID), 'thumbnail' );
    $img_url_large = wp_get_attachment_url( get_post_thumbnail_id($post->ID), 'large' );

    if( has_post_thumbnail() ):
        if ( $attachmentGrid ) :
?>
<div class="row row-post-img">
    <div class="col-md-7 col-post-img-featured">
        <?php
            echo '<a href="'.$img_url_large.'" rel="gallery"><img src="'.$img_url_thumb.'"></a>';
        ?>
    </div>
    <div class="col-md-5 col-post-img-grid">
        <?php echo $attachmentGrid; ?>
    </div>
    <div class="col-xs-12 col-post-breadcrumb">
        <?php
            echo amactive_breadcrumb();
        ?>
    </div>
</div>
<?php
        else:
            ?>
<div class="row row-post-img row-no-paddingXXX">
    <div class="col-md-12">
        <div class="post-img-featured">
        <?php
            echo '<a href="'.$img_url_large.'" rel="gallery"><img src="'.$img_url_thumb.'"></a>';
        ?>
        <?php
            echo amactive_breadcrumb();
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
        <h3 class="post-title">
        <?php
            $postTitle = get_the_title();
            echo amactive_custom_title($postTitle, $post->ID);
        ?>
        </h3>
        <?php
            echo amactive_item_print_price( $post->ID );
        ?>
        <?php the_content();?>
    </div>
</div> 