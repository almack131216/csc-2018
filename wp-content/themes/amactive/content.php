<?php
    // REF: https://github.com/jchristopher/attachments/blob/master/docs/usage.md
    // retrieve all Attachments for the 'attachments' instance of post 123
    $attachments = new Attachments( 'attachments', $post->ID );
    amactive_debug('POST ID: '.$post->ID);

    if( $attachments->exist() ):
        $attachmentCount = $attachments->total();

        $attachmentGrid = '';
        $attachmentGrid .= '<div class="row">';
		$i = 0;
        while( $attachments->get() ) :
			if($i==0){
				$img_url_thumb = $attachments->src( 'thumbnail' );//wp_get_attachment_url( get_post_thumbnail_id($post->ID), 'thumbnail' );
			    $img_url_large = $attachments->src( 'large' );//wp_get_attachment_url( get_post_thumbnail_id($post->ID), 'thumbnail' );
			}	

            $attachmentGrid .= '<div class="col-xs-2 col-md-3">';
            $attachmentGrid .= '<a href="'. $attachments->src( 'large' ) .'" title="'. $attachments->field( 'title' ) .'" class="fancybox image" rel="gallery">';
            $attachmentGrid .= $attachments->image( 'thumbnail' );
            $attachmentGrid .= '</a>';
            $attachmentGrid .= '</div>';
			$i++;
        endwhile;
        $attachmentGrid .= '</div>';
    endif;

    $img_url_thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'medium' );
	$img_url_large = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'large' );
	//echo '<br>???: '.$img_url_thumb[0];

    if( has_post_thumbnail() ):
        $postImgRow = '';
        $postImgRow .= '<div class="row row-post-img">';

        $postImgRow .= '<div class="col-sm-12 col-md-7 col-post-img featured">';
        $postImgRow .= '<a href="'.$img_url_large[0].'" class="fancybox image" rel="gallery"><img src="'.$img_url_thumb[0].'"></a>';
        $postImgRow .= '</div>'."\r\n";

        if ( $attachmentGrid ) :
            
            $postImgRow .= '<div class="col-sm-12 col-md-5 col-post-img-grid">';
            $postImgRow .= $attachmentGrid;
            $postImgRow .= '</div>'."\r\n";          

        endif;

        $postImgRow .= '</div>'."\r\n";
    endif;

    $postContentRow = '';
    $postContentRow .= '<div class="row">';
    $postContentRow .= '<div class="col-xs-12 col-post-text">';
    $postContentRow .= '<h1 class="post-title">';
    $postContentRow .= $GLOBALS['postPageTitle'];
    $postContentRow .= '</h1>';
    // $postContentRow .= '<h3>'.amactive_item_print_price( $post->ID ).'</h3>';

    if (get_field('youtube_code')) {
        //$postContentRow .= '<iframe src="https://www.youtube.com/embed/'.get_field('youtube_code').'" width="100%" height="auto" frameborder="0" allowfullscreen="allowfullscreen"></iframe>';
        $postContentRow .= '<div class="video-container"><iframe width="853" height="480" src="https://www.youtube.com/embed/'.get_field('youtube_code').'" frameborder="0" allowfullscreen></iframe</div>';
    }

    $postContentRow .= '<div class="post-text-body">';

    $postContentRow .= '<div class="post-details-box">';
        $postContentRow .= '<h3>'.amactive_item_print_price( $post->ID ).'</h3>';
        
        $postContentRow .= '<div class="post-tags">';
        $postContentRow .= get_the_tag_list('<h4>Tags:</h4><ul class="ul-tags"><li>','</li><li>','</li></ul>');
        $postContentRow .= '</div>'."\r\n";
        
    $postContentRow .= '</div>'."\r\n";

    $postContentRow .= get_the_content();
    $postContentRow .= '</div>'."\r\n";
    $postContentRow .= '</div>'."\r\n";
    $postContentRow .= '</div>'."\r\n";

    if($postImgRow) echo $postImgRow;
    echo $postContentRow;
?>