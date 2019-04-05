<?php
	$postId = $_REQUEST['uid'];

    $args = array(
        'post_parent' => $postId,
        'post_type'      => 'attachment',
        'post_mime_type' => 'image',
        'orderby' => array('post_parent,menu_order'),
        'order' => 'ASC',
        'posts_per_page' => 5
    );
    $images = get_children( $args );
    if ($images) {
        $i=0;

        $files_to_zip = array();

        $attachmentGrid = '';
        $attachmentGrid .= '<div class="row">';

        foreach ($images as $image) {
            // $zip->addFile(wp_get_attachment_url( $image->ID ));       
            // print_r( $image );
            if( amactive_is_localhost() ){
                $imgSRC = str_replace( 'http://localhost:8080/classicandsportscar.ltd.uk/', '../../', $image->guid);
            }else{
                $imgSRC = str_replace( 'http://www.classicandsportscar.ltd.uk/', '../../', $image->guid);
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

    $error = "";        //error holder
    if($postId && isset($_POST['createpdf'])){   

        // $file_folder = "zips/";    // folder to load files
        if(extension_loaded('zip')){    // Checking ZIP extension is available
            if(isset($_POST['files']) and count($_POST['files']) > 0){
                // $tmpRoot = 'http://www.classicandsportscar.ltd.uk/_wp180906/';
                // $tmpRoot = '../../';
                // $tmpFiles = array(
                //     $tmpRoot.'wp-content/uploads/2017/01/alvis-td21-series-1-5_7032.jpg',
                //     $tmpRoot.'wp-content/uploads/2017/01/alvis-td21-series-1-5_30773.jpg',
                //     $tmpRoot.'wp-content/uploads/2017/01/alvis-td21-series-1-5_30775.jpg'
                // );
                // $tmpFiles = $files_to_zip;

                $zip = new ZipArchive();            // Load zip library 
                $zip_name = time().".zip";          // Zip name
                if($zip->open($zip_name, ZIPARCHIVE::CREATE)!==TRUE){       // Opening zip file to load files
                    $error .=  "* Sorry ZIP creation failed at this time<br/>";
                }
                foreach($_POST['files'] as $file){               
                    $zip->addFile($file);          // Adding files into zip
                }
                $zip->close();
                if(file_exists($zip_name)){
                    // push to download the zip
                    header('Content-type: application/zip');
                    header('Content-Disposition: attachment; filename="'.$zip_name.'"');
                    readfile($zip_name);
                    // remove zip file is exists in temp path
                    unlink($zip_name);
                }

            }else{
                $error .= "* Please select file to zip <br/>";
            }


        }else{
            $error .= "* You dont have ZIP extension<br/>";
		}
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Download As Zip</title>
</head>
<body>
<center><h1>Create Zip</h1></center>
<form name="zips" method="post">
<?php if(!empty($error)) { ?>
<p style=" border:#C10000 1px solid; background-color:#FFA8A8; color:#B00000;padding:8px; width:588px; margin:0 auto 10px;"><?php echo $error; ?></p>
<?php } ?>


<table width="600" border="1" align="center" cellpadding="10" cellspacing="0" style="border-collapse:collapse; border:#ccc 1px solid;">
  <tr>
    <td width="33" align="center">*</td>
    <td width="117" align="center">File Type</td>
    <td width="382">File Name</td>
  </tr>

<?php

    foreach($files_to_zip as $file){ 
        echo '<tr>';
        echo '<td align="center"><input type="checkbox" name="files[]" value="'.$file.'" /></td>';
        echo '<td align="center"><img src="'.$file.'" title="Image" width="20" height="20" /></td>';
        echo '<td>'.$file.'</td>';
        echo '</tr>';
    }

?>

  <tr>
    <td colspan="3" align="center">
        <input name="uid" value="<?php echo $postId; ?>" type="text">
        <input type="submit" name="createpdf" style="border:0px; background-color:#800040; color:#FFF; padding:10px; cursor:pointer; font-weight:bold; border-radius:5px;" value="Download as ZIP" />&nbsp;
        <input type="reset" name="reset" style="border:0px; background-color:#D3D3D3; color:#000; font-weight:bold; padding:10px; cursor:pointer; border-radius:5px;" value="Reset" />
    </td>
    </tr>
</table>

</form>
</body>
</html>