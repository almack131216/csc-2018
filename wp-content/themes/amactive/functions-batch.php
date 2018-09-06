<?php

function amactive_wp_set_post_lock( $post_id ) {
	if ( ! $post = get_post( $post_id ) ) {
		return false;
	}

	if ( 0 == ( $user_id = get_current_user_id() ) ) {
		return false;
	}

	$now = time();
	$lock = "$now:$user_id";

	update_post_meta( $post->ID, '_edit_lock', $lock );

	return array( $now, $user_id );
}

function amactive_batch_insert_postmeta( $getArr ) {
    global $wpdb;

    if($getArr['type'] == 'post' || $getArr['type'] == 'revision'){

        $wpdb->insert('wp_postmeta', array(
            'post_id' => $getArr['post_id'],
            'meta_key' => 'csc_car_sale_status',
            'meta_value' => $getArr['item_arr']->status
        ));
        $wpdb->insert('wp_postmeta', array(
            'my_post_id' => $getArr['post_id'],
            'meta_key' => '_csc_car_sale_status',
            'meta_value' => 'field_5b47617c80afd'
        ));
        $wpdb->insert('wp_postmeta', array(
            'post_id' => $getArr['post_id'],
            'meta_key' => 'csc_car_year',
            'meta_value' => $getArr['item_arr']->year
        ));
        $wpdb->insert('wp_postmeta', array(
            'post_id' => $getArr['post_id'],
            'meta_key' => '_csc_car_year',
            'meta_value' => 'field_5b0d704a3289e'
        ));
        $wpdb->insert('wp_postmeta', array(
            'post_id' => $getArr['post_id'],
            'meta_key' => 'csc_car_price',
            'meta_value' => $getArr['item_arr']->price
        ));
        $wpdb->insert('wp_postmeta', array(
            'post_id' => $getArr['post_id'],
            'meta_key' => '_csc_car_price',
            'meta_value' => 'field_5b0d70b73289f'
        ));                    
        $wpdb->insert('wp_postmeta', array(
            'post_id' => $getArr['post_id'],
            'meta_key' => 'csc_car_price_details',
            'meta_value' => $getArr['item_arr']->price_details
        ));
        $wpdb->insert('wp_postmeta', array(
            'post_id' => $getArr['post_id'],
            'meta_key' => '_csc_car_price_details',
            'meta_value' => 'field_5b0d70fd328a0'
        ));
    } elseif( $getArr['type'] == 'attachment' ) {
        $wpdb->insert('wp_postmeta', array(
            'post_id' => $getArr['post_arr']->id,
            'meta_key' => '_thumbnail_id',
            'meta_value' => $getArr['post_arr']->id_attachment
        ));
    }
}

function amactive_batch_delete_all( $getQuery ) {
    global $wpdb, $stepNum;

    $result = $wpdb->get_results( $getQuery );// LIMIT 3

    if($result) {
        $stepNum = 0;
        echo '<h1>DELETE...</h1>';
        amactive_debug_info($wpdb->last_query);

        $debug_count = 0;
        $debug_counted = 0;

        foreach($result as $wp_migrated_items){   
            $stepNum = 1;         
            $debug_count = sizeof($result);
            $item_id = $wp_migrated_items->id;

            $result_migrated = $wpdb->get_results("SELECT * FROM amactive_migrated WHERE id_before=$item_id");//LIMIT 1
            if($result_migrated) {          
                amactive_debug_info($wpdb->last_query);
                foreach($result_migrated as $wp_migrated_posts){
                    $stepNum = 2;
                    amactive_batch_delete_post($wp_migrated_posts->id_after);
                    $debug_counted++;
                }              
            } else {
                amactive_debug_info('(NOT FOUND) '.$wpdb->last_query);
                if($wpdb->last_error) amactive_debug_error($wpdb->last_error);
            }

            amactive_debug_title('successfully deleted '.$debug_counted.' from '.$debug_count);

            if($debug_counted == $debug_count){
                amactive_debug_success('successfully deleted ALL');
            }
            
        }               
    } else {
        amactive_debug_step($wpdb->last_query);
        if($wpdb->last_error) amactive_debug_error($wpdb->last_error);
    }

    
}

