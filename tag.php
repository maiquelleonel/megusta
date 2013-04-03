<?php get_header(); ?>
<div class="b_content clearfix" id="main">

    <!-- Start content -->
    <div class="b_page clearfix">

        <!-- left sidebar -->
        <?php
            $left = layout::get_side( 'left' , 0 , 'tag' );
            if( $left ){
                if( layout::get_length( 0 , 'tag' ) == 940 ){
                    $classes = 'fullwidth';
                }else{
                    $classes = 'fr';
                }
            }else{
                if( layout::get_length( 0 , 'tag' ) == 940 ){
                    $classes = 'fullwidth';
                }else{
                    $classes = 'fl';
                }
            }

            $grid = post::is_grid( 'tag' );
        ?>

        <div id="primary" class="w_<?php echo layout::get_length( 0 , 'tag' , true ); ?> <?php echo $classes; ?>">
            <div id="content" role="main">

                <div class="b w_<?php echo layout::get_length( 0 , 'tag' ); ?> tag <?php if( $grid ){ echo 'grid-view';  }else{ echo 'list-view'; } ?>">

                    <h1 class="entry-title tag"><?php _e( 'Tags archives' , 'cosmotheme' ); echo ': ';  echo get_query_var('tag'); ?></h1>
                    <?php
                        if( have_posts () ){
                    ?>
                            <span class="list-grid fr"><a href="javascript:void();" rel="tag" class="switch <?php if( $grid ){ echo 'swap';  } ?>">&nbsp;</a></span>
                    <?php
                        }
                    ?>
                    <p class="delimiter">&nbsp;</p>
                    <?php post::loop( 'tag' ); ?>
                </div>
            </div>
        </div>
        <!-- right sidebar -->
        <?php layout::get_side( 'right' , 0 , 'tag' ); ?>
    </div>
</div>
<?php get_footer(); ?>
