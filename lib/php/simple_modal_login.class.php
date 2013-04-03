<?php

/**
 * Set the wp-content and plugin urls/paths
 */
/*if (!defined('WP_CONTENT_URL'))
	define('WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
if (!defined('WP_CONTENT_DIR'))
	define('WP_CONTENT_DIR', ABSPATH . 'wp-content');
if (!defined('WP_PLUGIN_URL') )
	define('WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins');
if (!defined('WP_PLUGIN_DIR') )
	define('WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins');*/

if (!class_exists('simple_modal_login')) {
	class simple_modal_login {
		/**
		 * @var string The plugin version
		 */
		var $version = '1.0.4';

		/**
		 * @var string The plugin version
		 */
		var $simplemodalVersion = '1.4.1';

		/**
		 * @var string The options string name for this plugin
		 */
		var $optionsName = 'simplemodal_login_options';

		/**
		 * @var string $nonce String used for nonce security
		 */
		var $nonce = 'simplemodal-login-update-options';

		/**
		 * @var string $pluginurl The url to this plugin
		 */
		var $pluginurl = '';

		/**
		 * @var string $pluginpath The path to this plugin
		 */
		var $pluginpath = '';

		/**
		 * @var array $options Stores the options for this plugin
		 */
		var $options = array();

		/**
		 * @var boolean $users_can_register Stores the option for this plugin
		 */
		var $users_can_register = null;

		/**
		 * PHP 4 Compatible Constructor
		 */
		function simple_modal_login() {$this->__construct();}

		/**
		 * PHP 5 Constructor
		 */
		function __construct() {
			$name = dirname(plugin_basename(__FILE__));

            if( options::logic( 'general' , 'user_register' ) ){
                $this->users_can_register = true;
            }else{
                $this->users_can_register = false;
            }
			/*if( get_option('users_can_register') == 1 ){
				$this->users_can_register = true;
			}else{
				$this->users_can_register = false;
			} */
				
			//Language Setup
			//load_plugin_textdomain('simplemodal-login', false, "$name/I18n/");

			//"Constants" setup
			/*$this->pluginurl = WP_PLUGIN_URL . "/$name/";*/
			$this->pluginurl = get_template_directory_uri().'/';
			
			//$this->pluginpath = WP_PLUGIN_DIR . "/$name/";
			$this->pluginpath = dirname(plugin_basename(__FILE__));

			//Initialize the options
			$this->get_options();

			//Actions
			//add_action('admin_menu', array(&$this, 'admin_menu_link'));

			if (!is_admin()) {
				add_filter('login_redirect', array(&$this, 'login_redirect'), 5, 3);
				add_filter('register', array(&$this, 'register'));
				add_filter('loginout', array(&$this, 'login_loginout'));
				add_action('wp_footer', array($this, 'login_footer'));
				add_action('wp_print_styles', array(&$this, 'login_css'));
				add_action('wp_print_scripts', array(&$this, 'login_js'));
			}
		}

		/**
		 * @desc Adds the options subpanel
		 */
		function admin_menu_link() {
			/*add_options_page('SimpleModal Login', 'SimpleModal Login', 'manage_options', basename(__FILE__), array(&$this, 'admin_options_page'));*/
			add_filter('plugin_action_links_' . plugin_basename(__FILE__), array(&$this, 'filter_plugin_actions'), 10, 2 );
		}

		

		/**
		 * @desc Loads the SimpleModal Login options. Responsible for 
		 * handling upgrades and default option values.
		 * @return array
		 */
		function check_options() {
			$options = array(
				'shortcut' => true,
				'theme' => 'default',
				'version' => $this->version,
				'registration' => $this->users_can_register,
				'reset' => true
			);

			return $options;
		}

		/**
		 * @desc Adds the Settings link to the plugin activate/deactivate page
		 * @return string
		 */
		function filter_plugin_actions($links, $file) {
			$settings_link = '<a href="options-general.php?page=' . basename(__FILE__) . '">' . __('Settings', 'cosmotheme') . '</a>';
			array_unshift($links, $settings_link); // before other links

			return $links;
		}

		/**
		 * @desc Retrieves the plugin options from the database.
		 */
		function get_options() {
			$options = $this->check_options();
			$this->options = $options;
		}

		/**
		 * @desc Determines if request is an AJAX call
		 * @return boolean
		 */
		function is_ajax() {
			return isset($_SERVER['HTTP_X_REQUESTED_WITH'])
					&& strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
		}

		/**
		 * @desc Checks to see if the given plugin is active.
		 * @return boolean
		 */
		function is_plugin_active($plugin) {
			return in_array($plugin, (array) get_option('active_plugins', array()));
		}

		/**
		 * @desc Enqueue's the CSS for the specified theme.
		 */
		function login_css() {
			/*$style = sprintf('%s.css', $this->options['theme']);
			wp_enqueue_style('simplemodal-login', $this->pluginurl . "css/$style", false, $this->version, 'screen');
			if (false !== @file_exists(TEMPLATEPATH . "simplemodal-login-$style")) {
				wp_enqueue_style('simplemodal-login-form', get_template_directory_uri() . $style, false, $this->version, 'screen');
			}*/
			wp_enqueue_style('simplemodal-login', $this->pluginurl . "css/default.css", false, $this->version, 'screen');
		}

		/**
		 * @desc Builds the login, registration, and password reset form HTML.
		 * Calls filters for each form, then echo's the output.
		 */
		function login_footer() {
			$output = '<div id="simplemodal-login-form" style="display:none">';

			$login_form = $this->login_form();
			$output .= apply_filters('simplemodal_login_form', $login_form);

			if ($this->users_can_register && $this->options['registration']) {
				$registration_form = $this->registration_form();
				$output .= apply_filters('simplemodal_registration_form', $registration_form);
			}

			if ($this->options['reset']) {
				$reset_form = $this->reset_form();
				$output .= apply_filters('simplemodal_reset_form', $reset_form);
			}

			/*$output .= sprintf('
            <div class="simplemodal-login-credit"><a href="http://www.ericmmartin.com/projects/simplemodal-login/">%s</a></div>
            </div>',''
			);*/

			echo $output;
		}

		/**
		 * @desc Builds the login form HTML.
		 * If using the simplemodal_login_form filter, copy and modify this code
		 * into your function.
		 * @return string
		 */
		function login_form() {
			$output = sprintf('
	<form name="loginform" id="loginform" action="%s" method="post">
		<div class="title">%s</div>
		
		<div class="simplemodal-login-fields">
        <div class="cosmo-box submit-content warning medium login_req hidden">
			<span class="cosmo-ico"></span>
			%s
		</div>

		<div class="cosmo-box love warning medium login_req">
			<span class="cosmo-ico"></span>
			%s 
		</div>
        
        <div class="cosmo-box nsfw warning medium login_req hidden">
			<span class="cosmo-ico"></span>
			%s 
		</div>

		<p>
			<label>%s<br />
			<input type="text" name="log" class="user_login input" value="" size="20" tabindex="10" /></label>
		</p>
		<p>
			<label>%s<br />
			<input type="password" name="pwd" class="user_pass input" value="" size="20" tabindex="20" /></label>
		</p>',
				site_url('wp-login.php', 'login_post'),
				__('Login', 'cosmotheme'),
                __('You need to be logged in to submit content.','cosmotheme'),
				__('You need to be logged in to vote.','cosmotheme'),
                __('You need to be logged in to see this post.','cosmotheme'),
				__('Username', 'cosmotheme'),
				__('Password', 'cosmotheme')
			);



			ob_start();
			do_action('login_form');
			$output .= ob_get_clean();
			
			/*Build the current page URL, it will be used for redirect*/
			$pageURL = 'http';
            if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
            $pageURL .= "://".$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];;
			
			$output .= sprintf('
		<p class="forgetmenot"><label><input name="rememberme" type="checkbox" id="rememberme" class="rememberme" value="forever" tabindex="90" /> %s</label></p>
        <div class="fr">
            <p class="submit blue">
                <input type="submit" name="wp-submit" value="%s" tabindex="100"  />
                <input type="hidden" name="testcookie" value="1" />
            </p>
            <p class="submit red">
                <input type="button" class="simplemodal-close" value="%s" tabindex="101" />
                <input type="hidden" name="redirect_to" value="%s">
            </p>
        </div>
		<p class="nav">',
				__('Remember Me', 'cosmotheme'),
				__('Log In', 'cosmotheme'),
				__('Cancel', 'cosmotheme'),
				$pageURL
			);

			if ($this->users_can_register && $this->options['registration']) {
				$output .= sprintf('<a class="simplemodal-register" href="%s">%s</a>', 
					site_url('wp-login.php?action=register', 'login'), 
					__('Register', 'cosmotheme')
				);
			}

			if (($this->users_can_register && $this->options['registration']) && $this->options['reset']) {
				$output .= ' | ';
			}

			if ($this->options['reset']) {
				$output .= sprintf('<a class="simplemodal-forgotpw" href="%s" title="%s">%s</a>',
					site_url('wp-login.php?action=lostpassword', 'login'),
					__('Password Lost and Found', 'cosmotheme'),
					__('Lost your password?', 'cosmotheme')
				);
			}

			$output .= ' 
			</p>';

            if( !( options::get_value( 'social' , 'facebook_app_id' ) == '' || options::get_value( 'social' , 'facebook_secret' ) == '' ) ){
                $output .= '<div class="fb_connect">';
				
				ob_start();
                facebook::login();
                $fb_login = ob_get_clean();
                $output .= $fb_login;
				
                $output .= '</div>';
            }
            
			$output .= '</div>
			<div class="simplemodal-login-activity" style="display:none;"></div>
		</form>';

			return $output;
		}

		/**
		 * @desc Responsible for loading the necessary scripts and localizing JavaScript messages
		 */
		function login_js() {
			global $wp_scripts;

			
			wp_enqueue_script('jquery-simplemodal', $this->pluginurl . 'js/jquery.simplemodal.js', array('jquery'), $this->simplemodalVersion, true);

			//$script = sprintf('js/%s.js', $this->options['theme']);
			wp_enqueue_script('simplemodal-login', $this->pluginurl . 'js/default.js', null, $this->version, true);
			wp_localize_script('simplemodal-login', 'SimpleModalLoginL10n', array( 
				'shortcut' => $this->options['shortcut'] ? 'true' : 'false',
				'empty_username' => __('<strong>ERROR</strong>: The username field is empty.', 'cosmotheme'),
				'empty_password' => __('<strong>ERROR</strong>: The password field is empty.', 'cosmotheme'),
				'empty_email' => __('<strong>ERROR</strong>: The email field is empty.', 'cosmotheme'),
				'empty_all' => __('<strong>ERROR</strong>: All fields are required.', 'cosmotheme')
			));

		}

		/**
		 * @desc loginout filter that adds the simplemodal-login class to the "Log In" link
		 * @return string
		 */
		function login_loginout($link) {
			if (!is_user_logged_in()) {
				$link = str_replace('href=', 'class="simplemodal-login" href=', $link);
			}
			return $link;
		}

		/**
		 * @desc login_redirect filter that determines where to redirect the user.
		 * Supports Peter's Login Redirect plugin, if enabled.
		 * @return string
		 */
		function login_redirect($redirect_to, $req_redirect_to, $user) {
		    if (!isset($user->user_login) || !$this->is_ajax()) {
				return $redirect_to;
		    }
		    if ($this->is_plugin_active('peters-login-redirect/wplogin_redirect.php')
		    		&& function_exists('redirect_to_front_page')) {
		    	$redirect_to = redirect_to_front_page($redirect_to, $req_redirect_to, $user);
		    }
			echo "<div id='simplemodal-login-redirect'>$redirect_to</div>"; echo $redirect_to .'-----------------';
			exit();
		}

		/**
		 * @desc register filter that adds the simplemodal-register class to the "Register" link
		 * @return string
		 */
		function register($link) {
			if ($this->users_can_register && $this->options['registration']) {
				if (!is_user_logged_in()) {
					$link = str_replace('href=', 'class="simplemodal-register" href=', $link);
				}
			}
			return $link;
		}

		/**
		 * @desc Builds the registration form HTML.
		 * If using the simplemodal_registration_form filter, copy and modify this code
		 * into your function.
		 * @return string
		 */
		function registration_form() {
			$output = sprintf('
<form name="registerform" id="registerform" action="%s" method="post">
	<div class="title">%s</div>
	<div class="simplemodal-login-fields">
	<p>
		<label>%s<br />
		<input type="text" name="user_login" class="user_login input" value="" size="20" tabindex="10" /></label>
	</p>
	<p>
		<label>%s<br />
		<input type="text" name="user_email" class="user_email input" value="" size="25" tabindex="20" /></label>
	</p>',
				site_url('wp-login.php?action=register', 'login_post'),
				__('Register', 'cosmotheme'),
				__('Username', 'cosmotheme'),
				__('E-mail', 'cosmotheme')
			);

			ob_start();
			do_action('register_form');
			$output .= ob_get_clean();

			$output .= sprintf('
	<p class="reg_passmail">%s</p>
    <div class="fr">
        <p class="submit blue">
            <input type="submit" name="wp-submit" value="%s" tabindex="100" />

        </p>
        <p class="submit red">
            <input type="button" class="simplemodal-close" value="%s" tabindex="101" />
        </p>
    </div>
	<p class="nav">
		<a class="simplemodal-login" href="%s">%s</a>',
				__('A password will be e-mailed to you.', 'cosmotheme'),
				__('Register', 'cosmotheme'),
				__('Cancel', 'cosmotheme'),
				site_url('wp-login.php', 'login'),
				__('Log in', 'cosmotheme')
			);

			if ($this->options['reset']) {
				$output .= sprintf(' | <a class="simplemodal-forgotpw" href="%s" title="%s">%s</a>',
					site_url('wp-login.php?action=lostpassword', 'login'),
					__('Password Lost and Found', 'cosmotheme'),
					__('Lost your password?', 'cosmotheme')
				);
			}

    $output .= '</p>';
    
    if( !( options::get_value( 'social' , 'facebook_app_id' ) == '' || options::get_value( 'social' , 'facebook_secret' ) == '' ) ){
        $output .= '<div class="fb_connect">';
        $output .= '<span class="fb-loading"></span>';
        $output .= '</div>';
    }

	$output .= '</div>
	<div class="simplemodal-login-activity" style="display:none;"></div>
</form>';

			return $output;
		}

		/**
		 * @desc Builds the reset password form HTML.
		 * If using the simplemodal_reset_form filter, copy and modify this code
		 * into your function.
		 * @return string
		 */
		function reset_form() {
			$output = sprintf('
	<form name="lostpasswordform" id="lostpasswordform" action="%s" method="post">
		<div class="title">%s</div>
		<div class="simplemodal-login-fields">
		<p>
			<label>%s<br />
			<input type="text" name="user_login" class="user_login input" value="" size="20" tabindex="10" /></label>
		</p>',
				site_url('wp-login.php?action=lostpassword', 'login_post'),
				__('Reset Password', 'cosmotheme'),
				__('Username or E-mail:', 'cosmotheme')
			);

			ob_start();
			do_action('lostpassword_form');
			$output .= ob_get_clean();

			$output .= sprintf('
        <div class="fr">
            <p class="submit blue">
                <input type="submit" name="wp-submit" value="%s" tabindex="100" />

            </p>
            <p class="submit red">
                <input type="button" class="simplemodal-close" value="%s" tabindex="101" />
            </p>
        </div>
		<p class="nav">
			<a class="simplemodal-login" href="%s">%s</a>',
				__('Get New Password', 'cosmotheme'),
				__('Cancel', 'cosmotheme'),
				site_url('wp-login.php', 'login'),
				__('Log in', 'cosmotheme')
			);

			if ($this->users_can_register && $this->options['registration']) {
				$output .= sprintf('| <a class="simplemodal-register" href="%s">%s</a>', site_url('wp-login.php?action=register', 'login'), __('Register', 'cosmotheme'));
			}

        $output .= '</p>';
        if( !( options::get_value( 'social' , 'facebook_app_id' ) == '' || options::get_value( 'social' , 'facebook_secret' ) == '' ) ){
            $output .= '<div class="fb_connect">';
            $output .= '<span class="fb-loading"></span>';
            $output .= '</div>';
        }
		$output .= '</div>
		<div class="simplemodal-login-activity" style="display:none;"></div>
	</form>';

			return $output;
		}

		/**
		 * Saves the admin options to the database.
		 */
		function save_admin_options(){
			return update_option($this->optionsName, $this->options);
		}
	}
}

// instantiate the class
if (class_exists('simple_modal_login')) {
	$simplemodal_login = new simple_modal_login();
	//$simplemodal_login->users_can_register = get_option('users_can_register') ? true : false;
}

/*
 * The format of this plugin is based on the following plugin template:
 * http://pressography.com/plugins/wordpress-plugin-template/
 */
?>