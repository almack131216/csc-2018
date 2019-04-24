<?php

    ///
    function my_attachments( $attachments ){
        $fields         = array(
            array(
            'name'      => 'title',                         // unique field name
            'type'      => 'text',                          // registered field type
            'label'     => __( 'Title', 'attachments' ),    // label to display
            'default'   => 'title',                         // default value upon selection
            ),
            array(
            'name'      => 'caption',                       // unique field name
            'type'      => 'textarea',                      // registered field type
            'label'     => __( 'Caption', 'attachments' ),  // label to display
            'default'   => 'caption',                       // default value upon selection
            ),
        );

        $args = array(

            // title of the meta box (string)
            'label'         => 'My Attachments',

            // all post types to utilize (string|array)
            'post_type'     => array( 'post', 'page' ),

            // meta box position (string) (normal, side or advanced)
            'position'      => 'normal',

            // meta box priority (string) (high, default, low, core)
            'priority'      => 'high',

            // allowed file type(s) (array) (image|video|text|audio|application)
            'filetype'      => null,  // no filetype limit

            // include a note within the meta box (string)
            'note'          => 'Attach files here!',

            // by default new Attachments will be appended to the list
            // but you can have then prepend if you set this to false
            'append'        => true,

            // text for 'Attach' button in meta box (string)
            'button_text'   => __( 'Attach Files', 'attachments' ),

            // text for modal 'Attach' button (string)
            'modal_text'    => __( 'Attach', 'attachments' ),

            // which tab should be the default in the modal (string) (browse|upload)
            'router'        => 'browse',

            // whether Attachments should set 'Uploaded to' (if not already set)
            'post_parent'   => false,

            // fields array
            'fields'        => $fields,

        );

        $attachments->register( 'my_attachments', $args ); // unique instance name
    }
    add_action( 'attachments_register', 'my_attachments' );

    function getPostImages( $getId ){
        // REF: https://github.com/jchristopher/attachments/blob/master/docs/usage.md
        // retrieve all Attachments for the 'attachments' instance of post 123
        $attachments = new Attachments( 'attachments', $getId );
        amactive_debug('POST ID: '.$getId);
        $postImgRow = '';

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

        $img_url_thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $getId ), 'medium' );
        $img_url_large = wp_get_attachment_image_src( get_post_thumbnail_id( $getId ), 'large' );
        //echo '<br>???: '.$img_url_thumb[0];

        if( has_post_thumbnail() ):
            $postImgRow = '';
            $postImgRow .= '<div class="row row-post-img">';

            $postImgRow .= '<div class="col-sm-12 col-md-7 col-post-img featured">';
            $postImgRow .= '<a href="'.$img_url_large[0].'" class="fancybox image" rel="gallery"><img src="'.$img_url_thumb[0].'"></a>';
            //$postImgRow .= do_shortcode('[zoom]');
            $postImgRow .= '</div>'."\r\n";

            // if ( function_exists('cc_zoom_featured_image') ) {
            //     cc_zoom_featured_image();
            // }

            if ( $attachmentGrid ) :
                
                $postImgRow .= '<div class="col-sm-12 col-md-5 col-post-img-grid">';
                $postImgRow .= $attachmentGrid;
                $postImgRow .= '</div>'."\r\n";          

            endif;

            $postImgRow .= '</div>'."\r\n";
        endif;

        return $postImgRow;
    }

    function getPostImagesWithZoom( $getId ){
        // REF: https://jsfiddle.net/gq74rgc3/3/        
        $attachments = new Attachments( 'attachments', $getId );
        amactive_debug('POST ID: '.$getId);        

        $img_url_thumb_featured = wp_get_attachment_image_src( get_post_thumbnail_id( $getId ), 'thumbnail' );
        $img_url_large_featured = wp_get_attachment_image_src( get_post_thumbnail_id( $getId ), 'large' );
        //echo '<br>???: '.$img_url_thumb[0];

        if( $attachments->exist() ):
            $attachmentCount = $attachments->total();

            $attachmentGrid = '';
            $attachmentGrid .= '<div class="row">';

            $attachmentGrid .= '<div class="col-xs-2 col-md-3">';
            // $attachmentGrid .= '<a href="'. $attachments->src( 'large' ) .'" title="'. $attachments->field( 'title' ) .'" class="fancybox image" rel="gallery">';
            $attachmentGrid .= '<img data-big="'.$img_url_large_featured[0].'" src="'.$img_url_thumb_featured[0].'" class="thumb">';
            // $attachmentGrid .= '</a>';
            $attachmentGrid .= '</div>';

            $i = 0;
            while( $attachments->get() ) :
                if($i==0){
                    $img_url_thumb = $attachments->src( 'thumbnail' );//wp_get_attachment_url( get_post_thumbnail_id($post->ID), 'thumbnail' );
                    $img_url_large = $attachments->src( 'large' );//wp_get_attachment_url( get_post_thumbnail_id($post->ID), 'thumbnail' );
                }	

                $attachmentGrid .= '<div class="col-xs-2 col-md-3">';
                // $attachmentGrid .= '<a href="'. $attachments->src( 'large' ) .'" title="'. $attachments->field( 'title' ) .'" class="fancybox image" rel="gallery">';
                $attachmentGrid .= '<img data-big="'.$attachments->src( 'full' ).'" src="'.$attachments->src( 'thumbnail' ).'" class="thumb">';
                // $attachmentGrid .= '</a>';
                $attachmentGrid .= '</div>';
                $i++;
            endwhile;
            $attachmentGrid .= '</div>';
        endif;

        

        if( has_post_thumbnail() ):
            $postImgRow = '';
            $postImgRow .= '<div class="row row-post-img">';

            $postImgRow .= '<div class="col-sm-12 col-md-7 col-post-img featured can-zoom">';
            // $postImgRow .= '<a href="'.$img_url_large[0].'" class="fancybox image" rel="gallery">';
            // $postImgRow .= '<img src="'.$img_url_thumb[0].'" id="image">';
            // $postImgRow .= '</a>';
            $postImgRow .= do_shortcode('[zoom]');
            $postImgRow .= '</div>'."\r\n";

            // if ( function_exists('cc_zoom_featured_image') ) {
            //     cc_zoom_featured_image();
            // }

            if ( $attachmentGrid ) :
                
                $postImgRow .= '<div class="col-sm-12 col-md-5 col-post-img-grid">';
                $postImgRow .= $attachmentGrid;
                $postImgRow .= '</div>'."\r\n";          

            endif;

            $postImgRow .= '</div>'."\r\n";
        endif;

        return $postImgRow;
    }

    function getPostImagesWithLightbox( $getId ){
        // REF: https://github.com/jchristopher/attachments/blob/master/docs/usage.md
        // retrieve all Attachments for the 'attachments' instance of post 123
        $attachments = new Attachments( 'attachments', $getId );
        amactive_debug('POST ID: '.$getId);
        $postImgRow = '';

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

        $img_url_thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $getId ), 'medium' );
        $img_url_large = wp_get_attachment_image_src( get_post_thumbnail_id( $getId ), 'large' );
        //echo '<br>???: '.$img_url_thumb[0];

        if( has_post_thumbnail() ):
            $postImgRow = '';
            $postImgRow .= '<div class="row row-post-img">';

            $postImgRow .= '<div class="col-sm-12 col-md-7 col-post-img featured">';
            $postImgRow .= '<a href="'.$img_url_large[0].'" class="fancybox image" rel="gallery"><img src="'.$img_url_thumb[0].'"></a>';
            //$postImgRow .= do_shortcode('[zoom]');
            $postImgRow .= '</div>'."\r\n";

            // if ( function_exists('cc_zoom_featured_image') ) {
            //     cc_zoom_featured_image();
            // }

            if ( $attachmentGrid ) :
                
                $postImgRow .= '<div class="col-sm-12 col-md-5 col-post-img-grid">';
                $postImgRow .= $attachmentGrid;
                $postImgRow .= '</div>'."\r\n";          

            endif;

            $postImgRow .= '</div>'."\r\n";
        endif;

        return $postImgRow;
    }

?>