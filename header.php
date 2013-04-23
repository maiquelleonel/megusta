<?php 
	$fb_id = options::get_value( 'social' , 'facebook' );
    if( strlen( trim( $fb_id ) ) ){
        $fb['likes'] = social::pbd_get_transient($name = 'facebook',$user_id=$fb_id,$cacheTime = 120); /*cache - in minutes*/
        $fb['link'] = 'http://facebook.com/people/@/'  . $fb_id ;
    }
?>  
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?> xmlns:fb="http://www.facebook.com/2008/fbml">

    <head>
        <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
        <meta name="robots"  content="index, follow"/>
		<meta name="description" content="<?php echo get_bloginfo('description'); ?>"/> 
		<meta name="msvalidate.01" content="E88B4A1A153C86695E5B056B33C8A96C" />
		<?php if(is_single() || is_page()){ ?>
			<meta property='og:title' content='<?php the_title() ?>' />
			<meta property='og:site_name' content='<?php echo get_bloginfo('name'); ?>' />
			<meta property='og:url' content='<?php the_permalink() ?>' />
			<meta property='og:type' content='article' />
			<meta property='og:locale' content='en_US' />
			<meta property="og:description" content="<?php 
					$post = $wp_query -> post;
					if( trim( $post -> post_excerpt ) != '' ){
						$descrip = strip_tags( $post -> post_excerpt );
					} elseif ( trim( $post -> post_content ) != '' ) {
						$descrip = strip_tags( $post -> post_content );
					} else {
						$descrip = get_bloginfo('description');
					}
					$descrip = str_replace( '"' , '' , $descrip );
					$descrip = preg_replace( '|\[(.+?)\](.+?\[/\\1\])?|s', '', $descrip);
					$descripwords = preg_split( '/[\n\r\t ]+/' , $descrip , -1 , PREG_SPLIT_NO_EMPTY );
					array_pop( $descripwords );
					$descrip = implode( ' ' , $descripwords ) ;
					$descrip_more = '';
					if ( strlen($descrip) > 150 ) {
						$descrip = substr( $descrip , 0 , 150);
						$descrip_more = ' ...';
					}
					echo $descrip . $descrip_more;
					?>"/>
			<?php 
                if(options::get_value( 'social' , 'facebook_app_id' ) != ''){
                    ?><meta property='fb:app_id' content='<?php echo options::get_value( 'social' , 'facebook_app_id' ); ?>'><?php
                }
                
				global $post;
				$src  = wp_get_attachment_image_src( get_post_thumbnail_id( $post -> ID ) , 'thumbnail' );
				if($src[0] != ''){
					echo '<meta property="og:image" content="'.$src[0].'"/>'; 
				}else{
					echo '<meta property="og:image" content="'.get_template_directory_uri().'/images/bg.white.png"/>'; 
				
				}
				echo ' <link rel="image_src" href="'.$src[0].'" / >'; 			
				wp_reset_query();	
            }else{ ?>
				
				<meta property='og:site_name' content='<?php echo get_bloginfo('name'); ?>' />
				<meta property='og:url' content='<?php echo home_url() ?>' />
				<meta property='og:type' content='blog' />
				<meta property='og:locale' content='en_US' />
				<meta property="og:description" content="<?php echo get_bloginfo('description'); ?>"/>
		<?php
			}
        ?>

        <title><?php bloginfo('name'); ?> &raquo; <?php bloginfo('description'); ?><?php if ( is_single() ) { ?><?php } ?><?php wp_title(); ?></title>

        <?php
            if( strlen( options::get_value( 'styling' , 'favicon' ) ) ){
                $path_parts = pathinfo( options::get_value( 'styling' , 'favicon' ) );
                if( $path_parts['extension'] == 'ico' ){
        ?>
                    <link rel="shortcut icon" href="<?php echo options::get_value( 'styling' , 'favicon' ); ?>" />
        <?php
                }else{
        ?>
                    <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico" />
        <?php
                }
            }else{
        ?>
                <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico" />
        <?php
            }
        ?>
        
        <link rel="profile" href="http://gmpg.org/xfn/11" />

        <!-- ststylesheet -->
        <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="all" />
        <link href='http://fonts.googleapis.com/css?family=PT+Sans+Narrow:400,700&amp;subset=latin,cyrillic' rel='stylesheet' type='text/css' />

        <?php if( options::get_value( 'styling' , 'logo_type' ) == 'text' ) { ?>
        	<link href='http://fonts.googleapis.com/css?family=<?php  echo str_replace(' ' , '+' , trim( options::get_value( 'styling' , 'logo_font_family' ) ) );?>' rel='stylesheet' type='text/css' />
        <?php } ?>

        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
		<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/lib/css/shortcode.css" />

		<link rel="stylesheet" type="text/css" href="<?php echo home_url()?>/wp-admin/css/farbtastic.css" />
			
        <!-- javascript -->
        <?php 
			wp_enqueue_script( "jquery" );	
			if ( is_singular() ){ wp_enqueue_script( "comment-reply" ); } 
			wp_register_script( 'actions', get_template_directory_uri().'/lib/js/actions.js' , array( 'jquery' , 'media-upload' , 'thickbox' ) );
			wp_enqueue_script('media-upload');
            wp_enqueue_script('thickbox'); 
            wp_enqueue_script( 'actions' );

			wp_enqueue_style( 'ui-lightness');
            wp_enqueue_style('thickbox');
            
            wp_head();

        ?>

        <script src="<?php echo get_template_directory_uri(); ?>/js/jquery.hoverIntent.js" type="text/javascript"></script>
        <script src="<?php echo get_template_directory_uri(); ?>/js/jquery.superfish.js" type="text/javascript"></script>
        <script src="<?php echo get_template_directory_uri(); ?>/js/jquery.supersubs.js" type="text/javascript"></script>
        <script src="<?php echo get_template_directory_uri(); ?>/js/jquery.mosaic.1.0.1.min.js" type="text/javascript" ></script>
 
		<script src="<?php echo get_template_directory_uri(); ?>/js/jquery.prettyPhoto.js" type="text/javascript"></script>
        <script src="<?php echo get_template_directory_uri(); ?>/js/prettyPhoto.settings.js" type="text/javascript"></script>
		<script src="<?php echo get_template_directory_uri(); ?>/js/jquery.tabs.pack.js" type="text/javascript"></script>
        <script src="<?php echo get_template_directory_uri(); ?>/js/jquery.cookie.js" type="text/javascript" ></script>
		
		<!-- the following scripts:  scrollTo  are used for hot keys navigation-->
		<script src="<?php echo get_template_directory_uri(); ?>/js/jquery.scrollTo-1.4.2-min.js" type="text/javascript"></script>
		<script src="<?php echo get_template_directory_uri(); ?>/js/slides.min.jquery.js" type="text/javascript" ></script>

		<script src="<?php echo get_template_directory_uri(); ?>/js/functions.js" type="text/javascript"></script>
        
        <script src="<?php echo get_template_directory_uri(); ?>/lib/js/meta.js" type="text/javascript"></script>
        <script src="<?php echo get_template_directory_uri(); ?>/lib/js/actions.js" type="text/javascript"></script>

        <?php
            if( is_single() && options::logic( 'general' , 'enb_likes' ) ){
                ?><script src="<?php echo get_template_directory_uri(); ?>/js/voteaction.js" type="text/javascript"></script><?php
            }
        ?>
        
		<?php 
        	$siteurl = get_option('siteurl');
                if( !empty($siteurl) ){
                    $farbtastic_url = rtrim( $siteurl , '/') . '/wp-admin/js/farbtastic.js' ;
                }else{
                    $farbtastic_url = home_url('/wp-admin/js/farbtastic.js');
                }
        ?>
        <script type="text/javascript" src="<?php echo $farbtastic_url;?>"></script> <!-- for color picker -->
        <?php wp_enqueue_style( 'farbtastic' ); ?>
        
        
        <!-- init ajaxurl -->
        <script type="text/javascript">
			<?php
                $siteurl = get_option('siteurl');
                if( !empty($siteurl) ){
                    $siteurl = rtrim( $siteurl , '/') . '/wp-admin/admin-ajax.php' ;
                }else{
                    $siteurl = home_url('/wp-admin/admin-ajax.php');
                }
            ?>

            var ajaxurl = "<?php echo $siteurl; ?>";
            var themeurl = "<?php echo get_template_directory_uri(); ?>";
        </script>
        <?php
			if( options::logic( 'styling' , 'automat' ) && !isset($_COOKIE["megusta_day_night"]) ){
                ?>
                <script type="text/javascript">
                    jQuery(function(){
                        var currentTime = new Date()
                        var t = currentTime.getHours();
                        if( t > 6 && t < 18 ){
                            if( jQuery('body').hasClass('night') ){
                                jQuery('body').removeClass('night');
                            }
                            
                            jQuery('body').addClass('day'); 
                        }else{
                            if( jQuery('body').hasClass('day') ){
                                jQuery('body').removeClass('day');
                            }

                            jQuery('body').addClass('night');

                        }
                    }); 
                </script>
                <?php
               
            }

            $background_color = '';
            
            if(get_background_image() == '' && !isset($_COOKIE["megusta_day_night"]) && get_bg_image() != ''){  
            	$background_img = 'background-image: url('.get_template_directory_uri().'/lib/images/pattern/'.get_bg_image().');';
				/*if day or night images are set then we will add 'background-attachment:fixed'   */
				if(strpos(get_bg_image(),'.jpg')){
					$background_img .= ' background-attachment:fixed'; 
				}	
            }else{
            	$background_img = '';
            }	
            if(get_content_bg_color() != ''){
				$background_color = "background-color: " . get_content_bg_color() . "; ";
			}
            
			
        ?>
        
        <script type="text/javascript">
			/*redirect to post item page*/
			jQuery(document).ready(function(){
				<?php $post_item_page = get_page_by_title('Post Item'); ?>
				var post_item_page = "<?php echo get_page_link($post_item_page->ID)?>";
				jQuery('.simplemodal-submit').click(function() { 
					jQuery('[name="redirect_to"]').val(post_item_page);
				})
			});
			
			
	        	<?php 
	        	if ( options::logic( 'styling' , 'front_end' ) ){
	        	?>		
	        	 
	        		jQuery(document).ready(function() { 
	        	    	jQuery('.style_switcher input[id^="pick_"]').each(function(index) { 
	        			    
	        			    var farbtastic;
	        				var $obj = this;
	        				(function(jQuery){
	        					var pickColor = function(a) { 
	        						farbtastic.setColor(a);
	        						jQuery('#pick_' + jQuery($obj).attr('op_name') ).val(a);
	        						jQuery('#link_pick_' + jQuery($obj).attr('op_name') ).css('background-color', a); 
	        						setPickedColor(a,jQuery($obj).attr('zone'));
	        					};
	        				
	        					jQuery(document).ready( function() {
	        					
	        						farbtastic = jQuery.farbtastic('#colorPickerDiv_'  + jQuery($obj).attr('op_name') , pickColor);
	        				
	        						pickColor( jQuery('#pick_' + jQuery($obj).attr('op_name') ).val() );
	        				
	        						jQuery('#link_pick_' + jQuery($obj).attr('op_name') ).click( function(e) {
	        							jQuery('#colorPickerDiv_'  + jQuery($obj).attr('op_name') ).show();
	        							e.preventDefault();
	        						});
	        				
	        						jQuery('#pick_' + jQuery($obj).attr('op_name') ).keyup( function() { 
	        							var a = jQuery('#pick_' + jQuery($obj).attr('op_name') ).val(),
	        								b = a;
	        				
	        							a = a.replace(/[^a-fA-F0-9]/, '');
	        							if ( '#' + a !== b )
	        								jQuery('#pick_' + jQuery($obj).attr('op_name') ).val(a);
	        							if ( a.length === 3 || a.length === 6 )
	        								pickColor( '#' + a );
	        						});
	        				
	        						jQuery(document).mousedown( function() {
	        							jQuery('#colorPickerDiv_'  + jQuery($obj).attr('op_name')).hide();
	        						});
	        					});
	        				})(jQuery);
	        				
	        			});
	        		        
	        	        
	        	    });
				<?php 
	        		}
	        	?>
	        	
		        
		        function changeBgImage(rd_id){ 
		        	
					if(jQuery("#"+rd_id).val() != 'day' && jQuery("#"+rd_id).val() != 'night'){ 
						jQuery('body').css('background-image', 'url(<?php echo get_template_directory_uri(); ?>/lib/images/pattern/pattern.'+jQuery("#"+rd_id).val()+'.png)' );
					}else{
						jQuery('body').css('background-image', 'url(<?php echo get_template_directory_uri(); ?>/lib/images/pattern/pattern.'+jQuery("#"+rd_id).val()+'.jpg)' );
						jQuery('body').css('background-attachment', 'fixed' );  

					}
					/*for day and night we don't need bg image in the footer*/
					if(jQuery("#"+rd_id).val() != 'day' && jQuery("#"+rd_id).val() != 'night'){  
						jQuery('.b_f_c').css('background-image', 'url(<?php echo get_template_directory_uri(); ?>/lib/images/pattern/pattern.'+jQuery("#"+rd_id).val()+'.png)' );
					}else{
						jQuery('.b_f_c').css('background-image','none');
					}
	
		        	jQuery.cookie("megusta_bg_image",jQuery('#'+rd_id).val(), {expires: 365, path: '/'});
		        	jQuery.cookie("megusta_day_night",null, {expires: 365, path: '/'});    
                    return false;
			    }	

		        function doDayNight(obj){
		        	if(jQuery(obj).attr('mode') == 'night' ){  
		        		jQuery('body').addClass('day');
		        		jQuery('body').removeClass('night');
		        		jQuery('body').removeAttr("style");
						jQuery('.b_f_c').addClass('day'); 
						jQuery('.b_f_c').removeClass('night'); 
						//jQuery('.b_f_c').removeAttr("style");  
						jQuery('.b_f_c').css("background-image","none");
		        		jQuery(obj).attr('mode','day'); 
		        		jQuery(obj).addClass('day_mode');
		        		jQuery(obj).removeClass('night_mode');
		        		jQuery('#day_night_label').html('<?php _e('Day mode','cosmotheme'); ?>');
		        		jQuery.cookie("megusta_day_night",'day', {expires: 365, path: '/'});
		        	}
		        	else{ 
		        		jQuery('body').addClass('night');
		        		jQuery('body').removeClass('day');
		        		jQuery('body').removeAttr("style");
						jQuery('.b_f_c').addClass('night'); 
						jQuery('.b_f_c').removeClass('day'); 
						//jQuery('.b_f_c').removeAttr("style");  
						jQuery('.b_f_c').css("background-image","none");  
		        		jQuery(obj).attr('mode','night');
		        		jQuery(obj).addClass('night_mode');
		        		jQuery(obj).removeClass('day_mode');
		        		jQuery('#day_night_label').html('<?php _e('Night mode','cosmotheme'); ?>');
		        		jQuery.cookie("megusta_day_night",'night', {expires: 365, path: '/'});
		        	}
		        }
        </script>
        
		<?php if( options::get_value( 'styling' , 'logo_type' ) == 'text' ) {
			$logo_font_family = explode('&',options::get_value('styling' , 'logo_font_family'));
			$logo_font_family = $logo_font_family[0];
			$logo_font_family = str_replace( '+',' ',$logo_font_family );
		?>
			<style type="text/css">
				div.logo h1 a {
				font-family: '<?php echo $logo_font_family ?>', arial, serif !important;
				font-size: <?php echo options::get_value('styling' , 'logo_font_size')?>px;
				font-weight: <?php echo options::get_value('styling' , 'logo_font_weight')?>;
			}
			</style>
		<?php } ?>
    </head>
    <?php
    	$classes = de_boxed();
    	if(isset($_COOKIE["megusta_day_night"])){ 
    		$classes .= ' '.$_COOKIE["megusta_day_night"];
    	}
    ?>
  
