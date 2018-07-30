
<?php

    if( is_front_page() ):
        $amactive_classes_body = array( 'amactive-class', 'my-class' );
    else:
        $amactive_classes_body = array( 'not-amactive-class' );
    endif;

    $GLOBALS['categoryIdIsForSale'] = 2;
    $GLOBALS['categoryIdIsSold'] = 38;
    define("DV_categoryIdIsForSale", 2);// echo DV_categoryIdIsForSale;
    define("DV_categoryIdIsSold", 38);// echo DV_categoryIdIsForSale;
    define('DV_categorySlugIsForSale', 'classic-cars-for-sale');
    define('DV_categorySlugIsSold', 'classic-cars-sold');

    $GLOBALS['postPageIsForSale'] = null;
    $GLOBALS['postPageIsSold'] = null;

    $GLOBALS['postPageIsSold'] = null;
    $GLOBALS['showProductCats'] = null;
    $GLOBALS['postPageTitle'] = null;
    $GLOBALS['postPageCategoryId'] = null;
    $GLOBALS['postPageCategoryName'] = null;
    $GLOBALS['sidebarCategoryListTitle'] = null;

    $GLOBALS['pageType'] = null;

    if( have_posts() ):
        if ( is_page() ):
            $GLOBALS['pageType'] = 'page';
        elseif( is_front_page() ):
            $GLOBALS['pageType'] = 'front_page'; 
        elseif( is_single() ):
            $GLOBALS['pageType'] = 'single';
            $GLOBALS['showProductCats'] = true;

            $categoryArr = get_the_category();
            // if ( $GLOBALS['postPageCategoryId'] == DV_categoryIdIsForSale || $GLOBALS['postPageCategoryId'] ==  DV_categoryIdIsSold ) {                        
            //REF: https://stackoverflow.com/questions/45417125/how-to-exclude-specific-category-and-show-only-one-from-the-get-the-category
            if ($categoryArr) :
                foreach($categoryArr as $category) {
                    if(!$GLOBALS['postPageCategoryId'] && ($category->term_id == DV_categoryIdIsForSale || $category->term_id == DV_categoryIdIsSold)) {
                        $GLOBALS['postPageCategoryId'] = $category->term_id;
                        $GLOBALS['postPageCategoryName'] = $category->name;
                        $GLOBALS['postPageSubCategorySlug'] = $category->slug;
                        // echo '<h5>??? categoryId: '.$GLOBALS['postPageCategoryId'].'</h6>';
                        // echo '<h5>??? categoryName: '.$GLOBALS['postPageCategoryName'].'</h6>';
                        continue;
                    }
                    if($GLOBALS['postPageCategoryId'] == DV_categoryIdIsForSale && ($category->term_id == DV_categoryIdIsSold)) {
                        $GLOBALS['postPageCategoryId'] = $category->term_id;
                        $GLOBALS['postPageCategoryName'] = $category->name;
                        $GLOBALS['postPageCategorySlug'] = $category->slug;
                        // echo '<h5>???? categoryId: '.$GLOBALS['postPageCategoryId'].'</h6>';
                        // echo '<h5>???? categoryName: '.$GLOBALS['postPageCategoryName'].'</h6>';
                        continue;
                    }
                    if($GLOBALS['postPageCategoryId'] && ($category->term_id != DV_categoryIdIsSold)) {
                        $GLOBALS['showProductCats'] = true;
                        $GLOBALS['postPageSubCategoryId'] = $category->term_id;
                        $GLOBALS['postPageSubCategoryName'] = $category->name;
                        $GLOBALS['postPageSubCategorySlug'] = $category->slug;
                        // echo '<h5>????? subcategoryId: '.$GLOBALS['postPageSubCategoryId'].'</h6>';
                        // echo '<h5>????? subcategoryName: '.$GLOBALS['postPageSubCategoryName'].'</h6>';
                        break;
                    }
                }
            endif;
            // }
                                
            // echo '<h5>??? csc_car_sale_status: '.get_post_meta( $post->ID, 'csc_car_sale_status', true).'</h6>';
      
        else:
            $GLOBALS['pageType'] = 'posts';
        endif;
    endif;

    // [?] if posts page */
    if(!empty($cat)){
        $GLOBALS['page_object'] = get_queried_object();
        // var_dump($GLOBALS['page_object']);
        // $GLOBALS['postPageCategoryId'] = get_query_var('cat');        
        $GLOBALS['postPageCategoryId'] = $GLOBALS['page_object']->term_id;
        $GLOBALS['postPageCategoryName'] = $GLOBALS['page_object']->cat_name;
        $GLOBALS['postPageCategoryCount'] = $GLOBALS['page_object']->category_count;
        $GLOBALS['postPageTitle'] = $GLOBALS['postPageCategoryName'];
        $GLOBALS['sidebarCategoryListTitle'] = $GLOBALS['postPageCategoryCount'].' '.$GLOBALS['postPageCategoryName'];

        if( $GLOBALS['page_object']->category_parent ){
            // echo '<h6>category_parent: '.$GLOBALS['page_object']->category_parent.'</h6>';
            $thisCat = get_category($GLOBALS['page_object']->category_parent);
            $GLOBALS['postPageCategoryId'] = $GLOBALS['page_object']->category_parent;
            $GLOBALS['postPageCategoryName'] = $thisCat->name;            

            $GLOBALS['postPageSubCategoryId'] = $GLOBALS['page_object']->term_id;
            $GLOBALS['postPageSubCategoryName'] = $GLOBALS['page_object']->name;

            if(strpos($_SERVER['REQUEST_URI'], 'classic-cars-sold') !== false){
                $GLOBALS['postPageCategoryId'] = DV_categoryIdIsSold;
                $GLOBALS['postPageCategoryName'] = 'XXX SOLD XXX';
            }
        }

        if ( $GLOBALS['postPageCategoryId'] == DV_categoryIdIsForSale ) {
            $GLOBALS['postPageIsForSale'] = true;
            $GLOBALS['showProductCats'] = true;
            // dynamic_sidebar( 'custom-side-bar' );
        } else if ( $GLOBALS['postPageCategoryId'] == DV_categoryIdIsSold ) {
            $GLOBALS['postPageIsSold'] = true;
            $GLOBALS['showProductCats'] = true;
            // dynamic_sidebar( 'custom-side-bar-sold' );
        }
    }


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en_gb">
<head>
    <title><?php
        // separator, print immediately, separator position
        wp_title( ' | ', TRUE, 'right' );
        bloginfo( 'name' );
    ?></title>

    <meta http-equiv="Content-Type" content="text/htm; charset=iso-8859-1">
    <meta name="Description" content="">
    <meta name="Keywords" content="">
    <meta name="Author" content="Alex Mackenzie, amactive.net 2018">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>    
    <?php
        wp_head();
    ?>
    <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico" type="image/x-icon">
</head>



<body <?php body_class( $amactive_classes_body ); ?>>

<nav class="navbar navbar-expand-lg" role="navigation">
  <div class="container">
	<!-- Brand and toggle get grouped for better mobile display -->
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#bs-amactive-navbar-collapse" aria-controls="bs-amactive-navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<a class="navbar-brand" href="<?php bloginfo('url') ?>"><?php bloginfo('name')?></a>
        <?php
            wp_nav_menu( array(
                'theme_location'  => 'primary_menu',
                'depth'	          => 1, // 1 = no dropdowns, 2 = with dropdowns.
                'container'       => 'div',
                'container_class' => 'collapse navbar-collapse',
                'container_id'    => 'bs-amactive-navbar-collapse',
                'menu_class'      => 'navbar-nav mr-auto',
                'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
                'walker'          => new WP_Bootstrap_Navwalker(),
            ));
        ?>
    </div>
</nav>

<div class="container">
            