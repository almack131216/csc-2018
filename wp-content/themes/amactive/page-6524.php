<?php
    //REF: https://www.shift8web.ca/2016/03/customize-wordpress-search-results-page/
    get_header();

	$PhotoPage = true;
	$currentpage = $_GET['currPage'];
	if(!$currentpage) $currentpage = "catalogue";
	$currentpageSub = "photos";
	$postId = $_GET['uid'];
	
	$Content = '';

	if($_SERVER['HTTP_HOST']!="localhost") include("product-photos/Zip.php");
	if($_SERVER['HTTP_HOST']!="localhost") include("zip.php");

	$Content .= '<div class="contentbox" id="FullPhotos">';
	
		$Photos = '';

        $args = array(
            'post_parent' => $postId,
            'post_type'      => 'attachment',
            'post_mime_type' => 'image',
            'orderby' => array('post_parent,menu_order'),
            'order' => 'ASC',
            'posts_per_page' => 3
        );
        $images = get_children( $args );
        if ($images) {
            $i=0;
            $files_to_zip = array();

            $attachmentGrid = '';
            $attachmentGrid .= '<div class="row">';

            foreach ($images as $image) {     
                // print_r( $image );

                if($_SERVER['HTTP_HOST']=="localhost"){
                    $imgSRC = str_replace( 'http://localhost:8080/classicandsportscar.ltd.uk/', '../../', $image->guid);
                }else{
                    //$_SERVER['DOCUMENT_ROOT']
                    $imgSRC = $image->guid;
                    // $imgSRC = str_replace( 'http://www.classicandsportscar.ltd.uk/', '../../', $image->guid);
                }
                
                //$imgSRC = $image->guid;
                $files_to_zip[] = $imgSRC;//wp_get_attachment_url( $image->ID );//$imgSRC;

                $PhotoID = 'FullPhoto_'.$i;
                $PhotoTitle = $image->post_title;

                if($i>0 && !is_float($i/2)) $attachmentGrid.= '<div class="PageBreak"><span>Page Break</span></div>'; 
                $attachmentGrid .= '<div class="col-xs-12 text-align-center">';

                $attachmentGrid.= '<div class="PhotoBox">';
                // $attachmentGrid .= '!!! '.$image->ID .' / '. $postId.' !!!<br>';
                // $Content .= wp_get_attachment_image($image->ID, 'full');
                $attachmentGrid .= '<img src="'.$imgSRC.'" id="'.$PhotoID.'">';

                $attachmentGrid.= '<div class="PhotoBoxFooter">';
                    // if($PhotoTitle) $attachmentGrid.= '<span class="photo-title">'.$PhotoTitle.'</span>';
                    $attachmentGrid.= '<ul class="ul-clean ul-inline ul-photo-options">';
                    $attachmentGrid.= '<li><a href="javascript:printme(\''.$PhotoTitle.'\',\''.$PhotoID.'\')" class="a-print">Print Photo</a></li>';
                    $attachmentGrid.= '<li>'.ibenic_the_file_link( $image->ID ).'</li>';
                    $attachmentGrid.= '</ul>';
                $attachmentGrid.= '</div>';

                $attachmentGrid .= '</div>';

                $attachmentGrid .= '</div>';
                $i++;
            }

            $attachmentGrid .= '</div>';

            if($ii==1) $Photos.= '<div class="row PrintHidden"><div class="col-xs-12"><p>&nbsp;</p>'.$ContactDetails->PrintContactDetails("footer").'</div></div>';
        }
		
		
		// $query = "SELECT id,name,image_large FROM catalogue WHERE id=$itemID OR id_xtra=$itemID ORDER BY id ASC";
		// $result = mysql_query($query);
		// if($result && mysql_num_rows($result)>=1){
		// 	$PhotoTitle = '';
		// 	$PrimaryPhotoTitle = '';
		// 	$files_to_zip = array();
		// 	$ii=0;
		// 	for($i=0;$i<mysql_num_rows($result);$i++){
		// 		$row = mysql_fetch_row($result);
				
		// 		$imgSRC = $gp_uploadPath['large'].$row[2];
		// 		if($CMSShared->FileExists($imgSRC)){
		// 			$ii++;
		// 			$files_to_zip[] = $imgSRC;
		// 			$PhotoID = 'FullPhoto_'.$i;
		// 			if($row[1]){
		// 				$PhotoTitle = $row[1];
		// 				if(!$PrimaryPhotoTitle) $PrimaryPhotoTitle = $PhotoTitle;
		// 			}else{
		// 				$PhotoTitle = $PrimaryPhotoTitle;
		// 			}
					
		// 			if($ii>0 && !is_float($ii/2)) $Photos.= '<div class="PageBreak"><span>Page Break</span></div>';
		// 			$Photos.= '<div class="PhotoBox">';
		// 				$Photos.= '<img src="'.$imgSRC.'" alt="'.$PhotoTitle.'" id="'.$PhotoID.'">';
		// 				$Photos.= '<div class="PhotoBoxFooter">';
		// 					if($row[1]) $Photos.= '<p>'.$PhotoTitle.'</p>';
		// 					$Photos.= '<ul class="PhotoOptions">';
		// 					//$Photos.= '<li><a href="javascript:printImg(\''.$PhotoID.'\')" class="print">Print Photo</a></li>';
		// 					$Photos.= '<li><a href="javascript:printme(\''.$PhotoTitle.'\',\''.$PhotoID.'\')" class="print">Print Photo</a></li>';
		// 					$Photos.= '<li><a href="'.$SiteFunctions->FileDownloadLink($gp_uploadPath['large'].$row[2]).'" class="disk" title="Download - Save this photo">Save Photo</a></li>';
		// 					$Photos.= '</ul>';
		// 				$Photos.= '</div>';
					

		// 				$Photos .= '<noscript><p class="noscript">You have JavaScript turned off. So the \'Print Photo\' feature will not work.</p></noscript>';

		// 			$Photos.= '</div>';
		// 			if($ii==1) $Photos.= '<div class="PrintHidden"><p>&nbsp;</p>'.$ContactDetails->PrintContactDetails("footer").'</div>';
		// 		}
				
		// 	}
		// }
		
		$PageOptions = '<div class="PhotoBox">';		
		$PageOptions.= '<ul>';
		
		$PrevPage = "javascript:history.go(-1);";
		$PageOptions .= '<li><a href="'.$PrevPage.'" title="Return to previous page" class="BackBut">Back</a></li>';
		
		if($_SERVER['HTTP_HOST']!="localhost"){			
            $ZipSRC = 'zips/'.time().'.zip';

			//echo $ZipSRC;
			//if true, good; if false, zip creation failed	
			//print_r($files_to_zip);
			
			$result = $ZipImages->create_zip($files_to_zip,$ZipSRC);
			$PageOptions.= '<li><a href="'.get_template_directory_uri().'/force-download.php?file='.$ZipSRC.'" class="disk" title="Download - Save ALL Photos">Save ALL Photos to zip file</a></li>';


            $zip_file_name='demo6.zip';
            $zip = new ZipArchive();
            $tmp_file = $_SERVER['DOCUMENT_ROOT'].'/_wp180906/wp-content/themes/amactive/zips/'.$zip_file_name;//tempnam('.','');
            
            if($zip->open($zip_file_name, ZIPARCHIVE::CREATE) === true){

                // $c = 0;
                // foreach (glob($files_to_zip) as $image) {
                //     $c++;
                //     $files = $image;
                //     $download_file = file_get_contents($files);

                //     // $zip->addFromString(basename($files),$download_file);
                //     // $zip->addFile($download_file);

                //     //$_SERVER['DOCUMENT_ROOT']
                //     $tmpImg = str_replace( 'http://www.classicandsportscar.ltd.uk/', '', $image);
                //     $abs_path = $_SERVER['DOCUMENT_ROOT'].'/classicandsportscar.ltd.uk/'.$tmpImg;
                //     $relative_path = $tmpImg;
                //     // echo '<br>??? '.$abs_path.' -> '.$relative_path;
                //     // $zip->addFromString($abs_path, file_get_contents($relative_path) );
                //     $zip->addFile($image,basename($files));
                // }

                $tstFile = $_SERVER['DOCUMENT_ROOT'].'/_wp180906/wp-content/themes/amactive/stat/back.gif';
                // $tstFile = './../wp-content/themes/amactive/stat/back.gif';
                // if(is_file($tstFile)){
                if( (@is_array(getimagesize($tstFile)) && file_exists($tstFile)) ) {
                    $Content .= 'FILE DOES EXIST: <img src="'.$tstFile.'">'."\r\n";
                    $zip->addFromString($tstFile,'back.gif');

                    $zip->close();
                    // header('Content-type: application/zip');
                    // header('Content-Disposition: attachment; filename="'.$zip_file_name.'"');
                    // header("Content-length: ".filesize($zip_file_name));
                    // readfile($tmp_file);

                    // exit;
                } else {
                    $Content .= 'FILE DOES NOT EXIST: <img src="'.$tstFile.'">'."\r\n";
                }
                

            }

		}
		$PageOptions.= '<li><a href="JavaScript:window.print();" class="print" title="Print ALL Photos">Print ALL Photos</a></li>';
		$PageOptions.= '</ul>';
		$PageOptions.= '</div>';
		
		$Content .= $PageOptions;
		$Content .= $attachmentGrid;
		$Content .= $PageOptions;
		
	$Content .= '</div>'."\r\n";
	
	echo $Content;
?>