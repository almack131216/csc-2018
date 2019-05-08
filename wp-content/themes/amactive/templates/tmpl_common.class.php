<?php
/**
 * Class Template - a very simple PHP class for rendering PHP templates
 */
class Template {
	/**
	 * Location of expected template
	 *
	 * @var string
	 */
	public $folder;
	/**
	 * Template constructor.
	 *
	 * @param $folder
	 */
	function __construct( $folder = null ){
		if ( $folder ) {
			$this->set_folder( $folder );
		}
	}
	/**
	 * Simple method for updating the base folder where templates are located.
	 *
	 * @param $folder
	 */
	function set_folder( $folder ){
		// normalize the internal folder value by removing any final slashes
		$this->folder = $this->folder = rtrim( $folder, '/' );
	}
    /**
    * template test
    */
    function postTitle( $getStr ){
        $tmpStr = '<h1 class="post-title">';
        $tmpStr .= $getStr;
        $tmpStr .= '</h1>';
        return $tmpStr;
    }

	function postTitleWithDetailsBox( $getStr, $getId ){
        $tmpStr = '';
		$tmpStr .= '<div class="row">';
		$tmpStr .= '<div class="col-sm-7">';
		$tmpStr .= '<h2 class="post-title">';
        $tmpStr .= $getStr;
        $tmpStr .= '</h2>';
		$tmpStr .= '</div>';
		$tmpStr .= '<div class="col-sm-5">';
		$tmpStr .= $this->postDetailsBox( $getId );
		$tmpStr .= '</div>';
		$tmpStr .= '</div>';
        return $tmpStr;
    }

	

    function postYouTube( $getCode ){
        if ($getCode) {
            //$postContentRow .= '<iframe src="https://www.youtube.com/embed/'.get_field('youtube_code').'" width="100%" height="auto" frameborder="0" allowfullscreen="allowfullscreen"></iframe>';
            $tmpCode = '<div class="video-container">';
            $tmpCode .= '<iframe width="100%" height="480" src="https://www.youtube.com/embed/'.$getCode.'" frameborder="0" allowfullscreen></iframe>';
            $tmpCode .= '</div>';
            return $tmpCode;
        }
    }

    function postDetailsBox( $getId ) {
		
        if($getId){
            $tmpStr = '<div class="post-details-box ';
			if(on_photos_page()) $tmpStr .= 'on-photos-page';
			$tmpStr .= '">';
            

			$tmpStr .= '<div class="feature-list">';
				$tmpStr .= '<div>';
					$tmpStr .= '<h3>'.amactive_item_print_price( $getId ).'</h3>';
				$tmpStr .= '</div>'."\r\n";

				$tmpStr .= '<div>';
					$tmpStr .='<ul class="ul-fa">';
					if( on_photos_page() ){
						$tmpStr .= '<li><a href="'.$GLOBALS['postPageSlug'].'" title="Link to '.get_the_title().'" class="details"><span>Details</span></a></li>';
					}else{
						$tmpStr .= '<li><a href="'.$GLOBALS['postPageSlug'].'?photos" title="Link to '.get_the_title().'" class="images"><span>Large Photos</span></a></li>';
					}
					$tmpStr .= '<li><a href="mailto:sales@classicandsportscar.ltd.uk?subject=Enquiry for '.get_the_title().' ('.$getId.')" title="Make enquiry about '.get_the_title().'" class="enquire"><span>Enquire</span></a></li>';
					$tmpStr .= '</ul>';
				$tmpStr .= '</div>'."\r\n";

				$tmpStr .= '<div class="post-tags">';
					$tmpStr .= get_the_tag_list('<ul class="ul-tags"><li><h4>Tags:</h4></li><li>','</li><li>','</li></ul>');
				$tmpStr .= '</div>'."\r\n";

				$tmpStr .= '<div class="post-share">';
					$tmpStr .= '<h4>Share:</h4>';
					// echo $tmpStr;	



					// $tmpStr .= do_shortcode('[DISPLAY_ULTIMATE_SOCIAL_ICONS]');      
					if ( function_exists( 'sharing_display' ) ) {
						// sharing_display( '', true );
						$tmpStr .= sharing_display( '', false );
					}

// 					$tmpStr .= <<<EOD
// <div class="sharedaddy sd-sharing-enabled"><div class="robots-nocontent sd-block sd-social sd-social-icon sd-sharing"><h3 class="sd-title">Share this:</h3><div class="sd-content"><ul><li class="share-facebook"><a rel="nofollow noopener noreferrer" data-shared="sharing-facebook-14727" class="share-facebook sd-button share-icon no-text" href="https://classicandsportscar.ltd.uk/_wp190503/test-large-190501/?share=facebook&amp;nb=1" target="_blank" title="Click to share on Facebook"><span></span><span class="sharing-screen-reader-text">Click to share on Facebook (Opens in new window)</span></a></li><li class="share-twitter"><a rel="nofollow noopener noreferrer" data-shared="sharing-twitter-14727" class="share-twitter sd-button share-icon no-text" href="https://classicandsportscar.ltd.uk/_wp190503/test-large-190501/?share=twitter&amp;nb=1" target="_blank" title="Click to share on Twitter"><span></span><span class="sharing-screen-reader-text">Click to share on Twitter (Opens in new window)</span></a></li><li class="share-end"></li></ul></div></div></div>
// EOD;
					
					// if ( class_exists( 'Jetpack_Likes' ) ) {
					// 	$custom_likes = new Jetpack_Likes;
					// 	echo $custom_likes->post_likes( '' );
					// }
					// $tmpStr = '';
				$tmpStr .= '</div>'."\r\n";

			$tmpStr .= '</div>'."\r\n";//feature-list

            $tmpStr .= '</div>'."\r\n";//post-details-box

            return $tmpStr;
        }        
    }
	// /**
	//  * Find and attempt to render a template with variables
	//  *
	//  * @param $suggestions
	//  * @param $variables
	//  *
	//  * @return string
	//  */
	// function render( $suggestions, $variables = array() ){
	// 	$template = $this->find_template( $suggestions );
	// 	$output = '';
	// 	if ( $template ){
	// 		$output = $this->render_template( $template, $variables );
	// 	}
	// 	return $output;
	// }
	// /**
	//  * Look for the first template suggestion
	//  *
	//  * @param $suggestions
	//  *
	//  * @return bool|string
	//  */
	// function find_template( $suggestions ){
	// 	if ( !is_array( $suggestions ) ) {
	// 		$suggestions = array( $suggestions );
	// 	}
	// 	$suggestions = array_reverse( $suggestions );
	// 	$found = false;
	// 	foreach( $suggestions as $suggestion ){
	// 		$file = "{$this->folder}/{$suggestion}.php";
	// 		if ( file_exists( $file ) ){
	// 			$found = $file;
	// 			break;
	// 		}
	// 	}
	// 	return $found;
	// }
	// /**
	//  * Execute the template by extracting the variables into scope, and including
	//  * the template file.
	//  *
	//  * @internal param $template
	//  * @internal param $variables
	//  *
	//  * @return string
	//  */
	// function render_template( /*$template, $variables*/ ){
	// 	ob_start();
	// 	foreach ( func_get_args()[1] as $key => $value) {
	// 		${$key} = $value;
	// 	}
	// 	include func_get_args()[0];
	// 	return ob_get_clean();
	// }
}