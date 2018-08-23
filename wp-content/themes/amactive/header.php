<?php
    include('_base-variables.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en_gb">
<head>
    <title>
        <?php
            // separator, print immediately, separator position
            wp_title( ' | ', TRUE, 'right' );
            bloginfo( 'name' );
        ?>
    </title>

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

<nav class="navbar navbar-expand-lgXXX" role="navigation">
<div class="container">
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#bs-amactive-navbar-collapse" aria-controls="bs-amactive-navbar-collapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="fa fa-bars"></span>
    </button>
    <div class="row">
        <div class="col-md-6 navbar-brand-wrap">
            <a class="navbar-brand" href="<?php bloginfo('url') ?>">        
                <img src="<?php echo get_template_directory_uri(); ?>/amadded/assets/img/logo.gif" alt="<?php bloginfo('name')?>" />
            </a>
        </div>
        <div class="hidden-md-down col-md-6">
                  
            <?php
                echo '<h1>'.DV_strapline.'</h1>';
                echo '<ul class="ul-header ul-inline-pipes">';
                echo '<li class="li-telephone">Telephone: '.DV_contact_telephone.'</li>';
                echo '<li class="li-contact">Contact</li>';
                echo '</ul>';

                wp_nav_menu( array(
                    'theme_location'  => 'social_menu',
                    'depth'	          => 1, // 1 = no dropdowns, 2 = with dropdowns.
                    'container'       => '',
                    'container_class' => '',
                    'container_id'    => '',
                    'menu_class'      => 'ul-headerXXX social_menu'
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
            