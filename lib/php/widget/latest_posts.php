<?php
class widget_latest_posts extends WP_Widget {

	function widget_latest_posts() {
	/*Constructor*/
		$widget_ops = array('classname' => 'widget_latest_posts ', 'description' => __( 'Latest Posts' , 'cosmotheme' ) );
		$this->WP_Widget('widget_cosmo_latestPosts', _TN_ . ' : ' . __( 'Latest Posts' , 'cosmotheme' ), $widget_ops);
	}
	
	function widget($args, $instance) {
        /* prints the widget*/
		extract($args, EXTR_SKIP);
        
		echo $before_widget;

		$title = empty($instance['title']) ? __('Latest Posts','cosmotheme') : apply_filters('widget_title', $instance['title']);
		$number = empty($instance['number']) ? 3 : apply_filters('widget_number', $instance['number']);

        if( strlen( $title) > 0 ){
            echo $before_title . $title . $after_title;
        }
?>
		
        <?php

            $recent = get_posts(array('orderby' => 'created', 'numberposts' =>$number ));  /*NOTE use settings*/
            if( is_array( $recent ) && !empty( $recent ) ){
                ?><ul><?php
                foreach( $recent as $post )  {
					if( get_post_thumbnail_id( $post -> ID ) ){
								if( is_user_logged_in () ){
									$post_img = wp_get_attachment_image( get_post_thumbnail_id( $post -> ID ) , '60x60' , '' );
									$cnt_a1 = ' href="' . get_permalink($post -> ID) . '"';
									$cnt_a2 = ' href="' . get_permalink($post -> ID) . '#comments"';
									$cnt_a3 = ' class="entry-img" href="' . get_permalink($post -> ID) . '"';
								}else{
									$meta = meta::get_meta( $post -> ID , 'settings' );
									if( isset( $meta['safe'] ) ){
										if( !meta::logic( $post , 'settings' , 'safe' ) ){
											$post_img = wp_get_attachment_image( get_post_thumbnail_id( $post -> ID ) , '60x60' , '' );
											$cnt_a1 = ' href="' . get_permalink($post -> ID) . '"';
											$cnt_a2 = ' href="' . get_permalink($post -> ID) . '#comments"';
											$cnt_a3 = ' class="entry-img" href="' . get_permalink($post -> ID) . '"';
										}else{
											$post_img = '<img src="' . get_template_directory_uri() . '/images/nsfw.60x60.png" />';
											$cnt_a1 = ' class="simplemodal-nsfw" href="' . get_permalink($post -> ID) . '"';
											$cnt_a2 = ' class="simplemodal-nsfw" href="' . get_permalink($post -> ID) . '#comments"';
											$cnt_a3 = ' class="simplemodal-nsfw entry-img" href="' . get_permalink($post -> ID) . '"';
										}
									}else{
										$post_img = wp_get_attachment_image( get_post_thumbnail_id( $post -> ID ) , '60x60' , '' );
										$cnt_a1 = ' href="' . get_permalink($post -> ID) . '"';
										$cnt_a2 = ' href="' . get_permalink($post -> ID) . '#comments"';
										$cnt_a3 = ' class="entry-img" href="' . get_permalink($post -> ID) . '"';
									}
								}
							}else{
								$post_img = '<img src="' . get_template_directory_uri() . '/images/no.image.60x60.png" />';
								$cnt_a1 = ' href="' . get_permalink($post -> ID) . '"';
								$cnt_a2 = ' href="' . get_permalink($post -> ID) . '#comments"';
								$cnt_a3 = ' class="entry-img" href="' . get_permalink($post -> ID) . '"';
							}

					$likes = meta::get_meta( $post -> ID , 'like' );
					$nr_like = count( $likes );
					?>
                    <li>
                        <a <?php echo $cnt_a3; ?>><?php echo $post_img; ?></a>
						<article class="entry-item">
							<h5>
								<a <?php echo $cnt_a1; ?>>
								<?php
									echo mb_substr( $post -> post_title , 0 , BLOCK_TITLE_LEN );
									if( strlen( $post->post_title ) > BLOCK_TITLE_LEN ) {
										echo '...';
									}
								?>
								</a>
							</h5>
							<div class="entry-meta">
								<ul>
									<li class="cosmo-comments">
                                        <?php
                                            if ( $post -> comment_status == 'open' ) {
                                        ?>
                                                <a <?php echo $cnt_a2; ?>>
                                                    <?php
                                                        if( options::logic( 'general' , 'fb_comments' ) ){
                                                            ?> <fb:comments-count href=<?php echo get_permalink( $post -> ID  ) ?>></fb:comments-count> <?php
                                                        }else{
                                                            echo $post -> comment_count . ' ';
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
                                            $meta = meta::get_meta( $post -> ID  , 'settings' );
                                            if( isset( $meta['love'] ) ){
                                                if( meta::logic( $post , 'settings' , 'love' ) ){
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
                    </li>
        <?php

                }
                ?></ul><?php
            }
            
            wp_reset_query();

            echo $after_widget;
	}
	
	function update($new_instance, $old_instance) {

	/*save the widget*/
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = strip_tags($new_instance['number']);
		
		return $instance;
	}
	
	function form($instance) {
	/*widgetform in backend*/

		$instance = wp_parse_args( (array) $instance, array('title' => '',  'number' => '') );
		$title = strip_tags($instance['title']);
		$number = strip_tags($instance['number']);
?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title','cosmotheme') ?>: 
			    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
			</label>
		</p>
		<p>
		    <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts','cosmotheme') ?>:
		        <input class="widefat" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo esc_attr($number); ?>" />
		    </label>
		</p>
<?php 		
		
		$title = strip_tags( $instance['title'] );
		$number = strip_tags( $instance['number'] );
	}	
}
?>