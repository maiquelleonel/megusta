<?php
    class widget_tweets extends WP_Widget {
        function widget_tweets() {
            $widget_ops = array( 'classname' => 'tweets', 'description' => 'Display Latest tweets' );
            parent::WP_Widget( false , _TN_ . ' : ' . __('Latest tweets','cosmotheme') , $widget_ops );

        }

        function form($instance) {
            if( isset($instance['title']) ){
                $title = esc_attr($instance['title']);
            }else{
                $title = null;
            }

            if( isset($instance['number']) ){
                $number = esc_attr($instance['number']);
            }else{
                $number = 10;
            }

            if( isset($instance['username']) ){
                $username = esc_attr($instance['username']);
            }else{
                $username = null;
            }
            
        	if( isset($instance['dynamic']) ){
                $dynamic = esc_attr( $instance['dynamic'] );
            }else{
                $dynamic = '';
            }
        ?>
         <p>
          <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title' , 'cosmotheme' ); ?>:</label>
          <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
        <p>
          <label for="<?php echo $this->get_field_id( 'username' ); ?>"><?php _e( 'Twitter User Name' , 'cosmotheme' ); ?>:</label>
          <input class="widefat" id="<?php echo $this->get_field_id( 'username' ); ?>" name="<?php echo $this->get_field_name( 'username' ); ?>" type="text" value="<?php echo $username; ?>" />
        </p>
        <p>
          <label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of latest tweets to show' , 'cosmotheme' ); ?>:</label>
          <input id="<?php echo $this->get_field_id( 'number' ); ?>"  size="3" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" />
        </p>
        <p>
        	<label for="<?php echo $this->get_field_id( 'dynamic' ); ?>"><?php _e( 'Animated' , 'cosmotheme' ); ?>:</label>
        	<input type="checkbox" id="<?php echo $this->get_field_id( 'dynamic' ); ?>"  <?php checked( $dynamic , true ); ?>  name="<?php echo $this->get_field_name( 'dynamic' ); ?>"  value="1" />
        </p>
        <?php
        }

        function update( $new_instance, $old_instance) {
            $instance = $old_instance;
            $instance['title']      = strip_tags($new_instance['title']);
            $instance['number']     = strip_tags($new_instance['number']);
            $instance['username']   = strip_tags($new_instance['username']);
            $instance['dynamic']   = strip_tags($new_instance['dynamic']);
            return $instance;
        }

        function widget($args, $instance) {
            extract( $args );
            if( !empty( $instance['title'] ) ){
               $title = trim( apply_filters('widget_title', $instance['title'] ) );
            }else{
               $title = __( 'Latest Tweets' , 'cosmotheme' );
            }

            if( isset($instance['number'])){
                $number = $instance['number'];
            }else{
                $number = 10;
            }


            if( isset($instance['username'])){
                $username = $instance['username'];
            }else{
                $username = null;
            }

        	if( isset($instance['dynamic']) && $instance['dynamic'] == 1){ 
                $dynamic = $instance['dynamic']; 
            }else{
                $dynamic = 0; 
            }
            
            echo $before_widget;
  

            if ( !empty( $title ) ) {
                    echo $before_title . $title . $after_title;
            }
            
            tweets_new::the_tweets( $username , $number, $dynamic );
            
            echo $after_widget;
        }
    }

  /*This is for test, if it will work well replace the old class with this one*/
  class tweets_new{
         function the_tweets( $username , $number, $dynamic ){
        	$feed_classes = 'dynamic';
        	if( $dynamic != 1 ){
        		$feed_classes = 'static';
        	}

			echo '<div class="cosmo-twit-container ' . $feed_classes . '">';
            $tweets = self::get_tweets( $username , $number , $classes = '' , $before = "<div class='tweet_item'><p>" , $after = "</p></div>" , $static_class = $feed_classes );
            echo $tweets[0];
			echo '</div>';
            echo $tweets[1];
        }

		
		function get_tweets( $username , $number , $classes = '' , $before = "<div class='tweet_item'><p>" , $after = "</p></div>", $static_class = '' ){
			/*
			* JSON list of tweets using:
			*         http://dev.twitter.com/doc/get/statuses/user_timeline
			* Cached using WP transient API.
			*        http://www.problogdesign.com/wordpress/use-the-transients-api-to-list-the-latest-commenter/
			*/
			
			// Configuration.
			$numTweets = $number;
			$name = $username;
			$transName = 'list-tweets'.$username; // Name of value in database.
			$cacheTime = 5; // Time in minutes between updates.
			
			// Do we already have saved tweet data? If not, lets get it.
			if(false === ($tweets = get_transient($transName) ) ||  get_transient($transName) == '') :    
			
				// Get the tweets from Twitter.
				$json = wp_remote_get("http://api.twitter.com/1/statuses/user_timeline.json?screen_name=$name&count=$numTweets&include_rts=1");
				
				if( !is_wp_error( $json ) ) {
					// Get tweets into an array.
					$twitterData = json_decode($json['body'], true);
					
					// Now update the array to store just what we need.
					// (Done here instead of PHP doing this for every page load)
					if( !isset($twitterData['error']) ){  
						foreach ($twitterData as $tweet) :
							// Core info.
							$name = $tweet['user']['name'];
							$permalink = 'http://twitter.com/#!/'. $name .'/status/'. $tweet['id_str'];
							
							/* Alternative image sizes method: http://dev.twitter.com/doc/get/users/profile_image/:screen_name */
							$image = $tweet['user']['profile_image_url'];
							
							// Message. Convert links to real links.
							$pattern = '/http:(\S)+/';
							$replace = '<a href="${0}" target="_blank" rel="nofollow">${0}</a>';
							$text = preg_replace($pattern, $replace, $tweet['text']);
							
							// Need to get time in Unix format.
							$time = $tweet['created_at'];
							$time = date_parse($time);
							$uTime = mktime($time['hour'], $time['minute'], $time['second'], $time['month'], $time['day'], $time['year']);
							$tweet_id = $tweet["id_str"];

							// Now make the new array.
							$tweets[] = array(
											'text' => $text,
											'name' => $name,
											'permalink' => $permalink,
											'image' => $image,
											'time' => $uTime,
											'id'=>	$tweet_id	
											);
						endforeach;
					}else{ 
						$tweets = false;
					}
					
					if(is_array($tweets) && count($tweets) > 0){
						// Save our new transient.
						set_transient($transName, $tweets, 60 * $cacheTime);
						set_transient($transName.'_old', $tweets, 100 * 60 * $cacheTime);	
					}
				} /*EOF !is_wp_error( $json ) */	
			endif; 
			
			/*If tweets are unavailable, we show the old version of tweets*/	  
			if(!$tweets){
				$tweets = get_transient($transName.'_old');
			}
			
			echo '<div class="cosmo_twitter">
				  <div class="slides_container">';
			// Now display the tweets.
			  
			if($tweets){ 
				foreach($tweets as $t) : ?>
					
					<?php echo $before ?>
								<?php echo $t['name'] . ': '. $t['text']; ?>
								<span class="tweet-time date"><?php echo human_time_diff($t['time'], current_time('timestamp')); ?> ago</span>
							
					<?php echo $after ?>
					  
				<?php endforeach; 
			}else{
				echo $before;
				if($username == ''){
					_e('Please add the Twitter user name' , 'cosmotheme');
				}else{
					_e('Unable to read tweets !!!' , 'cosmotheme');
				}
				
				echo $after;

			}
				echo '</div>
				</div>   '; 
				echo '<a class="i_join_us '.$static_class.'" href="http://twitter.com/'.$username.'" title="'.__('Join the conversation','cosmotheme').'">'.__('Join the conversation','cosmotheme').'</a>';
		}

        
        

		// Use this function to retrieve the followers count
		function followers_count($screen_name = 'cosmothemes')
		{
			$key = 'my_followers_count_' . $screen_name;

			// Let's see if we have a cached version
			$followers_count = get_transient($key);
			if ($followers_count !== false)
				return $followers_count;
			else
			{
				// If there's no cached version we ask Twitter
				$response = wp_remote_get("http://api.twitter.com/1/users/show.json?screen_name={$screen_name}");
				if (is_wp_error($response))
				{
					// In case Twitter is down we return the last successful count
					return get_option($key);
				}
				else
				{
					// If everything's okay, parse the body and json_decode it
					$json = json_decode(wp_remote_retrieve_body($response));
					$count = $json->followers_count;

					if(is_numeric($count)){   
						// Store the result in a transient, expires after 1 day
						// Also store it as the last successful using update_option
						set_transient($key, $count, 60*60); /*1 h cache*/
						update_option($key, $count);
					}
					return $count;
				}
			}
		}

    }

?>