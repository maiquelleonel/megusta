<?php get_header(); ?>
<div class="b_content clearfix" id="main">

    <!-- start 404 content -->
    <div class="b_page clearfix">

        <!-- left sidebar -->
        <?php
            $left = layout::get_side( 'left' , 0 , '404' );
            if( $left ){
                if( layout::get_length( 0 , 'blog_page' ) == 940 ){
                    $classes = 'fullwidth';
                }else{
                    $classes = 'fr';
                }
            }else{
                if( layout::get_length( 0 , 'blog_page' ) == 940 ){
                    $classes = 'fullwidth';
                }else{
                    $classes = 'fl';
                }
            }
        ?>
        <div id="primary" class="w_<?php echo layout::get_length( $post -> ID , '404' , true ); ?> <?php echo $classes; ?>">
            <div id="content" role="main">
                <div class="b w_<?php echo layout::get_length( $post -> ID , '404' ); ?> front-page">

                    <!--left side-->
                    <?php get_template_part( 'loop' , '404' ); ?>
                    
                </div>
            </div>
        </div>

        <!-- right sidebar -->
        <?php layout::get_side( 'right' , 0 , '404' ); ?>
    </div>
</div>
<?php get_footer(); ?>