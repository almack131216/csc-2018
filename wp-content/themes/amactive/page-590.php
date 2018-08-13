<?php get_header(); ?>
<div class="row bg-accent">
    <div class="hidden-md-down col-lg-3 col-no-padding">
        <?php get_sidebar(); ?>
    </div>
    <div class="col-md-12 col-lg-9 padding-x-0 bg-white">
        <?php

            // $q = "SELECT * FROM ctalogue WHERE id_xtra=0";//AND migrated=0
            // $r = mysql_query($q);

            // if($r){
            //     for($i=0;$i<mysql_num_rows($r);$i++){
            //         $row = mysql_fetch_row($r);
                    
            //         $name = $row['name'];
            //         $img = $row['image_large'];
            //         $year = $row['detail_1'];
            //         $price = $row['price'];
            //         $price_details = $row['price_details'];
            //         $status = $row['status'];
                    
            //         if($img){
            //             echo '<br>item: '.$name;
            //             // $imgQ = "INSERT (post_id,meta_key,meta_value) INTO wp_postmeta VALUES($i, '_thumbnail_id', $img)";
            //             // $imgR = mysql_query($imgQ);
            //             // if(mysql_result($imgR) {
            //             // 	$insertId = mysql_insert_id();
            //             // }
            //         }
            //     }
            // }

            $isParent = true;
            if($isParent){
                $sqlParentOrChild = 'category=2 AND id_xtra=0';
            }else{
                $sqlParentOrChild = 'id_xtra!=0';
            }

            global $wpdb;
            $result = $wpdb->get_results("SELECT * FROM catalogue WHERE $sqlParentOrChild ORDER BY id DESC LIMIT 1");
            echo "<table border='1'>";
            echo "<tr><th>Id</th><th>Img</th><th>Name</th><th>Category</th><th>Subcategory</th><th>Date</th></tr>";

            $count = 0;

            foreach($result as $wp_formmaker_submits){
                $item_id = $wp_formmaker_submits->id;
                $item_name = $wp_formmaker_submits->name;
                $item_year = $wp_formmaker_submits->detail_1;
                $item_price = $wp_formmaker_submits->price;
                $item_price_details = $wp_formmaker_submits->price_details;
                $item_status = $wp_formmaker_submits->status;
                $item_category = $wp_formmaker_submits->category;
                // $item_description = $wp_formmaker_submits->description;
                $item_subcategory = $wp_formmaker_submits->subcategory;
                $item_upload_date = $wp_formmaker_submits->upload_date;
                $item_image_large = $wp_formmaker_submits->image_large;

                $itemWP_post_name = sanitize_title_with_dashes( $item_name, $unused, $context = 'display' );
                $itemWP_post_date = $item_upload_date.' 00:00:00';
                $itemWP_post_date_gmt = $item_upload_date.' 00:00:00';
                // $itemWP_status = 'publish';

                echo '<tr>';
                echo '<td>'.$item_id.'</td>';
                echo '<td><img src="http://www.classicandsportscar.ltd.uk/images_catalogue/thumbs/'.$item_image_large.'"></td>';
                echo '<td>';
                    echo $item_name;
                    echo '<br>'.$itemWP_post_name;
                echo '</td>';
                echo '<td>'.$item_category.'</td>';
                echo '<td>'.$item_subcategory.'</td>';
                echo '<td>';
                    echo $item_upload_date;
                    echo '<br>'.$itemWP_post_date;
                echo '</td>';
                echo '</tr>';

                //REF: https://stackoverflow.com/questions/18096555/how-to-insert-data-using-wpdb
                

                $postChecksArr = array(
                    'post_insert' => false,
                    'post_guid' => false,
                    'post_meta__edit_last' => false,
                    'post_meta__post_lock' => false,
                    'post_meta_csc_car_sale_status' => false,
                    'post_meta__csc_car_sale_status' => false,
                    'post_meta_csc_car_year' => false,
                    'post_meta__csc_car_year' => false,
                    'post_meta_csc_car_price' => false,
                    'post_meta__csc_car_price' => false,
                    'post_meta_csc_car_price_details' => false,
                    'post_meta__csc_car_price_details' => false,
                    'revision_insert' => false,
                    // 'revision_guid' => false,
                    'revision_meta__edit_last' => false,
                    'revision_meta__post_lock' => false,
                    'revision_meta_csc_car_sale_status' => false,
                    'revision_meta__csc_car_sale_status' => false,
                    'revision_meta_csc_car_year' => false,
                    'revision_meta__csc_car_year' => false,
                    'revision_meta_csc_car_price' => false,
                    'revision_meta__csc_car_price' => false,
                    'revision_meta_csc_car_price_details' => false,
                    'revision_meta__csc_car_price_details' => false,
                    'attachment_insert' => false,
                    'attachment_guid' => false,
                    'post_migrated' => false
                );

                $insertPost = true;
                if($insertPost){
                    // POST
                    // STEP 1: INSERT item INTO wp_posts
                    $args = array(
                        // 'ID' => $item_id,
                        'post_author' => 1,
                        'post_date' => $itemWP_post_date,
                        'post_date_gmt' => $itemWP_post_date_gmt,
                        'post_content' => $wp_formmaker_submits->description,
                        'post_title' => $item_name,
                        'post_excerpt' => $wp_formmaker_submits->detail_5,
                        'post_status' => '',
                        'comment_status' => '',
                        'ping_status' => '',
                        'post_modified' => $itemWP_post_date,
                        'post_modified_gmt' => $itemWP_post_date,
                        'comment_status' => 'closed',
                        'ping_status' => 'closed',
                        'post_name' => $itemWP_post_name,
                        'post_status' => 'publish',
                        'post_parent' => '0',
                        'guid' => '',
                        'post_type'	=> 'post'
                    );                  
                    $wpdb->insert('wp_posts', $args);
                    // echo '<br>LQ: '.$wpdb->last_query;
                    echo '<h1>STEP 1: INSERT item INTO wp_posts</h1>';
                    echo '<br>INSERT POST LR: '.print_r($wpdb->last_result);
                    echo '<br>INSERT POST LQE: '.$wpdb->last_error;                 
                    $post_id = $wpdb->insert_id;
                    echo '<br>INSERT POST ID: '.$post_id;

                    // STEP 1.2: UPDATE post guid
                    $wpdb->update(
                        'wp_posts',
                        array('guid' => 'http://localhost:8080/classicandsportscar.ltd.uk/?p='.$post_id),
                        array('ID' => $post_id)
                    );
                    echo '<br>UPDATE POST LQ: '.$wpdb->last_query;
                    echo '<br>UPDATE POST LQE: '.$wpdb->last_error; 
                    
                    // STEP 1.3: INSERT postmeta for POST
                    $wpdb->insert('wp_postmeta', array(
                        'post_id' => $post_id,
                        'meta_key' => '_edit_last',
                        'meta_value' => 1
                    ));                    
                    amactive_wp_set_post_lock($post_id);//REF: http://hookr.io/functions/wp_set_post_lock/                    
                    /*
                    $wpdb->insert('wp_postmeta', array(
                        'post_id' => $post_id,
                        'meta_key' => 'csc_car_sale_status',
                        'meta_value' => $item_status
                    ));
                    $wpdb->insert('wp_postmeta', array(
                        'post_id' => $post_id,
                        'meta_key' => '_csc_car_sale_status',
                        'meta_value' => 'field_5b47617c80afd'
                    ));
                    $wpdb->insert('wp_postmeta', array(
                        'post_id' => $post_id,
                        'meta_key' => 'csc_car_year',
                        'meta_value' => $item_year
                    ));
                    $wpdb->insert('wp_postmeta', array(
                        'post_id' => $post_id,
                        'meta_key' => '_csc_car_year',
                        'meta_value' => 'field_5b0d704a3289e'
                    ));
                    $wpdb->insert('wp_postmeta', array(
                        'post_id' => $post_id,
                        'meta_key' => 'csc_car_price',
                        'meta_value' => $item_price
                    ));
                    $wpdb->insert('wp_postmeta', array(
                        'post_id' => $post_id,
                        'meta_key' => '_csc_car_price',
                        'meta_value' => 'field_5b0d70b73289f'
                    ));                    
                    $wpdb->insert('wp_postmeta', array(
                        'post_id' => $post_id,
                        'meta_key' => 'csc_car_price_details',
                        'meta_value' => $item_price_details
                    ));
                    $wpdb->insert('wp_postmeta', array(
                        'post_id' => $post_id,
                        'meta_key' => '_csc_car_price_details',
                        'meta_value' => 'field_5b0d70fd328a0'
                    ));
                    */
                    

                    // IMG
                    // STEP 3: INSERT post for ATTACHMENT
                    $imgDateArr = explode("-", $item_upload_date);
                    $imgYear = $imgDateArr[0]; // year
                    $imgMonth = $imgDateArr[1]; // month
                    $imgDir = $imgYear.'/'.$imgMonth.'/';
                    $filepath_before = 'classicandsportscar-img/images_catalogue/large/'.$item_image_large;
                    $filepath_after = 'wp-content/uploads/'.$imgDir.$item_image_large;

                    if (file_exists($filepath_before)) {
                        echo '<h1>STEP 2: INSERT attachment INTO wp_posts</h1>';
                        copy ( $filepath_before, $filepath_after );
                        echo '<br>PATH BEFORE: '.$filepath_before.' = <img width="100px" height="auto" src="'.$filepath_before.'">';
                        echo '<br>PATH AFTER: '.$filepath_after.' = <img width="100px" height="auto" src="'.$filepath_after.'">';
                        
                        $filename = $item_image_large;
                        $filename_without_extension = substr($filename, 0, strrpos($filename, "."));

                        // REF: https://codex.wordpress.org/Function_Reference/wp_check_filetype
                        $tmpMimeType = wp_check_filetype( $item_image_large );
                        echo '<br>MIME TYPE: '.$tmpMimeType['ext'].' / '.$tmpMimeType['type'];

                        $args_img = array(
                            // 'ID' => $item_id,
                            'post_author' => 1,
                            'post_date' => $itemWP_post_date,
                            'post_date_gmt' => $itemWP_post_date_gmt,
                            'post_content' => '',
                            'post_title' => $itemWP_post_date,
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
                        $wpdb->insert('wp_posts', $args_img);
                        $post_id_attachment = $wpdb->insert_id;

                        // STEP 6.2: UPDATE guid for ATTACHMENT
                        // REF: https://codex.wordpress.org/Function_Reference/wp_check_filetype
                        // $tmpMimeType = wp_check_filetype( $item_image_large );
                        // echo '<br>MIME TYPE: '.$tmpMimeType['ext'].' / '.$tmpMimeType['type'];
                        // $wpdb->update(
                        //     'wp_posts',
                        //     array('post_mime_type' => $tmpMimeType['type']),
                        //     array('ID' => $post_id_attachment)
                        // );

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
                            'meta_value' => $imgDir.$item_image_large
                        );
                        $wpdb->insert('wp_postmeta', $args_postmeta);
                        $media_id = $wpdb->insert_id;                        

                        // ADD ATTACHMENT TO POST
                        // $args_postmeta = array(
                        //     'post_id' => $post_id,
                        //     'meta_key' => '_thumbnail_id',
                        //     'meta_value' => $media_id//ID of media file
                        // );
                        // $wpdb->insert('wp_postmeta', $args_postmeta);
                    }  


                    // STEP 4: INSERT categories INTO wp_term_relationships for POST
                    $wpdb->insert('wp_term_relationships', array(
                        'object_id' => $post_id,
                        'term_taxonomy_id' => 2
                    ));
                    $wpdb->insert('wp_term_relationships', array(
                        'object_id' => $post_id,
                        'term_taxonomy_id' => 26
                    ));
                    



                    // REVISION
                    // STEP 2: INSERT post revision-v1
                    // revision
                    $args['post_modified'] = '2018-08-13 00:00:00';
                    $args['post_modified_gmt'] = '2018-08-13 00:00:00';
                    $args['post_name'] = $post_id.'-revision-v1';
                    $args['post_status'] = 'inherit';
                    $args['post_parent'] = $post_id;
                    $args['guid'] = 'http://localhost:8080/classicandsportscar.ltd.uk/'.$post_id.'-revision-v1/';
                    $args['post_type'] = 'revision';

                    $wpdb->insert('wp_posts', $args);
                    $revision_id = $wpdb->insert_id;
                    echo '<br>REVISION ID: '.$revision_id;

                    // STEP 2.2: INSERT postmeta for REVISION
                    $wpdb->insert('wp_postmeta', array(
                        'post_id' => $revision_id,
                        'meta_key' => '_edit_last',
                        'meta_value' => 1
                    ));
                    amactive_wp_set_post_lock($revision_id);

                    /*
                    $wpdb->insert('wp_postmeta', array(
                        'post_id' => $revision_id,
                        'meta_key' => 'csc_car_sale_status',
                        'meta_value' => $item_status
                    ));
                    $wpdb->insert('wp_postmeta', array(
                        'post_id' => $revision_id,
                        'meta_key' => '_csc_car_sale_status',
                        'meta_value' => 'field_5b47617c80afd'
                    ));
                    $wpdb->insert('wp_postmeta', array(
                        'post_id' => $revision_id,
                        'meta_key' => 'csc_car_year',
                        'meta_value' => $item_year
                    ));
                    $wpdb->insert('wp_postmeta', array(
                        'post_id' => $revision_id,
                        'meta_key' => '_csc_car_year',
                        'meta_value' => 'field_5b0d704a3289e'
                    ));
                    $wpdb->insert('wp_postmeta', array(
                        'post_id' => $revision_id,
                        'meta_key' => 'csc_car_price',
                        'meta_value' => $item_price
                    ));
                    $wpdb->insert('wp_postmeta', array(
                        'post_id' => $revision_id,
                        'meta_key' => '_csc_car_price',
                        'meta_value' => 'field_5b0d70b73289f'
                    ));                    
                    $wpdb->insert('wp_postmeta', array(
                        'post_id' => $revision_id,
                        'meta_key' => 'csc_car_price_details',
                        'meta_value' => $item_price_details
                    ));
                    $wpdb->insert('wp_postmeta', array(
                        'post_id' => $revision_id,
                        'meta_key' => '_csc_car_price_details',
                        'meta_value' => 'field_5b0d70fd328a0'
                    ));
                    */

                    
                    // STEP 2: INSERT migrated reference
                    $args_migrated = array(
                        'id_before' => $item_id,
                        'id_after' => $post_id,
                        'date' => '2018-08-12 00:00:00'
                    );
                    // $query = 'INSERT INTO amactive_migrated (id_before,id_after) VALUES ('.$args_migrated['id_before'].','.$args_migrated['id_after'].')';
                    // echo 'Q: '.$query;
                    // $wpdb->insert($query);
                    $wpdb->insert('amactive_migrated', $args_migrated);
                    $migrated_id = $wpdb->insert_id;
                    echo '<br>migrated_id: '.$migrated_id;

                }
            }

            echo "</table>";

        ?>
    </div>
</div>



<?php get_footer(); ?>