<?php
    include('functions-post-img-featured.php');

    if( get_the_date( 'Y-m-d', $getId ) > $GLOBALS['dateLaunch'] ){
        $postImgRow = getPostImagesWithZoom( $post->ID );
    } else {
        $postImgRow = getPostImagesWithLightbox( $post->ID );
    }
    
    $img = new Img();

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

        //$postContentRow .= do_shortcode('[DISPLAY_ULTIMATE_SOCIAL_ICONS]');
        
    $postContentRow .= '</div>'."\r\n";

    $postContentRow .= get_the_content();
    $postContentRow .= '</div>'."\r\n";
    $postContentRow .= '</div>'."\r\n";
    $postContentRow .= '</div>'."\r\n";

    if($postImgRow) echo $postImgRow;
    echo $postContentRow;
?>