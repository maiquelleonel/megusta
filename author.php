<?php get_header(); ?>
<div class="b_content clearfix" id="main">

    <!-- Start content -->
    <div class="b_page clearfix">

        <!-- left sidebar -->
        <?php
            
            global $wp_query;
            $curauth = $wp_query->get_queried_object();

            $curauth -> ID;
            
            $left = layout::get_side( 'left' , 0 , 'author' );
            if( $left ){
                if( layout::get_length( 0 , 'author' ) == 940 ){
                    $classes = 'fullwidth';
                }else{
                    $classes = 'fr';
                }
            }else{
                if( layout::get_length( 0 , 'author' ) == 940 ){
                    $classes = 'fullwidth';
                }else{
                    $classes = 'fl';
                }
            }

            $grid = post::is_grid( 'author' );
        ?>

        <div id="primary" class="w_<?php echo layout::get_length( 0 , 'author' , true ); ?> <?php echo $classes; ?>">
            <div id="content" role="main">


                <div class="b w_<?php echo layout::get_length( 0 , 'author' ); ?> author <?php if( $grid ){ echo 'grid-view';  }else{ echo 'list-view'; } ?>">

                    <h1 class="entry-title author">
                        <?php 
                            _e( 'Author archives: ', 'cosmotheme' );
                        ?>
                                <span class='vcard'>
                                    <a class="url fn n" href="" title="<?php echo esc_attr( get_the_author_meta( 'display_name' , $curauth -> ID ) ); ?>" rel="me">
                                        <?php echo get_the_author_meta( 'display_name' , $curauth -> ID ); ?>
                                    </a>
                                </span>

                                <!-- All posts (  Likes , posted ) -->
                                <?php
                                    if( options::logic( 'general' , 'enb_likes' ) ){
                                ?>
                                <?php
                                        $url = get_author_posts_url( $curauth -> ID );

                                        $all = array( 'type' => "all" );
                                        $url_all = add_query_arg( $all , $url );

                                        $like = array( 'type' => "like" );
                                        $url_like = add_query_arg( $like , $url );

                                        $like = array( 'type' => "post" );
                                        $url_post = add_query_arg( $like , $url );

                                        $aclasses = '';
                                        $pclasses = '';
                                        $lclasses = '';

                                        if( isset( $_GET['type'] ) ){


                                            switch( $_GET[ 'type' ] ){
                                                case 'all' : {
                                                    $aclasses = 'class="active"';
                                                    break;
                                                }
                                                case 'like' : {
                                                    $lclasses = 'class="active"';
                                                    break;
                                                }
                                                case 'post' : {
                                                    $pclasses = 'class="active"';
                                                    break;
                                                }
                                                default : {
                                                    $pclasses = 'class="active"';
                                                    break;
                                                }
                                            }
                                        }else{
                                            $pclasses = 'class="active"';
                                        }
                                ?>
                                        <span class="links">
                                            <?php _e( 'Show' , 'cosmotheme' ); ?> :
                                            <a <?php echo $pclasses; ?> href="<?php echo $url_post; ?>"><?php _e( 'Posts' , 'cosmotheme' ); ?></a>
                                            <a <?php echo $lclasses; ?> href="<?php echo $url_like; ?>"><?php _e( 'Likes' , 'cosmotheme' ); ?></a>
                                            <a <?php echo $aclasses; ?> href="<?php echo $url_all; ?>"><?php _e( 'All' , 'cosmotheme' ); ?></a>
                                        </span>
                            <?php
                                    }
                            ?>
                    </h1>
                    <span class="list-grid fr"><a href="javascript:void(0);" rel="author" class="switch <?php if( $grid ){ echo 'swap';  } ?>">&nbsp;</a></span>
                    <p class="delimiter">&nbsp;</p>
                    <?php 
                        post::author( $curauth -> ID );
                    ?>
                </div>
            </div>
        </div>

        <!-- right sidebar -->
        <?php layout::get_side( 'right' , 0 , 'author' ); ?>
    </div>
</div>
<?php get_footer(); ?>