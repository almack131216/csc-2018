
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
    <?php
        wp_head();
    ?>
    <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico" type="image/x-icon">
</head>

<?php

    if( is_front_page() ):
        $amactive_classes = array( 'amactive-class', 'my-class' );
    else:
        $amactive_classes = array( 'not-amactive-class' );
    endif;

?>

<body <?php body_class( $amactive_classes ); ?>>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
    <a class="navbar-brand" href="#">Start Bootstrap</a>
    <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="navbar-collapse collapse" id="navbarResponsive" style="">
        <ul class="navbar-nav ml-auto">
        <li class="nav-item active">
            <a class="nav-link" href="#">Home
            <span class="sr-only">(current)</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">About</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Services</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Contact</a>
        </li>
        </ul>
        <div class="header" style="display:none;">
                <a href="/" title="<?php bloginfo( 'name' ); ?>">
                    <img class="logo_topleft" src="<?php header_image(); ?>" alt="<?php bloginfo( 'name' ); ?> Logo" />
                </a>
                <p class="right">Telephone: 01944 758000
                    <br>Fax: 01944 758963
                    <br>Email: <a href="/cdn-cgi/l/email-protection#2556444940566546494456564c46444b4156554a5751564644570b4951410b504e" title="Contact Classic and Sportscar Centre via email">
                    <span class="__cf_email__" data-cfemail="e49785888197a487888597978d87858a8097948b969097878596ca889080ca918f">[email&#160;protected]</span></a>
                </p>
            </div>
    </div>
    </div>
</nav>

<div class="container">
            