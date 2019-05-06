<?php

    include('functions-post-img-featured.php');
    include('templates/tmpl_common.class.php');
    $tpl = new Template( '/' );

    $postContentRow = '';
    $postContentRow .= '<div class="row">';
        $postContentRow .= '<div class="col-xs-12 col-post-text">';

        $postContentRow .= $tpl->postTitle($GLOBALS['postPageTitle']);
        // $postContentRow .= $tpl->postDetailsBox( $post->ID );
        // $postContentRow .= $tpl->postYouTube(get_field('youtube_code'));

            $postContentRow .= '<div class="post-text-body">';            
            $postContentRow .= $tpl->postDetailsBox( $post->ID );
            $postContentRow .= wp_trim_words( get_the_content(), $GLOBALS['pagePostPhotosProps']['excerptWordCount'] );
            $postContentRow .= getPostImagesLarge( $post->ID );

            $postContentRow .= '</div>'."\r\n";
        $postContentRow .= '</div>'."\r\n";
    $postContentRow .= '</div>'."\r\n";

    echo $postContentRow;
?>