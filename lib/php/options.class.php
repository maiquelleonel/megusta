<?php
    class options{
		static $menu;
		static $register;
		static $default;
		static $fields;

        function menu( ){

            if( is_array( self::$menu ) && !empty( self::$menu) ){
                

                foreach( self::$menu as $main => $items ){
                    
                    foreach( $items as $slug => $item ){
                        
                        switch( $main ){ 
                            default :{
								if( isset( $item['type'] ) ){
									if( $item['type'] == 'main' ){
										add_menu_page( $item['main_label'] , $item['main_label'] , 'administrator' , $main . '__' . $slug  , array( get_class() , $main . '__' . $slug ) , get_template_directory_uri() . '/lib/images/megusta.png' );
                                        
                                        //call_user_func_array( get_class() . '::' . $main . '__' . $slug, array_slice( array( 'name' , 'arguments' ) , 0, (int) 2 ) );
										$main_slug =  $main . '__' . $slug;
									}else{
                                        add_submenu_page( $main_slug , $item['label'] , $item['label'] , 'administrator' , $main . '__' . $slug , array( get_class() , $main . '__' . $slug )  );
									}
								}else{ 
                                    add_submenu_page( $main_slug , $item['label'] , __($item['label'],'cosmotheme') , 'administrator' , $main . '__' . $slug , array( get_class() , $main . '__' . $slug )  );
								}
                                break;
                            }
                        }
                    }
                }
            }
        }

        static function cosmothemes__general(){
            self::CallMenu( 'cosmothemes__general' );
        }
        static function cosmothemes__front_page(){
            self::CallMenu( 'cosmothemes__front_page' );
        }
        static function cosmothemes__layout(){
            self::CallMenu( 'cosmothemes__layout' );
        }
        static function cosmothemes__menu(){
            self::CallMenu( 'cosmothemes__menu' );
        }
        static function cosmothemes__styling(){
            self::CallMenu( 'cosmothemes__styling' );
        }
        static function cosmothemes__conference(){
            self::CallMenu( 'cosmothemes__conference' );
        }
        static function cosmothemes__blog_post(){
            self::CallMenu( 'cosmothemes__blog_post' );
        }

        static function cosmothemes__advertisement(){
            self::CallMenu( 'cosmothemes__advertisement' );
        }
		static function cosmothemes__upload(){
            self::CallMenu( 'cosmothemes__upload' );
        }
        static function cosmothemes__social(){
            self::CallMenu( 'cosmothemes__social' );
        }

        static function cosmothemes__slider(){
            self::CallMenu( 'cosmothemes__slider' );
        }

        static function cosmothemes___sidebar(){
            self::CallMenu( 'cosmothemes___sidebar' );
        }

        static function cosmothemes__stylos(){
            self::CallMenu( 'cosmothemes__stylos' );
        }

		static function cosmothemes__cosmothemes(){
            self::CallMenu( 'cosmothemes__cosmothemes' );
        }	
		
        static function CallMenu( $name ) {

            $slug           = $name;
            $items 			= explode( '__' , $slug );

            if( !isset( $items[1] ) ){
                exit();
            }

            $label          = isset( self::$menu[ $items[0] ][$items[1]]['label'] ) ? self::$menu[ $items[0] ][$items[1]]['label'] : '';
            $title          = isset( self::$menu[ $items[0] ][$items[1]]['title'] ) ? self::$menu[ $items[0] ][$items[1]]['title'] : '';
            $description    = isset( self::$menu[ $items[0] ][$items[1]]['desctiption'] ) ? self::$menu[ $items[0] ][$items[1]]['desctiption'] : '';
            $update         = isset( self::$menu[ $items[0] ][$items[1]]['update'] ) ? self::$menu[ $items[0] ][$items[1]]['update'] : true ;

            includes::load_css(  );
            includes::load_js(  );
            echo '<div class="admin-page">';
            self::get_header( $items[0] , $items[1] );
            self::get_page( __($title,'cosmotheme') , __($slug,'cosmotheme') , __($description,'cosmotheme') , __($update,'cosmotheme') );
            echo '</div>';
        }

        function register( ){
            if( is_array( self::$register ) && !empty( self::$register ) ){
                foreach( self::$register as $page => $groups ){
                    if( is_array( $groups ) && !empty( $groups ) ){
                        foreach( $groups as $group => $side ){
                            if( substr( $group , 0 , 1 ) != '_'){
                                register_setting( $page . '__' . $group , $group );
                            }
                        }
                    }
                }

            }
        }


        static function box(){
            if( is_array( self::$menu ) && !empty( self::$menu ) ){
                foreach( self::$menu  as $key => $value ){
                    switch( count( $value )  ){
                        case 7 : {
                            $value[0]( $value[1] , $value[2] , $value[3] , $value[4] , $value[5] , $value[6] );
                            break;
                        }
                    }
                }
            }
        }

		function get_header( $item , $current ){
			$result = '';
            $menu = self::$menu[ $item ];

			if(BRAND == ''){
				$brand_logo = get_template_directory_uri().'/images/freetotryme.png';
			}else{
				$brand_logo = get_template_directory_uri().'/images/cosmothemes.png';
			}
			
			if( function_exists( 'wp_get_theme' ) ){
                $ct = wp_get_theme();
            }else{
                $ct = current_theme_info();
            }
			
			$result .= '<div class="mythemes-intro">';
            $result .= '<img src="'.$brand_logo.'" />';
			$result .= '<span class="theme">'.$ct->title.' '.__('Version' , 'cosmotheme').': '.$ct->version.'</span>';
            $result .= '</div>';
			
			if( is_array( $menu ) ){
				$result .= '<div class="admin-menu">';
				$result .= '<ul>';
				foreach( $menu as $slug => $info){
                    $result .= '<li '. self::get_class( $slug , $current ) .'><a href="' . self::get_path( $item . '__' . $slug ) . '">' . get_item_label( __($info['label'],'cosmotheme') ) . '</a></li>';
				}
				$result .= '</ul>';
				$result .= '</div>';
			}

            echo $result;
		}
		
		function get_path( $slug ){
            $path = '?page=' . $slug;
            return $path;
        }
		
		function get_class( $slug , $current ){
            
            if( $current == $slug ){
                if( substr( $slug , 0 , 1 ) == '_' ){
                    $slug = substr( $slug , 1 , strlen( $slug ) );
                }
            
                $slug = str_replace( '_' , '-' , $slug  );
                
                return 'class="current ' . $slug . '"';
            }else{
                if( substr( $slug , 0 , 1 ) == '_' ){
                    $slug = substr( $slug , 1 , strlen( $slug ) );
                }
            
                $slug = str_replace( '_' , '-' , $slug  );
                
                return ' class="' . $slug . '"';
            }

        }

        function get_page( $title , $slug ,  $description = '' , $update = true ){
?>
            <div class="admin-content">
                <div class="title">
                    <h2><?php echo $title; ?></h2>
                    <?php
                        if( strlen( $description ) ){
                    ?>
                            <p><?php echo $description; ?></p>
                    <?php
                        }
                    ?>
                </div>
            <?php
                if( $update ){
            ?>
                    <form action="options.php" method="post">
            <?php
                        
                }
                        settings_fields( $slug );
						$items = explode( '__' , $slug );

                        _e(self::get_fields( $items[1] ),'cosmotheme');
                if( $update ){
            ?>
                        <div class="standard-generic-field submit">
                            <div class="field">
                                <input type="submit" value="Update Settings" />
                            </div>
                            <div class="clear"></div>
                        </div>
                    </form>
            <?php
                }else{
            ?>
                    <div class="record submit"></div>
            <?php
                }
            ?>
			</div>
<?php
        }

        function get_fields( $group ){
            $result = '';
            if( isset( self::$fields[ $group ] ) ){
                foreach( self::$fields[ $group ] as $side => $field ){
                    $field['topic'] = $side;
                    $field['group'] = $group;
                    if( !isset( $field['value'] ) ){
                        $field['value'] = self::get_value( $group , $side );
                    }

                    $field['ivalue'] = self::get_value( $group , $side );

                    /* special for upload-id*/
                    if( isset( $field['type'] ) ){
                        $type = explode( '--' , $field['type'] );
                        if( isset( $type[1] ) && $type[1] == 'upload-id' ){
							$option = self::get_value( $group );
                            $value_id = isset( $option[ $side .'_id' ] ) ? $option[ $side .'_id' ] : 0;
                            $field['value_id'] = $value_id;
                        }
                    }

                    $result .= fields::layout( $field );
                }
            }
			
            return $result;
        }

        

        function get_digit_array( $to , $from = 0 , $twodigit = false ){
            $result = array();
            for( $i = $from; $i < $to + 1; $i ++ ){
                if( $twodigit ){
                    $i = (string)$i;
                    if( strlen( $i ) == 1 ){
                        $i = '0' . $i;
                    }
                    $result[$i] = $i;
                }else{
                    $result[$i] = $i;
                }
            }

            return $result;
        }

        function get_value( $group , $side = null , $id = null){
			
            $values = @get_option( $group );
            if( is_array( $values ) ){
                if( strlen( $side ) ){
                    if( isset( $values[ $side ] ) ){
                        if( is_int( $id ) ){
                            if( isset( $values[ $side ][ $id ] ) ){
                                return $values[ $side ][ $id ];
                            }else{
                                if( isset( self::$default[ $group ][ $side ][ $id ] )){
                                    return self::$default[ $group ][ $side ][ $id ];
                                }else{
                                    return '';
                                }
                            }
                        }else{
                            return $values[ $side ];
                        }
                    }else{
                        if( isset( self::$default[ $group ][ $side ])){
                            return self::$default[ $group ][ $side ];
                        }else{
                            return '';
                        }
                    }
                }else{
                    return $values;
                }
            }else{
                if( strlen( $side ) ){
                    if( isset( self::$default[ $group ][ $side ] ) ){
                        if( is_int( $id ) ){
                            if( isset( self::$default[ $group ][ $side ][ $id ] ) ){
                                return self::$default[ $group ][ $side ][ $id ];
                            }else{
                                return '';
                            }
                        }else{
                            return self::$default[ $group ][ $side ];
                        }
                    }else{
                        return '';
                    }
                }else{
                    if( isset( self::$default[ $group ])){
                        return self::$default[ $group ];
                    }else{
                        return '';
                    }
                }
            }
        }

        function logic( $group , $side = null , $id = null ){
            $values = self::get_value( $group , $side , $id );
            
            if( !is_array( $values ) ){
                if( $values == 'yes' ){
                    return  true;
                }

                if( $values == 'no' ){
                    return false;
                }
            }

            return $values;
        }
        
    	function my_categories( $nr = -1  , $exclude = array() ){
            $categories = get_categories();

            $result = array();
            foreach($categories as $key => $category){
                if( $key == $nr ){
                    break;
                }
                if( $nr > 0 ){
                    if( !in_array( $category -> term_id , $exclude ) ){
                        $result[ $category -> term_id ] = $category -> term_id;
                    }
                }else{
                    if( !in_array( $category -> term_id , $exclude ) ){
                        $result[ $category -> term_id ] = $category -> cat_name;
                    }
                }
            }

            return $result;
        }
		
		function set_cosmo_news(){
			if(isset($_POST['msg_id'])){
				update_option($_POST['msg_id'].'_closed', 'disabled');
			}
			exit;
		}
    }

	class api_call{
		
		function getCosmoNews(){
			$key = 'cosmo_news_alert';

			$last_news = array();  
			// Let's see if we have a cached version
			$saved_cosmo_news_alert = get_transient($key);
			if ($saved_cosmo_news_alert !== false ){
				$last_news = $saved_cosmo_news_alert;
			}else{
				// If there's no cached version we ask is from Cosmothemes
				//$response = wp_remote_get("http://cosmothemes.com/api/news.php?key=D9a0ee79GEHdD");
				$response = wp_remote_get("http://dev.cosmothemes.com/tst/api/news.php?key=D9a0ee79GEHdD");
				//var_dump($response );
				if (is_wp_error($response))
				{
					// In case Cosmothemes is down we return the last successful info
					$saved_option = get_option($key);
					//var_dump($saved_option);
					if(is_array($saved_option) && sizeof($saved_option)){
						$last_news = get_option($key);
					}
				}
				else
				{
					// If everything's okay, parse the body and json_decode it

					$json = json_decode(wp_remote_retrieve_body($response));

					$responce_size = 0;
					if($json){
						foreach($json as $news ){
							$responce_size ++;
						}
						$counter = 0;	
						foreach($json as $index => $news ){
							$counter ++;
							if(  $responce_size == $counter  ){
								$last_news[$index] = $news;
							}
						}
					}	
					
					if(sizeof($last_news) ){	
						
							// Store the result in a transient, expires after 1 day
							// Also store it as the last successful using update_option
							set_transient($key, $last_news, 60*60*24); //1 day cache
							
							update_option($key, $last_news);
						
					
					}

				}

				
			}

			if(sizeof($last_news) ){
				
				foreach($last_news as $ind => $msg){
					$msg_key = $ind;
					$message = $msg;
				}	
		
				if(get_option($msg_key.'_closed') == ''){  

					$fn = "closeCosmoMsg(\'".trim($msg_key)."\');";	  
					$alert_msg1 =  '<div id="cosmo_news" >'.$message;
					$alert_msg1 .= '<span class="close_msg" onclick="'.$fn.'" >'.__('Close','cosmotheme').'</span>';   
					$alert_msg1 .= '</div>'; 
					
					/*insert the notification message in wphead */
					$result = '<script type="text/javascript">
								  jQuery(document).ready(function() {    
											jQuery("#wphead").append(\''.$alert_msg1.'\');	
									
								});	
								jQuery(document).ready(function() {    
											jQuery("#wpcontent").prepend(\''.$alert_msg1.'\');	
									
								});
							  </script>';  
							  
				}else{
					$result ='';	  
				}
				
			}else{
				$result ='';	
			}	  

			return $result;
		}
		
		function getLastThemeVersion(){
			$key = ZIP_NAME . '__theme_version';

			// Let's see if we have a cached version
			$saved_theme_version = get_transient($key);
			if ($saved_theme_version !== false){
				return $saved_theme_version;
			}else{
				// If there's no cached version we ask Twitter
				$response = wp_remote_get("http://cosmothemes.com/api/versions.php?key=D9a0ee79GEHdD&tn=".ZIP_NAME);
				if (is_wp_error($response))
				{
					// In case Twitter is down we return the last successful count
					return get_option($key);
				}
				else
				{
					// If everything's okay, parse the body and json_decode it
					$json = json_decode(wp_remote_retrieve_body($response));
					
					if(isset($json->version)){	
						$available_theme_version = $json->version;
						
						if(is_numeric($available_theme_version)){   
							// Store the result in a transient, expires after 1 day
							// Also store it as the last successful using update_option
							set_transient($key, $available_theme_version, 60*60*24); /*1 day cache*/
							
							update_option($key, $available_theme_version);
						}
						return $available_theme_version;
					}else{
						return;
					}

				}
			}

		}

		/*if there is available a newer version then we will return some js code that will be appended to the head*/  
		function compareVersions(){
			$last_version = self::getLastThemeVersion();
			
			if( function_exists( 'wp_get_theme' ) ){
               $theme_data = wp_get_theme();    
            }else{

                $theme_data = get_theme_data(get_stylesheet_uri());
            }
			$this_theme_version = $theme_data['Version'];
		  
			if(is_numeric($last_version) && is_numeric($this_theme_version) && $this_theme_version < $last_version){
				$alert_msg =  '<div id="cosmo_new_version">'.$theme_data["Name"].' '.__("version","cosmotheme").' '.$last_version.' '.__("is available, please update now.","cosmotheme").'</div>'; 
				
				/*insert the notification message in wphead */
				$result = '<script type="text/javascript">
							  jQuery(document).ready(function() {    
										jQuery("#wphead").append(\''.$alert_msg.'\');	
								
							});	
								jQuery(document).ready(function() {    
									jQuery("#wpcontent").prepend(\''.$alert_msg.'\');	
									
								});
						  </script>';  
				return $result;
			}
		}
	}
?>
