<?php
	class layout{
        function get_side( $side = 'right' , $post_id = 0 , $template = null ){
            $position = false;
            if( strlen( $side ) ){
                if( $post_id > 0 ){
                    $layout = meta::get_meta( $post_id , 'layout' );

                    if( isset( $layout['type'] ) && !empty( $layout['type'] ) ){
                        $result = $layout['type'];
                    }else{

                        if( strlen( $template ) ){
                            $result = options::get_value( 'layout' , $template );
                        }else{
                            $result = $side;
                        }
                    }
                }else{
                    if( strlen( $template ) ){
                        $result = options::get_value( 'layout' , $template );
                    }else{
                        $result = $side;
                    }
                }

                if( $side == 'right' ){
                    $classes = 'fr';
                }else{
                    $classes = 'fl';
                }

                if( $result == $side ){
                    echo '<div id="secondary" class="widget-area w_280 ' . $classes . '" role="complementary">';
                    echo '<div class="b w_260">';
                    if( isset( $layout['sidebar'] ) && !empty( $layout['sidebar'] ) ){
                        get_template_part('author-box');

                        if(dynamic_sidebar ( $layout['sidebar'] ) ){

                        }
                    }else{
                        $layout = options::get_value( 'layout' , $template . '_sidebar' );
                        if( !empty( $layout ) ){
                            if(dynamic_sidebar ( $layout ) ){

                            }
                        }else{
                            get_sidebar( );
                        }

                    }
                    echo '</div>';
                    echo '</div>';

                    $position = true;
                }
            }

            return $position;
        }

        function get_length( $post_id = 0 , $template = null , $large = false ){
            $layout = meta::get_meta( $post_id , 'layout' );
            if( isset( $layout['type'] ) && !empty( $layout['type'] ) && $layout['type'] == 'full' ) {
                if( $large ){
                    $length = 960;
                }else{
                    $length = 940;
                }
            }else{
                if( strlen( $template ) ){
                    $result = options::get_value( 'layout' , $template );
                    if( $result == 'full' ){
                        if( isset( $layout['type'] ) && $layout['type'] != 'full' ){
                            if( $large ){
                                $length = 660;
                            }else{
                                $length = 620;
                            }
                        }else{
                            if( $large ){
                                $length = 960;
                            }else{
                                $length = 940;
                            }
                        }
                    }else{
                        if( $large ){
                            $length = 660;
                        }else{
                            $length = 620;
                        }
                    }
                }
            }

            return $length;
        }

	}
?>