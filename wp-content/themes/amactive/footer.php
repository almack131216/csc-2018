        
    <!--(END) row-->
</div>
<!--(END) contentbox-->

<footer>
    <!--REF: https://bootsnipp.com/snippets/xrpdB-->
    <div class="container">
        <div class="row">
                    
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <h4><?php bloginfo( 'name' ) ?></h4>
                <?php
                    wp_nav_menu( array(
                        'theme_location'  => 'footer_menu_1',
                        'depth'	          => 1, // 1 = no dropdowns, 2 = with dropdowns.
                        'container'       => '',
                        'container_class' => '',
                        'container_id'    => '',
                        'menu_class'      => 'ul-footer footer_menu_1'
                    ));
                ?>
            </div>            
            
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <h4>Other Services</h4>
                <?php
                    wp_nav_menu( array(
                        'theme_location'  => 'footer_menu_2',
                        'depth'	          => 1, // 1 = no dropdowns, 2 = with dropdowns.
                        'container'       => '',
                        'container_class' => '',
                        'container_id'    => '',
                        'menu_class'      => 'ul-footer footer_menu_2'
                    ));
                ?>
            </div>
    
            <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="title-with-fa-links">
                    <h4>Contact Us</h4>
                    <?php
                        wp_nav_menu( array(
                            'theme_location'  => 'social_menu',
                            'depth'	          => 1, // 1 = no dropdowns, 2 = with dropdowns.
                            'container'       => '',
                            'container_class' => '',
                            'container_id'    => '',
                            'menu_class'      => 'ul-footer social_menu'
                        ));
                    ?>
                </div>

                <?php
                    echo '<ul class="ul-footer">';
                    if(DV_contact_address) echo '<li><span>'.DV_contact_address.'</span></li>';
                    if(DV_contact_telephone) echo '<li><span>Telephone: '.DV_contact_telephone.'</span></li>';
                    echo '<li><a href="'.DV_base.'contact">Contact Details</a></li>';
                    echo '<li><a href="https://goo.gl/maps/VeDEpYUZjzB2" target="_blank">Find us on Google Map</a></li>';
                    echo '</ul>';                   
                ?>
            </div>
            
        </div>
    </div>
</footer> 

    <?php
        wp_footer();
    ?>
 
    <?php
        if(!$offline){
            echo <<<EOD
 <script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-12902280-1");
pageTracker._trackPageview();
} catch(err) {}</script>
EOD;
        }
    ?>

</body>
</html>