
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
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
</head>

<?php

    if( is_front_page() ):
        $amactive_classes = array( 'amactive-class', 'my-class' );
    else:
        $amactive_classes = array( 'not-amactive-class' );
    endif;

?>

<body <?php body_class( $amactive_classes ); ?>>
<div class="wrap_all">
    <div class="wrap_content">
        <div class="header">
            <a href="http://www.classicandsportscar.ltd.uk/" title="Classic and Sportscar Centre homepage">
                <img class="logo_topleft" src="<?php echo get_template_directory_uri().'/stat/logo.gif'; ?>" width="440px" height="90px" border="0" alt="Classic and Sportscar Centre">
            </a>
            <p class="right">Telephone: 01944 758000
                <br>Fax: 01944 758963
                <br>Email: <a href="/cdn-cgi/l/email-protection#2556444940566546494456564c46444b4156554a5751564644570b4951410b504e" title="Email Classic and Sportscar Centre via email">
                <span class="__cf_email__" data-cfemail="e49785888197a487888597978d87858a8097948b969097878596ca889080ca918f">[email&#160;protected]</span></a>
            </p>
        </div>
        <div class="contentbox" id="BlueBG2">