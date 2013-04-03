<?php get_header(); ?>
<div class="b_content clearfix" id="main">
	<?php
        while( have_posts () ){
            the_post();
            $post_id = $post -> ID
    ?>
    <!-- Start content -->
    <div class="b_page clearfix">

        <!-- left sidebar -->
        <?php 
            $left = layout::get_side( 'left' , 0 , 'attachment' );

            if( $left ){
                if( layout::get_length( 0 , 'attachment' ) == 940 ){
                    $classes = 'fullwidth';
                }else{
                    $classes = 'fr';
                }
            }else{
                if( layout::get_length( 0 , 'attachment' ) == 940 ){
                    $classes = 'fullwidth';
                }else{
                    $classes = 'fl';
                }
            }
        ?>

        <div id="primary" class="w_<?php echo layout::get_length( 0 , 'attachment' , true ); ?>  <?php echo $classes; ?>">
            <div id="content" role="main">
                <div class="b w_<?php echo layout::get_length( 0 , 'attachment' ); ?> category">
                    <?php //get_template_part( 'loop' , 'index' ); ?>
					<div class="featimg readmore"   >
						<div class="img">
						<?php
							$img_src = wp_get_attachment_image_src(  $post_id  , '600xXXX' );
							echo '<img src="'.$img_src[0].'" alt="" />';

							
						?>                
						</div>
					</div>
                </div>
            </div>
        </div>
        <!-- right sidebar -->
        <?php layout::get_side( 'right' , 0 , 'attachment' ); ?>
    </div>
	<?php
	}	
	?>
</div>
<?php get_footer(); ?>