<body <?php body_class( $classes ); ?> style="<?php echo $background_color ; ?> <?php echo $background_img; ?>">
    
    <div class="b_body" id="wrapper" >
		<div class="b_body_c">
        <div id="fb-root"></div>
		
        <?php
            if( options::logic( 'general' , 'fb_comments' ) ){
                if(options::get_value( 'social' , 'facebook_app_id' ) != ''){
        ?>
                    <?php
                        if( is_user_logged_in () ){
                    ?>
                            <script src="http://connect.facebook.net/en_US/all.js#xfbml=1" type="text/javascript" id="fb_script"></script>
                            <script type="text/javascript">
                                FB.getLoginStatus(function(response) {
                                    if( typeof response.status == 'unknown' ){
                                        jQuery(function(){
                                            jQuery.cookie('fbs_<?php echo options::get_value( 'social' , 'facebook_app_id' ); ?>' , null , {expires: 365, path: '/'} );
                                        });
                                    }else{
                                        if( response.status == 'connected' ){
                                            jQuery(function(){
                                                jQuery('#fb_script').attr( 'src' ,  document.location.protocol + '//connect.facebook.net/en_US/all.js#appId=<?php echo options::get_value( 'social' , 'facebook_app_id' ); ?>&amp;xfbml=1' );
                                            });
                                        }
                                    }
                                });
                            </script>
                    <?php
                        }else{
                    ?>
                            <script src="http://connect.facebook.net/en_US/all.js#xfbml=1" type="text/javascript"></script>
                    <?php
                        }
                    ?>
        <?php
                }else{
        ?>
                    <script src="http://connect.facebook.net/en_US/all.js#xfbml=1" type="text/javascript"></script>

        <?php   }
            }else{
		?>
				<script src="http://connect.facebook.net/en_US/all.js#xfbml=1" type="text/javascript" id="fb_script"></script>
		<?php		
			}
            
            if( !is_author() ){
        ?>
                <script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script>      <!-- for FB share button -->
        <?php
            }
        ?>
		<?php get_template_part( 'style_switcher' ); ?>
			<header class="b_head clearfix" id="header"><!--Header starts here-->
                
				<div class="b_page clearfix">
					<div class="branding">
						<div class="logo b w_220"><!--Logo starts here-->
                            <?php 
                                if( (int) options::get_value( 'general' , 'random_page'  ) > 0 ){
                            ?>
                                    <a href="<?php echo get_permalink( options::get_value( 'general' , 'random_page'  ) ); ?>" class="random-link hidden"></a>
                            <?php
                                }
                            ?>
							<?php if( options::get_value( 'styling' , 'logo_type' ) == 'text' ) { ?>
                                    <h1><a href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?> <span><?php bloginfo('description'); ?></span></a></h1>
							<?php }elseif(options::get_value( 'styling' , 'logo_type' ) == 'image' && options::get_value( 'styling' , 'logo_url' ) == '' ){ ?>
                                    <h1>
                                        <a href="<?php echo home_url(); ?>">
                                            <img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" />
                                        </a>
                                    </h1>
							<?php }else{?>
                                    <h1>
                                        <a href="<?php echo home_url(); ?>">
                                            <img src="<?php echo options::get_value( 'styling' , 'logo_url' ) ?>" >
                                        </a>
                                    </h1>
							<?php } ?>
						</div>
						<nav id="access" role="navigation">
                            <div class="cosmo-icons b <?php if( (is_user_logged_in() ) && options::get_value('general', 'user_login') == 'yes' ) {echo 'w_255'; }else{echo 'w_380';} ?>"><!--Menu starts here-->
                                <?php echo menu( 'megusta' , array( 'class' => 'fr' , 'number-items' => options::get_value( 'menu' , 'header' )  , 'current-class' => 'active' ) ); ?>
							</div>
                            <?php echo user_link_menu(); ?>
						</nav>
						<div class="searchform b w_300"><!--Search form starts here-->
							<!-- search form -->
                            <?php get_template_part( 'searchform' ); ?>
						</div>
					</div>
                    <?php
                        $dclasses = 'hidden';
                        if( options::logic( 'front_page' , 'enb_float_box' ) && (is_front_page() || is_home() ) ){
                            if( !isset( $_COOKIE['tooltip'] ) ||  ( isset( $_COOKIE['tooltip'] )  && $_COOKIE['tooltip'] != 'closed' ) ){
                    ?>
                                <div class="b w_940" id="hide-this"><!--Floating box-->
                                    <div class="top-pointer">&nbsp;</div><div class="message"><a href="#hide-this" rel="nofollow" style="float:right;cursor:pointer" class="close" >close me</a><?php echo options::get_value( 'front_page' , 'float_box' ) ?></div>
                                </div>
                    <?php
                            }else{
                                $dclasses = '';
                            }
                        }else{
                            $dclasses = '';
                        }
                    ?>
                    <div class="b w_940  header-delimiter <?php echo $dclasses; ?>">
						<p class="delimiter">&nbsp;</p>
					</div>

                    <?php echo menu( 'header_menu' , array( 'class' => 'sf-menu' , 'number-items' => options::get_value( 'menu' , 'header' )  , 'current-class' => 'active' ) ); ?>

                    <!-- adds zone 1 -->
                    <?php
                        if( strlen( options::get_value( 'advertisement' , 'logo' ) )  > 0 ){
                    ?>
                            <div class="b w_940 cosmo-ads zone-1">
                                <?php echo options::get_value( 'advertisement' , 'logo' ); ?>
                            </div>
                    <?php
                        }
                    ?>
                    <?php
                        if( options::logic( 'general' , 'breadcrumbs' ) && !is_front_page() ){
                            echo '<div class="b w_940 breadcrumbs">';
                            echo '<ul>';
                            dimox_breadcrumbs();
                            echo '</ul>';
                            echo '</div>';
                        }
                    ?>
				</div>
			</header>
            