function amactive_batch_delete_post( $getPostId ) {
    global $wpdb;

    $post_id_to_delete = $getPostId;// $getPostArr->id;    

    $result_migrated = $wpdb->get_results("SELECT * FROM wp_posts WHERE (ID=$post_id_to_delete OR post_parent=$post_id_to_delete) ORDER BY ID desc");//LIMIT 1
    // REF: https://codex.wordpress.org/Function_Reference/wp_delete_post


    if($result_migrated) {
        amactive_debug_info($wpdb->last_query);
        foreach($result_migrated as $wp_migrated_posts){
            $post_id_to_delete = $wp_migrated_posts->ID;

            amactive_debug_step('DELETE > wp_posts > WHERE ID = '.$post_id_to_delete);
            $deletePost = wp_delete_post($post_id_to_delete, true);//$wpdb->delete( 'wp_posts', array( 'ID' => $post_id_to_delete ) );
            if($deletePost) amactive_debug_deleted(sizeof($deletePost).' DELETED > wp_posts > WHERE ID = '.$post_id_to_delete);

            /* POSTMETA... */
            // // // amactive_debug_step('DELETE > wp_postmeta > WHERE post_id = '.$post_id_to_delete);
            // // // $deletePostMeta = $wpdb->delete( 'wp_postmeta', array( 'post_id' => $post_id_to_delete ) );
            // // // if($deletePostMeta) amactive_debug_deleted($deletePostMeta.' DELETED > wp_postmeta > WHERE post_id='.$post_id_to_delete);
            // // // if($wpdb->last_error) amactive_debug_error('DELETE FAILED: '.$wpdb->last_error);

            /* CATEGORIES... */
            // // // amactive_debug_step('DELETE > wp_term_relationships > WHERE object_id = '.$post_id_to_delete);
            // // // $deletePostCategories = $wpdb->delete( 'wp_term_relationships', array( 'object_id' => $post_id_to_delete ) );
            // // // if($deletePostCategories) amactive_debug_deleted($deletePostCategories.' DELETED > wp_term_relationships > WHERE object_id='.$post_id_to_delete);
            // // // if($wpdb->last_error) amactive_debug_error('DELETE FAILED: '.$wpdb->last_error);

            /* MIGRATED RECORD */
            amactive_debug_step('DELETE > amactive_migrated > WHERE id_after = '.$post_id_to_delete);
            $deletePostMigrated = $wpdb->delete( 'amactive_migrated', array( 'id_after' => $post_id_to_delete ) );
            if($deletePostMigrated) amactive_debug_deleted($deletePostMigrated.' DELETED > amactive_migrated > WHERE id_after = '.$post_id_to_delete);
        }
    }
}


function amactive_batch_print_post( $getArr ){
    global $getCategory, $getSubcategory, $baseUrl;

    $tableSuccess = "<table border='1'>";
    $tableSuccess .= "<tr><th>Id</th><th>Img</th><th>Name</th><th>Category</th><th>Subcategory</th><th>Date</th></tr>";
    
    $tableSuccess .= '<tr>';
    $tableSuccess .= '<td>';
        $tableSuccess .= $getArr['item_arr']->id;
        $tableSuccess .= '<br>'.$getArr['post_arr']->id;
    $tableSuccess .= '</td>';
    $tableSuccess .= '<td>';
        $tableSuccess .= '<img width="60px" height="auto" src="http://www.classicandsportscar.ltd.uk/images_catalogue/thumbs/'.$getArr['item_arr']->image_large.'">';
        $tableSuccess .= '<br><img width="100px" height="auto" src="'.$baseUrl.$getArr['post_arr']->fileNameWithDir.'">';        
    $tableSuccess .= '</td>';
    $tableSuccess .= '<td>';
        $tableSuccess .= $getArr['item_arr']->name;
        $tableSuccess .= '<br>'.$getArr['post_arr']->name;
        $tableSuccess .= '<br>---';
        $tableSuccess .= '<br><a href="'.$baseUrl.'?page_id=2839&post='.$getArr['post_arr']->id.'" target="_blank">view post</a>';
        $tableSuccess .= '<br><a href="'.$baseUrl.'?page_id=2839&category='.$getCategory.'&subcategory='.$getSubcategory.'&attachments='.$getArr['post_arr']->id.'" target="_blank">add attachments</a>';
        $tableSuccess .= ' [<a href="'.$baseUrl.'?page_id=2839&category='.$getCategory.'&subcategory='.$getSubcategory.'&attachments='.$getArr['post_arr']->id.'&force=1" target="_blank">add attachments</a>]';
    $tableSuccess .= '</td>';
    $tableSuccess .= '<td>';
        $tableSuccess .= $getArr['item_arr']->category;
        $tableSuccess .= '<br>'.$getArr['post_arr']->category;
    $tableSuccess .= '</td>';
    $tableSuccess .= '<td>';
        $tableSuccess .= $getArr['item_arr']->subcategory;
        $tableSuccess .= '<br>'.$getArr['post_arr']->subcategory;
    $tableSuccess .= '</td>';
    $tableSuccess .= '<td>';
        $tableSuccess .= $getArr['item_arr']->upload_date;
        $tableSuccess .= '<br>'.$getArr['post_arr']->date;
    $tableSuccess .= '</td>';
    $tableSuccess .= '</tr>';

    $tableSuccess .= "</table>";

    return $tableSuccess;
}

