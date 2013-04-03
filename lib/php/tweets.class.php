<?php
    include  ABSPATH . WPINC . '/class-feed.php';

    class tweets{
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

        function fetch_feed( $url ) {

            $feed = get_transient( 'tweets_'.$url );

            if( false === $feed || '' === $feed || $feed->error() ){
                $feed = new SimplePie();
                $feed->set_feed_url($url);

                $feed->set_cache_class('WP_Feed_Cache');
                $feed->set_file_class('WP_SimplePie_File');
                if( !$feed->error() ){
                	$feed->set_cache_duration(apply_filters('wp_feed_cache_transient_lifetime', 60*10 , $url));
                }

                do_action_ref_array( 'wp_feed_options', array( &$feed, $url ) );
                $feed->init();
                $feed->handle_content_type();
                if( !$feed->error() ){
                	set_transient( 'tweets_'.$url, $feed, 60*10 );
                }
            }

            if ( $feed->error() ){
                return new WP_Error('simplepie-error', $feed->error());
            }else{
                return $feed;
            }
        }

        function linkiss( $tweet , $username ) {
            /*Get the length of username*/
            $string_length = strlen( $username );
            /*Replace the username (according to the length of the username) with an empty string. Add $string_length to 1 since we want to remove the colon as well.*/
            $tweet = substr_replace( $tweet , '' , 0 , $string_length + 1 );
            /*Linking usernames, URLs and HashTags.*/
            $tweet_search = array('|(http://[^ ]+)|', '/(^|[^a-z0-9_])@([a-z0-9_]+)/i', '#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t< ]*)#', '/#(\w+)/');
            $tweet_replace = array('<a href="$1">$1</a>', '$1<a href="http://twitter.com/$2">@$2</a>', '$1<a href="$2">@$2</a>', '<a href="http://search.twitter.com/search?q=$1">#$1</a>');
            $tweet = preg_replace($tweet_search, $tweet_replace, $tweet);
            return $tweet;
        }

        function get_tweets( $username , $number , $classes = '' , $before = "<div class='tweet_item'><p>" , $after = "</p></div>", $static_class = '' ){
        	if(trim($username) == ''){
        		$result[0] = '<div class="cosmo_twitter">';
	  			$result[0] .='	<div class="slides_container">';
	  			$result[0] .= '<div>' . $before . __('Please set Twitter user name !!!' , 'cosmotheme') .  $after . '</div>';
	  			$result[0] .= '</div>';
	  			$result[0] .= '</div>';

	  			$result[1]  = '<a class="i_join_us '.$static_class.'" href="http://twitter.com/" title="'.__('Join the conversation','cosmotheme').'">'.__('Join the conversation','cosmotheme').'</a>';
	  			return $result;
        	}

            $all_tweets = self::fetch_feed( 'http://twitter.com/statuses/user_timeline/'.$username.'.rss' );

            if( !is_wp_error( $all_tweets ) ) {
                /*We will get the number of tweets based on the settings supplied by the user. If the user inputs 3 as the number of tweets, we will only fetch 3 tweets.*/
                $tweets_list_num = $all_tweets -> get_item_quantity( $number );
                /*Returns an array of tweets. The number 0 means that we will get the tweets starting at the top of the array (0 index).*/
                /*The size of the array depends on the settings supplied by the user. $tweets_list_num is the variable that we used in order to set the number of tweets that should be display. See the above code*/
                if( isset( $tweets_list_num ) ){
                    $tweets = $all_tweets -> get_items( 0 , $tweets_list_num );
                }
            }
            $result = array();
            if( strlen( $classes ) ){  /*This is for the front page presentation*/
                $c = 'class="'.$classes.'"';
                $result[0] = '<ul ' . $c . '>';

	            /*If the number of tweets set by the user is 0 or if no tweets are found, the widget will show 'no items' instead.*/
	            if( !isset( $tweets_list_num ) || $tweets_list_num == 0) {
	                $result[0] .= '<li>' . $before . __( 'Unable to read tweets !!!' , 'cosmotheme' ) . $after . '</li>';
	            }else {
	                /*Otherwise, we will loop through the array and print out each of the tweets individually.*/
	                if( isset( $tweets ) && is_array( $tweets ) && !empty( $tweets ) ){
	                    foreach( $tweets as $tweet ) {
	                        /*supply the functions with each of the tweets and the username so that it can remove the username in every tweets*/
	                        $result[0] .= '<li>' . $before . self::linkiss( $tweet -> get_title() , $username ) . ' <span class="date"><a href="' . $tweet -> get_permalink() . '">' . $tweet -> get_date() . '</a></span>' .  $after . '</li>';
	                    }
	                }else{
	                    $result[0] .= '<li>' . $before . __('Unable to read tweets !!!' , 'cosmotheme') .  $after . '</li>';
	                }


	            }


	            $result[0] .= '</ul>';
            }else{  /*This is for twitter widget*/
                $c = '';

                $result[0] = '<div class="cosmo_twitter">';
	  			$result[0] .='	<div class="slides_container">';
	            /*If the number of tweets set by the user is 0 or if no tweets are found, the widget will show 'no items' instead.*/
	            if( !isset( $tweets_list_num ) || $tweets_list_num == 0) {
	                $result[0] .= '<div>' . $before . __( 'Unable to read tweets !!!' , 'cosmotheme' ) . $after . '</div>';
	            }else {
	                /*Otherwise, we will loop through the array and print out each of the tweets individually.*/
	                if( isset( $tweets ) && is_array( $tweets ) && !empty( $tweets ) ){
	                    foreach( $tweets as $tweet ) {
	                        /*supply the functions with each of the tweets and the username so that it can remove the username in every tweets*/
	                        $result[0] .=  $before . self::linkiss( $tweet -> get_title() , $username ) . ' <span class="date"><a href="' . $tweet -> get_permalink() . '">' . $tweet -> get_date() . '</a></span>' .  $after ;
	                    }
	                }else{
	                    $result[0] .= '<div>' . $before . __('Unable to read tweets !!!' , 'cosmotheme') .  $after . '</div>';
	                }


	            }

				$result[0] .= '		</div>';
	            $result[0] .= '</div>';

	            $result[1]  = '<a class="i_join_us '.$static_class.'" href="http://twitter.com/'.$username.'" title="'.__('Join the conversation','cosmotheme').'">'.__('Join the conversation','cosmotheme').'</a>';
            }

            return $result;
        }
    }

?>
