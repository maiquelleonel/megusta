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
                    $left = layout::get_side( 'left' , $post_id , 'single');
                    if( $left ){
                        if( layout::get_length( $post_id , 'single' ) == 940 ){
                            $classes = 'fullwidth';
                        }else{
                            $classes = 'fr';
                        }
                    }else{
                        if( layout::get_length( $post_id , 'single' ) == 940 ){
                            $classes = 'fullwidth';
                        }else{
                            $classes = 'fl';
                        }
                    }
                ?>

                <div id="primary" class="w_<?php echo layout::get_length( $post_id , 'single' , true ); ?> <?php echo $classes; ?>">
                    <div id="content" role="main">
                        <div class="b w_<?php echo layout::get_length( $post_id , 'single' ); ?> category">

                            <!-- post -->
                            <article id="post-<?php the_ID(); ?>" <?php post_class( 'post' , $post -> ID ); ?>>

                                <!-- header -->
                                <header class="entry-header">
                                    <!-- love button -->
                                    <?php
                                        if( options::logic( 'general' , 'enb_likes' ) ){
                                            $meta = meta::get_meta( $post -> ID  , 'settings' );
                                            if( isset( $meta['love'] ) ){
                                                if( meta::logic( $post , 'settings' , 'love' ) ){
                                    ?>
                                                    <div class="love">
                                                        <div <?php 	if( like::can_vote( $post -> ID ) ){  echo "onclick=\"javascript:act.like(".$post -> ID.", '#like-".$post -> ID."' , '');\""; } ?> class="set-like voteaction <?php if( like::is_voted( $post -> ID ) ){ echo 'voted '; } if( !like::can_vote( $post -> ID ) ){ echo "simplemodal-login"; }?>" id="voteaction"><em><strong  id="like-<?php echo $post -> ID; ?>"  ><?php  echo count( meta::get_meta( $post ->ID , 'like') ); ?></strong></em></div>
                                                    </div>
                                    <?php
                                                }
                                            }else{
                                    ?>
                                                <div class="love">
                                                    <div <?php 	if( like::can_vote( $post -> ID ) ){  echo "onclick=\"javascript:act.like(".$post -> ID.", '#like-".$post -> ID."' , '');\""; } ?> class="set-like voteaction <?php if( like::is_voted( $post -> ID ) ){ echo 'voted '; } if( !like::can_vote( $post -> ID ) ){ echo "simplemodal-login"; }?>" id="voteaction"><em><strong  id="like-<?php echo $post -> ID; ?>"  ><?php  echo count( meta::get_meta( $post ->ID , 'like') ); ?></strong></em></div>
                                                </div>
                                    <?php
                                            }
                                        }
                                    ?>

                                    <!-- title -->
                                    <h1 class="entry-title"><?php echo $post -> post_title; ?></h1>

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
                                        if( has_post_thumbnail ( $post -> ID ) && get_post_format( $post -> ID ) != 'video'){
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

                                                        /* safe / not safe */
                                                        if( is_user_logged_in () ){
                                                            if( options::logic( 'general' , 'enb_lightbox' )  ){
                                                    ?>
                                                                <a href="<?php echo $src_[0]; ?>" title="<?php echo $caption;  ?>" class="mosaic-overlay" rel="prettyPhoto-<?php echo $post -> ID; ?>">&nbsp;</a>
                                                    <?php
                                                            }
                                                    ?>
                                                           <a href="<?php echo $src_[0]; ?>"><img src="<?php echo $src[0]; ?>" class="no-safe image" alt="<?php echo $caption; ?>" ></a>
                                                    <?php
                                                            if( strlen( trim( $caption) ) ){
                                                    ?>
                                                                <p class="wp-caption-text"><?php echo $caption; ?></p>
                                                    <?php
                                                            }
                                                        }else{
                                                            $meta = meta::get_meta( $post -> ID  , 'settings' );
                                                            if( isset( $meta['safe'] ) ){
                                                                if( !meta::logic( $post , 'settings' , 'safe' ) ){
                                                                    if( options::logic( 'general' , 'enb_lightbox' )  ){
                                                    ?>
                                                                        <a href="<?php echo $src_[0]; ?>" title="<?php echo $caption;  ?>" class="mosaic-overlay" rel="prettyPhoto-<?php echo $post -> ID; ?>">&nbsp;</a>
                                                    <?php
                                                                    }
                                                    ?>
                                                                   <a href="<?php echo $src_[0]; ?>"><img src="<?php echo $src[0]; ?>" class="no-safe image" alt="<?php echo $caption; ?>" ></a>
                                                    <?php
                                                                    if( strlen( trim( $caption) ) ){
                                                    ?>
                                                                        <p class="wp-caption-text"><?php echo $caption; ?></p>
                                                    <?php
                                                                    }
                                                                }else{
                                                                    ?><a class="simplemodal-nsfw" href="<?php echo wp_login_url( ); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/nsfw.png" class="safe image" alt="<?php echo $caption; ?>" /></a><?php
                                                                }

                                                            }else{
                                                                if( options::logic( 'general' , 'enb_lightbox' )  ){
                                                    ?>
                                                                    <a href="<?php echo $src_[0]; ?>" title="<?php echo $caption;  ?>" class="mosaic-overlay" rel="prettyPhoto-<?php echo $post -> ID; ?>">&nbsp;</a>
                                                    <?php
                                                                }
                                                    ?>
                                                                <a href="<?php echo $src_[0]; ?>"><img src="<?php echo $src[0]; ?>" class="no-safe image" alt="<?php echo $caption; ?>" ></a>
                                                    <?php
                                                                if( strlen( trim( $caption) ) ){
                                                    ?>
                                                                    <p class="wp-caption-text"><?php echo $caption; ?></p>
                                                    <?php
                                                                }
                                                            }
                                                        }

                                                    ?>
                                                </div>
                                            </div>
                                <?php
                                        }
                                    }
                                ?>
                                    <div class="b_text">
                                        <?php
                                            if( is_user_logged_in () ){
                                                if( get_post_format( $post -> ID ) == 'video' ){
                                                    $video_format = meta::get_meta( $post -> ID , 'format' );
													$format=$video_format;
													if( isset( $format['video'] ) && !empty( $format['video'] ) && post::isValidURL( $format['video'] ) ){
                                                    	  $vimeo_id = post::get_vimeo_video_id( $format['video'] );
                                                        $youtube_id = post::get_youtube_video_id( $format['video'] );
                                                        $video_type = '';
                                                        if( $vimeo_id != '0' ){
                                                            $video_type = 'vimeo';
                                                            $video_id = $vimeo_id;
                                                        }

                                                        if( $youtube_id != '0' ){
                                                            $video_type = 'youtube';
                                                            $video_id = $youtube_id;
                                                        }

                                                        if( !empty( $video_type ) ){
                                                            echo post::get_embeded_video( $video_id , $video_type );
                                                        }elseif(isset( $format['video'] ) && !empty( $format['video'] )  ){ 
															echo post::get_local_video( $format['video']  );
														}
													  }
                                                    else if(strlen($video_format["feat_url"])>1)
													  {
														$video_url=$video_format["feat_url"];
														if(post::get_youtube_video_id($video_url)!="0")
														  {
															echo post::get_embeded_video(post::get_youtube_video_id($video_url),"youtube");
														  }
														else if(post::get_vimeo_video_id($video_url)!="0")
														  {
															echo post::get_embeded_video(urlencode(post::get_vimeo_video_id($video_url)),"vimeo");
														  }
													  }
													else if(strlen($video_format["feat_id"])>1)
													  {
														echo post::get_local_video( wp_get_attachment_url($video_format["feat_id"]));
													  }
													if(isset($video_format['video_ids']) && !empty($video_format['video_ids']))
													  {
														foreach($video_format["video_ids"] as $videoid)
														  {
															if( isset( $video_format[ 'video_urls' ][ $videoid ] ) ){
																$video_url = $video_format[ 'video_urls' ][ $videoid ];
																if(post::get_youtube_video_id($video_url)!="0")
																  {
																	echo post::get_embeded_video(post::get_youtube_video_id($video_url),"youtube");
																  }
																else if(post::get_vimeo_video_id($video_url)!="0")
																  {
																	echo post::get_embeded_video(post::get_vimeo_video_id($video_url),"vimeo");
																  }
															  }
															else
															  {
																echo post::get_local_video( urlencode(wp_get_attachment_url($videoid)));
															  }
														  }
													  }
												  }
												else if(get_post_format($post->ID)=="image")
												  {
													$image_format = meta::get_meta( $post -> ID , 'format' );
													echo "<div class=\"attached_imgs_gallery\">";
													if(isset($image_format['images']) && is_array($image_format['images']))
													  {
														foreach($image_format['images'] as $index=>$img_id)
														  {
															$thumbnail= wp_get_attachment_image_src( $img_id, 'thumbnail');
															$full_image=wp_get_attachment_url($img_id);
															$url=$thumbnail[0];
															$width=$thumbnail[1];
															$height=$thumbnail[2];
															echo "<div class=\"attached_imgs_gallery-element\">";
															echo "<a title=\"\" rel=\"prettyPhoto-".get_the_ID()."\" href=\"".$full_image."\">";

															if($height<150)
															  {
																$vertical_align_style="style=\"margin-top:".((150-$height)/2)."px;\"";
															  }
															else
															  {
																$vertical_align_style="";
															  }
		
															echo "<img alt=\"\" src=\"$url\" width=\"$width\" height=\"$height\" $vertical_align_style>";
															echo "</a>";
															echo "</div>";
														  }	
														echo "</div>";
													  }
												  }
												  the_content();
												 
                                            }else{
                                                if( !meta::logic( $post , 'settings' , 'safe' ) ){
                                                        if( get_post_format( $post -> ID ) == 'video' ){
                                                    $video_format = meta::get_meta( $post -> ID , 'format' );
													 $format=$video_format;
														  if( isset( $format['video'] ) && !empty( $format['video'] ) && post::isValidURL( $format['video'] ) ){
                                                            $vimeo_id = post::get_vimeo_video_id( $format['video'] );
                                                            $youtube_id = post::get_youtube_video_id( $format['video'] );
                                                            $video_type = '';
                                                            if( $vimeo_id != '0' ){
                                                                $video_type = 'vimeo';
                                                                $video_id = $vimeo_id;
                                                            }

                                                            if( $youtube_id != '0' ){
                                                                $video_type = 'youtube';
                                                                $video_id = $youtube_id;
                                                            }

                                                            if( !empty( $video_type ) ){
                                                                echo post::get_embeded_video( $video_id , $video_type );
                                                            }
															else {
														  ?>
													  <header class="entry-header">
														<div class="featimg">
															<?php if( strlen( $classes ) ){ ?>
															<div class="img">
															<?php } ?> 	
															<?php
																if( strlen( $classes ) ){
																	echo image::mis($post->ID, $template, $size, 'safe image', 'nsfw');
																}else{
																	echo post::get_local_video( urlencode($format['video']  ));
																}
															?>
															<?php if( strlen( $classes ) ){ ?>  
														  </div>
														<?php } ?> 		
														</div>
													  </header>
												<?php }
													}
                                                    else if(strlen($video_format["feat_url"])>1)
													  {
														$video_url=$video_format["feat_url"];
														if(post::get_youtube_video_id($video_url)!="0")
														  {
															echo post::get_embeded_video(post::get_youtube_video_id($video_url),"youtube");
														  }
														else if(post::get_vimeo_video_id($video_url)!="0")
														  {
															echo post::get_embeded_video(urlencode(post::get_vimeo_video_id($video_url)),"vimeo");
														  }
													  }
													else if(strlen($video_format["feat_id"])>1)
													  {
														echo post::get_local_video( wp_get_attachment_url($video_format["feat_id"]));
													  }
													if(isset($video_format['video_ids']) && !empty($video_format['video_ids']))
													  {
														foreach($video_format["video_ids"] as $videoid)
														  {
															if( isset( $video_format[ 'video_urls' ][ $videoid ] ) ){
																$video_url = $video_format[ 'video_urls' ][ $videoid ];
																if(post::get_youtube_video_id($video_url)!="0")
																  {
																	echo post::get_embeded_video(post::get_youtube_video_id($video_url),"youtube");
																  }
																else if(post::get_vimeo_video_id($video_url)!="0")
																  {
																	echo post::get_embeded_video(post::get_vimeo_video_id($video_url),"vimeo");
																  }
															  }
															else
															  {
																echo post::get_local_video( urlencode(wp_get_attachment_url($videoid)));
															  }
														  }
													  }
												  }
												else if(get_post_format($post->ID)=="image")
												  {
													$image_format = meta::get_meta( $post -> ID , 'format' );
													echo "<div class=\"attached_imgs_gallery\">";
													if(isset($image_format['images']) && is_array($image_format['images']))
													  {
														foreach($image_format['images'] as $index=>$img_id)
														  {
															$thumbnail= wp_get_attachment_image_src( $img_id, 'thumbnail');
															$full_image=wp_get_attachment_url($img_id);
															$url=$thumbnail[0];
															$width=$thumbnail[1];
															$height=$thumbnail[2];
															echo "<div class=\"attached_imgs_gallery-element\">";
															echo "<a title=\"\" rel=\"prettyPhoto[".get_the_ID()."]\" href=\"".$full_image."\">";

															if($height<150)
															  {
																$vertical_align_style="style=\"margin-top:".((150-$height)/2)."px;\"";
															  }
															else
															  {
																$vertical_align_style="";
															  }
		
															echo "<img alt=\"\" src=\"$url\" width=\"$width\" height=\"$height\" $vertical_align_style>";
															echo "</a>";
															echo "</div>";
														  }	
														echo "</div>";
													  }
												  }
                                                    the_content();
													
                                                }elseif(!has_post_thumbnail ( $post -> ID ) || get_post_format( $post -> ID ) == 'video'){
										?>  
													<a class="simplemodal-nsfw" href="<?php echo wp_login_url( ); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/nsfw.png" class="safe image" alt="<?php echo $caption; ?>" /></a>
										<?php
												}
                                            }
                                        ?>
                                    </div>
                                </div>

                                <footer class="entry-footer">
									<?php
										if( get_post_format( $post -> ID ) == 'link' ){
											echo post::get_attached_file($post -> ID);
										}  
									?>
									<?php
										if( get_post_format( $post -> ID ) == 'audio' ){
											$audio = new AudioPlayer();	
											echo $audio->processContent(post::get_audio_file($post -> ID));
										}  
									?>  
                                    <?php get_template_part( 'social-sharing' ); ?>
									<?php 
									if(options::logic( 'blog_post' , 'show_source' ) && meta::logic( $post , 'settings' , 'meta' )){
										echo post::get_source($post -> ID);
									}  
									?>  
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
                                            <h3 id="reply-title"><?php _e( 'Leave a reply' , 'cosmotheme' ); ?></h3>
                                            <p class="delimiter">&nbsp;</p>
                                            <fb:comments href="<?php the_permalink(); ?>" num_posts="5" width="620" height="120" reverse="true"></fb:comments>
                                        </div>
                                        <?php
                                    }else{
                                        comments_template( '', true );
                                    }
                                }

                                /* related posts */
                                get_template_part( 'related-posts' );
                            ?>
                        </div>
                    </div>
                </div>
                <!-- right sidebar -->
                <?php layout::get_side( 'right' , $post_id , 'single' ); ?>
            </div>
    <?php
        }
    ?>
</div>
<?php get_footer(); ?>