function amactive_getDatetimeNow() {
    $tz_object = new DateTimeZone('Europe/Madrid');
    //date_default_timezone_set('Europe/Madrid');

    $datetime = new DateTime();
    $datetime->setTimezone($tz_object);
    return $datetime->format('Y\-m\-d\ h:i:s');
}

function amactive_debug_title( $getMessage = '' ){    
    amactive_debug_output($getMessage, 'step title');
}
function amactive_debug_step( $getMessage = '' ){
    amactive_debug_output($getMessage, 'step');
}
function amactive_debug_error( $getMessage = '' ){
    amactive_debug_output($getMessage, 'error');
}
function amactive_debug_deleted( $getMessage = '' ){
    amactive_debug_output($getMessage, 'deleted');
}
function amactive_debug_if_error( $getMessage = '' ){
    if($getMessage) amactive_debug_output($getMessage, 'error');
}
function amactive_debug_if_success( $getMessage = '' ){
    if($getMessage) amactive_debug_output($getMessage, 'success');
}
function amactive_debug_info( $getMessage = '' ){
    amactive_debug_output($getMessage, 'info');
}
function amactive_debug_suggest( $getMessage = '' ){
    amactive_debug_output($getMessage, 'suggest');
}
function amactive_debug_success( $getMessage = '' ){
    amactive_debug_output($getMessage, 'success');
}

function amactive_debug_output( $getMessage = '', $getStyle='info' ){
    global $debug_counted, $stepNum;

    $output = '<span class="msg_debug '.$getStyle.'">';
    $output .= '['.$stepNum.'] ';
    $output .= $getMessage;
    $output .= '</span>';

    echo $output;
}

function amactive_strip_special_chars( $getName ){

    // $new_string = preg_replace("/[^a-zA-Z0-9\s]/", "", $getName);
    // $url = preg_replace('/\s/', '-', $new_string);
    // $new_url = urlencode($url);

    // return $new_url;

    $safeName = str_replace ( array('é','%c3%a9'),'e',$getName);//Avon Standard 16HP Coupé
    return $safeName;
}

function amactive_prepare_post_arr( $getArr ) {
    global $baseUrl;
    
    if($getArr){
        $args = array(
            'post_author' => 1,
            'post_date' => $getArr['post_arr']->date,
            'post_date_gmt' => $getArr['post_arr']->date_gmt,
            'post_content' => $getArr['item_arr']->description,
            'post_title' => $getArr['item_arr']->name,
            'post_excerpt' => $getArr['item_arr']->detail_5,
            'post_status' => 'publish',
            'comment_status' => 'closed',
            'ping_status' => 'closed',
            'post_modified' => $getArr['post_arr']->date,
            'post_modified_gmt' => $getArr['post_arr']->date_gmt,                                
            'post_name' => $getArr['post_arr']->name,                                
            'post_parent' => 0,
            'guid' => '',
            'post_type'	=> 'post'
        );

        if( $getArr['type'] == 'attachment' ){
            $args['post_content'] = '';
            $args['post_title'] = $getArr['post_arr']->name;
            $args['post_excerpt'] = '';
            $args['post_status'] = 'inherit';
            $args['post_parent'] = $getArr['post_arr']->post_parent;
            $args['guid'] = $baseUrl.$getArr['post_arr']->fileNameWithDir;
            $args['post_type']	= 'attachment';
            $args['post_mime_type'] = $getArr['post_arr']->fileType;
        }

        return $args;
    }
}

/* get category (old to new) */
function amactive_get_category( $getSlug ) {
    global $catsArr;

    $catsArr = array(
        'classic-cars-for-sale'     => [2,2],
        // 'classic-cars-sold'         => [2,38],// old site shared same parent category, and had sale status as a detail, whereas now it is an additional category
        'testimonials'              => [3,3],
        'press'                     => [4,4],
        'news'                      => [5,40],        
        'plates'                    => [6,0],//NA - now using page, not posts
        'page-text'                 => [7,0],//NA - now using pages, not posts
        'links'                     => [8,13],
        'homepage-image-sequence'   => [7,0],//NA - now using pages, not posts
        'history'                   => [10,21],
    );

    return $catsArr[$getSlug];
}

