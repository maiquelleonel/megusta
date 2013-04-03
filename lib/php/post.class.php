<?php
    class post{
        static $post_id = 0;
        function get_my_posts( $author, $status = 'any'){
            $wp_query = new WP_Query( array('post_status' => $status, 'post_type' => 'post' , 'author' => $author ) );
            if( count( $wp_query -> posts ) > 0 ){
                return true;
            }else{
                return false;
            }
        }
        
        function author( $author = '' ){
            global $wp_query;
            
            echo '<div class="author-container">';
            if( isset( $_GET['type'] ) ){
                switch( $_GET['type'] ){
                    case 'all' : {
                        $type = 'all';
                        break;
                    }
                    case 'like' : {
                        $type = 'like';
                        break;
                    }
                    case 'post' : {
                        $type = 'post';
                        break;
                    }
                    default : {
                        $type = 'post';
                        break;
                    }
                }
            }else{
                $type = 'post';
            }
            /* content */
            echo '<script type="text/javascript">';
            echo 'jQuery(document).ready(function(){ act.author( \'' . $type . '\' , ' . $author . ' , 0 , [] , 1 ); });';
            echo '</script>';
            
            echo '</div>';
        }
        
        function author_type( ){
            $post_id = isset( $_POST['post_id'] ) ? $_POST['post_id'] : exit;
            $uid   = isset( $_POST['author'] ) ? $_POST['author'] : exit;
            $ptype   = isset( $_POST['ptype'] ) ? $_POST['ptype'] : 'post';
            $result  = isset( $_POST['data'] ) ? $_POST['data'] : array();
			if(!is_array($result)){$result = array(); }
            $type  = isset( $_POST['type'] ) ? $_POST['type'] : 0;
            global $wp_query;
           
            self::$post_id = $post_id;
            add_filter( 'posts_where', array( 'post' , 'filter_where' ) );
            if( $ptype == 'post' ){
                $wp_query = new WP_Query( array( 'post_type' => 'post' , 'post_status' => 'publish' , 'post_status' => 'publish' , 'posts_per_page' => 250 , 'author' => $uid , 'orderby' => 'ID' , 'type' => '' ) );
            }else{
                $wp_query = new WP_Query( array( 'post_type' => 'post' , 'post_status' => 'publish' , 'post_status' => 'publish' , 'posts_per_page' => 250 , 'orderby' => 'ID' , 'type' => '' ) );
            }
            $break = false;
            foreach( $wp_query -> posts as $p ){
                $post_id = $p -> ID;
                if( isset( $ptype ) ){
                    switch( $ptype ){
                        case 'all' : {
                            if( $p -> post_author == $uid ){
                                if( !in_array( $p -> ID , $result ) ){
                                    array_push( $result , $p -> ID );
                                }
                            }else{
                                $likes = meta::get_meta( $p -> ID , 'like' );
                                foreach( $likes as $like ){
                                    if( $like['user_id'] == $uid ){
                                        if( !in_array( $p -> ID , $result ) ){
                                            array_push( $result , $p -> ID );
                                        }
                                        break;
                                    }
                                }
                            }
                            break;
                        }
                        case 'like' : {
                            $likes = meta::get_meta( $p -> ID , 'like' );
                            foreach( $likes as $like ){
                                if( $like['user_id'] == $uid ){
                                    if( !in_array( $p -> ID , $result ) ){
                                        array_push( $result , $p -> ID );
                                    }
                                    break;
                                }
                            }
                            break;
                        }
                        case 'post' : {
                            if( !in_array( $p -> ID , $result ) ){
                                array_push( $result , $p -> ID );
                            }
                            break;
                        }
                        default : {
                            if( !in_array( $p -> ID , $result ) ){
                                array_push( $result , $p -> ID );
                            }
                            break;
                        }
                    }
                }else{
                    if( $p -> post_author == $uid ){
                        if( !in_array( $p -> ID , $result ) ){
                            array_push( $result , $p -> ID );
                        }
                    }
                }
                    
                if( count( $result ) == 12 ){
                    $break = true;
                    break;
                }
            }

            if( count( $result ) < 12 && ( $wp_query -> max_num_pages > 1 || $break ) ){
                echo json_encode( array( 'post_id' => $post_id , 'data' => $result ) );
            }else{
                /* content */
                if( !empty( $result ) ){
                    global $wp_query;
                    remove_filter( 'posts_where', array( 'post' , 'filter_where' ) );
                    $wp_query = new WP_Query( array( 'post__in' => $result , 'fp_type' => 'like' , 'post_type' => 'post' , 'post_status' => 'publish' , 'posts_per_page' => 12 ) );
                    if( $type == 1 ){
                        self::loop( 'author' );
                    }else{
                        if( self::is_grid( 'author' ) ){
                            self::loop_switch( 'author' , 1 );
                        }else{
                            echo '<p class="delimiter">&nbsp;</p>';
                            self::loop_switch( 'author' , 0 );
                        }
                    }           
                    if( $wp_query -> max_num_pages > 1 || $break ){
                        echo '<div class="clearfix get-more"><p class="button"><a id="get-more" index="' . $post_id . '" href="javascript:act.author( \'' . $ptype . '\' , '.$uid.' , jQuery(\'#get-more\').attr(\'index\') , [] , 0 );">'. __( 'get more' , 'cosmotheme' ) .'</a></p></div>';
                    }
                }else{
?>
                    <article <?php post_class() ?>>
                        <!-- content -->
                        <footer class="entry-footer">
                            <div class="excerpt"><?php _e( 'Unfortunately we did not find any posts.' , 'cosmotheme' ); wp_link_pages(); ?>
                            </div>
                        </footer>
                    </article>
<?php
                }
            }
        
            exit();
        }
        
        function filter_where( $where = '' ) {
            global $wpdb;
            if( self::$post_id > 0 ){
                $where .= " AND  ".$wpdb->prefix."posts.ID < " . self::$post_id;
            }
            return $where;
        }
    
        function random_posts( $no_ajax = false ){
            global $wp_query;
            if( (int) get_query_var( 'paged' ) > 0 ){
                $paged = get_query_var('paged');
            }else{
                if( (int) get_query_var( 'page' ) > 0 ){
                    $paged = get_query_var('page');
                }else{
                    $paged = 1;
                }
            }

            $wp_query = new WP_Query( array( 'post_status' => 'publish' ,'post_type' => 'post' , 'posts_per_page' => 1 , 'orderby' => 'rand' ,  'paged' => $paged  ) );

            if( $wp_query -> found_posts > 0 ){
                $k = 0;
                foreach( $wp_query -> posts as $post  ){
                    $wp_query -> the_post();
                    $result = get_permalink( $post -> ID );
                }
            }

            if( isset( $no_ajax ) && $no_ajax ){
                return $result;
            }else{
                echo $result;
                exit;
            }
        }

        function new_posts(){
            global $wp_query;
            if( (int) get_query_var( 'paged' ) > 0 ){
                $paged = get_query_var('paged');
            }else{
                if( (int) get_query_var( 'page' ) > 0 ){
                    $paged = get_query_var('page');
                }else{
                    $paged = 1;
                }
            }

            $wp_query = new WP_Query( array( 'post_status' => 'publish' ,'post_type' => 'post' ,  'paged' => $paged , 'fp_type' => 'news', ) );

            self::loop( 'front_page' );
        }

        function hot_posts(){
            global $wp_query;
            if( (int) get_query_var( 'paged' ) > 0 ){
                $paged = get_query_var('paged');
            }else{
                if( (int) get_query_var( 'page' ) > 0 ){
                    $paged = get_query_var('page');
                }else{
                    $paged = 1;
                }
            }

            $wp_query = new WP_Query( array(
                'post_status' => 'publish' ,
                'paged' => $paged ,
                'meta_key' => 'hot_date' ,
                'orderby' => 'meta_value_num' ,
                'fp_type' => 'hot',
                'meta_query' => array(
                        array(
                            'key' => 'nr_like' ,
                            'value' => options::get_value( 'general' , 'min_likes' ) ,
                            'compare' => '>=' ,
                            'type' => 'numeric',
                        ) ),
                'order' => 'DESC' ));
            
            self::loop( 'front_page' );
        }

        function get( $post , $template = 'blog_page' ){

            $meta = meta::get_meta( $post -> ID  , 'settings' );
            if( isset( $meta['safe'] ) ){
                if( meta::logic( $post , 'settings' , 'safe' ) ){
                    $classes = ' nsfw';
                }else{
                    $classes = ' ';
                }
            }else{
                $classes = ' ';
            }
        ?>
            <!-- post -->
            <article id="post-<?php echo $post -> ID; ?>" <?php post_class( 'post ' . $classes , $post -> ID ); ?>>

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
										<div id="top_love_<?php echo $post -> ID?>" class="top_love"></div>
                                        <div <?php 	if( like::can_vote( $post -> ID ) ){  echo "onclick=\"javascript:act.like(".$post -> ID.", '#like-".$post -> ID."' , '');\""; } ?> class="set-like voteaction <?php if( !like::can_vote( $post -> ID ) ){ echo "simplemodal-love"; }?> <?php if( like::is_voted( $post -> ID ) ){ echo 'voted'; } ?>"><em><strong  id="like-<?php echo $post -> ID; ?>"  ><?php  echo count( meta::get_meta( $post ->ID , 'like') ); ?></strong></em></div>
                                    </div>
                    <?php
                                }
                            }else{
                    ?>
                                <div class="love">
									<div id="top_love_<?php echo $post -> ID?>" class="top_love"></div>
                                    <div <?php 	if( like::can_vote( $post -> ID ) ){  echo "onclick=\"javascript:act.like(".$post -> ID.", '#like-".$post -> ID."' , '');\""; } ?> class="set-like voteaction <?php if( !like::can_vote( $post -> ID ) ){ echo "simplemodal-love"; }?> <?php if( like::is_voted( $post -> ID ) ){ echo 'voted'; } ?>"><em><strong  id="like-<?php echo $post -> ID; ?>"  ><?php  echo count( meta::get_meta( $post ->ID , 'like') ); ?></strong></em></div>
                                </div>
                    <?php
                            }
                        }
                    ?>

                    <!-- title -->
                    <?php
                        if( $template == 'front_page' ){
                            $tag = 'h1';
                        }else{
                            $tag = 'h2';
                        }
                    ?>
                    <<?php echo $tag; ?> class="entry-title">
                        <?php
                            /* if not NSFW go to login */
                            if( is_user_logged_in () ){
                                ?><a href="<?php echo get_permalink( $post -> ID ); ?>" title="<?php _e( 'Permalink to' , 'cosmotheme' ); ?> <?php echo $post -> post_title; ?>" rel="bookmark"><?php echo $post -> post_title; ?></a><?php
                            }else{
                                $meta = meta::get_meta( $post -> ID  , 'settings' );
                                if( isset( $meta['safe'] ) ){
                                    if( !meta::logic( $post , 'settings' , 'safe' ) ){
                                        ?><a href="<?php echo get_permalink( $post -> ID ); ?>" title="<?php _e( 'Permalink to' , 'cosmotheme' ); ?> <?php echo $post -> post_title; ?>" rel="bookmark"><?php echo $post -> post_title; ?></a><?php
                                    }else{
                                        ?><a class="simplemodal-nsfw" href="<?php  echo wp_login_url( ); ?>" title="<?php _e( 'Permalink to' , 'cosmotheme' ); ?> <?php echo $post -> post_title; ?>" rel="bookmark"><?php echo $post -> post_title; ?></a><?php
                                    }
                                }else{
                                    ?><a href="<?php echo get_permalink( $post -> ID ); ?>" title="<?php _e( 'Permalink to' , 'cosmotheme' ); ?> <?php echo $post -> post_title; ?>" rel="bookmark"><?php echo $post -> post_title; ?></a><?php
                                }
                            }
                        ?>
                    </<?php echo $tag; ?>>

                    <!-- meta -->

                    <?php 
                        if( options::logic( 'general' , 'meta' ) ){
                            self::meta( $post );
                        }
                    ?>

                </header>

                <!-- featured images -->
                <?php
                    if( has_post_thumbnail ( $post -> ID ) ){
                        if( layout::get_length( 0 , $template ) == 940 ){
                            $size = '920xXXX';
                        }else{
                            $size = '600xXXX';
                        }

                        $src  = wp_get_attachment_image_src( get_post_thumbnail_id( $post -> ID ) , $size );

						if( get_post_format( $post -> ID ) == 'video' ){
							$format = meta::get_meta( $post -> ID , 'format' );
						if( isset( $format['feat_id'] ) && !empty( $format['feat_id'] ) )
						  {
							$video_id = $format['feat_id'];
							$video_type = 'self_hosted';
							if(isset($format['feat_url']) && post::isValidURL($format['feat_url']))
							  {
								$vimeo_id = post::get_vimeo_video_id( $format['feat_url'] );
								$youtube_id = post::get_youtube_video_id( $format['feat_url'] );
							
								if( $vimeo_id != '0' ){
								  $video_type = 'vimeo';
								  $video_id = $vimeo_id;
								}

								if( $youtube_id != '0' ){
								  $video_type = 'youtube';
								  $video_id = $youtube_id;
								}
							  }

							if(isset($video_type) && isset($video_id) && is_user_logged_in () ){
								if($video_type == 'self_hosted'){
									$onclick = 'playVideo("'.urlencode(wp_get_attachment_url($video_id)).'","'.$video_type.'",jQuery(this))';
								}else{
									$onclick = 'playVideo("'.$video_id.'","'.$video_type.'",jQuery(this))';
								}    
							
							}else{
								$meta = meta::get_meta( $post -> ID  , 'settings' );
								if( isset( $meta['safe'] ) ){
									if( !meta::logic( $post , 'settings' , 'safe' ) ){		
										$onclick = 'playVideo("'.$video_id.'","'.$video_type.'",jQuery(this))';
									}
								}else{
									if($video_type == 'self_hosted'){
										$onclick = 'playVideo("'.urlencode(wp_get_attachment_url($video_id)).'","'.$video_type.'",jQuery(this))';
									}else{
										$onclick = 'playVideo("'.$video_id.'","'.$video_type.'",jQuery(this))';
									}    
								}	
							}
						  }
						}
                ?>
                        <!-- content -->
                        <div class="entry-content">
                            <div class="featimg readmore" <?php if(isset($onclick)){ echo "onclick=".$onclick; }?> >
                                <div class="img">
                                    <?php
                                        ob_start();
                                        ob_clean();
                                        get_template_part( 'caption' );
                                        $caption = ob_get_clean();

                                        /* safe / not safe */
                                        if( is_user_logged_in () ){
                                    ?>
                                            <a href="<?php if(!isset($onclick)){ echo get_permalink( $post -> ID ); }else{ echo 'javascript:void(0)'; } ?>" title="<?php echo $caption;  ?>" class="mosaic-overlay" > <?php if(!isset($onclick)){ ?><div class="details"><?php _e( 'Read more' , 'cosmotheme' ); ?></div> <?php }?></a><?php if(isset($onclick)){ ?><div class=" play">&nbsp;</div><?php } ?> <div class="format">&nbsp;</div>
                                            <img src="<?php echo $src[0]; ?>" class="no-safe image" alt="<?php echo $caption; ?>" >
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
                                    ?>
                                                    <a href="<?php if(!isset($onclick)){ echo get_permalink( $post -> ID ); }else{ echo 'javascript:void(0)'; }?>" title="<?php echo $caption;  ?>" class="mosaic-overlay"><?php if(!isset($onclick)){ ?><div class="details"><?php _e( 'Read more' , 'cosmotheme' ); ?></div><?php } ?></a><?php if(isset($onclick)){ ?><div class="play">&nbsp;</div><?php } ?> <div class="format">&nbsp;</div>
                                                    <img src="<?php echo $src[0]; ?>" class="no-safe image" alt="<?php echo $caption; ?>" >
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
                                    ?>
                                                <a href="<?php if(!isset($onclick)){ echo get_permalink( $post -> ID ); }else{ echo 'javascript:void(0)'; }?>"" title="<?php echo $caption;  ?>" class="mosaic-overlay"> <?php if(!isset($onclick)){ ?><div class="details"><?php _e( 'Read more' , 'cosmotheme' ); ?></div> <?php } ?></a> <?php if(isset($onclick)){ ?><div class="play">&nbsp;</div><?php } ?> <div class="format">&nbsp;</div>
                                                <img src="<?php echo $src[0]; ?>" class="no-safe image" alt="<?php echo $caption; ?>" >
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
                        </div>
                <?php
                    }
                ?>
                
                <footer class="entry-footer">
					<?php /*$attached_file_meta = meta::get_meta( $post -> ID , 'format' ); 
						if( get_post_format( $post -> ID ) == 'link' ){
							echo post::get_attached_file($post -> ID);
						}  */ 
					  
					?>	
                    <?php
                        if( strlen( $post -> post_excerpt . $post -> post_content ) > 0 ){
                    ?>
                            <div class="excerpt">
                                <?php
                                    if( is_user_logged_in () ){
                                        the_excerpt();
                                    }else{
                                        $meta = meta::get_meta( $post -> ID  , 'settings' );
                                        if( isset( $meta['safe'] ) ){
                                            if( !meta::logic( $post , 'settings' , 'safe' ) ){
                                                the_excerpt();
                                            }
                                        }else{
                                            the_excerpt();
                                        }
                                    }
                                ?>
                            </div>
                    <?php
                        }
                    ?>
                    <?php get_template_part( 'social-sharing' ); ?>
                </footer>
            </article>
        <?php
        }

        function grid( $post , $template ){

            $meta = meta::get_meta( $post -> ID  , 'settings' );
            if( isset( $meta['safe'] ) ){
                if( meta::logic( $post , 'settings' , 'safe' ) ){
                    $classes = 'nsfw';
                }else{
                    $classes = ' ';
                }
            }else{
                $classes = ' ';
            }
        ?>
            <article id="post-<?php echo $post -> ID; ?>" <?php post_class( 'col ' . $classes , $post -> ID ); ?>>
                <?php
                    if( layout::get_length( 0 , $template ) == 940 ){
                        $size   = '290x150';
                    }else{
                        $size   = '285x150';
                    }

                    if( has_post_thumbnail ( $post -> ID ) ){
                        $src  = wp_get_attachment_image_src( get_post_thumbnail_id( $post -> ID ) , $size );
                ?>
                        <div class="readmore">
                            <?php
                                ob_start();
                                ob_clean();
                                get_template_part( 'caption' );
                                $caption = ob_get_clean();

                                /* safe / not safe */
                                if( is_user_logged_in () ){
                            ?>
                                    <a href="<?php echo get_permalink( $post -> ID ); ?>" title="<?php echo $caption;  ?>" class="mosaic-overlay" ><div class="details">&nbsp;</div></a> <div class="format">&nbsp;</div>
                                    <img src="<?php echo $src[0]; ?>" alt="<?php echo $caption; ?>" />
                            <?php
                                }else{
                                    $meta = meta::get_meta( $post -> ID  , 'settings' );
                                    if( isset( $meta['safe'] ) ){
                                        if( !meta::logic( $post , 'settings' , 'safe' ) ){
                            ?>
                                            <a href="<?php echo get_permalink( $post -> ID )?>" title="<?php echo $caption;  ?>" class="mosaic-overlay"><div class="details">&nbsp;</div></a> <div class="format">&nbsp;</div>
                                            <img src="<?php echo $src[0]; ?>" alt="<?php echo $caption; ?>" />
                            <?php
                                        }else{
                                            ?><a href="<?php echo wp_login_url( ); ?>" class="simplemodal-nsfw"><img src="<?php echo get_template_directory_uri(); ?>/images/nsfw.<?php echo $size; ?>.png" class="safe image" alt="<?php echo $caption; ?>" /></a><?php
                                        }
                                    }else{
                            ?>
                                        <a href="<?php echo get_permalink( $post -> ID )?>" title="<?php echo $caption;  ?>" class="mosaic-overlay"><div class="details">&nbsp;</div></a> <div class="format">&nbsp;</div>
                                        <img src="<?php echo $src[0]; ?>" alt="<?php echo $caption; ?>" />
                            <?php
                                    }
                                }

                            ?>
                        </div>
                <?php
                    }else{
                        ?>
                        <div class="readmore">
                            <a href="<?php echo get_permalink( $post -> ID ); ?>" class="mosaic-overlay" ><div class="details">&nbsp;</div></a> <div class="format">&nbsp;</div>
                            <img src="<?php echo get_template_directory_uri(); ?>/images/no.image.<?php echo $size; ?>.png" />
                        </div>
                        <?php
                    }

                    /* post likes */
                    $likes = meta::get_meta( $post -> ID , 'like' );
                    $nr_like = count( $likes );

                ?>
                <h2 class="entry-title">
                    <?php
                        /* if not NSFW go to login */
                        if( is_user_logged_in () ){
                            ?><a class="readmore" href="<?php echo get_permalink( $post -> ID ); ?>" title="<?php _e( 'Permalink to' , 'cosmotheme' ); ?> <?php echo $post -> post_title; ?>" rel="bookmark"><?php echo $post -> post_title; ?></a><?php
                        }else{
                            $meta = meta::get_meta( $post -> ID  , 'settings' );
                            if( isset( $meta['safe'] ) ){
                                if( !meta::logic( $post , 'settings' , 'safe' ) ){
                                    ?><a class="readmore" href="<?php echo get_permalink( $post -> ID ); ?>" title="<?php _e( 'Permalink to' , 'cosmotheme' ); ?> <?php echo $post -> post_title; ?>" rel="bookmark"><?php echo $post -> post_title; ?></a><?php
                                }else{
                                    ?><a class="readmore simplemodal-nsfw" href="<?php  echo wp_login_url( ); ?>" title="<?php _e( 'Permalink to' , 'cosmotheme' ); ?> <?php echo $post -> post_title; ?>" rel="bookmark"><?php echo $post -> post_title; ?></a><?php
                                }
                            }else{
                                ?><a class="readmore" href="<?php echo get_permalink( $post -> ID ); ?>" title="<?php _e( 'Permalink to' , 'cosmotheme' ); ?> <?php echo $post -> post_title; ?>" rel="bookmark"><?php echo $post -> post_title; ?></a><?php
                            }
                        }
                    ?>
                </h2>
				<!-- meta -->
   
				<?php   if( options::logic( 'general' , 'meta' ) ){	 ?>
                <div class="entry-meta">
                    <ul>
                        <li class="author"><a href="<?php echo get_author_posts_url( $post -> post_author ) ?>"><?php echo get_the_author_meta( 'display_name' , $post -> post_author ); ?></a></li>
                        <li class="cosmo-comments">
                            <?php
                                if( is_user_logged_in () ){
                            ?>
                                    <a href="<?php echo get_comments_link( $post -> ID ) ?>">
                                        <?php
                                            if( options::logic( 'general' , 'fb_comments' ) ){
                                                ?> <fb:comments-count href=<?php echo get_permalink( $post -> ID  ) ?>></fb:comments-count> <?php
                                            }else{
                                                echo get_comments_number( $post -> ID );
                                            }
                                        ?>
                                    </a>
                            <?php
                                }else{
                                    $meta = meta::get_meta( $post -> ID  , 'settings' );
                                    if( isset( $meta['safe'] ) ){
                                        if( !meta::logic( $post , 'settings' , 'safe' ) ){
                            ?>
                                            <a href="<?php echo get_comments_link( $post -> ID ) ?>">
                                                <?php
                                                    if( options::logic( 'general' , 'fb_comments' ) ){
                                                        ?> <fb:comments-count href=<?php echo get_permalink( $post -> ID  ) ?>></fb:comments-count> <?php
                                                    }else{
                                                        echo get_comments_number( $post -> ID );
                                                    }
                                                ?>
                                            </a>
                            <?php
                                        }else{
                            ?>
                                            <a class="simplemodal-nsfw" href="<?php  echo wp_login_url( ); ?>">
                                                <?php
                                                    if( options::logic( 'general' , 'fb_comments' ) ){
                                                        ?> <fb:comments-count href=<?php echo get_permalink( $post -> ID  ) ?>></fb:comments-count> <?php
                                                    }else{
                                                        echo get_comments_number( $post -> ID );
                                                    }
                                                ?>
                                            </a>
                            <?php
                                        }
                                    }else{
                            ?>
                                        <a href="<?php echo get_comments_link( $post -> ID ) ?>">
                                            <?php
                                                if( options::logic( 'general' , 'fb_comments' ) ){
                                                    ?> <fb:comments-count href=<?php echo get_permalink( $post -> ID  ) ?>></fb:comments-count> <?php
                                                }else{
                                                    echo get_comments_number( $post -> ID );
                                                }
                                            ?>
                                        </a>
                            <?php
                                    }
                                }
                            ?>
                        </li>
                        <?php
                            if( options::logic( 'general' , 'enb_likes' ) ){
                                $meta = meta::get_meta( $post -> ID  , 'settings' );
                                if( isset( $meta['love'] ) ){
                                    if( meta::logic( $post , 'settings' , 'love' ) ){
                                        if( is_user_logged_in () ){
                                            ?>
                                                <li class="cosmo-love">
                                                    <a href="<?php echo get_permalink( $post -> ID ); ?>"><?php echo $nr_like; ?></a>
                                                </li>
                                            <?php
                                        }else{
                                            if( isset( $meta['safe'] ) ){
                                                if( !meta::logic( $post , 'settings' , 'safe' ) ){
                                                    ?>
                                                        <li class="cosmo-love">
                                                            <a href="<?php echo get_permalink( $post -> ID ); ?>"><?php echo $nr_like; ?></a>
                                                        </li>
                                                    <?php
                                                }else{
                                                    ?>
                                                        <li class="cosmo-love">
                                                            <a class="simplemodal-nsfw" href="<?php  echo wp_login_url( ); ?>"><?php echo $nr_like; ?></a>
                                                        </li>
                                                    <?php
                                                }
                                            }else{
                                                ?>
                                                    <li class="cosmo-love">
                                                        <a href="<?php echo get_permalink( $post -> ID ); ?>"><?php echo $nr_like; ?></a>
                                                    </li>
                                                <?php
                                            }
                                        }
                                    }
                                }else{
                                    if( is_user_logged_in () ){
                                        ?>
                                            <li class="cosmo-love">
                                                <a href="<?php echo get_permalink( $post -> ID ); ?>"><?php echo $nr_like; ?></a>
                                            </li>
                                        <?php
                                    }else{
                                        if( isset( $meta['safe'] ) ){
                                            if( !meta::logic( $post , 'settings' , 'safe' ) ){
                                                ?>
                                                    <li class="cosmo-love">
                                                        <a href="<?php echo get_permalink( $post -> ID ); ?>"><?php echo $nr_like; ?></a>
                                                    </li>
                                                <?php
                                            }else{
                                                ?>
                                                    <li class="cosmo-love">
                                                        <a class="simplemodal-nsfw" href="<?php  echo wp_login_url( ); ?>"><?php echo $nr_like; ?></a>
                                                    </li>
                                                <?php
                                            }
                                        }else{
                                            ?>
                                                <li class="cosmo-love">
                                                    <a href="<?php echo get_permalink( $post -> ID ); ?>"><?php echo $nr_like; ?></a>
                                                </li>
                                            <?php
                                        }
                                    }
                                }
                            }
                        ?>
                    </ul>
                </div>
				<?php } ?>
            </article>
        <?php
        }

        function is_grid( $template ){
            $grid = false;

            if( $template == 'front_page' ){
                if( options::logic( 'layout' , 'v_' . $template  ) ){
                    $grid = false;
                }else{
                    $grid = true;
                }
            }else{
                if( isset( $_COOKIE['grid_' . $template ] ) ){
                    if( $_COOKIE['grid_' . $template ] == 'grid'  ){
                        $grid = true;
                    }else{
                        $grid = false;
                    }
                }else{
                    if( options::logic( 'layout' , 'v_' . $template  ) ){
                        $grid = false;
                    }else{
                        $grid = true;
                    }
                }
            }

            return $grid;
        }
        
        function loop( $template ) {
            global $wp_query;        
            echo '<input type="hidden" id="query-' . $template . '" value="' . urlencode( json_encode( $wp_query -> query ) ) . '" />';
            if ( count( $wp_query->posts) > 0 ) {
                if( self::is_grid( $template ) ){
    ?>
                    <div class="loop-container-view grid">
                        <?php self::loop_switch( $template , 1 ); ?>
                    </div>
    <?php
                }else{
    ?>
                    <div class="loop-container-view list">
                        <?php self::loop_switch( $template , 0 ); ?>
                    </div>
    <?php
                }
                
                get_template_part('pagination');
            } else {
                get_template_part('loop', '404');
            }
        }
        
        function loop_switch( $template = '' , $grid = 1 ) {
            global $wp_query;
            if ( !empty( $template ) ) {
                $ajax = false;
            } else {
                $query = array();
                $template = isset( $_POST['template'] ) && strlen( $_POST['template'] ) ? $_POST['template'] : exit();
                $query = isset( $_POST['query'] ) && !empty( $_POST['query'] ) ? (array)json_decode( urldecode( $_POST['query'] ) ) : exit();
                $query['post_status'] = 'publish';
                $wp_query = new WP_Query( $query );
                $grid = isset($_POST['grid']) ? (int)$_POST['grid'] : 1;
                $ajax = true;
            }

            $template   = str_replace( array( '_hot' , '_new' , '_like' ) , '' , $template );

            if( $grid == 1 ){
                $k = 1;
                $i = 1;
                $nr = $wp_query->post_count;

                if (layout::get_length(0, $template) == 940 ) {
                    $div = 3;
                } else {
                    $div = 2;
                }

                foreach ($wp_query->posts as $post) {
                    $wp_query->the_post();
                    if ($i == 1) {
                        if (( $nr - $k ) < $div) {
                            $classes = 'class="last"';
                        } else {
                            $classes = '';
                        }
                        echo '<div ' . $classes . '>';
                    }

                    self::grid( $post, $template );

                    if ($i % $div == 0) {
                        echo '</div>';
                        $i = 0;
                    }
                    $i++;
                    $k++;
                }

                if ($i > 1) {
                    echo '</div>';
                }
            }else{
                foreach( $wp_query->posts as $index => $post ) {
                    $wp_query->the_post();
                    if ($index > 0) {
                        ?><p class="delimiter">&nbsp;</p><?php
                    }

                    self::get( $post, $template );
                }
            }

            if( $ajax ){
                exit();
            }
        }

        function meta( $post , $nav = true ){
            global $wp_query;
        ?>
            <div class="entry-meta">
                <ul>
					<?php if(options::logic( 'upload' , 'enb_edit_delete' ) && is_user_logged_in() && $post->post_author == get_current_user_id() ){ 
						$post_item_page = get_page_by_title('Post Item');
						//get_page_link($post_item_page->ID);
					?> 
						<li class="edit_post" title="<?php _e('Edit post','cosmotheme') ?>"><a href="<?php  echo add_query_arg( 'post', $post->ID, get_page_link($post_item_page->ID) );   ?>"  ><?php echo _e('Edit','cosmotheme'); ?></a></li>    
					<?php }   ?>
					<?php if( options::logic( 'upload' , 'enb_edit_delete' )  && is_user_logged_in() && $post->post_author == get_current_user_id() ){  
						$confirm_delete = __('Confirm to delete this post.','cosmotheme');
					?>
					<li class="delete_post" title="<?php _e('Remove post','cosmotheme') ?>"><a href="javascript:void(0)" onclick="if(confirm('<?php echo $confirm_delete; ?> ')){ removePost('<?php echo $post->ID; ?>','<?php echo home_url() ?>');}" ><?php echo _e('Delete','cosmotheme'); ?></a></li>
					<?php  } ?>  
                    <?php //edit_post_link( __( 'Edit', 'cosmotheme' ), '<li class="edit_post" title="' . __( 'Edit' , 'cosmotheme' ) . '">', '</li>' ); ?>
                    <li class="author" title="Author"><a href="<?php echo get_author_posts_url( $post-> post_author ) ?>"><?php echo mb_substr( get_the_author_meta( 'display_name' , $post-> post_author ) , 0 , _AUTL_ ); ?></a></li>
                    <li class="time">
                        <time>
                            <?php
                                if( options::logic( 'general' , 'time' ) ){

                                    echo human_time_diff( get_the_time( 'U' , $post -> ID ) , current_time('timestamp') ) . ' ' . __( 'ago' , 'cosmotheme' );
                                }else{
                                    echo date_i18n( get_option( 'date_format' ) , get_the_time( 'U' , $post -> ID ) ); /*echo ' '.__('at','cosmotheme') . ' '. get_the_time( get_option( 'time_format' ) , $post -> ID  );*/

                                }
                            ?>
                        </time>
                    </li>
                    <?php
                        if( is_user_logged_in () ){
                            if ( comments_open( $post -> ID ) ){
                                if( options::logic( 'general' , 'fb_comments' ) ){
                                    ?><li class="cosmo-comments" title=""><a href="<?php echo get_comments_link( $post -> ID ); ?>"> <fb:comments-count href="<?php echo get_permalink( $post -> ID ) ?>"></fb:comments-count> </a></li><?php
                                }else{
                                    ?><li class="cosmo-comments" title="<?php echo get_comments_number( $post -> ID ); ?> Comments"><a href="<?php echo get_comments_link( $post -> ID ) ?>"> <?php echo get_comments_number( $post -> ID ) ?> </a></li><?php
                                }
                            }
                        }else{
                            $meta = meta::get_meta( $post -> ID  , 'settings' );
                            if( isset( $meta['safe'] ) ){
                                if( !meta::logic( $post , 'settings' , 'safe' ) ){
                                    if ( comments_open( $post -> ID ) ){
                                        if( options::logic( 'general' , 'fb_comments' ) ){
                                            ?><li class="cosmo-comments" title=""><a href="<?php echo get_comments_link( $post -> ID ) ?>"> <fb:comments-count href="<?php echo get_permalink( $post -> ID ) ?>"></fb:comments-count> </a></li><?php
                                        }else{
                                            ?><li class="cosmo-comments" title="<?php echo get_comments_number( $post -> ID ); ?> Comments"><a href="<?php echo get_comments_link( $post -> ID ) ?>"> <?php echo get_comments_number( $post -> ID ) ?> </a></li><?php
                                        }
                                    }
                                }else{
                                    if ( comments_open( $post -> ID ) ){
                                        if( options::logic( 'general' , 'fb_comments' ) ){
                                            ?><li class="cosmo-comments" title=""><a class="simplemodal-nsfw" href="<?php  echo wp_login_url( ); ?>"> <fb:comments-count href="<?php echo get_permalink( $post -> ID ) ?>"></fb:comments-count> </a></li><?php
                                        }else{
                                            ?><li class="cosmo-comments" title="<?php echo get_comments_number( $post -> ID ); ?> Comments"><a class="simplemodal-nsfw" href="<?php  echo wp_login_url( ); ?>"> <?php echo get_comments_number( $post -> ID ) ?> </a></li><?php
                                        }
                                    }
                                }
                            }else{
                                if ( comments_open( $post -> ID ) ){
                                    if( options::logic( 'general' , 'fb_comments' ) ){
                                        ?><li class="cosmo-comments" title=""><a href="<?php echo get_comments_link( $post -> ID ) ?>"> <fb:comments-count href="<?php echo get_permalink( $post -> ID ) ?>"></fb:comments-count> </a></li><?php
                                    }else{
                                        ?><li class="cosmo-comments" title="<?php echo get_comments_number( $post -> ID ); ?> Comments"><a href="<?php echo get_comments_link( $post -> ID ) ?>"> <?php echo get_comments_number( $post -> ID ) ?> </a></li><?php
                                    }
                                }
                            }
                        }
                    ?>
                </ul>
                <!-- tags -->
                <?php
                    $tags = wp_get_post_terms( $post -> ID , 'post_tag' );

                    if( !empty( $tags ) ){
                ?>
                        <ul class="b_tag">
                        <?php
                            foreach ($tags as $tag ) {
                                $t = get_tag( $tag );
                                echo '<li><a href="'.get_tag_link( $tag ).'" rel="tags">' . $t -> name . '</a></li>';
                            }
                        ?>
                        </ul>
                <?php
                    }
                ?>
                <!-- categories -->
                <?php
                    $categories = wp_get_post_terms( $post -> ID , 'category' );
                    if( !empty( $categories ) ){
                ?>
                            <ul class="category">
                <?php
                                foreach ( $categories as $category ) {
                                    $cat = get_category( $category );
                                    echo '<li><a href="'.get_category_link( $category ).'">' . $cat -> name . '</a></li>';
                                }
                ?>
                            </ul>
                <?php
                    }

                    if( !is_page() ){
            ?>
                <nav class="hotkeys-meta <?php if( is_single() ){ ?>sticky-bar<?php }else{ ?> nav <?php } ?>" <?php if( is_single() ){ ?>id="sticky-bar"<?php }?>>
                    <?php
                        if( is_single () ){
                            ?><span class="nav-previous"><?php previous_post_link( '%link', __( 'Previous' , 'cosmotheme' ) ); ?></span><?php
                            ?><span class="nav-next"><?php next_post_link( '%link', __( 'Next' , 'cosmotheme' ) ); ?></span><?php
                        }else{
                            if( $wp_query -> current_post == 0 ){
                                ?><span class="nav-previous"><a href="#prev"><?php _e( 'Previous' , 'cosmotheme' ); ?></a></span><?php
                                ?><span class="nav-next"><a href="#next"><?php _e( 'Next' , 'cosmotheme' ); ?></a></span><?php
                            }else{
                                if( $wp_query -> current_post == $wp_query -> post_count - 1 ){
                                    ?><span class="nav-previous"><a href="#prev"><?php _e( 'Previous' , 'cosmotheme' ); ?></a></span><?php
                                    ?><span class="nav-next"><a href="#next"><?php _e( 'Next' , 'cosmotheme' ); ?></a></span><?php
                                }else{
                                    ?><span class="nav-previous"><a href="#prev"><?php _e( 'Previous' , 'cosmotheme' ); ?></a></span><?php
                                    ?><span class="nav-next"><a href="#next"><?php _e( 'Next' , 'cosmotheme' ); ?></a></span><?php
                                }
                            }
                        }
                    ?>
                </nav>
                <?php
                    }
                ?>
            </div>
        <?php
        }
        
        function add_image_post(){
        	$response = array(  'image_error' => '',
        						'error_msg' => '',	
        						'title_error' => '',
        						'post_id' => 0,
        						'auth_error' => '',
        						'success_msg' => ''	);
        	
        	
        	$is_valid = true;
        	
        	if(!is_user_logged_in()){
        		$is_valid = false;	
        		$response['error_msg'] = 'error';
        		$response['auth_error'] = __('You must be logged in to submit a post! ','cosmotheme');	
        	}
        	if(is_user_logged_in() && isset($_POST['post_id'])){
				$post_edit = get_post($_POST['post_id']);
				
				if(get_current_user_id() != $post_edit->post_author){
					$is_valid = false;	
					$response['error_msg'] = __('You are not the author of this post. ','cosmotheme');
					$response['title_error'] = __('You are not the author of this post. ','cosmotheme');
				}
			}
        	if(!isset($_POST['title']) || trim($_POST['title']) == ''){
        		$is_valid = false;	
        		$response['error_msg'] = 'Title is required. ';
        		$response['title_error'] = __('Title is required. ','cosmotheme');
        	}
     
			if(!isset($_POST['attachments']) || !is_array($_POST['attachments']) || !isset($_POST['featured']) || !is_numeric($_POST['featured']))
			  {
        		$is_valid = false;	
        		$response['error_msg'] = 'error';
        		$response['image_error'] = __('An image post must have a featured image. ','cosmotheme');
			  }
        	
        	
        		if($is_valid){
        			/*create post*/
        			$post_categories = array(1);
        			if(isset($_POST['category_id'])){
        				$post_categories = array($_POST['category_id']);
        			}
        			
        			$post_content = '';
        			if(isset($_POST['image_content'])){
        				$post_content = $_POST['image_content'];
        			}
        			
        			if(isset($_POST['post_id'])){
						$new_post = self::create_new_post($_POST['title'], $_POST['tags'], $post_categories, $post_content, $_POST['post_id']);  /*add image as content*/
					}else{
						$new_post = self::create_new_post($_POST['title'],$_POST['tags'],$post_categories,$post_content);  /*add image as content*/
					}
        			
				    
				    if(is_numeric($new_post))
					  {	
						$attachments = get_children( array('post_parent' => $new_post, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID') );
						foreach ($attachments as $index => $id) {
							$attachment = $index;
						} 
						foreach($_POST['attachments'] as $index=>$imageid)
						  {
							if($imageid==$_POST['featured'])
							  {
								set_post_thumbnail($new_post, $imageid);
								unset($_POST['attachments'][$index]);
							  }
							 $attachment_post=get_post($imageid);
							 $attachment_post->post_parent=$new_post;
							 wp_update_post($attachment_post);
						  }
						
						if(isset($_POST['nsfw'])){
							$settings_meta = array(	  "safe"=>  "yes");
							meta::set_meta( $new_post , 'settings' , $settings_meta );
						}else{
							$settings_meta = array(	  "safe"=>  "yes");
							delete_post_meta($new_post, 'settings', $settings_meta );
						}	
						
						/*add source meta data*/
						if(isset($_POST['source']) && trim($_POST['source']) != ''){
						  $settings_meta = array(	  "post_source"=>  $_POST['source']);
						  meta::set_meta( $new_post , 'source' , $settings_meta );	
						}else{
							$settings_meta = array(	  "post_source"=>  $_POST['source']);
							delete_post_meta($new_post, 'source', $settings_meta );
						}	
							
						/*add video url meta data*/
						$image_format_meta = array("type" => 'image', 'images'=>$_POST['attachments']);
						meta::set_meta( $new_post , 'format' , $image_format_meta );

						if(isset($_POST['post_format']) && ($_POST['post_format'] == 'video' || $_POST['post_format'] == 'image' || $_POST['post_format'] == 'audio') ){
							set_post_format( $new_post , $_POST['post_format']);
						}
						
						if(isset($_POST['post_id'])){ /*if the post is edited */
							$post_status = options::get_value( 'upload' , 'default_edit_status' );
						}else{ /* if the post is just created */
							$post_status = options::get_value( 'general' , 'default_posts_status' );
						}
						if($post_status == 'publish'){
							/*if post was publihed imediatelly then we will show the prmalink to the user*/
								
							$response['success_msg'] = sprintf(__('You can check your post %s here%s.','cosmotheme'),'<a href="'.get_permalink($new_post).'">','</a>');
							
						}else{
							$response['success_msg'] = __('Success. Your post is awaiting moderation.','cosmotheme');
						}	
						$response['post_id'] = $new_post;
				    }	
				    
	        		
        		}	
        	echo json_encode($response);
        	exit;
        }

		function add_file_post(){

			$response = array(  'image_error' => '',
								'file_error' => '',
        						'error_msg' => '',	
        						'title_error' => '',
        						'post_id' => 0,
        						'auth_error' => '',
        						'success_msg' => ''	);
        	
        	
        	$is_valid = true;
        	
        	if(!is_user_logged_in()){
        		$is_valid = false;	
        		$response['error_msg'] = 'error';
        		$response['auth_error'] = __('You must be logged in to submit a post! ','cosmotheme');	
        	}
            
            if(is_user_logged_in() && isset($_POST['post_id'])){
				$post_edit = get_post($_POST['post_id']);
				
				if(get_current_user_id() != $post_edit->post_author){
					$is_valid = false;	
					$response['error_msg'] = __('You are not the author of this post. ','cosmotheme');
					$response['title_error'] = __('You are not the author of this post. ','cosmotheme');
				}
			}
            
        	if(!isset($_POST['title']) || trim($_POST['title']) == ''){
        		$is_valid = false;	
        		$response['error_msg'] = 'Title is required. ';
        		$response['title_error'] = __('Title is required. ','cosmotheme');
        	}

			if(!isset($_POST['attachments'])){
        		$is_valid = false;	
        		$response['error_msg'] = 'File is required. ';
        		$response['file_error'] = __('File is required. ','cosmotheme');
        	}

        		if($is_valid){
        			/*create post*/
        			$post_categories = array(1);
        			if(isset($_POST['category_id'])){
        				$post_categories = array($_POST['category_id']);
        			}
        			
        			$post_content = '';
        			if(isset($_POST['file_content'])){
        				$post_content = $_POST['file_content'];
        			}
        			
        			
                    if(isset($_POST['post_id'])){
						$new_post = self::create_new_post($_POST['title'], $_POST['tags'], $post_categories, $post_content, $_POST['post_id']);  
					}else{
						$new_post = self::create_new_post($_POST['title'],$_POST['tags'],$post_categories,$post_content);  
					}
                    
				    if(is_numeric($new_post))
					  {
						delete_post_thumbnail($new_post);
						foreach($_POST['attachments'] as $index=>$attachid)
						  {
							if($attachid==$_POST['featured'])
							  {
								set_post_thumbnail($new_post, $attachid);
								unset($_POST['attachments'][$index]);
							  }
							$attachment_post=get_post($attachid);
							$attachment_post->post_parent=$new_post;
							wp_update_post($attachment_post);
						  }
						$file_url_meta = array(	  "link"=>  "", "type" => 'link', 'link_id' => $_POST['attachments']);
						meta::set_meta( $new_post , 'format' , $file_url_meta );
						
						if(isset($_POST['nsfw'])){
							$settings_meta = array(	  "safe"=>  "yes");
							meta::set_meta( $new_post , 'settings' , $settings_meta );
						}else{
							$settings_meta = array(	  "safe"=>  "yes");
							delete_post_meta($new_post, 'settings', $settings_meta );
						}	
						
						/*add source meta data*/
						if(isset($_POST['source']) && trim($_POST['source']) != ''){
						  $settings_meta = array(	  "post_source"=>  $_POST['source']);
						  meta::set_meta( $new_post , 'source' , $settings_meta );	
						}else{
							$settings_meta = array(	  "post_source"=>  $_POST['source']);
							delete_post_meta($new_post, 'source', $settings_meta );
						}	
							
						/*add file url meta data*/

						if(isset($_POST['post_format']) && ($_POST['post_format'] == 'video' || $_POST['post_format'] == 'image' || $_POST['post_format'] == 'audio' || $_POST['post_format'] == 'link') ){
							set_post_format( $new_post , $_POST['post_format']);
						}
						
						if(isset($_POST['post_id'])){ /*if the post is edited */
							$post_status = options::get_value( 'upload' , 'default_edit_status' );
						}else{ /* if the post is just created */
							$post_status = options::get_value( 'general' , 'default_posts_status' );
						}
						if($post_status == 'publish'){
							/*if post was publihed imediatelly then we will show the prmalink to the user*/
								
							$response['success_msg'] = sprintf(__('You can check your post %s here%s.','cosmotheme'),'<a href="'.get_permalink($new_post).'">','</a>');
							
						}else{
							$response['success_msg'] = __('Success. Your post is awaiting moderation.','cosmotheme');
						}	
						$response['post_id'] = $new_post;
				    }	
				    
	        		
        		}	
        	echo json_encode($response);
        	exit;
		}

		function add_audio_post(){
			$response = array(  'image_error' => '',
								'audio_error' => '',
        						'error_msg' => '',	
        						'title_error' => '',
        						'post_id' => 0,
        						'auth_error' => '',
        						'success_msg' => ''	);
        	
        	
        	$is_valid = true;
        	
        	if(!is_user_logged_in()){
        		$is_valid = false;	
        		$response['error_msg'] = 'error';
        		$response['auth_error'] = __('You must be logged in to submit a post! ','cosmotheme');	
        	}
        	
            if(is_user_logged_in() && isset($_POST['post_id'])){
				$post_edit = get_post($_POST['post_id']);
				
				if(get_current_user_id() != $post_edit->post_author){
					$is_valid = false;	
					$response['error_msg'] = __('You are not the author of this post. ','cosmotheme');
					$response['title_error'] = __('You are not the author of this post. ','cosmotheme');
				}
			}
            
        	if(!isset($_POST['title']) || trim($_POST['title']) == ''){
        		$is_valid = false;	
        		$response['error_msg'] = 'Title is required. ';
        		$response['title_error'] = __('Title is required. ','cosmotheme');
        	}

			if(!isset($_POST['attachments'])){
        		$is_valid = false;	
        		$response['error_msg'] = 'Audio File is required. ';
        		$response['audio_error'] = __('Audio File is required. ','cosmotheme');
        	}	
   	        	
        		if($is_valid){
        			/*create post*/
        			$post_categories = array(1);
        			if(isset($_POST['category_id'])){
        				$post_categories = array($_POST['category_id']);
        			}
        			
        			$post_content = '';
        			if(isset($_POST['audio_content'])){
        				$post_content = $_POST['audio_content'];
        			}

					if(isset($_POST['post_id'])){
						$new_post = self::create_new_post($_POST['title'], $_POST['tags'], $post_categories, $post_content, $_POST['post_id']);  
					}else{
						$new_post = self::create_new_post($_POST['title'],$_POST['tags'],$post_categories,$post_content);  
					}
                    
				    if(is_numeric($new_post))
					  {
						delete_post_thumbnail($new_post);
						foreach($_POST['attachments'] as $index=>$attachid)
						  {
							if($attachid==$_POST['featured'])
							  {
								set_post_thumbnail($new_post, $attachid);
								unset($_POST['attachments'][$index]);
							  }
							$attachment_post=get_post($attachid);
							$attachment_post->post_parent=$new_post;
							wp_update_post($attachment_post);
						  }
						$audio_url_meta = array(	  "audio"=>  $_POST['attachments'], "type" => 'audio');
						meta::set_meta( $new_post , 'format' , $audio_url_meta );

						if(isset($_POST['nsfw'])){
							$settings_meta = array(	  "safe"=>  "yes");
							meta::set_meta( $new_post , 'settings' , $settings_meta );
						}else{
							$settings_meta = array(	  "safe"=>  "yes");
							delete_post_meta($new_post, 'settings', $settings_meta );
						}	
						
						/*add source meta data*/
						if(isset($_POST['source']) && trim($_POST['source']) != ''){
						  $settings_meta = array(	  "post_source"=>  $_POST['source']);
						  meta::set_meta( $new_post , 'source' , $settings_meta );	
						}else{
							$settings_meta = array(	  "post_source"=>  $_POST['source']);
							delete_post_meta($new_post, 'source', $settings_meta );
						}	
												
						if(isset($_POST['post_format']) && ($_POST['post_format'] == 'video' || $_POST['post_format'] == 'image' || $_POST['post_format'] == 'audio' || $_POST['post_format'] == 'link') ){
							set_post_format( $new_post , $_POST['post_format']);
						}
						
						if(isset($_POST['post_id'])){ /*if the post is edited */
							$post_status = options::get_value( 'upload' , 'default_edit_status' );
						}else{ /* if the post is just created */
							$post_status = options::get_value( 'general' , 'default_posts_status' );
						}
						if($post_status == 'publish'){
							/*if post was publihed imediatelly then we will show the prmalink to the user*/
								
							$response['success_msg'] = sprintf(__('You can check your post %s here%s.','cosmotheme'),'<a href="'.get_permalink($new_post).'">','</a>');
							
						}else{
							$response['success_msg'] = __('Success. Your post is awaiting moderation.','cosmotheme');
						}	
						$response['post_id'] = $new_post;
				    }	
				    
	        		
        		}	
        	echo json_encode($response);
        	exit;
		}
        
        function add_text_post(){
        	$response = array(  'error_msg' => '',	
        						'title_error' => '',
        						'post_id' => 0,
        						'auth_error' => '' );
        	
        	$is_valid = true;
        	
        	if(!is_user_logged_in()){
        		$is_valid = false;	
        		$response['error_msg'] = 'error';
        		$response['auth_error'] = __('You must be logged in to submit a post!','cosmotheme');	
        	}
        	
            if(is_user_logged_in() && isset($_POST['post_id'])){
				$post_edit = get_post($_POST['post_id']);
				
				if(get_current_user_id() != $post_edit->post_author){
					$is_valid = false;	
					$response['error_msg'] = __('You are not the author of this post. ','cosmotheme');
					$response['title_error'] = __('You are not the author of this post. ','cosmotheme');
				}
			}
            
        	if(!isset($_POST['title']) || trim($_POST['title']) == ''){
        		$is_valid = false;	
        		$response['error_msg'] = 'error';
        		$response['title_error'] = __('Title is required. ','cosmotheme');
        	}
        	
        		if($is_valid){

	        			/*create post*/
        				/*$post_content = self::get_embeded_video($video_id,$video_type);*/
	        			$post_categories = array(1);
	        			//$response['video_error'] = $_POST['category_id'];
	        			if(isset($_POST['category_id'])){
	        				$post_categories = array($_POST['category_id']);
	        			}
	        			
	        			$post_content = '';
	        			if(isset($_POST['text_content'])){
	        				$post_content = $_POST['text_content'];
	        			}
	        			
                        if(isset($_POST['post_id'])){
                            $new_post = self::create_new_post($_POST['title'], $_POST['tags'], $post_categories, $post_content, $_POST['post_id']);  
                        }else{
                            $new_post = self::create_new_post($_POST['title'],$_POST['tags'],$post_categories,$post_content);  
                        }
                        
					    if(is_numeric($new_post)){	
						   
							
							if(isset($_POST['nsfw'])){
								$settings_meta = array(	  "safe"=>  "yes");
								meta::set_meta( $new_post , 'settings' , $settings_meta );
							}else{
								$settings_meta = array(	  "safe"=>  "yes");
								delete_post_meta($new_post, 'settings', $settings_meta );
							}	
							
							/*add source meta data*/
							if(isset($_POST['source']) && trim($_POST['source']) != ''){
							  $settings_meta = array(	  "post_source"=>  $_POST['source']);
							  meta::set_meta( $new_post , 'source' , $settings_meta );	
							}else{
								$settings_meta = array(	  "post_source"=>  $_POST['source']);
								delete_post_meta($new_post, 'source', $settings_meta );
							}	
						
							if(isset($_POST['post_id'])){ /*if the post is edited */
								$post_status = options::get_value( 'upload' , 'default_edit_status' );
							}else{ /* if the post is just created */
								$post_status = options::get_value( 'general' , 'default_posts_status' );
							} 
							if($post_status == 'publish'){
								/*if post was publihed imediatelly then we will show the prmalink to the user*/
									
								$response['success_msg'] = sprintf(__('You can check your post %s here%s.','cosmotheme'),'<a href="'.get_permalink($new_post).'">','</a>');
								
							}else{
								$response['success_msg'] = __('Success. Your post is awaiting moderation','cosmotheme');
							}	
							$response['post_id'] = $new_post;
					    }
				
        		}
        			
        	echo json_encode($response);
        	exit;
        	
        }
        
        function add_video_post(){
        	$response = array(  'video_error' => '',
        						'error_msg' => '',	
        						'title_error' => '',
        						'post_id' => 0,
        						'auth_error' => '' );
        	
        	
        	$is_valid = true;
        	
        	if(!is_user_logged_in()){
        		$is_valid = false;	
        		$response['error_msg'] = 'error';
        		$response['auth_error'] = __('You must be logged in to submit a post!','cosmotheme');	
        	}
        	
            if(is_user_logged_in() && isset($_POST['post_id'])){
				$post_edit = get_post($_POST['post_id']);
				
				if(get_current_user_id() != $post_edit->post_author){
					$is_valid = false;	
					$response['error_msg'] = __('You are not the author of this post. ','cosmotheme');
					$response['title_error'] = __('You are not the author of this post. ','cosmotheme');
				}
			}
            
        	if(!isset($_POST['title']) || trim($_POST['title']) == ''){
        		$is_valid = false;	
        		$response['error_msg'] = 'error';
        		$response['title_error'] = __('Title is required. ','cosmotheme');
        	}
        	
			if(!isset($_POST['attachments']) || !is_array($_POST['attachments']) || !isset($_POST['featured']) || !is_numeric($_POST['featured']))
			{
				$is_valid = false;	
        		$response['error_msg'] = 'error';
        		$response['video_error'] = __('A video post must have a featured video.','cosmotheme');
			}
        	
        	if($is_valid)
			  {
	        	/*create post*/
        		/*$post_content = self::get_embeded_video($video_id,$video_type);*/
	        	$post_categories = array(1);
	        	//$response['video_error'] = $_POST['category_id'];
	        			
	        	if(isset($_POST['category_id'])){
	        		$post_categories = array($_POST['category_id']);
	        	}
	        			
	        	$post_content = '';
	        	if(isset($_POST['video_content'])){
	        		$post_content = $_POST['video_content'];
	        	}
	        			
                if(isset($_POST['post_id'])){
				  $new_post = self::create_new_post($_POST['title'], $_POST['tags'], $post_categories, $post_content, $_POST['post_id']);  
                }else{
                  $new_post = self::create_new_post($_POST['title'],$_POST['tags'],$post_categories,$post_content);  
                }
                    
				if(is_numeric($new_post))
				  {	
					if(isset($_POST['nsfw'])){
						$settings_meta = array(	  "safe"=>  "yes");
						meta::set_meta( $new_post , 'settings' , $settings_meta );
					}else{
						$settings_meta = array(	  "safe"=>  "yes");
						delete_post_meta($new_post, 'settings', $settings_meta );
					}	
							
					/*add source meta data*/
					if(isset($_POST['source']) && trim($_POST['source']) != ''){
					  $settings_meta = array(	  "post_source"=>  $_POST['source']);
					  meta::set_meta( $new_post , 'source' , $settings_meta );	
					}else{
						$settings_meta = array(	  "post_source"=>  $_POST['source']);
						delete_post_meta($new_post, 'source', $settings_meta );
					}	

					$featured_video_url = '';
					foreach($_POST['attachments'] as $index=>$videoid)
					  {
						if($videoid==$_POST['featured'])
						  {
							$featured_video_id=$videoid;
							unset($_POST['attachments'][$index]);
							if(isset($_POST['video_urls'][$videoid]))
							  {
								set_post_thumbnail($new_post,$videoid);
								$featured_video_url=$_POST['video_urls'][$videoid];
								unset($_POST['video_urls'][$videoid]);
							  }
							else delete_post_thumbnail($new_post);
							}
						 $attachment_post=get_post($videoid);
						 $attachment_post->post_parent=$new_post;
						 wp_update_post($attachment_post);
					  }
				
				  $video_format_meta=array("type"=>"video", "video_ids"=>$_POST['attachments'], "feat_id"=>$featured_video_id, "feat_url"=>$featured_video_url);
				  if(isset($_POST['video_urls']))
					$video_format_meta["video_urls"]=$_POST["video_urls"];
				  meta::set_meta( $new_post , 'format' , $video_format_meta );

							if(isset($_POST['post_format']) && ($_POST['post_format'] == 'video' || $_POST['post_format'] == 'image' || $_POST['post_format'] == 'audio') ){
								set_post_format( $new_post , $_POST['post_format']);
							}
							
							
							if(isset($_POST['post_id'])){ /*if the post is edited */
								$post_status = options::get_value( 'upload' , 'default_edit_status' );
							}else{ /* if the post is just created */
								$post_status = options::get_value( 'general' , 'default_posts_status' );
							}
							if($post_status == 'publish'){
								/*if post was publihed imediatelly then we will show the prmalink to the user*/
									
								$response['success_msg'] = sprintf(__('You can check your post %s here%s.','cosmotheme'),'<a href="'.get_permalink($new_post).'">','</a>');
								
							}else{
								$response['success_msg'] = __('Success. Your post is awaiting moderation','cosmotheme');
							}	
							$response['post_id'] = $new_post;
					    }
				    
        			}else{
        				$is_valid = false;	
		        		$response['error_msg'] = 'error, could not create thumbnail for this video';
		        		$response['video_error'] = __('Invalid video URL. ','cosmotheme');
        			}
        			
        	echo json_encode($response);
        	exit;
        }
        
       
        function get_embeded_video($video_id,$video_type,$autoplay = 0,$width = 620,$height = 450){
        	
        	$embeded_video = '';
        	if($video_type == 'youtube'){
        		$embeded_video	= '<iframe width="'.$width.'" height="'.$height.'" src="http://www.youtube.com/embed/'.$video_id.'?wmode=transparent&autoplay='.$autoplay.'" wmode="opaque" frameborder="0" allowfullscreen></iframe>';
        	}elseif($video_type == 'vimeo'){
        		$embeded_video	= '<iframe src="http://player.vimeo.com/video/'.$video_id.'?title=0&amp;autoplay='.$autoplay.'&amp;byline=0&amp;portrait=0" width="'.$width.'" height="'.$height.'" frameborder="0" webkitAllowFullScreen allowFullScreen></iframe>';
        	}
        	
        	return $embeded_video;
        }
        
		function get_local_video($video_url, $width = 610, $height = 443, $autoplay = false ){
			
            $result = '';    
			
            if($autoplay){
                $auto_play = 'true';
            }else{
                $auto_play = 'false';
            }
            
            $result = '<embed src="' . get_template_directory_uri() . '/flv/gddflvplayer.swf" 
                flashvars="?&autoplay='.$auto_play.'&sound=70&buffer=2&vdo=' . $video_url . '" 
                width="'.$width.'" 
                height="'.$height.'" 
                allowFullScreen="true" 
                quality="best" 
                wmode="transparent" 
                allowScriptAccess="always"  
                pluginspage="http://www.macromedia.com/go/getflashplayer"  
                type="application/x-shockwave-flash"></embed>';
            

			return $result;	
		}
		
        function get_video_thumbnail($video_id,$video_type){
        	$thumbnail_url = '';
        	if($video_type == 'youtube'){
				$thumbnail_url = 'http://i1.ytimg.com/vi/'.$video_id.'/hqdefault.jpg';
        	}elseif($video_type == 'vimeo'){
        		
				$hash = wp_remote_get("http://vimeo.com/api/v2/video/$video_id.php");
				$hash = unserialize($hash['body']);
				
				$thumbnail_url = $hash[0]['thumbnail_large'];  
        	}
        	
        	return $thumbnail_url;
        }
        
    	function get_youtube_video_id($url){
	        /*
	         *   @param  string  $url    URL to be parsed, eg:  
	 		*  http://youtu.be/zc0s358b3Ys,  
	 		*  http://www.youtube.com/embed/zc0s358b3Ys
	 		*  http://www.youtube.com/watch?v=zc0s358b3Ys 
	 		*  
	 		*  returns
	 		*  */	
        	$id=0;
        	
        	/*if there is a slash at the en we will remove it*/
        	$url = rtrim($url, " /");
        	if(strpos($url, 'youtu')){
	        	$urls = parse_url($url); 
	     
			    /*expect url is http://youtu.be/abcd, where abcd is video iD*/
			    if(isset($urls['host']) && $urls['host'] == 'youtu.be'){  
			        $id = ltrim($urls['path'],'/'); 
			    } 
			    /*expect  url is http://www.youtube.com/embed/abcd*/ 
			    else if(strpos($urls['path'],'embed') == 1){  
			        $id = end(explode('/',$urls['path'])); 
			    } 
			     
			    /*expect url is http://www.youtube.com/watch?v=abcd */
			    else if( isset($urls['query']) ){ 
			        parse_str($urls['query']); 
			        $id = $v; 
			    }else{
					$id=0;
				} 
        	}	
			
			return $id;
        }
        
        function  get_vimeo_video_id($url){
        	/*if there is a slash at the en we will remove it*/
        	$url = rtrim($url, " /");
        	$id = 0;
        	if(strpos($url, 'vimeo')){
				$urls = parse_url($url); 
				if(isset($urls['host']) && $urls['host'] == 'vimeo.com'){  
					$id = ltrim($urls['path'],'/'); 
					if(!is_numeric($id) || $id < 0){
						$id = 0;
					}
				}else{
					$id = 0;
				} 
        	}	
			return $id;
		}
        

	    function isValidURL($url)
		{
			return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
		}

        
		
		function create_new_post($post_title,$post_tags, $post_categories, $content = '', $post_id = 0 ){
        	$current_user = wp_get_current_user();

        	
        	if($post_id == 0){
				$post_status = options::get_value( 'general' , 'default_posts_status' )	;
				$post_args = array(
		            'post_title' => $post_title,
		            'post_content' => $content ,
		            'post_status' => $post_status,
		            'post_type' => 'post',
					'post_author' => $current_user -> ID,
					'tags_input' => $post_tags,
					'post_category' => $post_categories
		        );
                
                $new_post = wp_insert_post($post_args);
        	}else{
				$post_status = options::get_value( 'upload' , 'default_edit_status' )	;
                $updated_post = get_post($post_id);
        		$post_args = array(
        			'ID' => $post_id,	
		            'post_title' => $post_title,
		            'post_content' => $content ,
		            'post_status' => $post_status,
                    'comment_status'=> $updated_post -> comment_status,
		            'post_type' => 'post',
					'post_author' => $current_user -> ID,
					'tags_input' => $post_tags,
        			'post_category' => $post_categories
		        );
                
                $new_post = wp_update_post($post_args);
        	}    
	
	        return $new_post;
        }
        
        function get_source($post_id){
        	
        	$source = '';
  			$source_meta = meta::get_meta( $post_id , 'source' );
  			
  			if(is_array($source_meta) && sizeof($source_meta) && isset($source_meta['post_source']) && trim($source_meta['post_source']) != ''){
  				if(self::isValidURL($source_meta['post_source'])){
  					$source_url = $source_meta['post_source'];
        			if( !is_numeric(strpos($source_meta['post_source'], 'http')) ){ /*if the $source dos not contain http we will add it*/
						$source_url = 'http://'.$source_meta['post_source'];
					}
        			$source = '<div class="source"><p><a href="'.$source_url.'" target="_blank" >'.__('View source','cosmotheme').'</a></p></div>';
        		}else{
        			$source = '<div class="source"><p>'.__('Source:','cosmotheme').' '.$source_meta['post_source'].'</p></div>';
        		}
  			}else{
  				$source = '<div class="source no_source"><p>'.__('Unknown source','cosmotheme').'</p></div>';
  			}
  			
        
        			
  			return $source;      	
        }

		function get_attached_file($post_id){
        	
        	$attached_file = '';
  			$attached_file_meta = meta::get_meta( $post_id , 'format' );


  			
			if(is_array($attached_file_meta) && sizeof($attached_file_meta) && isset($attached_file_meta['link_id']) && is_array($attached_file_meta['link_id'])){
				foreach($attached_file_meta['link_id'] as $file_id)
				  {
					$attachment_url = explode('/',wp_get_attachment_url($file_id));
					$file_name = '';
					if(sizeof($attachment_url)){
					  $file_name = $attachment_url[sizeof($attachment_url) - 1];
					}	
					$attached_file .= '<div class="attach">';
					$attached_file .= '	<a href="'.wp_get_attachment_url($file_id).'">'.$file_name.'</a>';
					$attached_file .= '</div>';
				  }
			}else if(is_array($attached_file_meta) && sizeof($attached_file_meta) && isset($attached_file_meta['link_id']))
			  {
				$file_id=$attached_file_meta['link_id'];
				$attachment_url = explode('/',wp_get_attachment_url($file_id));
					$file_name = '';
					if(sizeof($attachment_url)){
					  $file_name = $attachment_url[sizeof($attachment_url) - 1];
					}	
					$attached_file .= '<div class="attach">';
					$attached_file .= '	<a href="'.wp_get_attachment_url($file_id).'">'.$file_name.'</a>';
					$attached_file .= '</div>';
			  }
  					
  			return $attached_file;      	
        }

		function get_audio_file($post_id){
        	
        	$attached_file = '';
  			$attached_file_meta = meta::get_meta( $post_id , 'format' );
			if( is_array( $attached_file_meta ) && sizeof( $attached_file_meta ) && isset( $attached_file_meta[ 'audio' ] ) && is_array( $attached_file_meta[ 'audio' ] ) ) {

				foreach($attached_file_meta['audio'] as $audio_id)
				  {
					$attached_file .= '[audio:'.wp_get_attachment_url($audio_id).']';
				  }				
			}else if(is_array($attached_file_meta) && sizeof($attached_file_meta) && isset($attached_file_meta['audio']) && $attached_file_meta['audio'] != '' ){
			  $attached_file .= '[audio:'.$attached_file_meta['audio'].']';
			}
  					
  			return $attached_file;  	
        }
        
        function play_video(){
        	$result = '';	
        	if(isset($_POST['video_id']) && isset($_POST['video_type']) && $_POST['video_type'] != 'self_hosted'){	
        		$result = self::get_embeded_video($_POST['video_id'],$_POST['video_type'],1);
        	}else{
                $video_url = urldecode($_POST['video_id']);
                $result = self::get_local_video($video_url, 610, 443, true );
            }	
        	
        	echo $result;
        	exit;
        }
		
		function list_tags($post_id){
            $tag_list = '';
            $tags = wp_get_post_terms($post_id, 'post_tag');

            if (!empty($tags)) {
                    $i = 1;
                    foreach ($tags as $tag) { 
                        if($i==1){
                            $tag_list .= $tag->name;
                        }else{
                            $tag_list .= ', '.$tag->name;
                        }    
                        $i++;
                    }
            }
            
            return $tag_list;
        }
		
		function remove_post(){
			if(isset($_POST['post_id']) && is_numeric($_POST['post_id'])){
				$post = get_post($_POST['post_id']);
				if(get_current_user_id() == $post->post_author){ echo 'ee';
					wp_delete_post($_POST['post_id']);
				}
			}  

			exit;
		}
    }
?>