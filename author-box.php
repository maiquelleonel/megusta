<?php
    if( !isset( $post ) ){
        return '';
    }
    $meta = meta::get_meta( $post -> ID , 'settings' );

    if( isset( $meta[ 'author' ] ) && strlen( $meta[ 'author' ] ) && !is_author() ){
        $show_author = meta::logic( $post , 'settings' , 'author' );
    }else{
        if( is_single() ){
            $show_author = options::logic( 'blog_post' , 'post_author_box' );
        }

        if( is_page() ){
            $show_author = options::logic( 'blog_post' , 'page_author_box' );
        }

        if( !( is_single() || is_page() ) ){
            $show_author = true;
        }
    }

    if( ( is_single () || is_author() || is_page() ) &&  $show_author   ){
?>
        <aside id="archives-3" class="widget">
            <h4 class="widget-title">
                <?php _e( 'By' , 'cosmotheme' )?>
                <span class="vcard">
                    <a class="url fn n" href="<?php echo get_author_posts_url( $post-> post_author ) ?>" title="<?php echo esc_attr( get_the_author_meta( 'display_name' , $post-> post_author ) ); ?>" rel="me">
                        <?php echo get_the_author_meta( 'display_name' , $post-> post_author ); ?>
                    </a>
                </span>
                <?php
                    if( !is_author() && options::logic( 'general' , 'enb_likes' ) ){

                        $url = get_author_posts_url( $post-> post_author );

                        $all = array( 'type' => "all" );
                        $url_all = add_query_arg( $all , $url );

                        $like = array( 'type' => "like" );
                        $url_like = add_query_arg( $like , $url );

                        $like = array( 'type' => "post" );
                        $url_post = add_query_arg( $like , $url );
                ?>
                        <span class="links">
                            <a href="<?php echo $url_all; ?>"><?php _e( 'All' , 'cosmotheme' ); ?></a>
                            <a href="<?php echo $url_post; ?>"><?php _e( 'Posts' , 'cosmotheme' ); ?></a>
                            <a href="<?php echo $url_like; ?>"><?php _e( 'Likes' , 'cosmotheme' ); ?></a>
                        </span>
                <?php
                    }
                ?>
            </h4>
            <div class="box-author clearfix">
                <p>
                    <a href="<?php echo get_author_posts_url( $post -> post_author) ?>"><?php echo cosmo_avatar( $post -> post_author , $size = '60', $default = DEFAULT_AVATAR );  ?></a>
                    <?php
                        $author_bio = get_the_author_meta( 'description' , $post -> post_author );

                        if( $author_bio != '' ){
                            echo '<span class="author-page">' . $author_bio . '</span>';
                        }
                    ?>
                </p>
            </div>
        </aside>
<?php
    }
?>
