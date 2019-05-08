<?php
    include('functions-post-img-featured.php');
    include('templates/tmpl_common.class.php');
    $tpl = new Template( '/' );

    if( get_the_date( 'Y-m-d', $getId ) > $GLOBALS['dateLaunch'] ){
        $postImgRow = getPostImagesWithZoom( $post->ID );
    } else {
        $postImgRow = getPostImagesWithLightbox( $post->ID );
    }

    $postContentRow = '';
    $postContentRow .= '<div class="row">';
        $postContentRow .= '<div class="col-xs-12 col-post-text">';

        $postContentRow .= $tpl->postTitle($GLOBALS['postPageTitle']);
        $postContentRow .= $tpl->postYouTube(get_field('youtube_code'));

            $postContentRow .= '<div class="post-text-body">';

            $postContentRow .= $tpl->postDetailsBox( $post->ID );
            $postContentRow .= wpautop( get_the_content(), true);
            
            $postContentRow .= '</div>'."\r\n";
        $postContentRow .= '</div>'."\r\n";
    $postContentRow .= '</div>'."\r\n";

    if($postImgRow) echo $postImgRow;
    echo $postContentRow;
?>