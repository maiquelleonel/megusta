<?php
    /* related posts by herarchical taxonomy */
    /* get tax slugs and number of similar posts  */ 
    function similar_query( $post_id , $taxonomy , $nr ){
        if( $nr > 0 ){
            $topics = wp_get_post_terms( $post_id , $taxonomy );

            $terms = array();
            if( !empty( $topics ) ){
                foreach ( $topics as $topic ) {
                    $term = get_category( $topic );
                    array_push( $terms, $term -> slug );
                }
            }

            if( !empty( $terms ) ){
                $query = new WP_Query( array(
                    'post__not_in' => array( $post_id ) ,
                    'posts_per_page' => $nr,
                    'orderby' => 'rand',
                    'tax_query' => array(
                        array(
                        'taxonomy' => $taxonomy ,
                        'field' => 'slug',
                        'terms' => $terms ,
                        )
                    )
                ));
            }else{
                $query = array();
            }
        }else{
            $query = array();
        }

        return $query;
    }

    /* post taxonomy */
    $tax = options::get_value( 'blog_post' , 'similar_type' );
    $layout = meta::get_meta( $post -> ID , 'layout' );
    
    if( isset( $layout['type'] ) ){
        if( $layout['type'] != 'full' ){
            $nr = (int)options::get_value( 'blog_post' , 'post_similar_side' );
        }else{
            $nr = (int)options::get_value( 'blog_post' , 'post_similar_full' );
        }
    }else{
        $layout = options::get_value( 'layout' , 'single' );
        if( $layout != 'full' ){
            $nr = (int)options::get_value( 'blog_post' , 'post_similar_side' );
        }else{
            $nr = (int)options::get_value( 'blog_post' , 'post_similar_full' );
        }
    }
    
    $label  = __( 'Related Posts' , 'cosmotheme' );
    $query  = similar_query( $post -> ID , $tax , $nr );
    $length = layout::get_length( $post -> ID , 'single' );

    if( !empty( $query ) ){
        if( $query -> found_posts < $nr ){
            $nr = $query -> found_posts;
        }

        $result = $query -> posts;
    }
        


    

    if( !empty( $result) && meta::logic( $post , 'settings' , 'related' ) ){
?>
        <div class="box-related clearfix">
            <h3 class="related-title"><?php _e( 'Related posts' , 'cosmotheme' ); ?></h3>
            <p class="delimiter">&nbsp;</p>
            <div>
            <?php
                if( (int)$length == 940 ){
                    $div    = 4;
                    $size   = '210x100';
                }else{
                    $div = 3;
                    $size   = '170x100';
                }

                $i = 1;
                $k = 1;

                foreach( $result as $similar ){
                    if( $i == 1 ){
                        if( ( $nr - $k ) < $div  ){
                            $classes = 'class="last"';
                        }else{
                            $classes = '';
                        }
                        echo '<div ' . $classes . '>';
                    }

                    /* post likes */
                    $likes = meta::get_meta( $similar -> ID , 'like' );
                    $nr_like = count( $likes );
                
                    /* featured image */
                    $text = '';
                    $image = '';

                    if( has_post_thumbnail( $similar -> ID ) ){

                        if( is_user_logged_in () ){
                            $image = wp_get_attachment_image( get_post_thumbnail_id( $similar -> ID ) , $size );

                            $img = get_post( get_post_thumbnail_id( $similar -> ID ) );

                            if( !empty( $images ) ){
                                $text = $img -> post_excerpt;
                            }else{
                                $text = '';
                            }

                        }else{
                            $meta = meta::get_meta( $post -> ID  , 'settings' );
                            if( isset( $meta['safe'] ) ){
                                if( !meta::logic( $similar , 'settings' , 'safe' ) ){
                                    $image = wp_get_attachment_image( get_post_thumbnail_id( $similar -> ID ) , $size );

                                    $img = get_post( get_post_thumbnail_id( $similar -> ID ) );

                                    if( !empty( $images ) ){
                                        $text = $img -> post_excerpt;
                                    }else{
                                        $text = '';
                                    }

                                }else{
                                    $image = '<img src="' . get_template_directory_uri() . '/images/nsfw.' . $size . '.png" class="safe image" />';
                                    $text = '';
                                }
                            }else{
                                $image = wp_get_attachment_image( get_post_thumbnail_id( $similar -> ID ) , $size );

                                $img = get_post( get_post_thumbnail_id( $similar -> ID ) );

                                if( !empty( $images ) ){
                                    $text = $img -> post_excerpt;
                                }else{
                                    $text = '';
                                }
                            }
                        }
                    }else{
                        $image = '<img src="' . get_template_directory_uri() . '/images/no.image.' . $size . '.png" />';
                    }

                    /*  related presentation */
                ?>
                <article  id="post-<?php echo $similar -> ID; ?>" class="col format-<?php echo strlen( get_post_format( $similar -> ID ) ) ? get_post_format( $similar -> ID ) : 'standard'; ?>">
                        <?php
                            if( strlen( $image ) ){
                        ?>
                                <div class="readmore related">
                                    <?php
                                        if( is_user_logged_in () ){
                                            $cnt_a = ' class="mosaic-overlay" href="' . get_permalink( $similar -> ID ) . '"';
                                        }else{
                                            $meta = meta::get_meta( $post -> ID  , 'settings' );
                                            if( isset( $meta['safe'] ) ){
                                                if( !meta::logic( $similar , 'settings' , 'safe' ) ){
                                                    $cnt_a = ' class="mosaic-overlay" href="' . get_permalink( $similar -> ID ) . '"';
                                                }else{
                                                    $cnt_a = ' class="simplemodal-nsfw mosaic-overlay" href="' . wp_login_url( ) . '"';
                                                }
                                            }else{
                                                $cnt_a = ' class="mosaic-overlay" href="' . get_permalink( $similar -> ID ) . '"';
                                            }
                                        }
                                    ?>
                                    <a <?php echo $cnt_a; ?>>
                                        <div class="details">&nbsp;</div>
                                    </a>
									<div class="format">&nbsp;</div>
                                    <?php echo $image ?>
                                </div>
                                
                        <?php
                            }
                        ?>
                        <h4>
                            <?php
                                if( is_user_logged_in () ){
                                    ?><a class="readmore" href="<?php echo get_permalink( $similar -> ID ) ?>"><?php echo mb_substr( $similar -> post_title , 0 , BLOCK_TITLE_LEN ); ?></a><?php
                                }else{
                                    $meta = meta::get_meta( $post -> ID  , 'settings' );
                                    if( isset( $meta['safe'] ) ){
                                        if( !meta::logic( $similar , 'settings' , 'safe' ) ){
                                            ?><a class="readmore" href="<?php echo get_permalink( $similar -> ID ) ?>"><?php echo mb_substr( $similar -> post_title , 0 , BLOCK_TITLE_LEN ); ?></a><?php
                                        }else{
                                            ?><a class="simplemodal-nsfw readmore" href="<?php echo wp_login_url( ); ?>"><?php echo mb_substr( $similar -> post_title , 0 , BLOCK_TITLE_LEN ); ?></a><?php
                                        }
                                    }else{
                                        ?><a class="readmore" href="<?php echo get_permalink( $similar -> ID ) ?>"><?php echo mb_substr( $similar -> post_title , 0 , BLOCK_TITLE_LEN ); ?></a><?php
                                    }
                                }
                            ?>
                        </h4>
                        <div class="entry-meta">
                            <ul>
                                <li class="author"><a href="<?php echo get_author_posts_url( $similar -> post_author ) ?>"><?php echo mb_substr( get_the_author_meta( 'display_name' , $similar -> post_author ) , 0 , _AUTL_ ); ?></a></li>
								<li class="cosmo-comments">
                                    <?php
                                        if( is_user_logged_in () ){
                                            $cnt_a1 = ' href="' . get_permalink( $similar -> ID ) . '"';
                                            $cnt_a2 = ' href="' . get_comments_link( $similar -> ID ) . '"';
                                        }else{
                                            $meta = meta::get_meta( $post -> ID  , 'settings' );
                                            if( isset( $meta['safe'] ) ){
                                                if( !meta::logic( $similar , 'settings' , 'safe' ) ){
                                                    $cnt_a1 = ' href="' . get_permalink( $similar -> ID ) . '"';
                                                    $cnt_a2 = ' href="' . get_comments_link( $similar -> ID ) . '"';
                                                }else{
                                                    $cnt_a1 = ' class="simplemodal-nsfw" href="' . wp_login_url( ) . '"';
                                                    $cnt_a2 = ' class="simplemodal-nsfw" href="' . wp_login_url( ) . '"';
                                                }
                                            }else{
                                                $cnt_a1 = ' href="' . get_permalink( $similar -> ID ) . '"';
                                                $cnt_a2 = ' href="' . get_comments_link( $similar -> ID ) . '"';
                                            }
                                        }

                                        if( comments_open ( $similar -> ID ) ){
                                    ?>
                                            <a <?php echo $cnt_a1 ?>>
                                                <?php
                                                    if( options::logic( 'general' , 'fb_comments' ) ){
                                                        ?> <fb:comments-count href=<?php echo get_permalink( $similar -> ID  ) ?>></fb:comments-count> <?php
                                                    }else{
                                                        echo get_comments_number( $similar -> ID );
                                                    }
                                                ?>
                                            </a>
                                    <?php
                                        }else{
											?><a><?php _e( ' Off' , 'cosmotheme' ); ?></a><?php
										}
                                    ?>
                                </li>
                                <?php
                                    if( options::logic( 'general' , 'enb_likes' ) ){
                                        $meta = meta::get_meta( $similar -> ID  , 'settings' );
                                        if( isset( $meta['love'] ) ){
                                            if( meta::logic( $similar , 'settings' , 'love' ) ){
                                ?>
                                                <li class="cosmo-love">
                                                    <a <?php echo $cnt_a1 ?>><?php echo $nr_like; ?></a>
                                                </li>
                                <?php
                                            }
                                        }else{
                                ?>
                                            <li class="cosmo-love">
                                                <a <?php echo $cnt_a1 ?>><?php echo $nr_like; ?></a>
                                            </li>
                                <?php
                                        }
                                    }
                                ?>
							</ul>
                        </div>
                    </article>
                    <?php
                        if( $i % $div == 0 ){
                            echo '</div>';
                            $i = 0;
                        }
                        $i++;
                        $k++;
                    ?>
            <?php
                }

            /* if div container is open */
            if( $i > 1 ){
                echo '</div>';
            }

            ?>
        </div> <!--  end container related posts -->
    </div>
<?php

        wp_reset_postdata();
    }
?>
    