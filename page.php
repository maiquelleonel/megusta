<?php 
if( $post -> ID == options::get_value( 'general' , 'user_profile_page' ) ){
	get_template_part( 'user_profile_update' );
}
get_header(); 

?>
<div class="b_content clearfix" id="main">

    <?php
        while( have_posts () ){
            the_post();
            $post_id = $post -> ID;
			
    ?>
            <!-- Start content -->
            <div class="b_page clearfix">

                <!-- left sidebar -->
                <?php
                    $left = layout::get_side( 'left' , $post_id , 'page');
                    if( $left ){
                        if( layout::get_length( $post_id , 'page' ) == 940 ){
                            $classes = 'fullwidth';
                        }else{
                            $classes = 'fr';
                        }
                    }else{
                        if( layout::get_length( $post_id , 'page' ) == 940 ){
                            $classes = 'fullwidth';
                        }else{
                            $classes = 'fl';
                        }
                    }
                ?>

                <div id="primary" class="w_<?php echo layout::get_length( $post_id , 'page' , true ); ?> <?php echo $classes; ?>">
                    <div id="content" role="main">
                        <div class="b w_<?php echo layout::get_length( $post_id , 'page' ); ?> category">
						<?php 
								if( $post -> post_title == 'Post Item' ){
									get_template_part( 'post_item' );
								
								}elseif( $post_id == options::get_value( 'general' , 'user_profile_page' ) ){
									get_template_part( 'user_profile' );
								}else{
					
						?>
                            <!-- post -->
                            <article id="post-<?php the_ID(); ?>" <?php post_class( 'post' , $post -> ID ); ?>>

                                <!-- header -->
                                <header class="entry-header">
    
                                    <!-- title -->
                                    <h1 class="entry-title"><?php the_title(); ?></h1>

                                    <!-- meta -->
                                    <?php
                                        if( meta::logic( $post , 'settings' , 'meta' ) ){
                                            post::meta( $post );
                                        }
                                    ?>
                                </header>

                                <!-- content -->
                                <div class="entry-content">
                                <!-- featured images -->
                                <?php
                                    if( options::logic( 'general' , 'enb_featured' ) ){
                                        if( has_post_thumbnail ( $post -> ID ) ){
                                            if( $classes == 'fullwidth' ){
                                                $src  = wp_get_attachment_image_src( get_post_thumbnail_id( $post -> ID ) , '920xXXX' );
                                            }else{
                                                $src  = wp_get_attachment_image_src( get_post_thumbnail_id( $post -> ID ) , '600xXXX' );
                                            }

                                            $src_  = wp_get_attachment_image_src( get_post_thumbnail_id( $post -> ID ) , 'full' );
                                ?>
                                            <div class="featimg circle">
                                                <div class="img">
                                                    <?php
                                                        ob_start();
                                                        ob_clean();
                                                        get_template_part( 'caption' );
                                                        $caption = ob_get_clean();

                                                        if( options::logic( 'general' , 'enb_lightbox' )  ){
                                                    ?>
                                                            <a href="<?php echo $src_[0]; ?>" title="<?php echo $caption;  ?>" class="mosaic-overlay" rel="prettyPhoto-<?php echo $post -> ID; ?>">&nbsp;</a>
                                                    <?php
                                                        }
                                                    ?>
                                                        <img src="<?php echo $src[0]; ?>" class="no-safe image" alt="<?php echo $caption; ?>" >
                                                    <?php
                                                        if( strlen( trim( $caption) ) ){
                                                    ?>
                                                            <p class="wp-caption-text"><?php echo $caption; ?></p>
                                                    <?php
                                                        }
                                                    ?>
                                                </div>
                                            </div>
                                <?php
                                        }
                                    }
                                ?>
                                    <div class="b_text">
                                        <?php the_content(); ?>
                                    </div>
                                </div>

                                <footer class="entry-footer">
                                    <?php get_template_part( 'social-sharing' ); ?>
                                    <?php
                                        if( strlen( options::get_value( 'advertisement' , 'content' ) ) > 0 ){
                                    ?>
                                            <div class="cosmo-ads zone-2">
                                                <?php echo options::get_value( 'advertisement' , 'content' ); ?>
                                            </div>
                                    <?php
                                        }
                                    ?>
                                </footer>
                            </article>
                            <p class="delimiter blank">&nbsp;</p>
                            <?php
                                /* comments */
                                if( comments_open() ){
                                    if( options::logic( 'general' , 'fb_comments' ) ){
                                        ?>
                                        <div id="comments">
                                            <h3 id="reply-title"><?php _e( 'Leave a Reply' , 'cosmotheme' ); ?></h3>
                                            <p class="delimiter">&nbsp;</p>
                                            <fb:comments href="<?php the_permalink(); ?>" num_posts="1" width="620"></fb:comments>
                                        </div>
                                        <?php
                                    }else{
                                        comments_template( '', true );
                                    }
                                }
							} /*EOF if( $post -> post_title == 'Post Item' ) */
                            ?>
                            
                        </div>
                    </div>
                </div>
                <!-- right sidebar -->
                <?php layout::get_side( 'right' , $post_id , 'page' ); ?>
            </div>
    <?php
			
        } /*EOF while*/
    ?>
</div>
<?php get_footer(); ?>
