    <?php if(!isset($_REQUEST["um_ajax_load_site"])): ?>
    </div>
    <!--Closed Inner Content-->
    <div id="footer" class="containerXXX left-spaceXXX">
        <div class="footer-widgets row">

            <?php
                if(is_dynamic_sidebar("footer")){
                    dynamic_sidebar("footer");
                }
            ?>

        </div>
    </div>
    <?php wp_footer(); ?>
</div>
<!--Closed wrap-all-->
</body>
</html>
<?php endif; ?>