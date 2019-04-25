<?php

    include('functions-post-img-featured.php');
    include('templates/tmpl_common.class.php');
    $tpl = new Template( '/' );

    $postContentRow = '';
    $postContentRow .= '<div class="row">';
        $postContentRow .= '<div class="col-xs-12 col-post-text">';

        $postContentRow .= $tpl->postTitle($GLOBALS['postPageTitle']);
        // $postContentRow .= $tpl->postYouTube(get_field('youtube_code'));

            $postContentRow .= '<div class="post-text-body">';
            
            $postContentRow .= getPostImagesLarge( $post->ID );

            $postContentRow .= '</div>'."\r\n";
        $postContentRow .= '</div>'."\r\n";
    $postContentRow .= '</div>'."\r\n";

    echo $postContentRow;
?>