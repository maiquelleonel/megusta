<?php
	class text{
		function preview( $family = null , $size = null , $weight = null , $text = null ){
			$classes = 'dinamic-text-preview-' . mktime() . '-' . rand( 1000 , 9999 ) ;
			if( empty( $family ) ){
				$family = isset( $_POST['family'] ) ? $_POST['family'] : exit;
				$size 	= isset( $_POST['size'] ) ? $_POST['size'] : exit;
				$weight = isset( $_POST['weight'] ) ? $_POST['weight'] : exit;
				$text 	= isset( $_POST['text'] ) ? $_POST['text'] : exit;
				$ajax	= true;
			}else{
				$ajax	= false;
			}

			$result  = '<style>';
            $result .= '@import url("' . includes::$fonts[ str_replace( ' ' , '+' , $family ) ] . '");';
			$result .= 'h3.' . $classes . '{';
			$result .= 'font-family:"' . str_replace( '+' , ' ' , rtrim( $family , '&v1' ) ) . '";';
			$result .= 'font-size:' . $size . 'px;';
			$result .= 'font-weight:' . $weight . ';';
			$result .= '}';
			$result .= '</style>';
			
			$result .= '<h3 class="' . $classes . '">' . $text . '</h3>';
			
			if( $ajax ){
				echo $result;
				exit;
			}else{
				return $result;
			}
		}
		
		function fields( $page , $prefix , $classes = '' , $text = 'Test text for preview' , $default = array( 'Francois+One&v1' , 24 , 'normal' ) ){
			
			$family = strlen( options::get_value( $page , $prefix . '_font_family' ) ) ? options::get_value( $page , $prefix . '_font_family' ) : $default[0];
			$size   = (int) options::get_value( $page , $prefix . '_font_size' ) > 0  ? options::get_value( $page , $prefix . '_font_size' ) : $default[1];
			$weight = strlen( options::get_value( $page , $prefix . '_font_weight' ) ) ? options::get_value( $page , $prefix . '_font_weight' ) : $default[2];
			
			$action = "act.preview( extra.val('#" . $prefix . "_font_family') , extra.val('#" . $prefix . "_font_size') , extra.val('#" . $prefix . "_font_weight') , '" . $text . "' ,'#" . $prefix . '_font_preview' . "' );";
			options::$fields[ $page ][ $prefix . '_font_family'] 	= array('type' => 'st--select' , 'label' => __( 'Font Family' , 'cosmotheme' ) , 'value' => includes::fonts() , 'action' => $action  , 'id' => $prefix . '_font_family' , 'classes' => $classes );
			options::$fields[ $page ][ $prefix . '_font_size']      = array('type' => 'st--select' , 'label' => __( 'Font Size' , 'cosmotheme' ) , 'value' => options::get_digit_array( 80 ) , 'action' => $action  , 'id' => $prefix . '_font_size' ,  'classes' => $classes );
			options::$fields[ $page ][ $prefix . '_font_weight']    = array('type' => 'st--select' , 'label' => __( 'Font Weight' , 'cosmotheme' ) , 'value' => array( 'normal' => 'Normal' , 'bold' => 'Bold' ) , 'action' => $action  , 'id' => $prefix . '_font_weight' ,  'classes' => $classes );
			options::$fields[ $page ][ $prefix . '_text_preview']   = array('type' => 'st--preview'  , 'label' => __( 'Preview Text' , 'cosmotheme' ) , 'content' => text::preview( $family  , $size , $weight  , $text ) , 'classes' => $classes , 'cid' => $prefix . '_font_preview' );
			
			options::$default[ $page ][ $prefix . '_font_family'] 	= $default[0];
			options::$default[ $page ][ $prefix . '_font_size'] 	= $default[1];
			options::$default[ $page ][ $prefix . '_font_weight'] 	= $default[2];
		}
		
		function family( $page , $prefix ){
			return str_replace( '+' , ' ' , rtrim( options::get_value( $page , $prefix . '_font_family' )  , '&v1' ) );
		}
		
		function size( $page , $prefix ){
			return options::get_value( $page , $prefix . '_font_size' );
		}
		
		function weight( $page , $prefix ){
			return options::get_value( $page , $prefix . '_font_weight' );
		}
		
		
	}
?>