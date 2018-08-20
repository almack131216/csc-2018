<?php
    /*
    BATCH SCRIPT FOR MIGRATING ITEMS TO POSTS
    *
    URLS:
    *
    1. move items from catalogue to wp_posts
    > ?subcategory=ferrari&migrate=1
    *
    2. add attachments
    > ?subcategory=ferrari&attachments=[postID]
    http://localhost:8080/classicandsportscar.ltd.uk/___migrate_id_xtras-attachments___/?subcategory=ferrari&attachments=4318
    *
    3. delete posts from wp_posts
    > ?subcategory=ferrari&delete=1
    */
    get_header();
    global $wpdb;

    $errorsArr = [];
    $thisPageUrl = get_bloginfo('url').'/?page_id=2839';//&category='.$getCategory.'&subcategory='.$getSubcategory.'&attachments='.$getAttachments;   
    
    $postsAddedArr = array();
    $fb_show_q_success = false;

    $sqlParentOrChild = 'id_xtra=0';

    $getSubcategoryCount = $_REQUEST['subcategoryCount'] ? $_REQUEST['subcategoryCount'] : false;
    $getMigrate = $_REQUEST['migrate'] ? $_REQUEST['migrate'] : false;
    // $getDelete = $_REQUEST['delete'] ? $_REQUEST['delete'] : false;
    $getDeletePost = $_REQUEST['delete-post'] ? $_REQUEST['delete-post'] : false;
    $getDeleteSubcategory = $_REQUEST['delete-subcategory'] ? $_REQUEST['delete-subcategory'] : false;
    // $getDeleteBespoke = "DELETE FROM wp_postmeta WHERE meta_value LIKE '%2009/%'";
    
    $getCategory = $_REQUEST['category'] ? $_REQUEST['category'] : false;
    if($getCategory){
        $getCategory = $getDeleteCategory ? $getDeleteCategory : $getCategory;
        $categoryIds = amactive_get_category($getCategory);
        $categoryIdOld = $categoryIds[0];
        $categoryIdNew = $categoryIds[1];

        $thisPageUrl .= '&category='.$getCategory;
        $sqlParentOrChild .= ' AND category='.$categoryIdOld;
    }

    $getSubcategory = $_REQUEST['subcategory'] ? $_REQUEST['subcategory'] : false;
    if($getSubcategory || $getDeleteSubcategory){
        $getSubcategory = $getDeleteSubcategory ? $getDeleteSubcategory : $getSubcategory;
        $subcategoryIds = amactive_get_subcategory($getSubcategory);
        $subcategoryIdOld = $subcategoryIds[0];
        $subcategoryIdNew = $subcategoryIds[1];

        $thisPageUrl .= '&subcategory='.$getSubcategory;
        $sqlParentOrChild .= ' AND subcategory='.$subcategoryIdOld;
    }

    $getAttachments = $_REQUEST['attachments'] ? $_REQUEST['attachments'] : false;
    if( $getAttachments ) {
        $thisPageUrl .= '&attachments='.$getAttachments;
    }
    
    $getItem = $_REQUEST['item'] ? $_REQUEST['item'] : null;
    $getPost = $_REQUEST['post'] ? $_REQUEST['post'] : null;
    // $dateTimeToday = amactive_getDatetimeNow();//'2018-08-14 00:00:00';
    // echo '$dateTimeToday: '.$dateTimeToday;
    
    // $statusArr = [0,1,2];

    $sql_Select = "SELECT * FROM catalogue";
    $sql_Where = " WHERE $sqlParentOrChild";
    $sql_OrderBy = " ORDER BY id ASC";
    $sql_Limit = $_REQUEST['limit'] ? ' LIMIT '.$_REQUEST['limit'] : '';
    
