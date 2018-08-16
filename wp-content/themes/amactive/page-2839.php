<?php
    // include_once( ABSPATH . 'wp-admin/includes/image.php' );
    get_header();

    global $wpdb;    
?>
<div class="row bg-accent">
    <div class="hidden-md-down col-lg-3 col-no-padding">
        <?php get_sidebar(); ?>
    </div>
    <div class="col-md-12 col-lg-9 padding-x-0 bg-white">
        <?php

            $sql_updateCategoryCount = true;

            $isDeleting = false;
            if($_REQUEST['delete']) {
                $isDeleting = true;
            }

            $dateTimeToday = amactive_getDatetimeNow();//'2018-08-14 00:00:00';
            echo '$dateTimeToday: '.$dateTimeToday;
            
            $statusArr = [0,1,2];
            $categoryId = DV_category_IsForSale_id;
            
            $subcategorySlug = 'ferrari';
            $getImgFrom = 'classicandsportscar-img/images_catalogue/'.$subcategorySlug.'/';
            $subcategoryId = amactive_get_subcategory($subcategorySlug);
            amactive_debug_info('SUBCATEGORY: '.$subcategoryId[0].' -> '.$subcategoryId[1].' ('.$subcategorySlug.')');

            $postsAddedArr = array();

            $debug_hide_postmeta = false;
            $fb_show_q_success = false;
            $fb_show_q_error = false;

            $sqlParentOrChild = 'category='.$categoryId.' AND subcategory='.$subcategoryId[0].' AND id_xtra=0';

            if($_REQUEST['getAttachments']){                
                $tmpPost = $_REQUEST['getPost'];
                $tmpItem = $_REQUEST['getItem'];
                $getImgFrom .= '/xtra/';//'/'.$itemId.'/';
                // $sqlParentOrChild = 'id_xtra='.$itemId;

                amactive_debug_step('POST: '.$tmpPost);
                if($tmpPost){
                    $xtraQuery = "SELECT * FROM amactive_migrated WHERE id_after=$tmpPost LIMIT 1";
                }else{
                    $xtraQuery = "SELECT * FROM amactive_migrated WHERE id_before=$tmpItem LIMIT 1";
                }
                amactive_debug_info($xtraQuery);
                $thepost = $wpdb->get_row( $xtraQuery );
                if($thepost) {
                    $addXtrasParent = $thepost;
                    $addXtrasParentId = $thepost->id_before;
                    // $itemId = $thepost->id_before;
                    amactive_debug_success('GET FROM amactive_migrated > ITEM: '.$addXtrasParentId.', POST: '.$addXtrasParent->id_after);
                }
            }
            // amactive_debug_info($sqlParentOrChild);
            
            $sql_Select = "SELECT * FROM catalogue";
            $sql_Where = " WHERE $sqlParentOrChild";
            $sql_OrderBy = " ORDER BY id ASC";
            $sql_Limit = "";

            if($isDeleting) {
                $isDeleting = true;
                // $deleteBespoke = "DELETE FROM wp_postmeta WHERE meta_value LIKE '%2009/%'";

                if($deleteBespoke){
                    amactive_debug_info($deleteBespoke);
                    $result = $wpdb->query($deleteBespoke);
                    amactive_debug_if_error($wpdb->last_error);
                    if($result) amactive_debug_success($wpdb->last_query);                    
                } else {
                    $q = $sql_Select.$sql_Where.$sql_OrderBy;
                    amactive_batch_delete_all( $q );                    
                }
                
            }
            /* (END) if($isDeleting)... */

            if(!$isDeleting) {
                if($addXtrasParentId) {
                    $sql_Where = " WHERE ($sqlParentOrChild AND id=$addXtrasParentId AND migrated=0) OR (id_xtra=$addXtrasParentId AND migrated=0)";
                    $sql_Limit = " LIMIT 2";
                    // $sql_Where .= " AND migrated=1";
                }else{
                    $sql_Where .= " AND migrated=0";
                }
                
                $result = $wpdb->get_results($sql_Select.$sql_Where.$sql_OrderBy.$sql_Limit);// LIMIT 3
                amactive_debug_if_error($wpdb->last_error);
                amactive_debug_info($wpdb->last_query);

                $debug_count = 0;
                $debug_counted = 0;
                amactive_debug_title(sizeof($result).' ITEMS TO ADD...');

                if(!$result && $sql_updateCategoryCount){
                    // REF: https://wordpress.stackexchange.com/questions/89241/count-posts-within-a-custom-post-type-and-specific-category
                    $the_query = new WP_Query( array(
                        'post_type' => 'post',
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'category',
                                'field' => 'term_id',
                                'terms' => $subcategoryId[1]
                            )
                        )
                    ) );
                    $categoryCount = $the_query->found_posts;
                    amactive_debug_title('category count before: '.$categoryCount);

                    $sqlUpdateCount = $wpdb->update(
                        'wp_term_taxonomy',
                        array( 'count' => $categoryCount ),
                        array( 'term_taxonomy_id' => $subcategoryId[1] )                      
                    );
                    // echo $sqlUpdateCount;
                    amactive_debug_info($wpdb->last_query);

                    if($sqlUpdateCount) {
                        amactive_debug_success('UPDATE > wp_term_taxonomy > count = '.$categoryCount);
                    }else{                        
                        amactive_debug_if_error($wpdb->last_error);
                    }                    
                }
                /* (END) !$result */
                
                foreach($result as $wp_formmaker_submits){
                    $debug_count++;

                    /* INIT | $item_arr */
                    $item_arr = $wp_formmaker_submits;
                    $switch_item_status_category_name = $item_arr->status == 2 ? 'classic-cars-sold' : 'classic-cars-for-sale';                    
                    
                    /* INIT | $new_post_arr */
                    $new_post_arr = new stdClass;
                    $new_post_arr->id = null;

                    $tmpStripSpecialChars = amactive_strip_special_chars($item_arr->name);
                    $new_post_arr->name = sanitize_title_with_dashes( $tmpStripSpecialChars, $unused, $context = 'display' );
                    $new_post_arr->category = $categoryId;
                    $new_post_arr->subcategory = $subcategoryId[1];
                    $new_post_arr->date = $item_arr->upload_date.' 00:00:00';
                    $new_post_arr->date_gmt = $item_arr->upload_date.' 00:00:00';

                    //REF: https://stackoverflow.com/questions/18096555/how-to-insert-data-using-wpdb               

                    $imgDateArr = explode("-", $item_arr->upload_date);
                    $imgYear = $imgDateArr[0]; // year
                    $imgMonth = $imgDateArr[1]; // month
                    $imgDir = $imgYear.'/'.$imgMonth.'/';
                    $filepath_before = $getImgFrom.$item_arr->image_large;
                    
                    if(@is_array(getimagesize($filepath_before)) && file_exists($filepath_before)) {
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
                                $postsAddedArr[] = $new_post_arr->id;

                                amactive_debug_success('INSERT > wp_posts > POST ID: '.$new_post_arr->id);
                                if($fb_show_q_success) amactive_debug_success($wpdb->last_query);
                            }
                        } elseif($addXtrasParent) {
                            $new_post_arr->id = $addXtrasParent->id_after;
                            $new_post_arr->name = $addXtrasParent->name;
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
                        $new_post_arr->fileName = $new_post_arr->name.'_'.$new_post_arr->id.'.'.$tmpMimeType['ext'];
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
                            amactive_debug_info('PATH AFTER: '.$new_post_arr->fileNameWithDir);                        
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
                                amactive_debug_step('??? > metadata > '.$new_post_arr->id_attachment.' > '.print_r($attach_data));
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
                            amactive_debug_step('STEP 2: UPDATE post guid & post_name');
                            $result_updatePost = $wpdb->update(
                                'wp_posts',
                                array(
                                    'guid' => 'http://localhost:8080/classicandsportscar.ltd.uk/?p='.$new_post_arr->id,
                                    'post_name' => $new_post_arr->name.'_'.$new_post_arr->id
                                ),
                                array('ID' => $new_post_arr->id)
                            );
                            amactive_debug_if_error($wpdb->last_error);
                            if($result_updatePost){
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
                                    amactive_wp_set_post_lock($new_post_arr->id);//REF: http://hookr.io/functions/wp_set_post_lock/ 
                                    amactive_debug_success('INSERT > wp_postmeta > _edit_last');

                                    // postmeta
                                    if(!$debug_hide_postmeta){
                                        amactive_batch_insert_postmeta( array(
                                            'post_id'   => $new_post_arr->id,
                                            'item_arr'  => $item_arr,
                                            'type'      => 'post'
                                        ));                                        
                                    }
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
                                amactive_debug_success('INSERT > wp_term_relationships > cats: ['.$categoryId.','.$new_post_arr->subcategory.']');
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
                                $args['post_modified'] = $dateTimeToday;
                                $args['post_modified_gmt'] = $dateTimeToday;
                                $args['post_name'] = $new_post_arr->id.'-revision-v1';
                                $args['post_status'] = 'inherit';
                                $args['post_parent'] = $new_post_arr->id;
                                $args['guid'] = 'http://localhost:8080/classicandsportscar.ltd.uk/'.$new_post_arr->id.'-revision-v1/';
                                $args['post_type'] = 'revision';

                                $result_step4 = $wpdb->insert('wp_posts', $args);
                                amactive_debug_if_error($wpdb->last_error);

                                if($result_step4){
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

                                    if(!$debug_hide_postmeta){
                                        amactive_batch_insert_postmeta( array(
                                            'post_id'   => $revision_id,
                                            'item_arr'  => $item_arr,
                                            'type'      => 'revision'
                                        ));
                                    }

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
                                        'date' => $dateTimeToday
                                    );
                                    // $query = 'INSERT INTO amactive_migrated (id_before,id_after) VALUES ('.$args_migrated['id_before'].','.$args_migrated['id_after'].')';
                                    $result_step5 = $wpdb->insert('amactive_migrated', $args_migrated);
                                    amactive_debug_if_error($wpdb->last_error);
                                    
                                    if($result_step5){
                                        amactive_debug_step('STEP 6: UPDATE catalogue migrate');

                                        $updateCatalogue = $wpdb->update('catalogue',
                                            array('migrated' => 1),
                                            array('id' => $item_arr->id)
                                        );
                                        amactive_debug_if_error($wpdb->last_error);
                                        if ($updateCatalogue){
                                            amactive_debug_success('UPDATE > catalogue > migrated=1');
                                        }
                                    }

                                    if($result_step5){
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

                    } else {
                        amactive_debug_error('!!! CANNOT FIND IMAGE for item#<a href="http://www.classicandsportscar.ltd.uk/'.$new_post_arr->name.'/'.$switch_item_status_category_name.'/'.$item_arr->id.'">'.$item_arr->id.'</a> - '.$filepath_before.' !!!');
                    }
                }
                /* (END) foreach */                
            }

        ?>
    </div>
</div>



<?php get_footer(); ?>