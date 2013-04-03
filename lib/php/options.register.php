<?php
    /* register pages */
	if( function_exists( 'wp_get_theme' ) ){
       $current_theme_name = wp_get_theme();    
    }else{

        $current_theme_name = get_current_theme();
    }

    options::$menu['cosmothemes']['general']            = array( 'label' => __( 'General' , 'cosmotheme' ) , 'title' => __( 'General Settings' , 'cosmotheme' ) , 'description' => __( 'General page description.' , 'cosmotheme' ) , 'type' => 'main' , 'main_label' => $current_theme_name );
    options::$menu['cosmothemes']['front_page']         = array( 'label' => __( 'Front Page' , 'cosmotheme' )  , 'title' => __( 'Front Page Settings' , 'cosmotheme' )  , 'description' => __( 'Front page description.' , 'cosmotheme' ) );
    options::$menu['cosmothemes']['layout']             = array( 'label' => __( 'Layout' , 'cosmotheme' )  , 'title' => __( 'Layout Page Settings' , 'cosmotheme' )  , 'description' => __( 'Layout page description.' , 'cosmotheme' ) );
    options::$menu['cosmothemes']['styling']            = array( 'label' => __( 'Styling' , 'cosmotheme' )  , 'title' => __( 'Styling Settings' , 'cosmotheme' )  , 'description' => __( 'Styling page description.' , 'cosmotheme' ) );
    options::$menu['cosmothemes']['menu']               = array( 'label' => __( 'Menu' , 'cosmotheme' )  , 'title' => __( 'Menu Settings' , 'cosmotheme' )  , 'description' => __( 'Menu page description.' , 'cosmotheme' ) );
    options::$menu['cosmothemes']['blog_post']          = array( 'label' => __( 'Blogging' , 'cosmotheme' )  , 'title' => __( 'Blog Post Settings' , 'cosmotheme' )  , 'description' => __( 'Blog post page description.' , 'cosmotheme' ) );
    options::$menu['cosmothemes']['advertisement']      = array( 'label' => __( 'Advertisement' , 'cosmotheme' )  , 'title' => __( 'Advertisement Spaces' , 'cosmotheme' )  , 'description' => __( 'Sidebar manager page description.' , 'cosmotheme' ) );
    options::$menu['cosmothemes']['social']             = array( 'label' => __( 'Social' , 'cosmotheme' )  , 'title' => __( 'Social Settings' , 'cosmotheme' )  , 'description' => __( 'Social page description.' , 'cosmotheme' ) );
	options::$menu['cosmothemes']['upload']             = array( 'label' => __( 'Upload' , 'cosmotheme' )  , 'title' => __( 'Front end tabs' , 'cosmotheme' )  , 'description' => __( 'Front end tabs settings.' , 'cosmotheme' ) );
    options::$menu['cosmothemes']['_sidebar']           = array( 'label' => __( 'Sidebar' , 'cosmotheme' )  , 'title' => __( 'Sidebar Manager' , 'cosmotheme' )  , 'description' => __( 'Sidebar manager page description.' , 'cosmotheme' ) , 'update' => false );
	options::$menu['cosmothemes']['cosmothemes']        = array( 'label' => __( 'CosmoThemes' , 'cosmotheme' )  , 'title' => __( 'CosmoThemes' , 'cosmotheme' )  , 'description' => __( 'CosmoThemes notifications.' , 'cosmotheme' ) );

    /* OPTIONS */
    /* GENERAL DEFAULT VALUE */
    options::$default['general']['enb_keyboard']        = 'yes';
    options::$default['general']['enb_likes']           = 'yes';
    options::$default['general']['min_likes']           =  50;
    options::$default['general']['user_register']       = 'yes';
    options::$default['general']['user_login']          = 'yes';
    options::$default['general']['like_register']       = 'no';
    options::$default['general']['enb_featured']        = 'yes';
    options::$default['general']['enb_lightbox']        = 'yes';
    options::$default['general']['breadcrumbs']         = 'yes';
    options::$default['general']['meta']                = 'yes';
    options::$default['general']['time']                = 'yes';
    options::$default['general']['fb_comments']         = 'yes';
	options::$default['general']['show_admin_bar']      = 'yes';

    /* GENERAL OPTIONS */
    options::$fields['general']['enb_keyboard']         = array( 'type' => 'st--logic-radio' , 'label' => __( 'Enable keyboard demo' , 'cosmotheme' ) , 'hint' => __( 'If enabled users can click on the keyboard icon to visualize keyboard hot-keys.' , 'cosmotheme' ) );
	options::$fields['general']['enb_likes']            = array( 'type' => 'st--logic-radio' , 'label' => __( 'Enable Love for posts' , 'cosmotheme') , 'action' => "act.check( this , { 'yes' : '.g_like , .g_l_register' , 'no' : '' } , 'sh' );" , 'iclasses' => 'g_e_likes');
    options::$fields['general']['min_likes']            = array( 'type' => 'st--digit-like' , 'label' => __( 'Minimum number of Loves to set Hot' , 'cosmotheme' ) , 'hint' => __( 'Set minimum number of post likes to change it in hot post' , 'cosmotheme' ) , 'id' => 'nr_min_likes' ,'action' => "act.min_likes(  jQuery( '#nr_min_likes').val() , 1 );"  );
	options::$fields['general']['sim_likes']            = array( 'type' => 'st--button' , 'value' => __( 'Simulate' , 'cosmotheme' ) , 'label' => __( 'Simulate random number of Loves for posts' , 'cosmotheme' ) , 'action' => "act.sim_likes( 1 );" , 'hint' => __( 'WARNING! This will reset all current Loves.' , 'cosmotheme' ) );
	options::$fields['general']['reset_likes']			= array( 'type' => 'st--button' , 'value' => __( 'Reset' , 'cosmotheme' ) , 'label' => __( 'Reset likes' , 'cosmotheme' ) , 'action' =>"act.reset_likes(1);" , 'hint' => __( 'WARNING! This will reset all the likes for all the posts!' ) );
	
	$default_post_status = array('publish' => __('Published','cosmotheme'), 'pending' => __('Pending','cosmotheme') );  
	options::$fields['general']['default_posts_status'] = array('type' => 'st--select' , 'label' => __( 'Default Posts Status' , 'cosmotheme' ) ,'hint' => __('This is the status used for posts submited from front end.','cosmotheme'), 'value' => $default_post_status );
    options::$default['general']['default_posts_status'] = 'publish';
	/* minimum number of like for hot posts */

	options::$fields['general']['user_profile_page']    = array( 'type' => 'st--select' , 'label' => __( 'Select My account page' , 'cosmotheme' ) , 'value' => get__pages() , 'hint' => __('Select a blank page from the list to generate the My account page','cosmotheme'));
    options::$fields['general']['user_login']           = array( 'type' => 'st--logic-radio' , 'label' => __( 'Enable user login' , 'cosmotheme') , 'hint' => __( 'check to show "register" link.'  , 'cosmotheme' ) , 'action' => "act.check( this , { 'yes' : '.g_l_register' , 'no' : '' } , 'sh' );act.mcheck( [ '.yes.g_e_likes' , '.yes.g_u_register']  );" , 'iclasses' => 'g_u_register' );
    options::$fields['general']['like_register']        = array( 'type' => 'st--logic-radio' , 'label' => __( 'Registration is required to Love a post' , 'cosmotheme') );

    if( options::logic( 'general' , 'enb_likes' ) ){
        options::$fields['general']['min_likes']['classes']     = 'g_like';
        options::$fields['general']['like_register']['classes'] = 'g_l_register';
		options::$fields['general']['sim_likes']['classes']     = 'g_like generate_likes';
		options::$fields['general']['reset_likes']['classes']	= 'g_like reset_likes';
    }else{
        options::$fields['general']['min_likes']['classes']     = 'g_like hidden';
        options::$fields['general']['like_register']['classes'] = 'g_l_register hidden';
		options::$fields['general']['sim_likes']['classes']     = 'g_like generate_likes hidden';
		options::$fields['general']['reset_likes']['classes']	= 'g_like reset_likes hidden';
    }

    options::$fields['general']['enb_featured']         = array('type' => 'st--logic-radio' , 'label' => __( 'Display featured image inside post' , 'cosmotheme' ) , 'hint' => __( 'If enabled featured images will be displayed both on category and post' , 'cosmotheme' ) );
    options::$fields['general']['enb_lightbox']         = array('type' => 'st--logic-radio' , 'label' => __( 'Enable pretty-photo ligthbox' , 'cosmotheme' ) , 'hint' => __( 'Images inside posts will open inside a fancy lightbox' , 'cosmotheme' ) );
    options::$fields['general']['breadcrumbs']          = array( 'type' => 'st--logic-radio' , 'label' => __( 'Show breadcrumbs' , 'cosmotheme') );
    options::$fields['general']['meta']                 = array( 'type' => 'st--logic-radio' , 'label' => __( 'Show entry meta' , 'cosmotheme' ) , 'hint' => __( ' in blog / archive / search / tag / category page' , 'cosmotheme' ) );
    options::$fields['general']['time']                 = array( 'type' => 'st--logic-radio' , 'label' => __( 'Use human time' , 'cosmotheme') ,  'hint' => __( 'if you do not use this option, will be used wordpress time format'  , 'cosmotheme' ) );
    options::$fields['general']['fb_comments']          = array( 'type' => 'st--logic-radio' , 'label' => __( 'Use facebook comments' , 'cosmotheme' ), 'action' => "act.check( this , { 'yes' : '.fb_app_id ' , 'no' : '' } , 'sh' );" ,);
	options::$fields['general']['fb_app_id_note']       = array( 'type' => 'st--hint' , 'value' => __( 'You can set facebook application ID' , 'cosmotheme' ) . ' <a href="admin.php?page=cosmothemes__social">' . __( 'here' , 'cosmotheme') . '</a> ' );
	options::$fields['general']['show_admin_bar']       = array( 'type' => 'st--logic-radio' , 'label' => __( 'Show Wordpress Admin Bar' , 'cosmotheme' ));

	if( options::logic( 'general' , 'fb_comments' ) ){
        options::$fields['general']['fb_app_id_note']['classes']     = 'fb_app_id';
    }else{
        options::$fields['general']['fb_app_id_note']['classes']     = 'fb_app_id hidden';
    }

    options::$fields['general']['tracking_code']        = array('type' => 'st--textarea' , 'label' => __( 'Tracking Code' , 'cosmotheme' ) , 'hint' => __( 'Paste your Google Analytics or other tracking code here,<br /> and it will be added into the footer of your template.' , 'cosmotheme' ) );
    options::$fields['general']['copy_right']   	    = array('type' => 'st--textarea' , 'label' => __( 'Copyright' , 'cosmotheme' ) , 'hint' => __( 'Type here the Copyright text that will appear in the footer. To display the current year use "%year%" and it will be replaced.' , 'cosmotheme' ) );
    
    options::$default['general']['copy_right'] 			= 'Me Gusta Copyright &copy; %year% <a href="http://cosmothemes.com" target="_blank">CosmoThemes</a>. All rights reserved.';
 

	/*Front end tabs settings*/
	options::$default['upload']['enb_image']        = 'yes';
	options::$default['upload']['enb_video']        = 'yes';
	options::$default['upload']['enb_text']        = 'yes';
	options::$default['upload']['enb_file']        = 'yes';
	options::$default['upload']['enb_audio']        = 'yes';
	options::$default['upload']['enb_edit_delete']  = 'yes';

	options::$fields['upload']['enb_image']       = array( 'type' => 'st--logic-radio' , 'label' => __( 'Enable image posts' , 'cosmotheme') , 'hint' => __('If enabled, then user will be able to post Image posts from front end','cosmotheme') );
	options::$fields['upload']['enb_video']       = array( 'type' => 'st--logic-radio' , 'label' => __( 'Enable video posts' , 'cosmotheme') , 'hint' => __('If enabled, then user will be able to post Video posts from front end','cosmotheme') );
	options::$fields['upload']['enb_text']        = array( 'type' => 'st--logic-radio' , 'label' => __( 'Enable text posts' , 'cosmotheme') , 'hint' => __('If enabled, then user will be able to post Text posts from front end','cosmotheme') );
	options::$fields['upload']['enb_file']        = array( 'type' => 'st--logic-radio' , 'label' => __( 'Enable file posts' , 'cosmotheme') , 'hint' => __('If enabled, then user will be able to post File(Attachments) posts from front end','cosmotheme') );
	options::$fields['upload']['enb_audio']       = array( 'type' => 'st--logic-radio' , 'label' => __( 'Enable audio posts' , 'cosmotheme') , 'hint' => __('If enabled, then user will be able to post Audio posts from front end','cosmotheme') );
	options::$fields['upload']['enb_edit_delete'] = array( 'type' => 'st--logic-radio' , 'label' => __( 'Enable users to edit/delete own posts' , 'cosmotheme')  );

	options::$fields['upload']['default_edit_status'] = array('type' => 'st--select' , 'label' => __( 'Default Posts Status for edited posts' , 'cosmotheme' ) ,'hint' => __('This is the status used for posts edited from front end.','cosmotheme'), 'value' => $default_post_status );
    options::$default['upload']['default_edit_status'] = 'publish';
	
    /* LAYOUT DEFAULT VALUE */
    options::$default['layout']['front_page']           = 'right';
    options::$default['layout']['v_front_page']         = 'yes';
    options::$default['layout']['404']                  = 'right';
    options::$default['layout']['author']               = 'right';
    options::$default['layout']['v_author']             = 'yes';
    options::$default['layout']['page']                 = 'full';
    options::$default['layout']['single']               = 'right';
    options::$default['layout']['blog_page']            = 'right';
    options::$default['layout']['v_blog_page']          = 'yes';

    options::$default['layout']['search']               = 'right';
    options::$default['layout']['v_search']             = 'yes';
    options::$default['layout']['archive']              = 'right';
    options::$default['layout']['v_archive']            = 'yes';
    options::$default['layout']['category']             = 'right';
    options::$default['layout']['v_category']           = 'yes';
    options::$default['layout']['tag']                  = 'right';
    options::$default['layout']['v_tag']                = 'yes';
    options::$default['layout']['attachment']           = 'right';

    /* LAYOUT OPTIONS */
    $layouts                                            = array('left' => __( 'Left Sidebar' , 'cosmotheme' ) , 'right' => __( 'Right Sidebar' , 'cosmotheme' ) , 'full' => __( 'Full Width' , 'cosmotheme' ) );
    $view                                               = array('yes' => __( 'List view' , 'cosmotheme' ) , 'no' => __( 'Grid view' , 'cosmotheme' ) ); /* yes - list view , no - grid view */
    $sidebars_record = options::get_value( '_sidebar' );
    if( !is_array( $sidebars_record ) || empty( $sidebars_record ) ){
        $sidebar = array( '' => 'main' );
    }else{
        foreach( $sidebars_record as $sidebars ){
            $sidebar[ trim( strtolower( str_replace( ' ' , '-' , $sidebars['title'] ) ) ) ] = $sidebars['title'];
        }
        $sidebar[''] = 'main';
    }

    options::$fields['layout']['title0']                = array('type' => 'ni--title' , 'title' => __( 'Front page' , 'cosmotheme' ) );
    options::$fields['layout']['front_page']            = array('type' => 'st--select' , 'label' => __( 'Layout for front page' , 'cosmotheme' ) , 'value' => $layouts , 'action' => "act.select('.front_page_layout' , { 'left' : '.front_page_sidebar' , 'right' : '.front_page_sidebar' } , 'sh_' )" , 'iclasses' => 'front_page_layout' );
    options::$fields['layout']['front_page_sidebar']    = array('type' => 'st--select' , 'label' => __( 'Select sidebar for front page template.' , 'cosmotheme' ) , 'value' =>  $sidebar , 'classes' => 'front_page_sidebar' );
    if( options::get_value( 'layout' , 'front_page' ) == 'full' ){
        options::$fields['layout']['front_page_sidebar']['classes'] = 'front_page_sidebar hidden';
    }

    options::$fields['layout']['v_front_page']          = array('type' => 'st--select' , 'label' => __( 'Content view for front page' , 'cosmotheme') , 'value' => $view );
    options::$fields['layout']['title1']                = array('type' => 'ni--title' , 'title' => __( '404' , 'cosmotheme' ) );
    options::$fields['layout']['404']                   = array('type' => 'st--select' , 'label' => __( 'Layout for 404 page' , 'cosmotheme' ) , 'value' => $layouts , 'action' => "act.select('.layout_404' , { 'left' : '.sidebar_404' , 'right' : '.sidebar_404' } , 'sh_' )" , 'iclasses' => 'layout_404'  );
    options::$fields['layout']['404_sidebar']           = array('type' => 'st--select' , 'label' => __( 'Select sidebar for 404 template.' , 'cosmotheme' ) , 'value' =>  $sidebar , 'classes' => 'sidebar_404' );
    if( options::get_value( 'layout' , '404' ) == 'full' ){
        options::$fields['layout']['404_sidebar']['classes'] = 'sidebar_404 hidden';
    }
    options::$fields['layout']['title2']                = array('type' => 'ni--title' , 'title' => __( 'Author' , 'cosmotheme' ) );
    options::$fields['layout']['author']                = array('type' => 'st--select' , 'label' => __( 'Layout for author page' , 'cosmotheme' ) , 'value' => $layouts , 'action' => "act.select('.author_layout' , { 'left' : '.author_sidebar' , 'right' : '.author_sidebar' } , 'sh_' )" , 'iclasses' => 'author_layout' );
    options::$fields['layout']['author_sidebar']        = array('type' => 'st--select' , 'label' => __( 'Select sidebar for author template.' , 'cosmotheme' ) , 'value' =>  $sidebar , 'classes' => 'author_sidebar' );
    if( options::get_value( 'layout' , 'author' ) == 'full' ){
        options::$fields['layout']['author_sidebar']['classes'] = 'author_sidebar hidden';
    }
    options::$fields['layout']['v_author']              = array('type' => 'st--select' , 'label' => __( 'Content view for author page' , 'cosmotheme') , 'value' => $view );
    options::$fields['layout']['title3']                = array('type' => 'ni--title' , 'title' => __( 'Pages / single post' , 'cosmotheme' ) );
    options::$fields['layout']['page']                  = array('type' => 'st--select' , 'label' => __( 'Layout for pages' , 'cosmotheme' ) , 'value' => $layouts , 'action' => "act.select('.page_layout' , { 'left' : '.page_sidebar' , 'right' : '.page_sidebar' } , 'sh_' )" , 'iclasses' => 'page_layout' );
    options::$fields['layout']['page_sidebar']          = array('type' => 'st--select' , 'label' => __( 'Select sidebar for page template.' , 'cosmotheme' ) , 'value' =>  $sidebar , 'classes' => 'page_sidebar' );
    if( options::get_value( 'layout' , 'page' ) == 'full' ){
        options::$fields['layout']['page_sidebar']['classes'] = 'page_sidebar hidden';
    }
    options::$fields['layout']['single']                = array('type' => 'st--select' , 'label' => __( 'Layout for single post' , 'cosmotheme' ) , 'value' => $layouts , 'action' => "act.select('.single_layout' , { 'left' : '.single_sidebar' , 'right' : '.single_sidebar' } , 'sh_' )" , 'iclasses' => 'single_layout' );
    options::$fields['layout']['single_sidebar']        = array('type' => 'st--select' , 'label' => __( 'Select sidebar for single page template.' , 'cosmotheme' ) , 'value' =>  $sidebar , 'classes' => 'single_sidebar' );
    if( options::get_value( 'layout' , 'single' ) == 'full' ){
        options::$fields['layout']['single_sidebar']['classes'] = 'single_sidebar hidden';
    }
    options::$fields['layout']['title13']               = array('type' => 'ni--title' , 'title' => __( 'Blog Page' , 'cosmotheme' ) );
    options::$fields['layout']['blog_page']             = array('type' => 'st--select' , 'label' => __( 'Layout for blog page' , 'cosmotheme' ) , 'value' => $layouts , 'action' => "act.select('.blog_page_layout' , { 'left' : '.blog_page_sidebar' , 'right' : '.blog_page_sidebar' } , 'sh_' )" , 'iclasses' => 'blog_page_layout' );
    options::$fields['layout']['blog_page_sidebar']     = array('type' => 'st--select' , 'label' => __( 'Select sidebar for blog page template.' , 'cosmotheme' ) , 'value' =>  $sidebar , 'classes' => 'blog_page_sidebar' );
    if( options::get_value( 'layout' , 'blog_page' ) == 'full' ){
        options::$fields['layout']['blog_page_sidebar']['classes'] = 'blog_page_sidebar hidden';
    }
    options::$fields['layout']['v_blog_page']           = array('type' => 'st--select' , 'label' => __( 'Content view for blog page' , 'cosmotheme') , 'value' => $view );

    options::$fields['layout']['title4']                = array('type' => 'ni--title' , 'title' => __( 'Search' , 'cosmotheme' ) );
    options::$fields['layout']['search']                = array('type' => 'st--select' , 'label' => __( 'Layout for search page' , 'cosmotheme' ) , 'value' => $layouts , 'action' => "act.select('.search_layout' , { 'left' : '.search_sidebar' , 'right' : '.search_sidebar' } , 'sh_' )" , 'iclasses' => 'search_layout' );
    options::$fields['layout']['search_sidebar']        = array('type' => 'st--select' , 'label' => __( 'Select sidebar for search template.' , 'cosmotheme' ) , 'value' =>  $sidebar , 'classes' => 'search_sidebar' );
    if( options::get_value( 'layout' , 'search' ) == 'full' ){
        options::$fields['layout']['search_sidebar']['classes'] = 'search_sidebar hidden';
    }
    options::$fields['layout']['v_search']              = array('type' => 'st--select' , 'label' => __( 'Content view for search page' , 'cosmotheme') , 'value' => $view );
    options::$fields['layout']['title5']                = array('type' => 'ni--title' , 'title' => __( 'Archive' , 'cosmotheme' ) );
    options::$fields['layout']['archive']               = array('type' => 'st--select' , 'label' => __( 'Layout for archive page' , 'cosmotheme' ) , 'value' => $layouts , 'action' => "act.select('.archive_layout' , { 'left' : '.archive_sidebar' , 'right' : '.archive_sidebar' } , 'sh_' )" , 'iclasses' => 'archive_layout' );
    options::$fields['layout']['archive_sidebar']       = array('type' => 'st--select' , 'label' => __( 'Select sidebar for archive template.' , 'cosmotheme' ) , 'value' =>  $sidebar , 'classes' => 'archive_sidebar' );
    if( options::get_value( 'layout' , 'archive' ) == 'full' ){
        options::$fields['layout']['archive_sidebar']['classes'] = 'archive_sidebar hidden';
    }
    options::$fields['layout']['v_archive']             = array('type' => 'st--select' , 'label' => __( 'Content view for archive page' , 'cosmotheme') , 'value' => $view );
    options::$fields['layout']['title6']                = array('type' => 'ni--title' , 'title' => __( 'Category' , 'cosmotheme' ) );
    options::$fields['layout']['category']              = array('type' => 'st--select' , 'label' => __( 'Layout for category page' , 'cosmotheme' ) , 'value' => $layouts , 'action' => "act.select('.category_layout' , { 'left' : '.category_sidebar' , 'right' : '.category_sidebar' } , 'sh_' )" , 'iclasses' => 'category_layout' );
    options::$fields['layout']['category_sidebar']      = array('type' => 'st--select' , 'label' => __( 'Select sidebar for category template.' , 'cosmotheme' ) , 'value' =>  $sidebar , 'classes' => 'category_sidebar' );
    if( options::get_value( 'layout' , 'category' ) == 'full' ){
        options::$fields['layout']['category_sidebar']['classes'] = 'category_sidebar hidden';
    }
    options::$fields['layout']['v_category']            = array('type' => 'st--select' , 'label' => __( 'Content view for category page' , 'cosmotheme') , 'value' => $view );;
    options::$fields['layout']['title7']                = array('type' => 'ni--title' , 'title' => __( 'Tag' , 'cosmotheme' ) );
    options::$fields['layout']['tag']                   = array('type' => 'st--select' , 'label' => __( 'Layout for tags page' , 'cosmotheme' ) , 'value' => $layouts , 'action' => "act.select('.tag_layout' , { 'left' : '.tag_sidebar' , 'right' : '.tag_sidebar' } , 'sh_' )" , 'iclasses' => 'tag_layout' );
    options::$fields['layout']['tag_sidebar']           = array('type' => 'st--select' , 'label' => __( 'Select sidebar for tag template.' , 'cosmotheme' ) , 'value' =>  $sidebar , 'classes' => 'tag_sidebar' );
    if( options::get_value( 'layout' , 'tag' ) == 'full' ){
        options::$fields['layout']['tag_sidebar']['classes'] = 'tag_sidebar hidden';
    }
    options::$fields['layout']['v_tag']                 = array('type' => 'st--select' , 'label' => __( 'Content view for tags page' , 'cosmotheme') , 'value' => $view );
    options::$fields['layout']['title8']                = array('type' => 'ni--title' , 'title' => '' );
    options::$fields['layout']['attachment']            = array('type' => 'st--select' , 'label' => __( 'Layout for attachment page' , 'cosmotheme' ) , 'value' => $layouts , 'action' => "act.select('.attachment_layout' , { 'left' : '.attachment_sidebar' , 'right' : '.attachment_sidebar' } , 'sh_' )" , 'iclasses' => 'attachment_layout' );
    options::$fields['layout']['attachment_sidebar']    = array('type' => 'st--select' , 'label' => __( 'Select sidebar for attachment template.' , 'cosmotheme' ) , 'value' =>  $sidebar , 'classes' => 'attachment_sidebar' );
    if( options::get_value( 'layout' , 'attachment' ) == 'full' ){
        options::$fields['layout']['attachment_sidebar']['classes'] = 'attachment_sidebar hidden';
    }

    /* FRONT-PAGE DEFAULT VALUES */
    options::$default['front_page']['type']             = 'new_posts';
    options::$default['front_page']['enb_float_box']    = 'yes';
    options::$default['front_page']['float_box']        = 'Welcome to Me Gusta! - the simplest way for everyone to publish, collect and share fun. You can create your fun portfolio or collection effortlessly. It\'s the best place where fun creators and bored people meet.';
    
    /* FRONT-PAGE OPTIONS */
    if( !options::logic( 'general' , 'enb_likes' ) ){
        $type = array( 'page' => __( 'Static Page' , 'cosmotheme' ) , 'new_posts' => __( 'New Posts' , 'cosmotheme' ) );
    }else{
        $type = array( 'page' => __( 'Static Page' , 'cosmotheme' ) , 'hot_posts' => __( 'Hot Posts' , 'cosmotheme' ) , 'new_posts' => __( 'New Posts' , 'cosmotheme' ) );
    }
    options::$fields['front_page']['type']              = array( 'type' => 'st--select' , 'label' => __( 'Select content type' , 'cosmotheme' ) , 'value' =>  $type , 'action' => "act.select( '.fp_type' , { 'page':'.fp_page' , 'hot_posts':'.fp_hot' , 'new_posts':'.fp_new' } , 'sh_' );" , 'iclasses' => 'fp_type' );
    options::$fields['front_page']['page']              = array( 'type' => 'st--select' , 'label' => __( 'Select static page for front page' , 'cosmotheme' ) , 'value' => get__pages() );
    options::$fields['front_page']['info_page']         = array( 'type' => 'st--hint' , 'value' => __( 'If you wish to set blog page go to '  , 'cosmotheme' ) . '<a href="options-reading.php">' . __( 'Settings -> Reading ' , 'cosmotheme' ) . '</a>' );
    options::$fields['front_page']['info_hot']          = array( 'type' => 'st--hint' , 'value' => __( 'Please set Love limit for Hot posts in '  , 'cosmotheme' ) . '<a href="admin.php?page=cosmothemes__general">' . __( 'General settings' , 'cosmotheme' ) . '</a>' );
    options::$fields['front_page']['info_new']          = array( 'type' => 'st--hint' , 'value' => __( 'Classic blog style '  , 'cosmotheme' ) );

    options::$fields['front_page']['enb_float_box']     = array( 'type' => 'st--logic-radio' , 'label' => __( 'Show floating box' , 'cosmotheme') );
    options::$fields['front_page']['float_box']         = array( 'type' => 'st--textarea' , 'label' => __( 'Add content displayed in floating box' , 'cosmotheme') );

    if( !options::logic( 'general' , 'enb_likes' ) ){
        if( options::get_value( 'front_page' , 'type' ) == 'hot_posts' ){
            $fp = get_option( 'front_page' );
            $fp['type'] = 'new_posts';
            update_option( 'front_page' , $fp );
        }
    }

    if( options::get_value( 'front_page' , 'type' ) == 'page' ){
        options::$fields['front_page']['page']['classes']       = 'fp_page';
        options::$fields['front_page']['info_page']['classes']  = 'fp_page';
        options::$fields['front_page']['info_hot']['classes']   = 'fp_hot hidden';
        options::$fields['front_page']['info_new']['classes']   = 'fp_new hidden';
    }

    if( options::get_value( 'front_page' , 'type' ) == 'hot_posts' ){
        options::$fields['front_page']['page']['classes']       = 'fp_page hidden';
        options::$fields['front_page']['info_page']['classes']  = 'fp_page hidden';
        options::$fields['front_page']['info_hot']['classes']   = 'fp_hot';
        options::$fields['front_page']['info_new']['classes']   = 'fp_new hidden';
    }

    if( options::get_value( 'front_page' , 'type' ) == 'new_posts' ){
        options::$fields['front_page']['page']['classes']       = 'fp_page hidden';
        options::$fields['front_page']['info_page']['classes']  = 'fp_page hidden';
        options::$fields['front_page']['info_hot']['classes']   = 'fp_hot hidden';
        options::$fields['front_page']['info_new']['classes']   = 'fp_new';
    }

	/* STYLING DEFAULT VALUES */
    options::$default['styling']['front_end']           = 'no';
    options::$default['styling']['automat']             = 'yes';
	options::$default['styling']['background']			= 'pattern.paper.png';
    options::$default['styling']['logo_type']           = 'text';
	options::$default['styling']['background_color']    = '#ffffff';
    options::$default['styling']['footer_bg_color']     = '#414B52';
	options::$default['styling']['boxed']        		= 'yes';

    /* STYLING OPTIONS */
    options::$fields['styling']['front_end']            = array( 'type' => 'st--logic-radio' , 'label' => __( 'Allow changing theme stylesheet' , 'cosmotheme' ) , 'hint' => __( 'Allow front-end users to change theme stylesheet for themselves. The choosen style will be saved in their cookies' , 'cosmotheme' ) );
    options::$fields['styling']['automat']              = array( 'type' => 'st--logic-radio' , 'label' => __( 'Enable automated day/night mode style switch' , 'cosmotheme') , 'action' => "act.check( this , { 'no' : '.s_bkg' } , 'sh' ); ");

    $pattern_path = 'pattern/s.pattern.';
    $pattern = array(
        "flowers"=>"flowers.png" , "flowers_2"=>"flowers_2.png" , "flowers_3"=>"flowers_3.png" , "flowers_4"=>"flowers_4.png" ,"circles"=>"circles.png","dots"=>"dots.png","grid"=>"grid.png","noise"=>"noise.png",
        "paper"=>"paper.png","rectangle"=>"rectangle.png","squares_1"=>"squares_1.png","squares_2"=>"squares_2.png","thicklines"=>"thicklines.png","thinlines"=>"thinlines.png" , "day"=>"day.png","night"=>"night.png","none"=>"none.png"
    );

    options::$fields['styling']['bg_title']             = array( 'type' => 'ni--title' , 'title' => __( 'Select Theme Background' , 'cosmotheme' ) );
    options::$fields['styling']['background']           = array( 'type' => 'ni--radio-icon' ,  'value' => $pattern , 'path' => $pattern_path , 'in_row' => 6 );

    options::$fields['styling']['boxed']                = array( 'type' => 'st--logic-radio' , 'label' => __( 'Set boxed content style' , 'cosmotheme') , 'hint' => __( 'check for boxed content style.' , 'cosmotheme' ) );
    
    /* color */
    /* background */
    options::$fields['styling']['background_color']     = array('type' => 'st--color-picker' , 'label' => __( 'Set background color' , 'cosmotheme' ) );

    if( options::logic( 'styling' , 'automat' ) ){
        options::$fields['styling']['bg_title']['classes']          = 's_bkg hidden';
        options::$fields['styling']['background']['classes']        = 's_bkg  hidden';
        options::$fields['styling']['background_color']['classes']  = 's_bkg  hidden';
    }else{
        options::$fields['styling']['bg_title']['classes']          = 's_bkg';
        options::$fields['styling']['background']['classes']        = 's_bkg';
        options::$fields['styling']['background_color']['classes']  = 's_bkg';
    }

    options::$fields['styling']['footer_bg_color']      = array('type' => 'st--color-picker' , 'label' => __( 'Set footer background color' , 'cosmotheme' ) );
    options::$fields['styling']['background_image']     = array( 'type' => 'st--hint' , 'value' => __( 'To set a background image go to' , 'cosmotheme' ) . ' <a href="themes.php?page=custom-background">' . __( 'Appearence - Background'  , 'cosmotheme' ) . '</a>' );

    $path_parts = pathinfo( options::get_value( 'styling' , 'favicon' ) );
    if( strlen( options::get_value( 'styling' , 'favicon' ) ) && $path_parts['extension'] != 'ico' ){
        $ico_hint = '<span style="color:#cc0000;">' . __( 'Error, please select "ico" type media file' , 'cosmotheme' ) . '</span>';
    }else{
        $ico_hint = __( "Please select 'ico' type media file. Make sure you allow uploading 'ico' type in General Settings -> Upload file types." , 'cosmotheme' );
    }

    options::$fields['styling']['favicon']              = array('type' => 'st--upload' , 'label' => __( 'Custom Favicon' , 'cosmotheme' ) , 'id' => 'favicon_path' , 'hint' => $ico_hint );
    options::$fields['styling']['logo_type']            = array('type' => 'st--select' , 'label' => __( 'Type Title ' , 'cosmotheme' ) , 'value' => array( 'text' => 'Text Logo' , 'image' => 'Image Logo' ) , 'hint' => __( 'Enable text-based Site Title and Tagline.' , 'cosmotheme' ) , 'action' => "act.select( '.g_logo_type' , { 'text':'.g_logo_text' , 'image':'.g_logo_image' } , 'sh_' );" , 'iclasses' => 'g_logo_type' );

    /* fields for general -> logo_type */
    options::$fields['styling']['logo_url']             = array('type' => 'st--upload' , 'label' => __( 'Custom Logo URL' , 'cosmotheme' ) , 'id' => 'logo_path' );

    /* hide not used fields */
	if( options::get_value( 'styling' , 'logo_type') == 'image' ){
        options::$fields['styling']['logo_url']['classes'] 	= 'g_logo_image';
        text::fields( 'styling' , 'logo' ,  'g_logo_text hidden' , get_option( 'blogname' ) );
        options::$fields['styling']['hint']                 = array('type' => 'st--hint' , 'classes' => 'g_logo_text hidden' ,'value' => __( 'to change blog title go to <a href="options-general.php">general settings </a> ' , 'cosmotheme') );
    }else{
		options::$fields['styling']['logo_url']['classes'] 	= 'generic-hint g_logo_image hidden';
        text::fields( 'styling' , 'logo' ,  'g_logo_text' , get_option( 'blogname' ) );
        options::$fields['styling']['hint']                 = array('type' => 'st--hint' , 'classes' => 'generic-hint g_logo_text' , 'value' => __( 'to change blog title go to <a href="options-general.php">general settings </a> ' , 'cosmotheme') );
    }
	
    /* MENU OPTIONS */
    options::$fields['menu']['megusta']                 = array('type' => 'st--logic-radio' , 'label' => __( 'Enable default menu' , 'cosmotheme' ) , 'hint' => __( 'To create a custom main or footer menu go to ' , 'cosmotheme' ) . '<a a href="' . home_url('/wp-admin/nav-menus.php') . '">' . __( 'Appearance -> Menus' , 'cosmotheme' ) . '</a>' );
    options::$fields['menu']['header']                  = array('type' => 'st--select' , 'value' => fields::digit_array( 20 ) , 'label' => __( 'Set limit for Main Menu' , 'cosmotheme' ) , 'hint' => __( 'Set the number of visible menu items. Remaining menu items<br />will be shown in the drop down menu item "More".' , 'cosmotheme' ) );
    options::$fields['menu']['footer']                  = array('type' => 'st--select' , 'value' => fields::digit_array( 20 ) , 'label' => __( 'Set limit for Footer Menu' , 'cosmotheme' ) , 'hint' => __( 'Set the number of visible menu items.' , 'cosmotheme' ) );

    options::$default['menu']['megusta']                = 'yes';
    options::$default['menu']['header']                 = 8;
    options::$default['menu']['footer']                 = 4;

    /* POSTS OPTIONS */
    options::$fields['blog_post']['post_title0']        = array('type' => 'ni--title' , 'title' => __( 'General Posts Settings' , 'cosmotheme' ) );

    options::$fields['blog_post']['post_similar_full']  = array('type' => 'st--select' , 'value' => fields::digit_array( 20 , 1 ) , 'label' => __( 'Number of similar posts ( full width )' , 'cosmotheme' ) );
    options::$fields['blog_post']['post_similar_side']  = array('type' => 'st--select' , 'value' => fields::digit_array( 20 , 1 ) , 'label' => __( 'Number of similar posts ( with sidebar )' , 'cosmotheme' ) );
    options::$fields['blog_post']['show_similar']       = array('type' => 'st--logic-radio' , 'label' => __( 'Enable similar posts' , 'cosmotheme' ), 'action' => "act.check( this , { 'yes' : '.similar_type_class ' , 'no' : '' } , 'sh' );" );
	$similar_type_options = array('post_tag'=>__('Same tags','cosmotheme'), 'category'=> __('Same category','cosmotheme'));
	options::$fields['blog_post']['similar_type']       = array('type' => 'st--select' , 'value' => $similar_type_options , 'label' => __( 'Similar posts criteria' , 'cosmotheme' ) );
    options::$fields['blog_post']['post_sharing']       = array('type' => 'st--logic-radio' , 'label' => __( 'Enable social sharing for posts' , 'cosmotheme' ) );
    options::$fields['blog_post']['post_author_box']    = array('type' => 'st--logic-radio' , 'label' => __( 'Post Author Box' , 'cosmotheme' ) , 'hint' => __( 'This will enable the post author box on the single posts page.<br /> Edit description in Users > Your Profile.' , 'cosmotheme' ) );
	options::$fields['blog_post']['show_source'] 	    = array('type' => 'st--logic-radio' , 'label' => __( 'Show Post Source' , 'cosmotheme' )  );
	
	

    options::$fields['blog_post']['post_title1']        = array('type' => 'ni--title' , 'title' => __( 'General Page Settings' , 'cosmotheme' ) );
    options::$fields['blog_post']['page_sharing']       = array('type' => 'st--logic-radio' , 'label' => __( 'Enable social sharing for page' , 'cosmotheme' ) );
    options::$fields['blog_post']['page_author_box']    = array('type' => 'st--logic-radio' , 'label' => __( 'Page Author Box' , 'cosmotheme' ) , 'hint' => __( 'This will enable the page author box on the single page.<br /> Edit description in Users > Your Profile.' , 'cosmotheme' ) );

    options::$fields['blog_post']['post_title2']        = array('type' => 'ni--title' , 'title' => __( 'Attachment Posts Settings' , 'cosmotheme' ) );
    options::$fields['blog_post']['attachment_sharing'] = array('type' => 'st--logic-radio' , 'label' => __( 'Enable social sharing for attachment posts' , 'cosmotheme' ) );
    options::$fields['blog_post']['attachment_comments']= array('type' => 'st--logic-radio' , 'label' => __( 'Enable comments for attachment posts' , 'cosmotheme' ) );

    /* POSTS DEFAULT VALUE */
    options::$default['blog_post']['post_similar_full'] = 4;
    options::$default['blog_post']['post_similar_side'] = 3;
    options::$default['blog_post']['show_similar']      = 'yes';
    options::$default['blog_post']['post_sharing']      = 'yes';
    options::$default['blog_post']['post_author_box']   = 'no';
	options::$default['blog_post']['show_source'] 		= 'yes';
    options::$default['blog_post']['page_sharing']      = 'yes';
    options::$default['blog_post']['page_author_box']   = 'no';
    options::$default['blog_post']['author_sharing']    = 'no';
    options::$default['blog_post']['attachment_sharing']= 'yes';
    options::$default['blog_post']['attachment_comments']= 'yes';
	options::$default['blog_post']['similar_type']= 'post_tag';

	if( options::logic( 'blog_post' , 'show_similar' ) ){
		options::$fields['blog_post']['similar_type']['classes']     = 'similar_type_class';
	}else{ 
		options::$fields['blog_post']['similar_type']['classes']     = 'similar_type_class hidden';
	}

    /* ADVERTISEMENT SPACES */
    options::$fields['advertisement']['logo']           = array('type' => 'st--textarea' , 'label' => __( 'Ad area nr 1' , 'cosmotheme' ) , 'hint' => __( 'Ad area below logo' , 'cosmotheme' ) );
    options::$fields['advertisement']['content']        = array('type' => 'st--textarea' , 'label' => __( 'Ad area nr 2' , 'cosmotheme' ) , 'hint' => __( 'Ad area below post content' , 'cosmotheme' ) );

    /* SOCIAL OPTIONS */
    options::$fields['social']['twitter']               = array('type' => 'st--text' , 'label' => __( 'Twitter ID' , 'cosmotheme' ) , 'hint' => __( '' , 'cosmotheme' ) );
    options::$fields['social']['facebook']              = array('type' => 'st--text' , 'label' => __( 'Facebook profile ID' , 'cosmotheme' ) , 'hint' => __( '' , 'cosmotheme' ) );
    options::$fields['social']['facebook_app_id']       = array('type' => 'st--text' , 'label' => __( 'Facebook Application ID' , 'cosmotheme' ) , 'hint' => __( 'You can create a FB Application from <a href="https://developers.facebook.com/apps">here</a>.' , 'cosmotheme' ) );
    options::$fields['social']['facebook_secret']       = array('type' => 'st--text' , 'label' => __( 'Facebook Secret key' , 'cosmotheme' ) , 'hint' => __( 'It is needed for Facebook connect.' , 'cosmotheme' ) );
    options::$fields['social']['linkedin']              = array('type' => 'st--text' , 'label' => __( 'LinkedIn Public Profile URL' , 'cosmotheme' ) , 'hint' => __( '(i.e. http://www.linkedin.com/company/cosmothemes)' , 'cosmotheme' ) );
    options::$fields['social']['email']                 = array('type' => 'st--text' , 'label' => __( 'Contact email' , 'cosmotheme' )  );
    options::$fields['social']['flickr']                = array('type' => 'st--text' , 'label' => __( 'Flickr ID' , 'cosmotheme' ) , 'hint' => __( 'Insert your flickr ID (<a target="_blank" href="http://www.idgettr.com">idGettr</a>)' , 'cosmotheme' ) );
    options::$fields['social']['google_map']            = array('type' => 'st--text' , 'label' => __( 'Google map key' , 'cosmotheme' ) , 'hint' => __( 'This key is neded if you want to use google map in contact form.' , 'cosmotheme' ) );

	/*Cosmothemes options*/

	options::$default['cosmothemes']['show_new_version']      = 'yes';
	options::$default['cosmothemes']['show_cosmo_news']      = 'yes';
	options::$fields['cosmothemes']['show_new_version'] = array( 'type' => 'st--logic-radio' , 'label' => __( 'Enable notification about new theme version' , 'cosmotheme' ) );
	options::$fields['cosmothemes']['show_cosmo_news']  = array( 'type' => 'st--logic-radio' , 'label' => __( 'Enable Cosmothemes news notification' , 'cosmotheme' ) );
	
	
    /* sidebar manager */
    $struct = array(
        'layout' => 'A',
        'check-column' => array(
            'name' => 'idrow[]',
            'type' => 'hidden'
        ),
        'info-column-0' => array(
            0 => array(
                'name' => 'title',
                'type' => 'text',
                'label' => 'Sidebar Title',
                'classes' => 'sidebar-title'
            )
        ),
        'select' => 'title'
    );

    /* delete_option( '_sidebar' ); */
    /* SOCIAL OPTIONS */
    options::$fields['_sidebar']['idrow']               = array('type' => 'st--m-hidden' , 'value' => 1 , 'id' => 'sidebar_title_id' , 'single' => true );
    options::$fields['_sidebar']['title']               = array('type' => 'st--text' , 'label' => __( 'Set title for new Sidebar','cosmotheme' ) , 'id' => 'sidebar_title' , 'single' => true );
    options::$fields['_sidebar']['save']                = array('type' => 'st--button' , 'value' => 'Add New Sidebar' , 'action' => "extra.add( '_sidebar' , { 'input' : [ 'sidebar_title_id' , 'sidebar_title'] })" );

    options::$fields['_sidebar']['struct']              = $struct;
    options::$fields['_sidebar']['hint']                = __( 'List of generic dinamic Sidebars' , 'cosmotheme' );

    options::$fields['_sidebar']['list']                = array( 'type' => 'ex--extra' , 'cid' => 'container__sidebar');
    options::$register['cosmothemes']                   = options::$fields;
?>
