<?php
    class resources{
        static $type;
        static $labels;
        static $taxonomy;
		static $box;
        function register(){
            if( !empty( self::$type ) ){
                foreach( self::$type as $res => $args ){
                    if( empty( $args )  ){
                        self::box( $res );
                    }else{
                        $label = self::$labels[ $res ];
                        $args['labels'] = $label;
                        $args['rewrite'] = array( 'slug' => $res , 'with_front' => false );
                        unset( $args['__on_front_page'] );
                        $args['has_archive'] = $res;
                        register_post_type( $res , $args );
                        self::taxonomy( $res );
                        self::box( $res );
                    }
                }
            }
        }

        function taxonomy( $res ){
            if( isset( self::$taxonomy[ $res ] ) ){
                foreach( self::$taxonomy[ $res ] as $tax => $args ){
                    register_taxonomy( $res . '-' . $tax , array( $res ) , $args );
                }
            }
        }
		//add_meta_box(	'gallery-type-div', __('Gallery Type','cosmotheme'),  'gallery_type_metabox', 'gallery', 'normal', 'low');
		function box( $res ){
			if( isset( self::$box[ $res ] ) ){
				foreach( self::$box[ $res ] as $box => $args ){
                    add_action('admin_init', array( get_class() , 'addbox_' . $res . '_' . $box ) , 1 );
				}
			}
		}

        /* replace callStatic  with Callbox */
        function post_shcode(){
            self::CallBox( 'post_shcode' );
        }

        function post_layout(){
            self::CallBox( 'post_layout' );
        }

        function post_settings(){
            self::CallBox( 'post_settings' );
        }

        function post_format(){
            self::CallBox( 'post_format' );
        }

        function post_source(){
            self::CallBox( 'post_source' );
        }

        function page_shcode(){
            self::CallBox( 'page_shcode' );
        }

        function page_layout(){
            self::CallBox( 'page_layout' );
        }

        function page_settings(){
            self::CallBox( 'page_settings' );
        }

       
        function addbox_post_shcode(){
            self::CallBox( 'addbox_post_shcode' );
        }

        function addbox_post_layout(){
            self::CallBox( 'addbox_post_layout' );
        }

        function addbox_post_settings(){
            self::CallBox( 'addbox_post_settings' );
        }

        function addbox_post_format(){
            self::CallBox( 'addbox_post_format' );
        }

        function addbox_post_source(){
            self::CallBox( 'addbox_post_source' );
        }

        function addbox_page_shcode(){
            self::CallBox( 'addbox_page_shcode' );
        }

        function addbox_page_layout(){
            self::CallBox( 'addbox_page_layout' );
        }

        function addbox_page_settings(){
            self::CallBox( 'addbox_page_settings' );
        }

        
        static function  CallBox( $name , $args = null ) {
			global $post;
            $items = explode( '_' , $name );

            if( $items[0] == 'addbox' ){

                foreach( self::$box[ $items[1] ] as $box => $args ){
                    add_meta_box( $items[1] . '_' . $box , $args[0] , array( get_class() , $items[1] . '_' . $box ) , $items[1] , $args[1] , $args[2] );

                    if( isset( $_POST[ $box ] ) ){
                        if( isset( $args[ 'update' ] ) && $args[ 'update' ] ){
                            $new_value = $_POST[ $box ];
                            if( is_array( $args['content'] ) ){
                                foreach( $args['content'] as $name => $fields ){
                                    $type = explode( '--' , $fields['type'] );
                                    if( isset( $type[1] ) && $type[1] == 'checkbox' ){
                                        if( !isset( $new_value[ $name ] ) ){
                                            $new_value[ $name ] = '';
                                        }
                                    }
                                }
                            }
                            
                            if( isset( $_POST[ 'post_ID' ] ) ){
								$metadata=Array();
								if(isset($_POST['attachments_type']))
								  {
									if(isset($_POST['attachments']))
									  {
										foreach($_POST['attachments'] as $attach_id)
										  {
											$attachment_post=get_post($attach_id);
											$attachment_post->post_parent=$_POST['post_ID'];
											wp_update_post($attachment_post);
										  }
										switch($_POST['attachments_type'])
										  {
											case 'image':
												$metadata=array("type" => 'image', 'images'=>$_POST['attachments']);	
											  break;
											case 'video':
												foreach($_POST['attachments'] as $index=>$attach_id)
												  {
													if($attach_id==$_POST['featured_video'])
													  {
														$_POST['featured_video_id']=$attach_id;
														unset($_POST['attachments'][$index]);
														if(isset($_POST['attached_urls'][$attach_id]))
														  {	
															set_post_thumbnail($_POST['post_ID'],$attach_id);
															$_POST['featured_video_url']=$_POST['attached_urls'][$attach_id];
															unset($_POST['attached_urls'][$attach_id]);
														  }
													  }
												  }
												$metadata=array("type"=>"video", "video_ids"=>$_POST['attachments'], "feat_id"=>$_POST['featured_video_id'], "feat_url"=>$_POST['featured_video_url']);
											  
												if(isset($_POST['attached_urls']))
												  $metadata["video_urls"]=$_POST["attached_urls"];
											  break;
											case 'audio':
											  $metadata = array("audio"=>  $_POST['attachments'], "type" => 'audio');
											break;
											case 'link':
											  $metadata = array("link"=>  $_POST['file'], "type" => 'link', 'link_id' => $_POST['attachments']);
											break;
										  }
									  }
									else
									  {
										$metadata=array("type"=>$_POST['attachments_type']);
									  }
								  }
								
								meta::set_meta($_POST['post_ID'] , 'format' , $metadata );

                                meta::set_meta( $_POST[ 'post_ID' ] , $box , $new_value );

                                if( isset( $_POST['format'] ) ){
                                    set_post_format( $_POST[ 'post_ID' ] , $_POST['format']['type'] );
                                    $_POST['post_format'] = $_POST['format']['type'];
                                }

                                                                
                                if( isset( $_POST['format']['type'] ) && !empty( $_POST['format']['video'] ) ){
                                    
                                    if( post::isValidURL( $_POST['format']['video'] ) ){
                                        $vimeo_id = post::get_vimeo_video_id( $_POST['format']['video'] );
                                        $youtube_id = post::get_youtube_video_id( $_POST['format']['video'] );
                                        $video_type = '';
                                        if( $vimeo_id != '0' ){
                                            $video_type = 'vimeo';
                                            $video_id = $vimeo_id;
                                        }

                                        if( $youtube_id != '0' ){
                                            $video_type = 'youtube';
                                            $video_id = $youtube_id;
                                        }

                                        if( !has_post_thumbnail( $_POST[ 'post_ID' ] ) && !empty( $video_type ) ){
                                            $video_image_url = post::get_video_thumbnail( $video_id , $video_type );

                                            /*attach an image to the post*/
                                            $upload =  media_sideload_image( urldecode( $video_image_url ) , $_POST[ 'post_ID' ] );

                                            /* set attached image as featured image */
                                            // Associative array of attachments, as $attachment_id => $attachment
                                            $attachments = get_children( array('post_parent' => $_POST[ 'post_ID' ] , 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID') );
                                            foreach ($attachments as $index => $id) {
                                                $attachment = $index;
                                            }

                                            set_post_thumbnail( $_POST[ 'post_ID' ] , $attachment );
                                        }
                                    }
                                }
                            }
                            
                        }
                    }
                }
            }else{
                if( isset( self::$box[ $items[0] ][ $items[1] ][ 'callback' ] ) ){
                    
                    if( self::$box[ $items[0] ][ $items[1] ][ 'callback' ][0] == 'get_meta_records' ){
                        $fn_result =  meta::get_meta_records( $post -> ID , $items );

                        if( !empty( $fn_result ) ){
                            $classes = "postbox";
                        }else{
                            $classes = '';
                        }

                        echo '<div id="box_' . $items[0] .'_'. $items[1] .'" class="' . $classes . '" >';
                        echo $fn_result;
                        echo '</div>';
                        
                    }else{                    
                        $fn = self::$box[ $items[0] ][ $items[1] ][ 'callback' ][0];
                        $fn_result = $fn( $post -> ID , self::$box[ $items[0] ][ $items[1] ][ 'callback' ][1] ) ;
                        
                        if( !empty( $fn_result ) ){
                            $classes = "postbox";
                        }else{
                            $classes = '';
                        }

                        echo '<div id="box_' . $items[0] .'_'. $items[1] .'" class="' . $classes. '" >';
                        echo $fn_result;
                        echo '</div>';
                        
                    }
                    
                }

                if( isset( self::$box[ $items[0] ][ $items[1] ][ 'includes' ] ) ){
                    include get_template_directory(). '/lib/php/' . self::$box[ $items[0] ][ $items[1] ][ 'includes' ];
                }

                if( isset( self::$box[ $items[0] ][ $items[1] ][ 'content' ] ) ){

                    if( isset( self::$box[ $items[0] ][ $items[1]][ 'box'  ] ) ){
                        $box = self::$box[ $items[0] ][ $items[1]][ 'box'  ];
                    }else{
                        $box = $items[1];
                    }

					echo '<div id="form' . $box . '">';


                    foreach( self::$box[ $items[0] ][ $items[1]][ 'content'  ] as $side => $field ){
                        $field['side'] 		= $side;
                        $field['box']  		= $box;
						$field['res']  		= $items[0];
						$field['post_id']  	= $post -> ID;
                        $field['pos']  		= self::$box[ $items[0] ][ $items[1]][1];
                        $meta  = meta::get_meta( $post -> ID , $box );
                        $value = isset( $meta[ $side ] ) ? $meta[ $side ] : '';
                        if( !isset( $field['value'] ) ){
                            $field['value'] = $value;
                        }

                        if( !isset( $field['ivalue'] ) ){
                            $field['ivalue'] = $value;
                        }

                        

                        /* special for upload-id*/
                        $type = explode( '--' , $field['type'] );
                        if( isset( $type[1] ) && $type[1] == 'upload-id' ){
                            $value_id = isset( $meta[ $side .'_id' ] ) ? $meta[ $side .'_id' ] : 0;
                            $field['value_id'] = $value_id;
                        }

                        $field['topic']  	= $side;
						$field['group']  	= $box;

                        echo fields::layout( $field );
                    }
					echo '</div>';
                }
            }
        }
    }
?>
