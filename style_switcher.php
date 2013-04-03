<?php
	if ( options::logic( 'styling' , 'front_end' ) ){
		$background_img = get_bg_image();
        $footer_background_color = get_footer_bg_color();
        $background_color = get_content_bg_color();
		
        $theme_bgs = array(
	        "flowers"=>"flowers" , "flowers_2"=>"flowers_2" , "flowers_3"=>"flowers_3" , "flowers_4"=>"flowers_4" ,"circles"=>"circles","dots"=>"dots","grid"=>"grid","noise"=>"noise",
	        "paper"=>"paper","rectangle"=>"rectangle","squares_1"=>"squares_1","squares_2"=>"squares_2","thicklines"=>"thicklines","thinlines"=>"thinlines" , "day"=>"day","night"=>"night"
    	);
?>
	<div class="cosmo-tabs style_switcher" >
		<div class="show_colors fr"></div>
		<div class="tabs-container fl">
			<div id="header_footer_inputs" class="switcher-inputs">
				<?php
					

					if ( isset($_COOKIE["megusta_bg_image"]) ){
						$current_body_bg_img = $_COOKIE["megusta_bg_image"];
					}else{
						$current_body_bg_img = options::get_value( 'styling' , 'background' ) ;
					}


					
				?>
				<div>
					<p><?php _e('Footer bg color','cosmotheme'); ?></p> <br/>
					<a href="#" class="pickcolor hide-if-no-js" id="link_pick_b_f_bg_color" ></a>
					<input type="text"  id="pick_b_f_bg_color" op_name="b_f_bg_color" zone="footer" value="<?php echo $footer_background_color ?>" />
					<div id="colorPickerDiv_b_f_bg_color" class="colorPickerDiv" style="z-index: 100;  background:#eee; border:1px solid #ccc; position:absolute; display:none;"></div>
				</div>
			</div>
			
			<div id="content_inputs" class="switcher-inputs" >
				<?php 
					$current_content_style = get_content_bg_color();
					/*if ( isset($_COOKIE["content_bg_color"]) ){
						$current_content_style = $_COOKIE["content_bg_color"];
					}else{
						$current_content_style = admin_options::get_values('styling_options' , 'content_background_color') ; 

					}*/
					
				?>
				<div>
					<p><?php _e('Content bg color','cosmotheme'); ?></p> <br/>
					<a href="#" class="pickcolor hide-if-no-js" id="link_pick_content_bg_color"></a>
					<input type="text" id="pick_content_bg_color" op_name="content_bg_color" zone="content" value="<?php echo $background_color ?>" />
					<div id="colorPickerDiv_content_bg_color" class="colorPickerDiv" style="z-index: 100; background:#eee; border:1px solid #ccc; position:absolute; display:none;"></div>
				</div>
			</div>
			
			<div id="patterns_inputs">
				<p><?php _e('Patterns','cosmotheme'); ?></p>
				<?php
				foreach ($theme_bgs as $theme_bg) {
					if( trim($current_body_bg_img) == trim(array_search($theme_bg,$theme_bgs)) ){
							$rd_selected = 'checked="checked"';
						}
						else{
							$rd_selected = '';
						}
					//$sample_bg_image = get_template_directory_uri().'/images/backend/pattern/b.pattern.'.array_search($theme_bg,$theme_bgs).'.png';
					$sample_bg_image = get_template_directory_uri().'/lib/images/pattern/b.pattern.'.array_search($theme_bg,$theme_bgs).'.png';  
					  
					echo '<a href="javascript:void(0)" onclick="setBgImage(\'rb_'.array_search($theme_bg,$theme_bgs).'\')" class=" cosmo-pattern active" style="background-image:url('.$sample_bg_image.');" title="'.$theme_bg.'">'.$theme_bg.'</a>';
					echo '<input type="radio" name="skin_bg_rb" value="'.array_search($theme_bg,$theme_bgs).'" '.$rd_selected.' id="rb_'.array_search($theme_bg,$theme_bgs).'"  style="display:none"/>';
				}
				/*for No BG image*/
						if( trim($current_body_bg_img) == 'none' ){
							$rd_selected = 'checked="checked"';
						}
						else{
							$rd_selected = '';
						}
				/*$sample_bg_image = get_template_directory_uri().'/images/backend/pattern/b.pattern.none.png';*/
				echo '<div style=" margin: 0 0 0 13px;">';
				echo '<a href="javascript:void(0)" onclick="setBgImage(\'rb_none\')" class=" cosmo-pattern active"  title="none">none</a>';
				echo '<input type="radio" name="skin_bg_rb" value="none" '.$rd_selected.' id="rb_none"  style="display:none"/>';
				echo '</div>';	
				?>
			</div>
			
			<div>
				<?php 
					$boxed = de_boxed(); 
					if(trim($boxed) == 'larger'){$checked = 'checked';} else {$checked = '';}
				?>
				<p><?php _e('Boxed body','cosmotheme'); ?></p> <input type="checkbox" <?php echo $checked; ?> onclick="doBoxed(jQuery(this));"/>
				  
			</div>
			
			<div>
				<?php 
					$day_night = get_day_night(); 
					if(trim($day_night) == 'day'){
						$label = __('Day mode','cosmotheme');
						$checked = "checked='checked'";
					} else {
						$label = __('Night mode','cosmotheme');
						$checked = "";
					}
				?>
				<p id="day_night_mode" class="<?php echo trim($day_night); ?>_mode" mode="<?php echo trim($day_night); ?>" onclick="doDayNight(jQuery(this));" ><span id="day_night_label"><?php echo $label; ?></span> </p> 
				  
			</div>
			
		</div>
		
	</div>
<?php
	}
?>