<?php
    /*
     *
    include 'lib/php/core.php';
    include 'lib/php/main.php';

    core::method( 'test' , 'a' , 1 );
    core::method( 'test' , 'b' , 1 , 2 , 3 );
    echo core::method( 'test' , 'c' , 1 , 2 , 3  );

    echo core::method( 'test' , 'c' , 1 , 2 );

    exit;
     */
    class core {
        static function method ( ){

            if( func_num_args() > 1 ){

                $class_name = func_get_arg( 0 );
                $method_name = func_get_arg( 1 );

                if( class_exists( $class_name ) ){
                    $object = new $class_name;
                    if( method_exists( $object , $method_name ) ){
                        switch( func_num_args() - 2 ){
                            case 0 : {
                                return call_user_method( $method_name , &$object );
                                break;
                            }
                            case 1 : {
                                return call_user_method( $method_name , &$object , func_get_arg( 2 ) );
                                break;
                            }
                            case 2 : {
                                return call_user_method( $method_name , &$object , func_get_arg( 2 ) , func_get_arg( 3 ) );
                                break;
                            }
                            case 3 : {
                                return call_user_method( $method_name , &$object , func_get_arg( 2 ) , func_get_arg( 3 ) ,  func_get_arg( 4 ) );
                                break;
                            }
                            case 4 : {
                                return call_user_method( $method_name , &$object , func_get_arg( 2 ) , func_get_arg( 3 ) ,  func_get_arg( 4 ) , func_get_arg( 5 ) );
                                break;
                            }
                            case 5 : {
                                return call_user_method( $method_name , &$object , func_get_arg( 2 ) , func_get_arg( 3 ) ,  func_get_arg( 4 ) , func_get_arg( 5 ) , func_get_arg( 6 ) );
                                break;
                            }
                            case 6 : {
                                return call_user_method( $method_name , &$object , func_get_arg( 2 ) , func_get_arg( 3 ) ,  func_get_arg( 4 ) , func_get_arg( 5 ) , func_get_arg( 6 ) , func_get_arg( 7 ) );
                                break;
                            }
                            case 7 : {
                                return call_user_method( $method_name , &$object , func_get_arg( 2 ) , func_get_arg( 3 ) ,  func_get_arg( 4 ) , func_get_arg( 5 ) , func_get_arg( 6 ) , func_get_arg( 7 ) , func_get_arg( 8 ) );
                                break;
                            }
                            case 8 : {
                                return call_user_method( $method_name , &$object , func_get_arg( 2 ) , func_get_arg( 3 ) ,  func_get_arg( 4 ) , func_get_arg( 5 ) , func_get_arg( 6 ) , func_get_arg( 7 ) , func_get_arg( 8 ) , func_get_arg( 9 ) );
                                break;
                            }
                            case 9 : {
                                return call_user_method( $method_name , &$object , func_get_arg( 2 ) , func_get_arg( 3 ) ,  func_get_arg( 4 ) , func_get_arg( 5 ) , func_get_arg( 6 ) , func_get_arg( 7 ) , func_get_arg( 8 ) , func_get_arg( 9 ) , func_get_arg( 10 ) );
                                break;
                            }
                            case 10 : {
                                return call_user_method( $method_name , &$object , func_get_arg( 2 ) , func_get_arg( 3 ) ,  func_get_arg( 4 ) , func_get_arg( 5 ) , func_get_arg( 6 ) , func_get_arg( 7 ) , func_get_arg( 8 ) , func_get_arg( 9 ) , func_get_arg( 10 ) , func_get_arg( 11 ) );
                                break;
                            }
                            case 11 : {
                                return call_user_method( $method_name , &$object , func_get_arg( 2 ) , func_get_arg( 3 ) ,  func_get_arg( 4 ) , func_get_arg( 5 ) , func_get_arg( 6 ) , func_get_arg( 7 ) , func_get_arg( 8 ) , func_get_arg( 9 ) , func_get_arg( 10 ) , func_get_arg( 11 ) , func_get_arg( 12 )  );
                                break;
                            }

                            default : {
                                return "Ned case for : " . func_num_args() . ' params';
                                break;
                            }
                        }
                    }else{
                        return null;
                    }
                }else{
                    return null;
                }
            }else{
                return null;
            }
        }
    }
?>