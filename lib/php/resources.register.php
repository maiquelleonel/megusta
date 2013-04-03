<?php
    $sidebar_value = extra::select_value( '_sidebar' );

    if(!( is_array( $sidebar_value ) || !empty( $sidebar_value ) ) ){
        $sidebar_value = array();
    }

    /* hide if is full width */
    $classes = 'sidebar_list';

    if( isset( $_GET['post'] ) ){
        $meta = meta::get_meta( (int) $_GET['post'] , 'layout' );

        if( isset( $meta['type'] ) && $meta['type'] == 'full' ){
            $classes = 'sidebar_list hidden';
        }
    }

    $form['post']['layout']['type']         = array( 'type' => 'sh--select' , 'label' =>  __( 'Select layout type' , 'cosmotheme' ) , 'value' => array( 'right' => __( 'Right Sidebar'  , 'cosmotheme' ) , 'left' => __( 'Left Sidebar' , 'cosmotheme' ) , 'full' => __( 'Full Width' , 'cosmotheme' )  ) , 'action' => "act.select( '#post_layout' , { 'full' : '.sidebar_list' } , 'hs_');" , 'id' => 'post_layout' , 'ivalue' =>  options::get_value( 'layout' , 'single' ) );
    $form['post']['layout']['sidebar']      = array( 'type' => 'sh--select' , 'label' =>  __( 'Select sidebar' , 'cosmotheme' ) , 'value' => $sidebar_value , 'classes' => $classes );
    $form['post']['layout']['link']         = array( 'type' => 'sh--link' , 'url' => 'admin.php?page=cosmothemes___sidebar' , 'title' => __( 'Add new Sidebar' , 'cosmotheme' ) );

    if( options::get_value( 'layout' , 'single' ) == 'full' ){
        $form['post']['layout']['sidebar']['classes'] = $classes . ' hidden';
        $form['post']['layout']['link']['classes'] = $classes .' hidden';
    }
    
    /* standard post */
    $form['post']['settings']['safe']       = array( 'type' => 'st--logic-radio' , 'label' => __( 'Not safe' , 'cosmotheme' ) , 'cvalue' => 'no' );
    $form['post']['settings']['related']    = array( 'type' => 'st--logic-radio' , 'label' => __( 'Show related posts' , 'cosmotheme' ) , 'hint' => __( 'Show related mosts on this post' , 'cosmotheme' ) , 'cvalue' => options::get_value(  'blog_post' , 'show_similar' ) );
    $form['post']['settings']['meta']       = array( 'type' => 'st--logic-radio' , 'label' => __( 'Show post meta' , 'cosmotheme' ) , 'hint' => __( 'Show post meta on this post' , 'cosmotheme' ) , 'cvalue' => 'yes' );
    $form['post']['settings']['love']       = array( 'type' => 'st--logic-radio' , 'label' => __( 'Show post love' , 'cosmotheme' ) , 'hint' => __( 'Show post love on this post' , 'cosmotheme' )  , 'cvalue' => options::get_value(  'general' , 'enb_likes' ) );
    $form['post']['settings']['sharing']    = array( 'type' => 'st--logic-radio' , 'label' => __( 'Show social sharing' , 'cosmotheme' ) , 'hint' => __( 'Show social mharing on this post'  , 'cosmotheme' ) , 'cvalue' => options::get_value( 'blog_post' , 'post_sharing' ) );
    $form['post']['settings']['author']     = array( 'type' => 'st--logic-radio' , 'label' => __( 'Show author box' , 'cosmotheme' ) , 'hint' => __( 'Show author box on this post'  , 'cosmotheme' ) , 'cvalue' => options::get_value( 'blog_post' , 'post_author_box' ) );

    if( isset( $_GET['post'] ) ){
        $post_format = get_post_format( $_GET['post'] );
    }else{
        $post_format = 'standard';
    }
    
    $form['post']['format']['type']         = array( 'type' => 'st--select' , 'label' => __( 'Select post format' , 'cosmotheme' ) , 'value' => array(  'standard' => __( 'Standard' , 'cosmotheme' ) , 'video' => __( 'Video' , 'cosmotheme' ) , 'image' => __( 'Image' , 'cosmotheme' ) , 'audio' => __( 'Audio' , 'cosmotheme' )  , 'link' => __( 'Attachment' , 'cosmotheme' ) )  , 'action' => "act.select( '.post_format_type' , { 'video' : '.post_format_video' , 'image' : '.post_format_image' , 'audio' : '.post_format_audio' , 'link' : '.post_format_link' } , 'sh_' );" , 'iclasses' => 'post_format_type' , 'ivalue' =>  $post_format );

   $form['post']['format']['init']=array('type'=>"no--form-upload-init");


	if( isset( $_GET['post'] ) && get_post_format( $_GET['post'] ) == 'video' ){
		$form['post']['format']['video']=array('type'=>'ni--form-upload', 'format'=>'video', 'classes'=>"post_format_video", 'post_id'=>$_GET['post']);
    }else{
		$form['post']['format']['video']=array('type'=>'ni--form-upload', 'format'=>'video', 'classes'=>"hidden post_format_video");
    }

    if( isset( $_GET['post'] ) && get_post_format( $_GET['post'] ) == 'image' ){
		$form['post']['format']['image']=array('type'=>'ni--form-upload', 'format'=>'image', 'classes'=>"post_format_image", 'post_id'=>$_GET['post']);
//         $form['post']['format']['image']        = array( 'type' => 'st--hint' , 'label' => '' , 'value' => __( 'Please set featured image'  , 'cosmotheme' )  , 'classes' => 'post_format_image' );
    }else{
		$form['post']['format']['image']=array('type'=>'ni--form-upload', 'format'=>'image', 'classes'=>"post_format_image hidden");
//         $form['post']['format']['image']        = array( 'type' => 'st--hint' , 'label' => '' , 'value' => __( 'Please set featured image'  , 'cosmotheme' )  , 'classes' => 'post_format_image hidden' );
    }

    if( isset( $_GET['post'] ) && get_post_format( $_GET['post'] ) == 'audio' ){
		$form['post']['format']['audio']=array('type'=>'ni--form-upload', 'format'=>'audio', 'classes'=>"post_format_audio", 'post_id'=>$_GET['post']);
//         $form['post']['format']['audio']        = array( 'type' => 'st--upload' , 'label' => __( 'Please add audio file or URL'  , 'cosmotheme' )  , 'classes' => 'post_format_audio' , 'id' => 'format_audio' , 'hint' => __( 'Please use  only MP3 files' , 'cosmotheme' ) );
    }else{
		$form['post']['format']['audio']=array('type'=>'ni--form-upload', 'format'=>'audio', 'classes'=>"post_format_audio hidden");
//         $form['post']['format']['audio']        = array( 'type' => 'st--upload' , 'label' => __( 'Please add audio file or URL'  , 'cosmotheme' )  , 'classes' => 'post_format_audio hidden' , 'id' => 'format_audio' , 'hint' => __( 'Please use  only MP3 files' , 'cosmotheme' ) );
    }
    
    if( isset( $_GET['post'] ) && get_post_format( $_GET['post'] ) == 'link' ){
		$form['post']['format']['link']=array('type'=>'ni--form-upload', 'format'=>'links', 'classes'=>"post_format_link", 'post_id'=>$_GET['post']);
//         $form['post']['format']['link']        = array( 'type' => 'st--upload-id' , 'label' => __( 'Please add attachment file or URL'  , 'cosmotheme' )  , 'classes' => 'post_format_link' , 'id' => 'format_link' , 'hint' => __( 'Please use only .ZIP, .RAR, .DOC, .DOCX, .PDF files' , 'cosmotheme' ) );
    }else{
		$form['post']['format']['link']=array('type'=>'ni--form-upload', 'format'=>'link', 'classes'=>"post_format_link hidden");
//         $form['post']['format']['link']        = array( 'type' => 'st--upload-id' , 'label' => __( 'Please add attachment file or URL'  , 'cosmotheme' )  , 'classes' => 'post_format_link hidden' , 'id' => 'format_link' , 'hint' => __( 'Please use only .ZIP, .RAR, .DOC, .DOCX, .PDF files' , 'cosmotheme' ) );
    }


    $form['post']['source']['post_source']   = array( 'type' => 'st--text' , 'label' => __( 'Source' , 'cosmotheme' ) , 'hint' => __( 'Example: http://cosmothemes.com' , 'cosmotheme' ) );
    
    $box['post']['shcode']                  = array( __('Shortcodes' , 'cosmotheme' ) , 'normal' , 'high'  , 'box' => 'shcode' , 'includes' => 'shcode/main.php' );
    $box['post']['layout']                  = array( __('Layout and Sidebars' , 'cosmotheme' ) , 'side' , 'low' , 'content' => $form['post']['layout'] , 'box' => 'layout' , 'update' => true  );
    $box['post']['settings']                = array( __('Post Settings' , 'cosmotheme' ) , 'normal' , 'high' , 'content' => $form['post']['settings'] , 'box' => 'settings' , 'update' => true  );
    $box['post']['format']                  = array( __('Post Format' , 'cosmotheme' ) , 'normal' , 'high' , 'content' => $form['post']['format'] , 'box' => 'format' , 'update' => true );
    $box['post']['source']                  = array( __('Post Source' , 'cosmotheme' ) , 'normal' , 'high' , 'content' => $form['post']['source'] , 'box' => 'source' , 'update' => true );
    

    resources::$type['post']                = array();
    resources::$box['post']                 = $box['post'];

    $form['page']['layout']['type']         = array( 'type' => 'sh--select' , 'label' =>  __( 'Select layout type' , 'cosmotheme' ) , 'value' => array( 'right' => __( 'Right Sidebar'  , 'cosmotheme' ) , 'left' => __( 'Left Sidebar' , 'cosmotheme' ) , 'full' => __( 'Full Width' , 'cosmotheme' )  ) , 'action' => "act.select( '#post_layout' , { 'full' : '.sidebar_list' } , 'hs_');" , 'id' => 'post_layout' , 'ivalue' =>  options::get_value( 'layout' , 'page' ) );
    $form['page']['layout']['sidebar']      = array( 'type' => 'sh--select' , 'label' =>  __( 'Select sidebar' , 'cosmotheme' ) , 'value' => $sidebar_value , 'classes' => $classes );
    $form['page']['layout']['link']         = array( 'type' => 'sh--link' , 'url' => 'admin.php?page=cosmothemes___sidebar' , 'title' => __( 'Add new Sidebar' , 'cosmotheme' ) );

    if( options::get_value( 'layout' , 'page' ) == 'full' ){
        $form['page']['layout']['sidebar']['classes'] = $classes . ' hidden';
        $form['page']['layout']['link']['classes'] = $classes .' hidden';
    }
    
    $form['page']['settings']['meta']       = array( 'type' => 'st--logic-radio' , 'label' => __( 'Show page meta' , 'cosmotheme' ) , 'hint' => 'Show post meta on this page' , 'cvalue' => 'no' );
    $form['page']['settings']['sharing']    = array( 'type' => 'st--logic-radio' , 'label' => __( 'Show social sharing' , 'cosmotheme' ) , 'hint' => 'Show social sharing on this page' , 'cvalue' => options::get_value( 'blog_post' , 'page_sharing' ) );
    $form['page']['settings']['author']     = array( 'type' => 'st--logic-radio' , 'label' => __( 'Show author box' , 'cosmotheme' ) , 'hint' => 'Show author box on this page' , 'cvalue' => options::get_value( 'blog_post' , 'page_author_box' ) );

    $box['page']['shcode']                  = array( __('Shortcodes' , 'cosmotheme' ) , 'normal' , 'high'  , 'box' => 'shcode' , 'includes' => 'shcode/main.php' );
    $box['page']['layout']                  = array( __('Layout and Sidebars' , 'cosmotheme' ) , 'side' , 'low' , 'content' => $form['page']['layout'] , 'box' => 'layout' , 'update' => true  );
    $box['page']['settings']                = array( __('Page Settings' , 'cosmotheme' ) , 'normal' , 'high' , 'content' => $form['page']['settings'] , 'box' => 'settings' , 'update' => true  );
    
    
    resources::$type['page']                = array();
    resources::$box['page']                 = $box['page'];
?>