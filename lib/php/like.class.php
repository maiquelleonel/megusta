<?php
    class like{
        function anti_loop(){
            $id = md5( md5( $_SERVER['HTTP_USER_AGENT'] ) );

            $time = mktime();

            $user = get_option('set_user_like');

            if( is_array( $user ) && array_key_exists( $id , $user ) ){
                if( (int) $user[ $id ] + 1  < (int) $time  ){
                    $user[ $id ]  = (int) $time;
                    update_option( 'set_user_like' , $user );
                    return false;
                }else{
                    $user[ $id ]  = (int) $time;
                    update_option( 'set_user_like' , $user );
                    return true;
                }
            }else{
                $user[ $id ]  = (int) $time;
                update_option( 'set_user_like' , $user );
                return true;
            }
        }
        function set( $post_id = 0 ){

            if( $post_id == 0 ){
                $post_id = isset( $_POST['post_id' ]) ? (int) $_POST['post_id'] : exit;
                $ajax = true;
            }else{
                $ajax = false;
            }


            $likes = meta::get_meta( $post_id , 'like' );

            if( self::anti_loop() ){
                echo (int)count( $likes );
                exit;
            }

            $user       = true;
            $user_ip    = true;

            $ip     = $_SERVER['REMOTE_ADDR'];

            if( is_user_logged_in () ){
                $user_id = get_current_user_id();
            }else{
                $user_id = 0;
            }

            if( $user_id > 0 ){
                /* like by user */
                foreach( $likes as  $like ){
                    if( isset( $like['user_id'] ) && $like['user_id'] == $user_id ){
                       $user   = false;
                       $user_ip = false;
                    }
                }
            }else{
                if( options::logic( 'general' , 'like_register' ) ){
                    if( $ajax ){
                        exit;
                    }else{
                        return '';
                    }
                }
                foreach( $likes as  $like ){
                    if( isset( $like['ip'] ) && ( $like['ip'] == $ip ) ){
                        $user = false;
                        $user_ip = false;
                    }
                }
            }

            if( $user && $user_ip ){
                /* add like */
                $likes[] = array( 'user_id' => $user_id , 'ip' => $ip );
                meta::set_meta( $post_id , 'nr_like' , count( $likes ) );
                meta::set_meta( $post_id , 'like' ,  $likes );
                $date = meta::get_meta( $post_id , 'hot_date' );
                if( empty( $date ) ){
                    if( ( count( $likes ) >= (int)options::get_value( 'general' , 'min_likes' ) ) ){
                        meta::set_meta( $post_id , 'hot_date' , mktime() );
                    }
                }else{
                    if( ( count( $likes ) < (int)options::get_value( 'general' , 'min_likes' ) ) ){
                        delete_post_meta( $post_id, 'hot_date' );
                    }
                }
            }else{
                /* delete like */
                if( $user_id > 0 ){
                    foreach( $likes as $index => $like ){
                        if( isset( $like['user_id'] ) && $like['user_id'] == $user_id ){
                            unset( $likes[ $index ] );
                        }
                    }
                }else{
                    if( options::logic( 'general' , 'like_register' ) ){
                        if( $ajax ){
                            exit;
                        }else{
                            return '';
                        }
                    }
                    foreach( $likes as $index => $like ){
                        if( isset( $like['ip'] ) && isset( $like['user_id'] ) && ( $like['ip'] == $ip ) && ( $like['user_id'] == 0 ) ){
                            unset( $likes[ $index ] );
                        }
                    }
                }

                meta::set_meta( $post_id , 'like' ,  $likes );
                meta::set_meta( $post_id , 'nr_like' ,  count( $likes ) );
                if( count( $likes ) < (int)options::get_value( 'general' , 'min_likes' ) ){
                    delete_post_meta($post_id, 'hot_date' );
                }
            }

            if( $ajax ){
                echo (int)count( $likes );
                exit;
            }
        }

        function is_voted( $post_id ){
            $ip     = $_SERVER['REMOTE_ADDR'];

            $likes = meta::get_meta( $post_id , 'like' );

            if( is_user_logged_in () ){
                $user_id = get_current_user_id();
            }else{
                $user_id = 0;
            }

            if( $user_id > 0 ){
                foreach( $likes as $like ){
                    if( isset( $like['user_id'] ) && $like['user_id'] == $user_id ){
                        return true;
                    }
                }
            }else{
                foreach( $likes as $like ){
                    if( isset( $like['ip'] ) && $like['ip'] == $ip ){
                        return true;
                    }
                }
            }

            return false;
        }

        function can_vote( $post_id ){
            $ip     = $_SERVER['REMOTE_ADDR'];

            $likes = meta::get_meta( $post_id , 'like' );

            if( is_user_logged_in () ){
                $user_id = get_current_user_id();
            }else{
                $user_id = 0;
            }

            if( options::logic( 'general' , 'like_register' ) && $user_id == 0 ){
                return false;
            }

            if( $user_id == 0 ){
                foreach( $likes as $like ){
                    if( isset( $like['user_id'] ) && $like['user_id'] > 0  && $like['ip'] == $ip ){
                        return false;
                    }
                }
            }

            return true;
        }

		function reset_likes(){
            global $wp_query;
            $paged      = isset( $_POST['page']) ? $_POST['page'] : exit;
            $wp_query = new WP_Query( array('posts_per_page' => 150 , 'post_type' => 'post' , 'paged' => $paged ) );
            
            foreach( $wp_query -> posts as $post ){
                delete_post_meta($post -> ID, 'nr_like' );
				delete_post_meta($post -> ID, 'like' );
				delete_post_meta($post -> ID, 'hot_date' );
            }
            
            if( $wp_query -> max_num_pages >= $paged ){
                if( $wp_query -> max_num_pages == $paged ){
                    echo 0;
                }else{
                    echo $paged + 1;
                }
            }
            
            exit();
        }

		function sim_likes(){
            global $wp_query;
            $paged      = isset( $_POST['page']) ? $_POST['page'] : exit;
            $wp_query = new WP_Query( array('posts_per_page' => 150 , 'post_type' => 'post' , 'paged' => $paged ) );
            

            foreach( $wp_query -> posts as $post ){
                $likes = array();
                $ips = array();
                $nr = rand( 60 , 200 );
                while( count( $likes ) < $nr ){
                    $ip = rand( -255 , -100 ) .  rand( -255 , -100 )  . rand( -255 , -100 ) . rand( -255 , -100 );

                    $ips[ $ip ] = $ip;

                    if( count( $ips )  > count( $likes ) ){
                        $likes[] = array( 'user_id' => 0 , 'ip' => $ip );
                    }
                }

                meta::set_meta( $post -> ID , 'nr_like' , count( $likes ) );
                meta::set_meta( $post -> ID , 'like' ,  $likes );
                meta::set_meta( $post -> ID , 'hot_date' , mktime() );
            }
            
            if( $wp_query -> max_num_pages >= $paged ){
                if( $wp_query -> max_num_pages == $paged ){
                    echo 0;
                }else{
                    echo $paged + 1;
                }
            }
            
            exit();
        }
        
        function min_likes(){
            global $wp_query;
            $new_limit  = isset( $_POST['new_limit']) ? $_POST['new_limit'] : exit;
            $paged      = isset( $_POST['page']) ? $_POST['page'] : exit;

            $wp_query = new WP_Query( array('posts_per_page' => 150 , 'post_type' => 'post' , 'paged' => $paged ) );
            foreach( $wp_query -> posts as $post ){
                $likes = meta::get_meta( $post -> ID , 'like' );
                meta::set_meta( $post -> ID , 'nr_like' , count( $likes ) );
                if( count( $likes ) < (int)$new_limit ){
                    delete_post_meta( $post -> ID, 'hot_date' );
                }else{
                    if( (int)meta::get_meta( $post -> ID , 'hot_date' ) > 0 ){

                    }else{
                        meta::set_meta( $post -> ID , 'hot_date' , mktime() );
                    }
                }
            }
            if( $wp_query -> max_num_pages >= $paged ){
                if( $wp_query -> max_num_pages == $paged ){
                    $general = options::get_value( 'general' );
                    $general['min_likes'] = $new_limit;
                    update_option( 'general' , $general );
                    echo 0;
                }else{
                    echo $paged + 1;
                }
            }

            exit();
        }
    }

?>
