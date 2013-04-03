<?php
    class fields {
        function layout( $field ){
            /* return field attributes */
            if( !is_array( $field ) || empty( $field ) ){
                return '';
            }

            foreach( $field as $attribut => $attribut_value ){
                $$attribut = $attribut_value;
            }

            /* if no specified type */
            if( !isset( $type ) ){
                return '';
            }

            /* return layout type from field type */
            $field_side = explode( '--' ,  $type );
            $layout_type = $field_side[ 0 ];

            /* generate label for field with $id */
            $field_id = isset( $id ) && strlen( $id ) ? $id  : '';

            $id = strlen( $field_id ) ? 'id="' . $field_id . '"' : '';

            $label_id = strlen( $field_id ) ? 'for="' . $field_id . '"' : '';

            $label = isset( $label ) ? '<label ' . $label_id . '>' . __($label,'cosmotheme') . '</label>' : '';

            $group = isset( $group ) ? $group : '';
            $topic = isset( $topic ) ? $topic : '';
            $index = isset( $index ) ? $index : '';

            $id     = isset( $id ) ? 'id="' . $id . '"' : '';

            $cid    = isset( $cid ) ? 'id="'.$cid.'"' : '';

            $hint = isset( $hint ) ? '<span class="generic-hint">' . __($hint,'cosmotheme') . '</span>': '' ;
            $help = isset( $help ) ? '<span class="generic-help" ' . __(self::action( $help ),'cosmotheme'). '></span>': '' ;

            $classes = isset( $classes ) ? $classes : '';

            /* reset field type */
            $field['type'] = $field_side[ 1 ];

            $field_type    = str_replace( 'm-' , '' , $field_side[ 1 ] );

            $result = '';

            switch( $layout_type ){
                /*
                    fields layout type
                    --------------------------------------------------
                    cd--*   - HTML Code
                    no--*   - not use layout
                    ni--*   - not input type
                    st--*   - sdandard layout
                    sh--*   - short layoutsult .
                    ln--*   - in line layout

                    * - field type

                 */

                /* code type layout */
                case 'cd' : {
                    $result .= $content;
                    break;
                }
                /* without layout  */
                case 'no'  :{
                    $result .= self::field( $field );
                    break;
                }
                /* not input type  */
                case 'ni' : {
                    $result .= '<div class="standard-generic-field generic-field-' . $group . ' ' . $classes . '">';
                    $result .= '<div class="generic-field full" ' . $cid . '>' . self::field( $field ) . $help . $hint . '</div>';
                    $result .= '<div class="clear"></div>';
                    $result .= '</div>';
                    break;
                }
                /* standard layout  */
                case 'st' : {
                    
                    $result .= '<div class="standard-generic-field generic-field-' . $group . ' ' . $classes . '">';
                    $result .= '<div class="generic-label">'. $label .'</div>';
                    $result .= '<div class="generic-field generic-field-' . $field_type  . '" ' . $cid . '>' . self::field( $field ) . $help . $hint . '</div>';
                    $result .= '<div class="clear"></div>';
                    $result .= '</div>';
                    break;
                }
                /* short layout */
                case 'sh' : {
                    $result .= '<div class="short-generic-field generic-field-' . $group . ' ' . $classes . '">';
                    $result .= '<div class="generic-label">'. $label .'</div>';
                    $result .= '<div class="generic-field generic-field-' . $field_type  . '" ' . $cid . '>' . self::field( $field ) . $help . $hint . '</div>';
                    $result .= '</div>';
                    break;
                }
                /* in line layout */
                case 'ln' : {
                    $result .= '<span class="inline-generic-field generic-field-' . $group . ' ' . $classes . '">';
                    $result .= '<span class="generic-label">'. $label .'</span>';
                    $result .= '<span class="generic-field generic-field-' . $field_type  . '" ' . $cid . '>' . self::field( $field ) . $help . $hint . '</span>';
                    $result .= '</span>';
                    break;
                }

                case 'ex' : {
                    $result .= '<div class="extra-generic-group extra-generic-' . $group . '" ' . $cid . '>';
                    $result .= extra::get( $group );
                    $result .= '</div>';
                    break;
                }
            }

            return $result;
        }

        function field( $field ){
            /* return field attributes */
            foreach( $field as $attribut => $attribut_value ){
                $$attribut =  $attribut_value;
            }

            $name       = isset( $single ) ?  $topic : $group . '[' . $topic . ']';
            $name_id    = isset( $single ) ?  $topic . '_id' : $group . '[' . $topic . '_id]';
            $iname      = isset( $topic ) ? $topic : '';

            $classes = isset( $iclasses ) ? $iclasses  : '';
            
            $field_id = isset( $id ) ? $id  : '';

            $id = strlen( $field_id ) ? 'id="' . $field_id . '"' : '';

            $group = isset( $group ) ? $group : '';
            $topic = isset( $topic ) ? $topic : '';
            $index = isset( $index ) ? $index : '';
            
            /* field classes */
            $fclasses   = 'generic-' . $group . ' generic-' . $topic . ' ' . $group . '-' . $topic . ' ' . $group . '-' . $topic . '-' . $index;

            $action = isset( $action ) ? $action : '';

            $result = '';
            
            switch( $type  ){
                /* no input type */
                case 'title' : {
                    $result .= '<h3 class="generic-record-title '  . $fclasses .  '" >' . __($title,'cosmotheme') . '</h3>';
                    break;
                }
                case 'hint' : {
                    $result .= __($value,'cosmotheme');
                    break;
                }
                case 'preview' : {
                    $result .= __($content,'cosmotheme');
                    break;
                }
                case 'image' : {
                    $width  .= isset( $width ) ? ' width="' . $width . '" ' : '';
                    $heigt  .= isset( $heigt ) ? ' height="' . $height . '" ' : '';
                    $result .= '<div class="generic-record-icon '  . $fclasses .  '" ><img src="' . $src  . '" ' . $width . $height . ' class="generic-record  '  . $fclasses .  '"/></div>';
                    break;
                }

                case 'color-picker' : {
                    $result .= '<input type="text" name="' . $name . '" id="pick_' . $iname . '" op_name="' . $iname . '" value="' .  $value . '" class="generic-record '  . $fclasses .  ' ' . $classes . '" />';
					$result .= '<a href="#" class="pickcolor hide-if-no-js" id="link_pick_' . $iname . '"></a>';
					$result .= '<div id="colorPickerDiv_' . $iname . '" class="colorPickerDiv" style="z-index: 100; background:#eee; border:1px solid #ccc; position:absolute; display:none;"></div>';
                    break;
                }

                case 'extra' : {
                    $result .= '<div id="container_' . $group . '">' . extra::get( $group ) . '</div>';
                    break;
                }

                case 'post-upload' : {
                    $result .= '<a class="thickbox" href="media-upload.php?post_id=' . $post_id  . '&type=image&TB_iframe=1&width=640&height=381">' . $title . '</a>';
                    break;
                }

				case "form-upload-init":
				  $result.='<div id="hidden_inputs_container" class="">';
				  $result.='</div>';
				  $result.='<script type="text/javascript">';
				  $result.='window.update_hidden_inputs=function(ids,type,urls,feat_vid)';
				  $result.="{";
					$result.='jQuery("#hidden_inputs_container").html("");';
					$result.='jQuery("#hidden_inputs_container").append("<input type=\\"hidden\\" name=\\"attachments_type\\" value=\\""+type+"\\">");';
					$result.='var i;';
					$result.='for(i=0;i<ids.length;i++)';
					  $result.="{";
						$result.='jQuery("#hidden_inputs_container").append("<input type=\\"hidden\\" name=\\"attachments[]\\" value=\\""+ids[i]+"\\">");';
						$result.="if(urls){";
						  $result.="if(urls[ids[i]]){";
							$result.='jQuery("#hidden_inputs_container").append("<input type=\\"hidden\\" name=\\"attached_urls["+ids[i]+"]\\" value=\\""+urls[ids[i]]+"\\">");';
						  $result.="}";
						$result.="}";
					  $result.="}";
					$result.="if(feat_vid){";
					  $result.='jQuery("#hidden_inputs_container").append("<input type=\\"hidden\\" name=\\"featured_video\\" value=\\""+feat_vid+"\\">");';
					$result.="}";
				  $result.="}";
				  $result.='</script>';
				  break;
				case "form-upload" :
				  $result.='<iframe id="'.$format.'_upload_iframe"  class="upload_iframe" src="'.get_template_directory_uri().'/upload_iframe.php?type='.$format.(isset($post_id)?('&post='.$post_id):"").'"></iframe>';
				break;

                case 'link' : {
                    $result .= '<a href="' . $url  . '">' . $title . '</a>';
                    break;
                }

                case 'callback' : {
                    $result .= '<span ' . $id . '> -- </span>';
                    break;
                }

                case 'radio-icon' : {
                    if( is_array( $value ) && !empty( $value ) ){
                        $path   = isset( $path ) ? $path : '';
                        $in_row = isset( $in_row ) ? $in_row : 8;
                        $i = 0;
                        foreach( $value  as $index => $icon ){
                            if( $i == 0 ){
                                $result .= '<div>';
                            }
                                if( isset( $ivalue ) &&  $ivalue == get_template_directory_uri() . '/lib/images/' . $path . $icon ){
                                        $s = 'checked="checked"';
                                        $sclasses = 'selected';
                                }else{
                                        $s = '';
                                        $sclasses = '';
                                }
                                $action['group'] = $group;
                                $action['topic'] = $topic;
                                $action['index'] = $index;

                                $result .= '<div class="generic-input-radio-icon ' . $index . ' hidden">';
                                $result .= '<input type="radio" value="' . get_template_directory_uri() . '/lib/images/' . $path . $icon . '" name="' . $name . '" class="generic-record  hidden ' . $fclasses . $index. ' ' . $classes . '" ' . $id . ' ' . $s . '>';
                                $result .= '</div>';
                                $result .= '<img ' . self::action( $action , 'radio-icon' ) . ' title="' . $icon . '" class="pattern-texture '. $sclasses . ' ' . $fclasses . $index. '" alt="' . $icon . '" src="' . get_template_directory_uri() . '/lib/images/' . $path . $icon . '" />';
                            $i++;
                            if( $i % $in_row == 0 ){
                                $i = 0;
                                $result .='<div class="clear"></div></div>';
                            }
                        }

                        if( $i % $in_row != 0){
                            $result .='<div class="clear"></div></div>';
                        }
                    }
                    break;
                }
                case 'logic-radio' : {
                    if( $value == 'yes' ){
                        $c1 = 'checked="checked"';
                        $c2 = '';
                    }else{
                        if( $value == 'no' ){
                            $c1 = '';
                            $c2 = 'checked="checked"';
                        }else{
                            if( isset( $cvalue ) ){
                                if( $cvalue == 'yes' ){
                                    $c1 = 'checked="checked"';
                                    $c2 = '';
                                }else{
                                    $c1 = '';
                                    $c2 = 'checked="checked"';
                                }
                            }else{
                                $c1 = '';
                                $c2 = 'checked="checked"';
                            }
                        }
                    }

                    $result  = '<input type="radio" value="yes" name="' . $name . '" class="generic-record  '  . $fclasses .  ' ' . $classes . ' yes" ' . $id . ' ' . $c1 . ' ' . self::action( $action , 'logic-radio' ) . ' /> ' . __( 'Yes' , 'cosmotheme' ) . '&nbsp;&nbsp;&nbsp;';
                    $result .= '<input type="radio" value="no" name="' . $name . '" class="generic-record  '  . $fclasses .  ' ' . $classes . ' no" ' . $id . ' ' . $c2 . ' ' . self::action( $action , 'logic-radio' ) . ' /> ' . __( 'No' , 'cosmotheme' );
                    break;
                }
                /* single type records */
                case 'hidden' : {
                    $result .= '<input type="hidden" name="' . $name . '" value="' . $value . '" class="generic-record  '  . $fclasses .  ' ' . $classes . '" ' . $id . '  />';
                    break;
                }
                case 'text' : {
                    $result .= '<input type="text" name="' . $name . '" value="' . $value . '" class="generic-record  '  . $fclasses .  ' ' . $classes . '" ' . $id . ' ' . self::action( $action , 'text' ) . ' />';
                    break;
                }
                case 'digit' : {
                    $result .= '<input type="text" name="' . $name . '" value="' . $value . '" class="generic-record  digit '  . $fclasses .  ' ' . $classes . '" ' . $id . ' ' . self::action( $action , 'text' ) . ' />';
                    break;
                }
                case 'digit-like' : {
                    $result .= '<input type="text" name="' . $name . '" value="' . $value . '" class="generic-record  digit like '  . $fclasses .  ' ' . $classes . '" ' . $id . ' />';
                    $result .= '<input type="button" name="' . $name . '" value="' . __( 'Reset Value' , 'cosmotheme' ) . '" class="generic-record-button  button-primary" ' . self::action( $action , 'digit-like' ) . ' /> <span class="digit-btn result"></span>';
                    break;
                }
                case 'textarea' : {
                    $result .= '<textarea name="' . $name . '" class="generic-record  '  . $fclasses .  ' ' . $classes . '" ' . $id . ' ' . self::action( $action , 'textarea' ) . '>' . $value . '</textarea>';
                    break;
                }

                case 'radio' : {
                    if( isset( $iname ) && $iname == $value ){
                        $status = ' checked="checked" ' ;
                    }else{
                        $status = '' ;
                    }

                    $name = isset( $single ) ? $iname : $group . '[' . $iname . ']';

                    $result .= '<input type="radio" name="' . $name . '" value="' . $value . '"  ' . $status . ' class="generic-record  '  . $fclasses .  ' ' . $classes . '" ' . $id . ' ' . self::action( $action , 'radio' ) . ' />';
                    break;
                }

                case 'select' : {
                    $result .= '<select  name="' . $name . '" class="generic-record  '  . $fclasses .  ' ' . $classes . '" ' . $id . ' ' . self::action( $action , 'select' ) . ' >';
                    foreach( $value as $index => $etichet ){
                        if( isset( $ivalue ) && $ivalue == $index ){
                            $status = ' selected="selected" ' ;
                        }else{
                            $status = '' ;
                        }

                        $result .= '<option value="' . $index . '" ' . $status . ' >' . $etichet . '</option>';
                    }
                    $result .= '</select>';
                    break;
                }

                case 'multiple-select' : {
                    $result .= '<select  name="' . $name . '[]" multiple="multiple" class="generic-record  '  . $fclasses .  ' ' . $classes . '" ' . $id . ' ' . self::action( $action , 'multiple-select' ) . ' >';
                    foreach( $value as $index => $etichet ){
                        if( isset( $ivalue ) && is_array( $ivalue ) && in_array( $index , $ivalue) ){
                            $status = ' selected="selected" ' ;
                        }else{
                            $status = '' ;
                        }

                        $result .= '<option value="' . $index . '" ' . $status . ' >' . $etichet . '</option>';
                    }
                    $result .= '</select>';
                    break;
                }

                case 'checkbox' : {
                    if( isset( $iname ) && $iname == $ivalue ){
                        $status = ' checked="checked" ' ;
                    }else{
                        $status = '' ;
                    }

                    $result .= '<input type="checkbox" name="' . $name . '" value="' . $iname . '"  ' . $status . ' class="generic-record '  . $fclasses .  ' ' . $classes . '" ' . $id . ' ' . self::action( $action , 'checkbox' ) . ' />';
                    break;
                }

                case 'button' : {
                    $result .= '<input type="button" name="' . $name . '" value="' . $value . '" class="generic-record-button  button-primary  ' . $classes . '" ' . $id . ' ' . self::action( $action , 'button' ) . ' /> <span class="btn result"></span>';
                    break;
                }

                case 'attach' : {
                    //'action' => "meta.save_data('presentation','speaker' , extra.val('select#field_attach_presentation'), [ { 'name':'speaker[idrecord][]' , 'value' : extra.val('select#field_attach_presentation') } ] );"
                    $action['res'] = $group;
                    $action['group'] = $res;
                    $action['post_id']  = $post_id;
                    $action['attach_selector'] = $attach_selector;
                    if( !isset( $selector ) ){
                        $selector = 'div#' . $res . '_' . $group . ' div.inside div#box_' . $res . '_' . $group;
                    }
                    $action['selector'] = $selector;
                    $result .= '<input type="button" name="' . $name . '" value="' . $value . '" class="generic-record-button  button-primary  ' . $classes . '" ' . $id . ' ' . self::action( $action , 'attach' ) . ' /> <p id="attach_' . $res . '_' . $group . '" class="attach_alert hidden">'.__( ' Attached ' , 'cosmotheme' ).'</sp>';
                    break;
                }

                case 'meta-save' : {
                    $action['res']      = $res;
                    $action['group']    = $group;
                    $action['post_id']  = $post_id;
                    $action['selector'] = $selector;
                    $result .= '<input type="button" name="' . $name . '" value="' . $value . '" class="generic-record-button  button-primary  ' . $classes . '" ' . $id . ' ' . self::action( $action , 'meta-save' ) . ' />';
                    break;
                }

                case 'upload' : {
                    $result .= '<input type="text" name="' . $name . '"  value="' . $value  . '" class="generic-record '  . $fclasses .  ' ' . $classes . '" ' . $id . ' /><input type="button" class="button-primary" value="'.__('Choose File','cosmotheme').'" ' . self::action( $field_id , 'upload' ) . ' />';
                    break;
                }

                case 'upload-id' :{
                    
                    $action['group'] = $group;
                    $action['topic'] = $topic;
                    $action['index'] = $index;

                    $result .= '<input type="text" name="' . $name . '" value="' . $value . '" class="generic-record '  . $fclasses .  ' ' . $classes . '" ' . $id . '  /><input type="button" class="button-primary" value="'.__('Choose File','cosmotheme').'" ' . self::action( $action , 'upload-id' ) . ' />';
                    $result .= '<input type="hidden" name="' . $name_id . '" id="' . $field_id . '_id"  class="generic-record generic-single-record '  . $fclasses .  '" value="' . $value_id . '"/>';
                    break;
                }

                /* multiple type records */
                case 'm-hidden' : {
                    $result .= '<input type="hidden" name="' . $name . '[]" value="' . $value . '" class="generic-record '  . $fclasses .  ' ' . $classes . '" ' . $id . '  />';
                    break;
                }
                case 'm-text' : {
                    $result .= '<input type="text" name="' . $name . '[]" value="' . $value . '" class="generic-record '  . $fclasses .  ' ' . $classes . '" ' . $id . ' ' . self::action( $action , 'text' ) . ' />';
                    break;
                }
                case 'm-digit' : {
                    $result .= '<input type="text" name="' . $name . '[]" value="' . $value . '" class="generic-record digit '  . $fclasses .  ' ' . $classes . '" ' . $id . ' ' . self::action( $action , 'text' ) . ' />';
                    break;
                }
                case 'm-textarea' : {
                    $result .= '<textarea name="' . $name . '[]" class="generic-record '  . $fclasses .  ' ' . $classes . '" ' . $id . ' ' . self::action( $action , 'textarea' ) . '>' . $value . '</textarea>';
                    break;
                }

                case 'm-radio' : {
                    if( isset( $iname ) && $iname == $value ){
                        $status = ' checked="checked" ' ;
                    }else{
                        $status = '' ;
                    }

                    $name = isset( $single ) ? $iname : $group . '[' . $iname . ']';

                    $result .= '<input type="radio" name="' . $name . '[]" value="' . $value . '"  ' . $status . ' class="generic-record '  . $fclasses .  ' ' . $classes . '" ' . $id . ' ' . self::action( $action , 'radio' ) . ' />';
                    break;
                }

                case 'm-select' : {
                    $result .= '<select  name="' . $name . '[]" class="generic-record '  . $fclasses .  ' ' . $classes . '" ' . $id . ' ' . self::action( $action , 'select' ) . ' >';
                    foreach( $value as $index => $etichet ){
                        if( isset( $ivalue ) && $ivalue == $index ){
                            $status = ' selected="selected" ' ;
                        }else{
                            $status = '' ;
                        }

                        $result .= '<option value="' . $index . '" ' . $status . ' >' . $etichet . '</option>';
                    }
                    $result .= '</select>';
                    break;
                }

                case 'm-multiple-select' : {
                    $result = '<select  name="' . $name . '[]" multiple="multiple" class="generic-record '  . $fclasses .  ' ' . $classes . '" ' . $id . ' ' . self::action( $action , 'multiple-select' ) . ' >';
                    foreach( $value as $index => $etichet ){
                        if( isset( $ivalue ) && is_array( $ivalue ) && in_array( $index , $ivalue) ){
                            $status = ' selected="selected" ' ;
                        }else{
                            $status = '' ;
                        }

                        $result .= '<option value="' . $index . '" ' . $status . ' >' . $etichet . '</option>';
                    }
                    $result .= '</select>';
                    break;
                }

                case 'm-checkbox' : {
                     if( isset( $iname ) && $iname == $value ){
                        $status = ' checked="checked" ' ;
                    }else{
                        $status = '' ;
                    }

                    $result .= '<input type="checkbox" name="' . $name . '[]" value="' . $value . '"  ' . $status . ' class="generic-record '  . $fclasses .  ' ' . $classes . '" ' . $id . ' ' . self::action( $action , 'checkbox' ) . ' />';
                    break;
                }
                case 'm-upload' : {
                    $result .= '<input type="text" name="' . $name . '[]"  value="' . $value  . '" class="generic-record '  . $fclasses .  ' ' . $classes . '" ' . $id . ' /><input type="button" class="button-primary" value="'.__('Choose File','cosmotheme').'" ' . self::action( $field_id , 'upload' ) . ' />';
                    break;
                }

                case 'm-upload-id' :{

                    $action['group'] = $group;
                    $action['topic'] = $topic;
                    $action['index'] = $index;

                    $result .= '<input type="text" name="' . $name . '[]" value="' . $value . '" class="generic-record '  . $fclasses .  ' ' . $classes . '" ' . $id . '  /><input type="button" class="button-primary" value="'.__('Choose File','cosmotheme').'" ' . self::action( $action , 'upload-id' ) . ' />';
                    $result .= '<input type="hidden" name="' . $name_id . '[]" id="' . $field_id . '_id"  class="generic-record '  . $fclasses .  '" />';
                    break;
                }
            }
            
            return $result;
        }

        function action( $action , $type ){

            if( empty( $action ) ){
                return '';
            }

            $result = '';
            switch( $type ){
                case 'text' : {
                    $result = 'onkeyup="javascript:' . $action . ';"';
                    break;
                }
                case 'radio-icon' : {
                    $result = 'onclick="javascript:act.radio_icon(\'' . $action['group'] . '\' , \'' . $action['topic'] . '\' ,  \'' . $action['index'] . '\' );"';
                    break;
                }
                case 'textarea' : {
                    $result = 'onkeyup="javascript:' . $action . ';"';
                    break;
                }
                case 'radio' : {
                    $result = 'onclick="javascript:' . $action . ';"';
                    break;
                }
                case 'checkbox' : {
                    $result = 'onclick="javascript:' . $action . ';"';
                    break;
                }
                case 'select' : {
                    $result = 'onchange="javascript:' . $action . ';"';
                    break;
                }
                case 'logic-radio' : {
                    $result = 'onclick="javascript:' . $action . ';"';
                    break;
                }
                case 'm-select' : {
                    $result = 'onchange="javascript:' . $action . ';"';
                    break;
                }
                case 'button' : {
                    $result = 'onclick="javascript:' . $action . ';"';
                    break;
                }
                case 'digit-like' : {
                    $result = 'onclick="javascript:' . $action . ';"';
                    break;
                }
                case 'meta-save' : {
                    $result = 'onclick="javascript:meta.save(\'' . $action['res'] . '\' , \'' . $action['group'] . '\' , '.$action['post_id'].' , \''.$action['selector'].'\' );meta.clear(\'.generic-' . $action['group'] . '\');"';
                    break;
                }
                case 'attach' : {
                    $result = 'onclick="javascript:meta.save_data(\'' . $action['res'] . '\' , \'' . $action['group'] . '\' , extra.val(\''.$action['attach_selector'].'\') , [ { \'name\' : \''.$action['group'].'[idrecord][]\' , \'value\' : ' . $action['post_id'] . ' }] , \''.$action['selector'].'\' );"';
                    break;
                }
                case 'upload' : {
                    $result = 'onclick="javascript:act.upload(\'input#' . $action . '\' );"';
                    break;
                }
                case 'upload-id' : {
					if(isset($action['upload_url']) && $action['upload_url'] != ''){  
						$upload_url =  $action['upload_url'];
					}else{
						$upload_url =  '';
					}	
                    $result = 'onclick="javascript:act.upload_id(\'' . $action['group'] . '\' , \'' . $action['topic'] . '\' , \''.$action['index'].'\',\''.$upload_url.'\' );"';
                    break;
                }

                case 'extern-upload-id' : {
					if(isset($action['upload_url']) && $action['upload_url'] != ''){
						$upload_url =  $action['upload_url'];
					}else{
						$upload_url =  '';
					}
                    $result = 'onclick="javascript:act.extern_upload_id(\'' . $action['group'] . '\' , \'' . $action['topic'] . '\' , \''.$action['index'].'\',\''.$upload_url.'\' );"';
                    break;
                }
            }

            return $result;
        }

        function digit_array( $to , $from = 0 , $twodigit = false ){
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

        function months_array( ){
            $result = array(
                '01' =>  __( 'January' , 'cosmotheme' ),
                '02' =>  __( 'February', 'cosmotheme' ),
                '03' =>  __( 'March' , 'cosmotheme' ),
                '04' =>  __( 'April', 'cosmotheme' ),
                '05' =>  __( 'May', 'cosmotheme' ),
                '06' =>  __( 'June', 'cosmotheme' ),
                '07' =>  __( 'July', 'cosmotheme' ),
                '08' =>  __( 'August', 'cosmotheme' ),
                '09' =>  __( 'September', 'cosmotheme' ),
                '10' =>  __( 'October', 'cosmotheme' ),
                '11' =>  __( 'November', 'cosmotheme' ),
                '12' =>  __( 'December', 'cosmotheme' )
            );

            return $result;
        }

        function monts_days_array( $month , $year  ){
            $days = date( 't' , mktime( 0 , 0 , 0 , $month, 0 , $year, 0 ) );
            return self::digit_array( $days , 1 , true );
        }
    }

?>