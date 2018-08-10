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
                $inserts = true;
                if($inserts){
                    // INSERT INTO wp_posts

                    $args = array(
                        'ID' => $item_id,
                        'post_author' => 1,
                        'post_date' => $itemWP_post_date,
                        'post_date_gmt' => $itemWP_post_date_gmt,
                        'post_content' => $wp_formmaker_submits->description,
                        'post_title' => $item_name,
                        'post_excerpt' => $wp_formmaker_submits->detail_5,
                        'post_status' => $item_status,
                        'post_name' => $itemWP_post_name,
                        'post_modified' => $itemWP_post_date,
                        'post_modified_gmt' => $itemWP_post_date                        
                    );

                    if($isParent){
                        $args2 = array(
                            'post_parent' => '0',
                            'guid' => 'http://localhost:8080/classicandsportscar.ltd.uk/?p='.$item_id,
                            'post_type'	=> 'post'
                        );
                    } else {
                        $args2 = array(
                            'post_parent' => '999',
                            'guid' => 'http://localhost:8080/classicandsportscar.ltd.uk/544-revision-v1/',
                            'post_type' => 'revision'
                        );
                    }

                    
                    $wpdb->insert('wp_posts', $args);
                    echo $wpdb->query();
                    $lastid = $wpdb->insert_id;

                    $args_migrated = array(
                        'id_before' => $item_id,
                        'id_after' => $lastid
                    );
                    $wpdb->insert('amactive_migrated', $args_migrated);

                    // INSERT INTO wp_term_relationships
                    $wpdb->insert('wp_term_relationships', array(
                        'object_id' => 'Kumkum',
                        'term_taxonomy_id' => 'kumkum@gmail.com'
                    ));
                }
            }

            echo "</table>";

        ?>
    </div>
</div>



<?php get_footer(); ?>