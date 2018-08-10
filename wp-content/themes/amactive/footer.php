        
    <!--(END) row-->
</div>
<!--(END) contentbox-->

<footer>
    <!--REF: https://bootsnipp.com/snippets/xrpdB-->
    <div class="container">
        <div class="row padding-x-g1">
                    
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
                    echo '<li>'.DV_contact_address.'</li>';
                    echo '<li>Telephone: '.DV_contact_telephone.'</li>';
                    echo '<li>Email Us | Find us on Google Map</li>';                    
                ?>
            </div>
            
        </div>
    </div>
</footer> 

    <?php
        wp_footer();
    ?>
 
 <script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-12902280-1");
pageTracker._trackPageview();
} catch(err) {}</script>
</body>

</html>