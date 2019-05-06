<?php
    include('_base-variables.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en_gb">
<head>
    <title>
        <?php
            if( $GLOBALS['postPageTitle'] ) {
                echo $GLOBALS['postPageTitle'];
                echo ' | ';
                bloginfo( 'name' );
            } else {
                // separator, print immediately, separator position
                wp_title( ' | ', TRUE, 'right' );
                bloginfo( 'name' );
            }            
        ?>
    </title>

    <meta http-equiv="Content-Type" content="text/htm; charset=iso-8859-1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="Description" content="">
    <meta name="Keywords" content="">
    <meta name="Author" content="Alex Mackenzie, amactive.net 2018">

       
    <?php
        if($offline){
            echo '<script src="'.get_template_directory_uri().'/offline/jquery.min.js"></script>';
        }else{
            echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>';
        }
        wp_head();
    ?>
    <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico" type="image/x-icon">

    <script type="text/javascript">
        // REF: https://premium.wpmudev.org/forums/topic/how-do-you-insert-a-jump-menu-for-categories-on-a-wp-page
        function MM_jumpMenu(targ,selObj,restore){ //v3.0
            eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
            if (restore) selObj.selectedIndex=0;
        }

        $(document).ready(function () {
            $(".thumb").click(function () {
                var dir = $(this).data("big");
                var dirBG = "background-image: url(" + $(this).data("big") + ")";
                // $('.col-post-img.featured img').hide();                
                // $('.col-post-img.featured img').attr("src", dir);
                $('.col-post-img.featured img').attr("src", '<?php echo DV_base; ?>/wp-content/themes/amactive/ajax-loader.gif');
                $('.col-post-img.featured .jsZoom').attr("data-zoom", dir);
                $('.col-post-img.featured .jsZoom').attr("style", dirBG);
                $('.col-post-img.featured img').attr("src", dir);
                // // $('.col-post-img.featured img').fadeIn('fast');
                // // $('.col-post-img.featured img').attr("style.background-image", dir);
                // // cc_zoom_featured_image();
            });

            $('.amcust-zoom-wrap > img').on('mouseenter', function() {
                $(this).parent().addClass( "amcust-zoom-hover" );
                // $(this).removeClass().addClass( "active a red" );
                // var imgLarge = $(this).data("amcust-zoom-large");
                // var imgFull = $(this).data("big");
                // var zoomerId = $(this).data("big-id");

                var dir = $(this).data("big");
                var zoomerId = $(this).data("big-id");                
                var dirBG = "background-image: url(" + $(this).data("big") + ")";
                // alert($(this).data("big-id") + ' - ' + imgFull);

                $(this).parent().children('.jsZoom').children('img').attr("src", '<?php echo DV_base; ?>/wp-content/themes/amactive/ajax-loader.gif');
                $(this).parent().children('.jsZoom').attr("data-zoom", dir);
                $(this).parent().children('.jsZoom').attr("style", dirBG);
                $(this).parent().children('.jsZoom').children('img').attr("src", dir);
            });
        });
    </script>
</head>



<body <?php body_class( $GLOBALS['pageBodyClass'] ); ?>>

<nav class="navbar" role="navigation">
<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-6 navbar-brand-wrap">
            <a class="navbar-brand" href="<?php bloginfo('url') ?>">        
                <img src="<?php echo get_template_directory_uri(); ?>/amadded/assets/img/logo.gif" alt="<?php bloginfo('name')?>" />
            </a>
            <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#bs-amactive-navbar-collapse" aria-controls="bs-amactive-navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="fa fa-chevron-down"></span>
                <span class="fa fa-chevron-up"></span>
            </button>
        </div>
        <div class="hidden-md-down col-md-6">
                  
            <?php
                echo '<h1>'.DV_strapline.'</h1>';
                echo '<ul class="ul-header">';
                echo '<li class="li-telephone">Telephone: '.DV_contact_telephone.'</li>';
                echo '<li class="li-contact"><a href="'.DV_base.'contact">Contact</a></li>';
                echo '</ul>';

                wp_nav_menu( array(
                    'theme_location'  => 'social_menu',
                    'depth'	          => 1, // 1 = no dropdowns, 2 = with dropdowns.
                    'container'       => '',
                    'container_class' => '',
                    'container_id'    => '',
                    'menu_class'      => 'social_menu'
                ));
            ?>
        </div>
    </div>

    <!-- Brand and toggle get grouped for better mobile display -->

        
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
            