/* get subcategory (old to new) */
function amactive_get_subcategory( $getSlug ) {
    global $subcatsArr;

    $subcatsArr = array(
        'ac'                    => [91,56],
        'aec'                   => [114,51],
        'alfa-romeo'            => [2,12],
        'alvis'                 => [3,20],
        'ariel'                 => [67,52],
        'armstrong-siddeley'    => [4,21],
        'aston-martin'          => [65,13],
        'audi'                  => [70,55],
        'austin'                => [6,23],
        'austin-healey'         => [5,22],
        'avon'                  => [108,53],

        'bedford'               => [7,31],
        'bentley'               => [8,57],
        'bmw'                   => [9,58],
        'bond'                  => [97,59],
        'borgward'              => [104,60],
        'bristol'               => [117,61],
        'british-leyland'       => [51,62],
        'bsa'                   => [10,63],
        'buick'                 => [59,64],
        'bus'                   => [11,65],

        'cadillac'              => [50],

        'triumph'               => [41,26],
        'ferrari'               => [18,14]
    );

    return $subcatsArr[$getSlug];
}



function amactive_migrate_item_attachments( $getMetaId, $getBaseArr, $getPostArr ) {
    // REF: http://php.net/manual/en/function.json-decode.php
    global $wpdb;

    $attachmentArr = $getBaseArr;

    if(!$getMetaId) {        
        $attachmentArr = array( 'attachments' => [] );
    }
    
    // if($getPostArr->id_attachment){
        $tmp_attachmentToAddArr = array(
            'id' => $getPostArr->id_attachment,
            'fields' => array(
                'title' => $getPostArr->name,
                'caption' => ''//$getPostArr->description
            )
        );

        if(array_push($attachmentArr['attachments'], $tmp_attachmentToAddArr)){
            $attachmentArrAsString = json_encode($attachmentArr);
            
            $args_postmeta = array(
                'post_id' => $getPostArr->id,
                'meta_key' => 'attachments',
                'meta_value' => $attachmentArrAsString
            );

            if( !$getMetaId ){
                $attachmentsResult = $wpdb->insert('wp_postmeta', $args_postmeta);
                if($attachmentsResult){
                    $attachments_id = $wpdb->insert_id;
                    amactive_debug_success('wp_postmeta > meta_key=\'attachments\', meta_value='.$attachmentArrAsString);
                }
            }else{
                $sqlUpdateAttachmentField = $wpdb->update(
                    'wp_postmeta',
                    array( 'meta_value' => $attachmentArrAsString ),
                    array( 'meta_id' => $getMetaId )                      
                );
                if($sqlUpdateAttachmentField) amactive_debug_success('UPDATED > wp_postmeta > meta_key=\'attachments\', meta_value=[arr]');
            }
            
        }

    // }


    // {
    //     "attachments": [
    //         {
    //             "id": "398",
    //             "fields": {
    //                 "title": "355 xtra title",
    //                 "caption": "&lt;p&gt;355 xtra desc&lt;\/p&gt;"
    //             }
    //         },
    //         {
    //             "id": "307",
    //             "fields": {
    //                 "title": "aaa",
    //                 "caption": "&lt;p&gt;aaaa&lt;\/p&gt;"
    //             }
    //         },
    //         {
    //             "id": "4123",
    //             "fields": {
    //                 "title": "xxx",
    //                 "caption": "xxxxxx"
    //             }
    //         }
    //     ]
    // }
}

function amactive_print_post( $getPost ){
    global $getCategory, $getSubcategory;

    $itemTable = '<table>';
    $itemTable .= '<tr><th>ID</th><th>IMG</th><th>Options</th></tr>';
    $itemTable .= '<tr>';
    $itemTable .= '<td>'.$getPost.'</th>';
    $itemTable .= '<th>'.get_the_post_thumbnail( $getPost ).'</th>';
    $itemTable .= '<th>';
    $itemTable .= '<a href="'.get_bloginfo('url').'/?page_id=2839&delete-post='.$getPost.'">DELETE POST</a>';
    $itemTable .= '<br><a href="'.get_bloginfo('url').'/?page_id='.$getPost.'">VIEW POST ONLINE</a>';
    $itemTable .= '</th>';
    $itemTable .= '</tr>';
    $itemTable .= '</table>';

    return $itemTable;
}