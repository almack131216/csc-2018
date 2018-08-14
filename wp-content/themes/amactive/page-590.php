<?php
    get_header();

    global $wpdb;    
?>
<div class="row bg-accent">
    <div class="hidden-md-down col-lg-3 col-no-padding">
        <?php get_sidebar(); ?>
    </div>
    <div class="col-md-12 col-lg-9 padding-x-0 bg-white">
        <?php

            $isDeleting = false;
            if($_REQUEST['delete']) {
                $isDeleting = true;
            }

            $statusArr = [0,1,2];
            $categoryId = DV_category_IsForSale_id;
            $subcatsArr = array(
                'triumph' => [41,26],
                'ferrari' => [18,14]
            );
            $subcategorySlug = 'ferrari';
            $subcategoryId = $subcatsArr[$subcategorySlug];
            echo '<br><strong>SUBCATEGORY: '.$subcategoryId[0].' -> '.$subcategoryId[1].' ('.$subcategorySlug.')</strong>';

            // $postsAddedArr = array();

            $debug_hide_postmeta = false;
            $fb_show_q_success = false;
            $fb_show_q_error = false;

            $isParent = true;
            if($isParent){
                $sqlParentOrChild = 'category='.$categoryId.' AND status='.$statusArr[2].' AND subcategory='.$subcategoryId[0].' AND id_xtra=0';
            }else{
                $sqlParentOrChild = 'id_xtra!=0';
            }
            // echo '<span class="sql_info">'.$sqlParentOrChild.'</span>';
            
            $sql_Select = "SELECT * FROM catalogue";
            $sql_Where = " WHERE $sqlParentOrChild";
            $sql_OrderBy = " ORDER BY id DESC";
            

            if($isDeleting) {
                $isDeleting = true;

                // $deleteBespoke = "DELETE FROM wp_postmeta WHERE meta_value LIKE '%2009/%'";

                if($deleteBespoke){
                    echo '<span class="sql_info">'.$deleteBespoke.'</span>';
                    $result = $wpdb->query($deleteBespoke);
                    if($result){
                        echo '<span class="sql_success">'.$wpdb->last_query.'</span>';
                    }else{
                        if($wpdb->last_error) echo '<span class="sql_error">'.$wpdb->last_error.'</span>';
                    }
                    
                } else {

                    $q = $sql_Select.$sql_Where.$sql_OrderBy;
                    amactive_batch_delete_all( $q );
                    
                }
                
            }
            /* (END) if($isDeleting)... */

            if(!$isDeleting) {
                $sql_Where .= " AND migrated=0";
                $result = $wpdb->get_results($sql_Select.$sql_Where.$sql_OrderBy);// LIMIT 3
                if($wpdb->last_error) echo '<span class="sql_error">'.$wpdb->last_error.'</span>';
                echo '<span class="sql_info">'.$wpdb->last_query.'</span>';

                foreach($result as $wp_formmaker_submits){
                    $item_arr = $wp_formmaker_submits;
                    $item_id = $wp_formmaker_submits->id;
                    $item_name = $wp_formmaker_submits->name;
                    $item_year = $wp_formmaker_submits->detail_1;
                    $item_price = $wp_formmaker_submits->price;
                    $item_price_details = $wp_formmaker_submits->price_details;
                    $item_status = $wp_formmaker_submits->status;
                    $switch_item_status_category_name = $item_status == 2 ? 'classic-cars-sold' : 'classic-cars-for-sale';
                    $item_category = $wp_formmaker_submits->category;
                    // $item_description = $wp_formmaker_submits->description;
                    $item_subcategory = $wp_formmaker_submits->subcategory;
                    $item_upload_date = $wp_formmaker_submits->upload_date;
                    $item_image_large = $wp_formmaker_submits->image_large;

                    $itemWP_post_arr = array();
                    $itemWP_post_name = sanitize_title_with_dashes( $item_name, $unused, $context = 'display' );
                    $itemWP_post_date = $item_upload_date.' 00:00:00';
                    $itemWP_post_date_gmt = $item_upload_date.' 00:00:00';
                    
                    $itemWP_post_arr['name'] = $itemWP_post_name;
                    $itemWP_post_arr['category'] = $categoryId;
                    $itemWP_post_arr['subcategory'] = $subcategoryId[1];
                    $itemWP_post_arr['date'] = $itemWP_post_date;
                    $itemWP_post_arr['date_gmt'] = $itemWP_post_date_gmt;
                    // $itemWP_status = 'publish';
                    //REF: https://stackoverflow.com/questions/18096555/how-to-insert-data-using-wpdb                    

                    // $postChecksArr = array(
                    //     'post_insert' => false,
                    //     'post_guid' => false,
                    //     'post_meta__edit_last' => false,
                    //     'post_meta__post_lock' => false,
                    //     'post_meta_csc_car_sale_status' => false,
                    //     'post_meta__csc_car_sale_status' => false,
                    //     'post_meta_csc_car_year' => false,
                    //     'post_meta__csc_car_year' => false,
                    //     'post_meta_csc_car_price' => false,
                    //     'post_meta__csc_car_price' => false,
                    //     'post_meta_csc_car_price_details' => false,
                    //     'post_meta__csc_car_price_details' => false,
                    //     'revision_insert' => false,
                    //     // 'revision_guid' => false,
                    //     'revision_meta__edit_last' => false,
                    //     'revision_meta__post_lock' => false,
                    //     'revision_meta_csc_car_sale_status' => false,
                    //     'revision_meta__csc_car_sale_status' => false,
                    //     'revision_meta_csc_car_year' => false,
                    //     'revision_meta__csc_car_year' => false,
                    //     'revision_meta_csc_car_price' => false,
                    //     'revision_meta__csc_car_price' => false,
                    //     'revision_meta_csc_car_price_details' => false,
                    //     'revision_meta__csc_car_price_details' => false,
                    //     'attachment_insert' => false,
                    //     'attachment_guid' => false,
                    //     'post_migrated' => false
                    // );

                    $imgDateArr = explode("-", $item_upload_date);
                    $imgYear = $imgDateArr[0]; // year
                    $imgMonth = $imgDateArr[1]; // month
                    $imgDir = $imgYear.'/'.$imgMonth.'/';
                    $filepath_before = 'classicandsportscar-img/images_catalogue/large/'.$item_image_large;
                    
                    if(@is_array(getimagesize($filepath_before)) && file_exists($filepath_before)) {
                        // POST
                        /*
                        **********
                        STEP 1: INSERT item INTO wp_posts
                        **********
                        */
                        if($item_id){
                            $args = array(
                                // 'ID' => $item_id,
                                'post_author' => 1,
                                'post_date' => $itemWP_post_date,
                                'post_date_gmt' => $itemWP_post_date_gmt,
                                'post_content' => $wp_formmaker_submits->description,
                                'post_title' => $item_name,
                                'post_excerpt' => $wp_formmaker_submits->detail_5,
                                'post_status' => 'publish',
                                'comment_status' => 'closed',
                                'ping_status' => 'closed',
                                'post_modified' => $itemWP_post_date,
                                'post_modified_gmt' => $itemWP_post_date,                                
                                'post_name' => $itemWP_post_name,                                
                                'post_parent' => '0',
                                'guid' => '',
                                'post_type'	=> 'post'
                            );   

                            echo '<span class="sql_step title">STEP 1: INSERT item INTO wp_posts</span>';               
                            $result_step1_insertPost = $wpdb->insert('wp_posts', $args);
                            if($wpdb->last_error) echo '<span class="sql_error">INSERT POST LQE: '.$wpdb->last_error.'</span>';                        
                            // $postsAddedArr[] = $post_id;
                            if($result_step1_insertPost){
                                $post_id = $wpdb->insert_id;
                                $itemWP_post_arr['id'] = $post_id;

                                echo '<span class="sql_success">INSERT > wp_posts > POST ID: '.$post_id.'</span>';
                                if($fb_show_q_success) echo '<span class="sql_success">'.$wpdb->last_query.'</span>';
                            }
                        }

                        /*
                        **********
                        STEP 2: INSERT post for ATTACHMENT
                        **********
                        */                                           
                        // REF: https://codex.wordpress.org/Function_Reference/wp_check_filetype
                        $tmpMimeType = wp_check_filetype( $item_image_large );
                        echo '<span class="sql_step">STEP 2: INSERT post for ATTACHMENT</span>';         
                        echo '<br>MIME TYPE: '.$tmpMimeType['ext'].' / '.$tmpMimeType['type'];
                        $filenameNew = $itemWP_post_name.'_'.$post_id.'.'.$tmpMimeType['ext'];
                        $filepath_after = 'wp-content/uploads/'.$imgDir.$filenameNew;
                        $itemWP_post_arr['filename'] = $filepath_after;

                        if(copy( $filepath_before, $filepath_after )){
                            echo '<br>PATH BEFORE: '.$filepath_before.' = <img width="50px" height="auto" src="'.$filepath_before.'">';
                            echo '<br>PATH AFTER: '.$filepath_after.' = <img width="50px" height="auto" src="'.$filepath_after.'">';                        
                            // $filename_without_extension = substr($filename, 0, strrpos($filename, "."));
                            // $filename_new = $itemWP_post_name.'_'.$post_id;

                            $args_img = array(
                                'post_author' => 1,
                                'post_date' => $itemWP_post_date,
                                'post_date_gmt' => $itemWP_post_date_gmt,
                                'post_content' => '',
                                'post_title' => $itemWP_post_name,
                                'post_excerpt' => '',
                                'post_status' => 'inherit',
                                'comment_status' => 'closed',
                                'ping_status' => 'closed',
                                'post_modified' => $itemWP_post_date,
                                'post_modified_gmt' => $itemWP_post_date,
                                'post_name' => $itemWP_post_name,
                                'post_parent' => $post_id,
                                'guid' => 'http://localhost:8080/classicandsportscar.ltd.uk/'.$filepath_after,
                                'post_type'	=> 'attachment',
                                'post_mime_type' => $tmpMimeType['type']
                            );
                            
                            $result_addPostAttachment = $wpdb->insert('wp_posts', $args_img);
                            if($wpdb->last_error) echo '<span class="sql_error">'.$wpdb->last_error.'</span>';

                            if($result_addPostAttachment){
                                $post_id_attachment = $wpdb->insert_id;
                                echo '<span class="sql_success">INSERT > wp_posts > ATTACHMENT ID: '.$post_id_attachment.'</span>';
                                if($fb_show_q_success) echo '<span class="sql_success">'.$wpdb->last_query.'</span>';

                                // $media_metadata = wp_get_attachment_metadata($post_id_attachment, true);
                                // echo '<br>$media_metadata: '.$media_metadata;
                                // echo '<br>$media_metadata: '.print_r($media_metadata);

                                // STEP 6.3: INSERT postmeta for ATTACHMENT
                                $args_postmeta = array(
                                    'post_id' => $post_id,
                                    'meta_key' => '_thumbnail_id',
                                    'meta_value' => $post_id_attachment//ID of media file
                                );
                                $wpdb->insert('wp_postmeta', $args_postmeta);

                                $args_postmeta = array(
                                    'post_id' => $post_id_attachment,
                                    'meta_key' => '_wp_attached_file',
                                    'meta_value' => $imgDir.$filenameNew
                                );
                                $wpdb->insert('wp_postmeta', $args_postmeta);
                                $media_id = $wpdb->insert_id;                                               
                            }
                            /* (ENDIF) STEP 2 */
                        }
                        /* (COPYING FILE...) */

                        /*
                        **********
                        STEP 1.2: UPDATE post guid
                        **********
                        */
                        if($result_addPostAttachment){
                            echo '<span class="sql_step">STEP 1.2: UPDATE post guid & post_name</span>';
                            $result_step1_2_updatePost = $wpdb->update(
                                'wp_posts',
                                array(
                                    'guid' => 'http://localhost:8080/classicandsportscar.ltd.uk/?p='.$post_id,
                                    'post_name' => $itemWP_post_name.'_'.$post_id
                                ),
                                array('ID' => $post_id)
                            );
                            if($wpdb->last_error) echo '<span class="sql_error">'.$wpdb->last_error.'</span>';
                            if($result_step1_2_updatePost){
                                // $post_id = $wpdb->insert_id;
                                echo '<span class="sql_success">UPDATE > wp_posts > guid & post_name</span>';
                                if($fb_show_q_success) echo '<span class="sql_success">'.$wpdb->last_query.'</span>';

                                /*
                                **********
                                STEP 1.3: INSERT postmeta for POST
                                **********
                                */
                                echo '<span class="sql_step">STEP 1.3: INSERT postmeta for POST</span>';
                                $result_step1_3_addPostmeta = $wpdb->insert('wp_postmeta', array(
                                    'post_id' => $post_id,
                                    'meta_key' => '_edit_last',
                                    'meta_value' => 1
                                ));
                                if($wpdb->last_error) echo '<span class="sql_error">'.$wpdb->last_error.'</span>';
                                
                                if($result_step1_3_addPostmeta){
                                    amactive_wp_set_post_lock($post_id);//REF: http://hookr.io/functions/wp_set_post_lock/ 
                                    echo '<span class="sql_success">INSERT > wp_postmeta > _edit_last</span>';

                                    // postmeta
                                    if(!$debug_hide_postmeta){

                                        amactive_batch_insert_postmeta( array(
                                            'post_id' => $post_id,
                                            'item_arr' => $item_arr
                                        ));
                                        
                                    }

                                }
                                    /* (ENDIF) STEP 1.3 */                                
                            }
                        }
                        /* (ENDIF) STEP 1.2 */
                        


                        /*
                        **********
                        STEP 3: INSERT categories INTO wp_term_relationships for POST
                        **********
                        */
                        echo '<span class="sql_step">STEP 3: INSERT categories INTO wp_term_relationships for POST</span>';
                        // STEP 3: INSERT categories INTO wp_term_relationships for POST
                        $result_step3a = $wpdb->insert('wp_term_relationships', array(
                            'object_id' => $post_id,
                            'term_taxonomy_id' => $categoryId
                        ));
                        if($wpdb->last_error) echo '<span class="sql_error">'.$wpdb->last_error.'</span>';
                        $result_step3b = $wpdb->insert('wp_term_relationships', array(
                            'object_id' => $post_id,
                            'term_taxonomy_id' => $itemWP_post_arr['subcategory']
                        ));
                        if($wpdb->last_error) echo '<span class="sql_error">'.$wpdb->last_error.'</span>';
                        
                        if($result_step3a && $result_step3b){
                            echo '<span class="sql_success">INSERT > wp_term_relationships > cats: ['.$categoryId.','.$itemWP_post_arr['subcategory'].']</span>';
                            if($fb_show_q_success) echo '<span class="sql_success">'.$wpdb->last_query.'</span>';

                            // REVISION
                            /*
                            **********
                            STEP 4: INSERT post revision-v1
                            **********
                            */
                            echo '<span class="sql_step">STEP 4: INSERT post revision-v1</span>';
                            // revision
                            $args['post_modified'] = '2018-08-13 00:00:00';
                            $args['post_modified_gmt'] = '2018-08-13 00:00:00';
                            $args['post_name'] = $post_id.'-revision-v1';
                            $args['post_status'] = 'inherit';
                            $args['post_parent'] = $post_id;
                            $args['guid'] = 'http://localhost:8080/classicandsportscar.ltd.uk/'.$post_id.'-revision-v1/';
                            $args['post_type'] = 'revision';

                            $result_step4 = $wpdb->insert('wp_posts', $args);
                            if($wpdb->last_error) echo '<span class="sql_error">'.$wpdb->last_error.'</span>';

                            if($result_step4){
                                $revision_id = $wpdb->insert_id;
                                echo '<span class="sql_success">INSERT > wp_posts > REVISION ID: '.$revision_id.'</span>';
                                if($fb_show_q_success) echo '<span class="sql_success">'.$wpdb->last_query.'</span>';

                                // STEP 4.2: INSERT postmeta for REVISION
                                $wpdb->insert('wp_postmeta', array(
                                    'post_id' => $revision_id,
                                    'meta_key' => '_edit_last',
                                    'meta_value' => 1
                                ));
                                amactive_wp_set_post_lock($revision_id);

                                if(!$debug_hide_postmeta){
                                    amactive_batch_insert_postmeta( array(
                                        'post_id' => $revision_id,
                                        'item_arr' => $item_arr
                                    ));
                                }

                                /*
                                **********
                                STEP 5: INSERT migrated reference
                                **********
                                */
                                echo '<span class="sql_step">STEP 5: INSERT migrated reference</span>';
                                $args_migrated = array(
                                    'id_before' => $item_id,
                                    'id_after' => $post_id,
                                    'id_after_revision' => $revision_id,
                                    'id_after_attachment' => $post_id_attachment,
                                    'name' => $item_name,
                                    'date' => '2018-08-14 00:00:00'
                                );
                                // $query = 'INSERT INTO amactive_migrated (id_before,id_after) VALUES ('.$args_migrated['id_before'].','.$args_migrated['id_after'].')';
                                // echo 'Q: '.$query;
                                // $wpdb->insert($query);
                                $result_step5 = $wpdb->insert('amactive_migrated', $args_migrated);
                                if($wpdb->last_error) echo '<span class="sql_error">'.$wpdb->last_error.'</span>';
                                
                                if($result_step5){
                                    echo '<span class="sql_step">STEP 6: UPDATE catalogue migrate</span>';

                                    $updateCatalogue = $wpdb->update(
                                        'catalogue',
                                            array(
                                                'migrated' => 1
                                            ),
                                            array('id' => $item_id)
                                    );
                                    if($wpdb->last_error) echo '<span class="sql_error">'.$wpdb->last_error.'</span>';
                                    if ($updateCatalogue){
                                        echo '<span class="sql_success">UPDATE > catalogue > migrated=1</span>';
                                    }
                                }

                                if($result_step5){
                                    $migrated_id = $wpdb->insert_id;
                                    echo '<span class="sql_success">INSERT > amactive_migrated > id: '.$migrated_id.'</span>';
                                    if($fb_show_q_success) echo '<span class="sql_success">'.$wpdb->last_query.'</span>';

                                    print_r($itemWP_post_arr);
                                    
                                    $tmpArr = array(
                                        'item_arr' => $item_arr,
                                        'post_arr' => $itemWP_post_arr
                                    );
                                    $tableSuccess = amactive_batch_print_post( $tmpArr );                                    
                                    echo $tableSuccess;
                                }
                                /* (ENDIF) STEP 5 */
                            }
                            /* (ENDIF) STEP 4 */
                        }
                        /* (ENDIF) STEP 3 */     

                    } else {
                        echo '<span class="sql_error">!!! CANNOT FIND IMAGE for item#<a href="http://www.classicandsportscar.ltd.uk/'.$item_name.'/'.$switch_item_status_category_name.'/'.$item_id.'">'.$item_id.'</a>!!!</span>';
                    }
                }
                /* (END) foreach */                
            }

        ?>
    </div>
</div>



<?php get_footer(); ?>