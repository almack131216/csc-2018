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
            