?>
<div class="row bg-accent">
    <div class="hidden-md-down col-lg-3 col-no-padding">
        <?php get_sidebar(); ?>
    </div>
    <div class="col-md-12 col-lg-9 padding-x-0 bg-white">
        <?php

            if ( !$getMigrate || ($getMigrate && !$getSubcategory) ) {
                $landingPage = '<h1>BATCH OPTIONS</h1>';                

                if( !$getCategory ) {
                    amactive_get_category( null );
                    $landingPage .= '<ul>';
                    foreach(array_keys($catsArr) as $key){
                        $landingPage .= '<li>';
                        $landingPage .= $key.' | ';                        
                        $landingPage .= '<a href="'.get_bloginfo('url').'/?page_id=2839&migrate=1&category='.$key.'">BATCH</a>';
                        $landingPage .= '</li>';
                    }
                    $landingPage .= '</ul>'; 
                }

                if( $getCategory && !$getSubcategory ) {
                    amactive_get_subcategory( null );
                    $landingPage .= '<ul>';
                    foreach(array_keys($subcatsArr) as $key){
                        $migrateLink = get_bloginfo('url').'/?page_id=2839&migrate=1&category='.$getCategory.'&subcategory='.$key;
                        $deleteLink = get_bloginfo('url').'/?page_id=2839&category='.$getCategory.'&delete-subcategory='.$key;

                        $landingPage .= '<li>';
                        $landingPage .= $getCategory.' > '.$key.' | ';  
                        $landingPage .= '<a href="'.$migrateLink.'">MIGRATE</a>';
                        $landingPage .= ' [<a href="'.$migrateLink.'&force=1">+FORCE</a>]';
                        
                        $landingPage .= ' ----------- '; 
                        $landingPage .= '<a href="'.$deleteLink.'">DELETE</a>';
                        $landingPage .= ' [<a href="'.$deleteLink.'&force=1">+FORCE</a>]';
                        $landingPage .= '</li>';
                    }
                    $landingPage .= '</ul>'; 
                }                          

                echo $landingPage;
            }

            if( $getDeleteBespoke ) {
                $isDeleting = true;
                amactive_debug_info($deleteBespoke);
                $result = $wpdb->query($deleteBespoke);
                amactive_debug_if_error($wpdb->last_error);
                if($result) amactive_debug_success($wpdb->last_query);                                   
            }
            /* (END) if($getDeleteBespoke)... */

            if( $getDeleteSubcategory ) {
                if( !$getCategory ) {
                    amactive_debug_error('DELETE SUBCATEGORY > CATEGORY NOT SET');
                } else {
                    $isDeleting = true;
                    $errorsArr[] = 'Error!';
                    $q = $sql_Select.$sql_Where.$sql_OrderBy;
                    amactive_batch_delete_all( $q );
                    amactive_debug_info( $q );   
                }                                                
            }
            /* (END) if($getDeleteSubcategory)... */

            /* GET SINGLE POST */
            if($getPost || $getDeletePost) {
                echo '<h1>POST: '.$getPost.'</h1>';

                $getPost = $getDeletePost ? $getDeletePost : $getPost;
                $postQuery = "SELECT * FROM wp_posts WHERE ID=$getPost LIMIT 1";
                amactive_debug_info($postQuery);
                $thisPost = $wpdb->get_row( $postQuery );
                if(!$thisPost) {
                    amactive_debug_error('POST NOT FOUND');
                }else{
                    $thisPostId = $thisPost->ID;

                    $this_post_arr = new stdClass;
                    $this_post_arr->id = $thisPostId;
                    $this_post_arr->id_attachment = $thisPostId;                    

                    $linkViewPost = '';
                    
                    $itemTable = '<table>';
                    $itemTable .= '<tr><th>ID</th><th>IMG</th><th>Options</th></tr>';
                    $itemTable .= '<tr>';
                    $itemTable .= '<td>'.$getPost.'</th>';
                    $itemTable .= '<th>'.get_the_post_thumbnail( $getPost ).'</th>';
                    $itemTable .= '<th>';
                    $itemTable .= '<a href="'.get_bloginfo('url').'/?page_id=2839&delete-post='.$thisPostId.'">DELETE POST</a>';
                    $itemTable .= '<br><a href="'.$linkViewPost.'">VIEW POST</a>';
                    $itemTable .= '</th>';
                    $itemTable .= '</tr>';
                    $itemTable .= '</table>';

                    echo $itemTable;

                    if($getDeletePost){
                        $isDeleting = true;
                        echo '<h2>Deleting post '.$getDeletePost.'...</h2>';
                        amactive_batch_delete_single( $this_post_arr );
                    }

                }
            }


            if( !$categoryIds ){
                amactive_debug_error('CATEGORY NOT SET');
                // exit();
            }

            if( !$subcategoryIds ){
                amactive_debug_error('SUBCATEGORY NOT SET');
                // exit();
            }else{
                $getImgFrom = 'classicandsportscar-img/images_catalogue/'.$getSubcategory.'/';//get_home_url()                
                amactive_debug_info('SUBCATEGORY: '.$subcategoryIdOld.' -> '.$subcategoryIdNew.' ('.$getSubcategory.')');                
            }    

            /*
            IF getting attachments...
            get POST row, because we need it later as a PARENT
            */
            if( $categoryIds && $subcategoryIds && $getAttachments ){                
                $getImgFrom .= '/xtra/';//'/'.$itemId.'/';

                amactive_debug_step('POST: '.$getAttachments);
                // if($getPost){
                    $xtraQuery = "SELECT * FROM amactive_migrated WHERE id_after=$getAttachments LIMIT 1";
                // }else{
                //     $xtraQuery = "SELECT * FROM amactive_migrated WHERE id_before=$getItem LIMIT 1";
                // }
                amactive_debug_info($xtraQuery);
                $thePost = $wpdb->get_row( $xtraQuery );
                if($thePost) {
                    $addXtrasParent = $thePost;
                    $addXtrasParentId = $thePost->id_before;
                    // $itemId = $thePost->id_before;
                    amactive_debug_success('GET FROM amactive_migrated > ITEM: '.$addXtrasParentId.', POST: '.$addXtrasParent->id_after.' ('.sizeof($thePost).')');
                // } else {
                //     amactive_debug_error('NO results found');
                }
            }


            /*
            *****
            START batch scripts...
            *****
            */
            if( !$isDeleting && ($categoryIds && $subcategoryIds) && ($getAttachments || $getMigrate) ) {
                if($addXtrasParentId) {
                    $sql_Where = " WHERE (id_xtra=$addXtrasParentId)";//($sqlParentOrChild AND id=$addXtrasParentId) OR
                    // $sql_Where .= " AND migrated=1";                                        
                // }else{
                //     $sql_Where .= " AND migrated=0";
                }

                if( !$_REQUEST['force'] ) $sql_Where .= " AND migrated=0";
                
                $resultsFount = $wpdb->get_results($sql_Select.$sql_Where.$sql_OrderBy.$sql_Limit);// LIMIT 3
                amactive_debug_if_error($wpdb->last_error);
                amactive_debug_info($wpdb->last_query);

                $debug_count = 0;
                $debug_counted = 0;
                amactive_debug_title(sizeof($resultsFount).' ITEMS found...');

                if( !sizeof($resultsFount) ) {
                    if ($getAttachments) amactive_debug_suggest('<a href="'.$thisPageUrl.'&force=1" target="_blank">Try again +FORCE</a>');
                } else {
                    $debug_count = sizeof($resultsFount);
                    foreach($resultsFount as $wp_formmaker_submits){
                        

                        /* INIT | $item_arr */
                        $item_arr = $wp_formmaker_submits;
                        $switch_item_status_category_name = $item_arr->status == 2 ? 'classic-cars-sold' : 'classic-cars-for-sale';                    
                        
                        /* INIT | $new_post_arr */
                        $new_post_arr = new stdClass;
                        $new_post_arr->id = null;
                        // $new_post_arr->categorySlug = $getCategory;
                        // $new_post_arr->subcategorySlug = $getSubcategory;

                        $tmpStripSpecialChars = amactive_strip_special_chars($item_arr->name);
                        $new_post_arr->name = sanitize_title_with_dashes( $tmpStripSpecialChars, $unused, $context = 'display' );

                        if(!$addXtrasParent) {
                            $new_post_arr->category = $categoryIdNew;
                            $new_post_arr->subcategory = $subcategoryIdNew;                        
                            $new_post_arr->date = $item_arr->upload_date.' 00:00:00';
                            $new_post_arr->date_gmt = $item_arr->upload_date.' 00:00:00';
                        } else{
                            $new_post_arr->id = $addXtrasParent->id_after;
                            if(!$new_post_arr->name) $new_post_arr->name = $addXtrasParent->name;                            
                            $new_post_arr->fileNameRaw = $new_post_arr->name.'_'.$item_arr->id;
                            $new_post_arr->date = $addXtrasParent->date_after;//id_xtra items have no date
                            $new_post_arr->date_gmt = $addXtrasParent->date_after;
                        }
                        

                        //REF: https://stackoverflow.com/questions/18096555/how-to-insert-data-using-wpdb               

                        $imgDateArr = explode("-", $new_post_arr->date);
                        $imgYear = $imgDateArr[0]; // year
                        $imgMonth = $imgDateArr[1]; // month
                        $imgDir = $imgYear.'/'.$imgMonth.'/';
                        $filepath_before = $getImgFrom.$item_arr->image_large;
                        
                        if( ($getMigrate || $getAttachments) && (@is_array(getimagesize($filepath_before)) && file_exists($filepath_before)) ) {
                            $imgExists = true;
                        }

                        if(!$imgExists){
                            echo 'POST ID: '.$new_post_arr->id;
                            amactive_debug_error('!!! CANNOT FIND IMAGE for item#<a href="http://www.classicandsportscar.ltd.uk/'.$new_post_arr->name.'/'.$switch_item_status_category_name.'/'.$item_arr->id.'" target="_blank">'.$item_arr->id.'</a> - '.$filepath_before.' <img src="'.$filepath_before.'"> !!!');
                        }else {
                            // POST
                            /*
                            **********
                            STEP 1: INSERT item INTO wp_posts
                            **********
                            */
                            if(!$addXtrasParent && $item_arr->id){
                                amactive_debug_title('STEP 1: INSERT item INTO wp_posts '.print_r(getimagesize($filepath_before)));  

                                $args = amactive_prepare_post_arr(array(
                                    'post_arr'  => $new_post_arr,
                                    'item_arr'  => $item_arr,
                                    'type'      => 'post'
                                ));          
                                $result_step1_insertPost = $wpdb->insert('wp_posts', $args);
                                if($wpdb->last_error) amactive_debug_error('INSERT POST LQE: '.$wpdb->last_error);                        
                                
                                if($result_step1_insertPost){
                                    $new_post_arr->id = $wpdb->insert_id;
                                    $new_post_arr->fileNameRaw = $new_post_arr->name.'_'.$new_post_arr->id;
                                    $postsAddedArr[] = $new_post_arr->id;

                                    amactive_debug_success('INSERT > wp_posts > POST ID: '.$new_post_arr->id);
                                    if($fb_show_q_success) amactive_debug_success($wpdb->last_query);
                                }
                            // } elseif($addXtrasParent) {
                                
                            }

                            /*
                            **********
                            STEP 1.2: INSERT post for ATTACHMENT
                            **********
                            */                                           
                            // REF: https://codex.wordpress.org/Function_Reference/wp_check_filetype
                            amactive_debug_step('STEP 1.2: INSERT post for ATTACHMENT'); 
                            $tmpMimeType = wp_check_filetype( $item_arr->image_large );
                            $new_post_arr->fileType = $tmpMimeType['type'];
                            $new_post_arr->fileName = $new_post_arr->fileNameRaw.'.'.$tmpMimeType['ext'];
                            $new_post_arr->fileNameWithDir = 'wp-content/uploads/'.$imgDir.$new_post_arr->fileName;
                            amactive_debug_info('MIME TYPE: '.$tmpMimeType['ext'].' / '.$new_post_arr->fileNameWithDir); 
                            $fileCopied = copy( $filepath_before, $new_post_arr->fileNameWithDir );

                            if(!$fileCopied){
                                amactive_debug_error('COULD NOT MOVE IMAGE - maybe destination dir does not exist?');
                                amactive_batch_delete_single($new_post_arr);
                                $result_addPostAttachment = false;
                                //wp_term_relationships
                                //wp_term_relationships
                                //wp_posts > REVISION ID: 3837
                            }else{
                                amactive_debug_info('PATH BEFORE: '.$filepath_before);
                                amactive_debug_success('PATH AFTER: '.$new_post_arr->fileNameWithDir);                        
                                // $filename_without_extension = substr($filename, 0, strrpos($filename, "."));

                                $args_img = amactive_prepare_post_arr(array(
                                    'post_arr'  => $new_post_arr,
                                    'item_arr'  => $item_arr,
                                    'type'      => 'attachment'
                                ));                            
                                $result_addPostAttachment = $wpdb->insert('wp_posts', $args_img);
                                amactive_debug_if_error($wpdb->last_error);

                                if($result_addPostAttachment){
                                    $new_post_arr->id_attachment = $wpdb->insert_id;
                                    amactive_debug_success('INSERT > wp_posts > ATTACHMENT ID: '.$new_post_arr->id_attachment);
                                    if($fb_show_q_success) amactive_debug_success($wpdb->last_query);

                                    // STEP 6.3: INSERT postmeta for ATTACHMENT
                                    amactive_batch_insert_postmeta( array(
                                        'post_arr'  => $new_post_arr,
                                        'type'      => 'attachment'
                                    ));

                                    $args_postmeta = array(
                                        'post_id' => $new_post_arr->id_attachment,
                                        'meta_key' => '_wp_attached_file',
                                        'meta_value' => $imgDir.$new_post_arr->fileName
                                    );
                                    $wpdb->insert('wp_postmeta', $args_postmeta);
                                    $media_id = $wpdb->insert_id;

                                    /*
                                    SET _wp_attachment_metadata
                                    REF: https://wordpress.stackexchange.com/questions/238294/programmatically-adding-images-to-the-media-library-with-wp-generate-attachment
                                    REF: https://developer.wordpress.org/reference/functions/wp_insert_attachment/
                                    */
                                    // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
                                    require_once( ABSPATH . 'wp-admin/includes/image.php' );                            
                                    // Generate the metadata for the attachment, and update the database record.
                                    $attach_data = wp_generate_attachment_metadata( $new_post_arr->id_attachment, $new_post_arr->fileNameWithDir );
                                    // amactive_debug_step('??? > metadata > '.$new_post_arr->id_attachment.' > '.json_encode($attach_data));
                                    wp_update_attachment_metadata( $new_post_arr->id_attachment, $attach_data );                                        
                                }
                                /* (ENDIF) STEP 1.2 */

                            }
                            /* (COPYING FILE...) */

                            /*
                            **********
                            STEP 2: UPDATE post guid
                            **********
                            */
                            if($result_addPostAttachment){
                                amactive_debug_step('STEP 2: UPDATE post guid & post_name...');
                                $result_updatePost = $wpdb->update(
                                    'wp_posts',
                                    array(
                                        'guid' => 'http://localhost:8080/classicandsportscar.ltd.uk/?p='.$new_post_arr->id,
                                        'post_name' => $new_post_arr->name.'_'.$new_post_arr->id
                                    ),
                                    array('ID' => $new_post_arr->id)
                                );
                                amactive_debug_info($wpdb->last_query);
                                amactive_debug_if_error($wpdb->last_error);
                                if($result_updatePost !== false){
                                    // $new_post_arr->id = $wpdb->insert_id;
                                    amactive_debug_success('UPDATE > wp_posts > guid & post_name');
                                    if($fb_show_q_success) amactive_debug_success($wpdb->last_query);

                                    /*
                                    **********
                                    STEP 2.2: INSERT postmeta for POST
                                    **********
                                    */
                                    amactive_debug_step('STEP 2.2: INSERT postmeta for POST');
                                    $result_addPostmeta = $wpdb->insert('wp_postmeta', array(
                                        'post_id' => $new_post_arr->id,
                                        'meta_key' => '_edit_last',
                                        'meta_value' => 1
                                    ));
                                    amactive_debug_if_error($wpdb->last_error);
                                    
                                    if($result_addPostmeta){
                                        // $debug_counted++;
                                        amactive_wp_set_post_lock($new_post_arr->id);//REF: http://hookr.io/functions/wp_set_post_lock/ 
                                        amactive_debug_success('INSERT > wp_postmeta > _edit_last');

                                        // postmeta
                                        amactive_batch_insert_postmeta( array(
                                            'post_id'   => $new_post_arr->id,
                                            'item_arr'  => $item_arr,
                                            'type'      => 'post'
                                        ));                                        
                                    }
                                    /* (ENDIF) STEP 2.2 */                                
                                }
                            }
                            /* (ENDIF) STEP 2 */

                            /*
                            **********
                            STEP 3: INSERT categories INTO wp_term_relationships for POST
                            **********
                            */
                            if(!$addXtrasParent && $result_addPostAttachment && $result_updatePost){
                                amactive_debug_step('STEP 3: INSERT categories INTO wp_term_relationships for POST');                        

                                $result_insertCategory = $wpdb->insert('wp_term_relationships', array(
                                    'object_id' => $new_post_arr->id,
                                    'term_taxonomy_id' => DV_category_IsForSale_id
                                ));
                                amactive_debug_if_error($wpdb->last_error);

                                // $result_insertCategory = $wpdb->insert('wp_term_relationships', array(
                                //     'object_id' => $new_post_arr->id,
                                //     'term_taxonomy_id' => $new_post_arr->category
                                // ));
                                // amactive_debug_if_error($wpdb->last_error);
                                
                                $result_insertSubcategory = $wpdb->insert('wp_term_relationships', array(
                                    'object_id' => $new_post_arr->id,
                                    'term_taxonomy_id' => $new_post_arr->subcategory
                                ));
                                amactive_debug_if_error($wpdb->last_error);
                                
                                if($result_insertCategory && $result_insertSubcategory){
                                    amactive_debug_success('INSERT > wp_term_relationships > cats: ['.$categoryIdNew.','.$new_post_arr->subcategory.']');
                                    if($item_arr->status == 2){
                                        $result_insertIsSold = $wpdb->insert('wp_term_relationships', array(
                                            'object_id' => $new_post_arr->id,
                                            'term_taxonomy_id' => DV_category_IsSold_id
                                        ));
                                        if($result_insertIsSold) amactive_debug_success('INSERT > wp_term_relationships > cats: '.DV_category_IsSold_id.' (SOLD)');
                                        amactive_debug_if_error($wpdb->last_error);
                                    }
                                    if($fb_show_q_success) amactive_debug_success($wpdb->last_query);                            

                                    // REVISION
                                    /*
                                    **********
                                    STEP 4: INSERT post revision-v1
                                    **********
                                    */
                                    amactive_debug_step('STEP 4: INSERT post revision-v1');
                                    // revision
                                    $args['post_modified'] = $new_post_arr->date;//$dateTimeToday;
                                    $args['post_modified_gmt'] = $new_post_arr->date_gmt;//$dateTimeToday;
                                    $args['post_name'] = $new_post_arr->id.'-revision-v1';
                                    $args['post_status'] = 'inherit';
                                    $args['post_parent'] = $new_post_arr->id;
                                    $args['guid'] = 'http://localhost:8080/classicandsportscar.ltd.uk/'.$new_post_arr->id.'-revision-v1/';
                                    $args['post_type'] = 'revision';

                                    $result_step4 = $wpdb->insert('wp_posts', $args);
                                    amactive_debug_if_error($wpdb->last_error);

                                    if($result_step4){
                                        //$debug_counted++;
                                        $revision_id = $wpdb->insert_id;
                                        amactive_debug_success('INSERT > wp_posts > REVISION ID: '.$revision_id);
                                        if($fb_show_q_success) amactive_debug_success($wpdb->last_query);

                                        // STEP 4.2: INSERT postmeta for REVISION
                                        $wpdb->insert('wp_postmeta', array(
                                            'post_id' => $revision_id,
                                            'meta_key' => '_edit_last',
                                            'meta_value' => 1
                                        ));
                                        amactive_wp_set_post_lock($revision_id);

                                        amactive_batch_insert_postmeta( array(
                                            'post_id'   => $revision_id,
                                            'item_arr'  => $item_arr,
                                            'type'      => 'revision'
                                        ));                                    

                                        /*
                                        **********
                                        STEP 5: INSERT migrated reference
                                        **********
                                        */
                                        amactive_debug_step('STEP 5: INSERT migrated reference');
                                        $args_migrated = array(
                                            'id_before' => $item_arr->id,
                                            'id_after' => $new_post_arr->id,
                                            'id_after_revision' => $revision_id,
                                            'id_after_attachment' => $new_post_arr->id_attachment,
                                            'name' => $new_post_arr->name,
                                            'date_before' => $item_arr->upload_date,
                                            'date_after' => $new_post_arr->date,
                                            'date' => $new_post_arr->date
                                        );
                                        // $query = 'INSERT INTO amactive_migrated (id_before,id_after) VALUES ('.$args_migrated['id_before'].','.$args_migrated['id_after'].')';
                                        $result_step5 = $wpdb->insert('amactive_migrated', $args_migrated);
                                        amactive_debug_if_error($wpdb->last_error);

                                        if(!$errorsArr && $result_step5){
                                            $debug_counted++;
                                            $migrated_id = $wpdb->insert_id;
                                            amactive_debug_success('INSERT > amactive_migrated > id: '.$migrated_id);
                                            if($fb_show_q_success) amactive_debug_success($wpdb->last_query);

                                            $tableSuccess = amactive_batch_print_post( array(
                                                'item_arr' => $item_arr,
                                                'post_arr' => $new_post_arr
                                            ));                                    
                                            echo $tableSuccess;
                                        }
                                        /* (ENDIF) STEP 5 */
                                    }
                                    /* (ENDIF) STEP 4 */
                                }
                                /* (ENDIF) STEP 3 */ 
                            }    

                            amactive_debug_title('successfully added '.$debug_counted.' from '.$debug_count);

                            if($debug_counted == $debug_count){
                                amactive_debug_success('successfully added ALL');
                            }

                            if(!$errorsArr){
                                amactive_debug_step('STEP 6: UPDATE catalogue migrate');

                                $updateCatalogue = $wpdb->update('catalogue',
                                    array('migrated' => 1),
                                    array('id' => $item_arr->id)
                                );                                        
                                if ($updateCatalogue){
                                    amactive_debug_success('UPDATE > catalogue > migrated=1');
                                }
                                amactive_debug_if_error($wpdb->last_error);
                            }

                            

                            if($addXtrasParent){
                                //$new_post_arr->id = $addXtrasParent->id_after;
                                amactive_debug_step('ADD ATTACHMENTS TO wp_postmeta ? attachments...');
                                $tmpQuery = "SELECT * FROM wp_postmeta WHERE post_id=".$new_post_arr->id." AND meta_key='attachments' LIMIT 1";
                                
                                amactive_debug_info($tmpQuery);
                                $postmeta_attachmentRow = $wpdb->get_row( $tmpQuery );
                                if($postmeta_attachmentRow) {
                                    $attachmentArrAsString = $postmeta_attachmentRow->meta_value;
                                    // print_r($postmeta_attachmentRow);
                                    amactive_debug_success('SELECT FROM wp_postmeta > SUCCESS > Record already exists');
                                    // amactive_debug_step('ARR > before: '.$attachmentArrAsString);
                                    $attachmentArr = json_decode($attachmentArrAsString, true);

                                    amactive_migrate_item_attachments( $postmeta_attachmentRow->meta_id, $attachmentArr, $new_post_arr );
                                } else {
                                    amactive_debug_info('SELECT FROM wp_postmeta > NO RECORD > Create one...');
                                    amactive_migrate_item_attachments( null, null, $new_post_arr );                                    
                                }
                            }

                        }
                        /* (END) COPY... */
                    }
                    /* (END) foreach */  
                }
                /* (END) !sizeof($resultsFount) */
                
                              
            }


            /*
            ******
            UPDATE category count after batch scripts...
            ******
            */
            if($getSubcategory && $getSubcategoryCount){
                // REF: https://wordpress.stackexchange.com/questions/89241/count-posts-within-a-custom-post-type-and-specific-category
                $the_query = new WP_Query( array(
                    'post_type' => 'post',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'category',
                            'field' => 'term_id',
                            'terms' => $subcategoryIdNew
                        )
                    )
                ) );
                $categoryCount = $the_query->found_posts;                
                amactive_debug_title('BEFORE > wp_term_taxonomy > count='.$categoryCount);

                $sqlUpdateCount = $wpdb->update(
                    'wp_term_taxonomy',
                    array( 'count' => $categoryCount ),
                    array( 'term_taxonomy_id' => $subcategoryIdNew )                      
                );
                // echo $sqlUpdateCount;
                amactive_debug_info($wpdb->last_query);
                amactive_debug_success('AFTER > wp_term_taxonomy > count='.$categoryCount);
                amactive_debug_if_error($wpdb->last_error);                   
            }
            /* (END) category count */

        ?>
    </div>
</div>



<?php get_footer(); ?>