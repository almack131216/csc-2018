<?php get_header(); ?>

<div class="photos-wrap-rows">
<?php

    $PhotoPage = true;
    $currentpage = $_GET['currPage'];
    if(!$currentpage) $currentpage = "catalogue";
    $currentpageSub = "photos";
    $postId = $_GET['uid'];
    $postId = 6376;
    
    $Content = '';
    if($_SERVER['HTTP_HOST']!="localhost") include("product-photos/Zip.php");
    if($_SERVER['HTTP_HOST']!="localhost") include("zip.php");
    echo '<script src="'.get_template_directory_uri().'/product-photos/photos.js"></script>';
    echo '<script src="'.get_template_directory_uri().'/product-photos/print.js"></script>';     
    
    /* GET post... */
    $itemArr = get_post( $postId );

    if( $itemArr ) {
        $GLOBALS['pageType'] = 'single';        

        $PageOptions = '';		
        $PageOptions.= '<ul>';
        
        $PrevPage = "javascript:history.go(-1);";
        $PageOptions .= '<li><a href="'.$PrevPage.'" title="Return to previous page" class="BackBut">Back</a></li>';
        
        if($_SERVER['HTTP_HOST']!="localhost"){
            $tmpName = $ItemData['name'];
            // $ZipSRC = $SEO_links->GenerateLink(array('type'=>'zip','name'=>$tmpName,'itemID'=>$itemID));                
            $SanitizeName = $getAttributes['name'];
            $ZipFileName = $getAttributes['itemID'].'_'.$SanitizeName;
            $pageURL = 'zips/'.$ZipFileName.'.zip';
            $ZipSRC = $pageURL;
            $result = $ZipImages->create_zip($files_to_zip,$ZipSRC);
            $PageOptions.= '<li><a href="force-download.php?file='.$ZipSRC.'" class="disk" title="Download - Save ALL Photos">Save ALL Photos to zip file</a></li>';
        }
        $PageOptions.= '<li><a href="JavaScript:window.print();" class="print" title="Print ALL Photos">Print ALL Photos</a></li>';
        $PageOptions.= '</ul>';

        $status = get_post_meta( $postId, 'csc_car_status', true);
        $year = get_post_meta( $postId, 'csc_car_year', true);
        $price = get_post_meta( $postId, 'csc_car_price', true);
        $price_details = get_post_meta( $postId, 'csc_car_price_details', true);
        $itemTitle = amactive_custom_title( $itemArr->post_title, $year );
        $GLOBALS['postPageTitle'] = $itemTitle;

        echo '<div class="row"><div class="col-xs-12 text-align-center"><div class="post-photos-nav">'.$PageOptions.'</div></div></div>';
        echo '<div class="row"><div class="col-xs-12 text-align-center"><div class="post-photos-title">';
        echo '<h1>'.$itemTitle.'</h1>';
        // if($ItemData['category']==$clientCats['Classifieds'] && $status==1){
        if ( $price ){
            echo '<h3>';
            echo amactive_my_custom_price_format( $price );            
            if(!empty($price_details)) echo '&nbsp;<span class="price_details">('.$price_details.')</span>';
            echo '</h3>';
        } 
        
        echo '</div></div></div>';

        //REF: https://wordpress.stackexchange.com/questions/107696/in-array-doesnt-recognize-category
        $postCategories = wp_get_post_categories( $postId );
        $categories = array();
        $category_ids = array();

        //REF: https://stackoverflow.com/questions/45417125/how-to-exclude-specific-category-and-show-only-one-from-the-get-the-category
        // if ($categoryArr) :
        // get all categories for this post
        // foreach ( get_the_category($categories) as $category ) {
        //     if ( !in_array( $category, $category_ids ) ) {
        //         $category_ids[] = $category;
        //         // $categories[] = $category;
        //     }
        // }
        foreach ( $postCategories as $category ) {
            if ( !in_array( $category, $category_ids ) ) {
                amactive_debug($category);
                $category_ids[] = $category;
                $categories[] = get_category($category);
            }
        }
        print_r( $categories );
        amactive_set_category_globals( $category_ids, $categories );

        // print_r( $category_ids );
        amactive_debug('postPageCategoryName: '.$GLOBALS['postPageCategoryName']);
        amactive_debug('postPageSubCategoryName: '.$GLOBALS['postPageSubCategoryName']);

        // REF: https://github.com/jchristopher/attachments/blob/master/docs/usage.md
        // retrieve all Attachments for the 'attachments' instance of post 123
        $attachments = new Attachments( 'attachments', $postId );

        if( $attachments->exist() ):
            $i=0;
            $files_to_zip = array();
            $attachmentCount = $attachments->total();

            $attachmentGrid = '';
            $attachmentGrid .= '<div class="row">';
            while( $attachments->get() ) :
                $imgSRC = $attachments->src( 'full' );
                $files_to_zip[] = $imgSRC;
                $PhotoID = 'FullPhoto_'.$i;
                $PhotoTitle = $attachments->field( 'title' );

                if($i>0 && !is_float($i/2)) $attachmentGrid.= '<div class="PageBreak"><span>Page Break</span></div>';                

                $attachmentGrid .= '<div class="col-xs-12 text-align-center">';

                $attachmentGrid.= '<div class="PhotoBox">';
                // $attachmentGrid .= '<a href="'. $attachments->src( 'full' ) .'" title="'. $attachments->field( 'title' ) .'" class="foobox" rel="gallery">';
                $attachmentGrid .= '<img src="'.$attachments->src( 'large' ).'" id="'.$PhotoID.'">';
                // $attachmentGrid .= '</a>';

                $attachmentGrid.= '<div class="PhotoBoxFooter">';
                    if($PhotoTitle) $attachmentGrid.= '<p>'.$PhotoTitle.'</p>';
                    $attachmentGrid.= '<ul class="PhotoOptions">';
                    //$Photos.= '<li><a href="javascript:printImg(\''.$PhotoID.'\')" class="print">Print Photo</a></li>';
                    $attachmentGrid.= '<li><a href="javascript:printme(\''.$PhotoTitle.'\',\''.$PhotoID.'\')" class="print">Print Photo</a></li>';
                    // $attachmentGrid.= '<li><a href="'.$SiteFunctions->FileDownloadLink($gp_uploadPath['large'].$row[2]).'" class="disk" title="Download - Save this photo">Save Photo</a></li>';
                    $attachmentGrid.= '</ul>';
                $attachmentGrid.= '</div>';

                $attachmentGrid .= '</div>';


                $attachmentGrid .= '</div>';
                $i++;
            endwhile;
            $attachmentGrid .= '</div>';
        endif;

        echo $attachmentGrid;

        
    }

    // echo $itemArr->post_title;

    if($ItemData['status']==2) $currentpage = "archive";
    if($ItemData['category']==$clientCats['Testimonials']) $currentpage = "testimonials";
    if($ItemData['category']==$clientCats['News']) $currentpage = "news";

    $itemFullName = $ItemData['name'];

    $Content .= '<div class="contentbox" id="FullPhotos">';
    
        $Photos = '';
        $Photos .= '<h1>'.$itemFullName.'</h1>';		

        if($ItemData['category']==$clientCats['Classifieds'] && $ItemData['status']==1){
            $Photos.='<p><strong>Price: </strong>';
            if($ItemData['price']) $Photos.=$CMSTextFormat->Price_StripDecimal($ItemData['price']);
            if(!empty($ItemData['price_details'])) $Photos.='&nbsp;<span class="price_details">&#40;'.$ItemData['price_details'].'&#41;</span>';
            $Photos.= '</p>';
        }			

        
        
        $query = "SELECT id,name,image_large FROM catalogue WHERE id=$itemID OR id_xtra=$itemID ORDER BY id ASC";
        $result = mysql_query($query);
        if($result && mysql_num_rows($result)>=1){
            $PhotoTitle = '';
            $PrimaryPhotoTitle = '';
            $files_to_zip = array();
            $ii=0;
            for($i=0;$i<mysql_num_rows($result);$i++){
                $row = mysql_fetch_row($result);
                
                $imgSRC = $gp_uploadPath['large'].$row[2];
                if($CMSShared->FileExists($imgSRC)){
                    $ii++;
                    $files_to_zip[] = $imgSRC;
                    $PhotoID = 'FullPhoto_'.$i;
                    if($row[1]){
                        $PhotoTitle = $row[1];
                        if(!$PrimaryPhotoTitle) $PrimaryPhotoTitle = $PhotoTitle;
                    }else{
                        $PhotoTitle = $PrimaryPhotoTitle;
                    }
                    
                    if($ii>0 && !is_float($ii/2)) $Photos.= '<div class="PageBreak"><span>Page Break</span></div>';
                    $Photos.= '<div class="PhotoBox">';
                        $Photos.= '<img src="'.$imgSRC.'" alt="'.$PhotoTitle.'" id="'.$PhotoID.'">';
                        $Photos.= '<div class="PhotoBoxFooter">';
                            if($row[1]) $Photos.= '<p>'.$PhotoTitle.'</p>';
                            $Photos.= '<ul class="PhotoOptions">';
                            //$Photos.= '<li><a href="javascript:printImg(\''.$PhotoID.'\')" class="print">Print Photo</a></li>';
                            $Photos.= '<li><a href="javascript:printme(\''.$PhotoTitle.'\',\''.$PhotoID.'\')" class="print">Print Photo</a></li>';
                            $Photos.= '<li><a href="'.$SiteFunctions->FileDownloadLink($gp_uploadPath['large'].$row[2]).'" class="disk" title="Download - Save this photo">Save Photo</a></li>';
                            $Photos.= '</ul>';
                        $Photos.= '</div>';
                    

                        $Photos .= '<noscript><p class="noscript">You have JavaScript turned off. So the \'Print Photo\' feature will not work.</p></noscript>';

                    $Photos.= '</div>';
                    if($ii==1) $Photos.= '<div class="PrintHidden"><p>&nbsp;</p>'.$ContactDetails->PrintContactDetails("footer").'</div>';
                }
                
            }
        }
        
        
        
        // $Content .= $PageOptions;
        // $Content .= $Photos;
        // $Content .= $PageOptions;
        
    $Content .= '</div>'."\r\n";        
    
    echo $Content;
?>
</div>

<?php get_footer(); ?>