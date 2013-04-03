<?php get_header(); ?>
<div class="b_content clearfix" id="main">

    <!-- Start content -->
    <div class="b_page clearfix">

        <!-- left sidebar -->
        <?php 
            $left = layout::get_side( 'left' , 0 , 'archive' );

            if( $left ){
                if( layout::get_length( 0 , 'archive' ) == 940 ){
                    $classes = 'fullwidth';
                }else{
                    $classes = 'fr';
                }
            }else{
                if( layout::get_length( 0 , 'archive' ) == 940 ){
                    $classes = 'fullwidth';
                }else{
                    $classes = 'fl';
                }
            }

            $grid = post::is_grid( 'archive' );
        ?>

        <div id="primary" class="w_<?php echo layout::get_length( 0 , 'archive' , true ); ?>  <?php echo $classes; ?>">
            <div id="content" role="main">


                <div class="b w_<?php echo layout::get_length( 0 , 'archive' ); ?> archive <?php if( $grid ){ echo 'grid-view';  }else{ echo 'list-view'; } ?>">

                <?php
                    if ( is_day() ) {
                        echo '<h1 class="entry-title archive">' . __( 'Daily archives' , 'cosmotheme' ) . ': <span>' . get_the_date() . '</span></h1>';
                    }else if ( is_month() ) {
						echo '<h1 class="entry-title archive">' . __( 'Monthly archives' , 'cosmotheme' ) . ': <span>' . get_the_date( 'F Y' ) . '</span></h1>';
                    }else if ( is_year() ) {
						echo '<h1 class="entry-title archive">' . __( 'Yearly archives' , 'cosmotheme' ) . ': <span>' . get_the_date( 'Y' ) . '</span></h1>';
                    }else {
                        echo '<h1 class="entry-title archive">' . __( 'Blog archives' , 'cosmotheme' ) . '</h1>';
                    }

                    if( have_posts () ){
                ?>
                        <span class="list-grid fr"><a href="javascript:void();" rel="archive" class="switch <?php if( $grid ){ echo 'swap';  } ?>">&nbsp;</a></span>
                <?php
                    }
                ?>
                    <p class="delimiter">&nbsp;</p>
                    <?php post::loop( 'archive' ); ?>
                </div>
            </div>
        </div>
        <!-- right sidebar -->
        <?php layout::get_side( 'right' , 0 , 'archive' ); ?>
    </div>
</div>
<?php get_footer(); ?>
