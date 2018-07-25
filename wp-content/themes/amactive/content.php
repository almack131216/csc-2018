<div class="card mt-4">
    <?php
        if( has_post_thumbnail() ):
            the_post_thumbnail( 'large', array(
                'class' => 'card-img-top img-fluid'
                )
            );
        else:
            echo 'xxx';
        endif;
    ?>
    
    
    <?php
    // REF: https://github.com/jchristopher/attachments/blob/master/docs/usage.md
  // retrieve all Attachments for the 'attachments' instance of post 123
  $attachments = new Attachments( 'attachments', $post->ID );
  echo '???'.$post->ID; 
?>
<?php if( $attachments->exist() ) : ?>
  <h3>Attachments</h3>
  <p>Total Attachments: <?php echo $attachments->total(); ?></p>
  <ul>
    <?php while( $attachments->get() ) : ?>

      <li>
        <a href="<?php echo $attachments->src( 'full' ); ?>" title="<?php echo $attachments->field( 'title' ); ?>" class="foobox" rel="gallery">
        <?php echo $attachments->image( 'thumbnail' ); ?>
        </a>
      </li>
    <?php endwhile; ?>
  </ul>

<div class="row justify-content-center xxx" style="display:none;">
    <div class="col-md-8">
        <div class="row">
            <a href="https://unsplash.it/1200/768.jpg?image=251" data-toggle="lightbox" data-gallery="example-gallery" class="col-sm-4">
                <img src="https://unsplash.it/600.jpg?image=251" class="img-fluid">
            </a>
            <a href="https://unsplash.it/1200/768.jpg?image=252" data-toggle="lightbox" data-gallery="example-gallery" class="col-sm-4">
                <img src="https://unsplash.it/600.jpg?image=252" class="img-fluid">
            </a>
            <a href="https://unsplash.it/1200/768.jpg?image=253" data-toggle="lightbox" data-gallery="example-gallery" class="col-sm-4">
                <img src="https://unsplash.it/600.jpg?image=253" class="img-fluid">
            </a>
        </div>
        <div class="row">
            <a href="https://unsplash.it/1200/768.jpg?image=254" data-toggle="lightbox" data-gallery="example-gallery" class="col-sm-4">
                <img src="https://unsplash.it/600.jpg?image=254" class="img-fluid">
            </a>
            <a href="https://unsplash.it/1200/768.jpg?image=255" data-toggle="lightbox" data-gallery="example-gallery" class="col-sm-4">
                <img src="https://unsplash.it/600.jpg?image=255" class="img-fluid">
            </a>
            <a href="https://unsplash.it/1200/768.jpg?image=256" data-toggle="lightbox" data-gallery="example-gallery" class="col-sm-4">
                <img src="https://unsplash.it/600.jpg?image=256" class="img-fluid">
            </a>
        </div>
    </div>
</div>

<?php endif; ?>


    <div class="card-body">
        <h3 class="card-title"><?php the_title();?></h3>
        <?php
            $price = get_post_meta( $post->ID, 'csc_car_price', true);
            if($price):
                echo '<h4>'.amactive_my_custom_price_format($price).'</h4>';
            endif;
        ?>
        <?php the_content();?>
        <?php the_content();?>
    </div>
</div> 