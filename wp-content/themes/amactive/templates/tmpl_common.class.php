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
            $tmpStr = '<div class="post-details-box">';
            $tmpStr .= '<h3>'.amactive_item_print_price( $getId ).'</h3>';

			$tmpStr .'<ul>';
			if( get_request_parameter('photos') ){
				$tmpStr .= '<li><a href="'.$GLOBALS['postPageSlug'].'" title="Link to '.get_the_title().'">Details</a></li>';
			}else{
				$tmpStr .= '<li><a href="'.$GLOBALS['postPageSlug'].'/photos" title="Link to '.get_the_title().'">Large Photos</a></li>';
			}
			$tmpStr .= '</ul>';
            
            $tmpStr .= '<div class="post-tags">';
            $tmpStr .= get_the_tag_list('<h4>Tags:</h4><ul class="ul-tags"><li>','</li><li>','</li></ul>');
            $tmpStr .= '</div>'."\r\n";
            //$postContentRow .= do_shortcode('[DISPLAY_ULTIMATE_SOCIAL_ICONS]');      
            $tmpStr .= '</div>'."\r\n";

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