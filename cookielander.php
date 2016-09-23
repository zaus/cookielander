<?php
/*

Plugin Name: Cookie-Lander
Plugin URI: https://github.com/zaus/cookielander
Description: Save referral variables to temporary storage (cookies, session, etc)
Author: zaus
Version: 0.6
Author URI: http://drzaus.com
Changelog:
	0.1	initial
	0.2	semi-standard options page
	0.3	dynamic admin ui (ractive)
	0.4	saves parameters
	0.5	session source
	0.6	redir option
*/

class Cookielander {

	const N = 'Cookielander';

	public function __construct() {
		require('cookielander-options.php');

		// only on frontend pages
		if(is_admin()) {
			CookielanderOptions::instance(__FILE__);
			return;
		}

		add_action('init', array(&$this, 'trap'));
	}

	public function trap() {
		$settings = CookielanderOptions::settings();
		
		### _log(__CLASS__, $settings);

		// nothing to do...leave
		if(empty($settings) || !isset($settings[CookielanderOptions::F_RAW]) || empty($settings[CookielanderOptions::F_RAW])) return;

		$headerSource = false; // populate on first request
		$emptySource = array(); // if invalid option selected
		
		$cookiesDest = array();
		$sessionDest = array();
		$headersDest = array();
		
		foreach($settings[CookielanderOptions::F_RAW] as $i => $setting) {
			$src_t = $src = $dest_t = $dest = null; // extract causes undefined variable warning...meh
			extract($setting, EXTR_IF_EXISTS); // easier access

			switch($src_t) {
				case 'req':
				case 'get':
					$source = $_REQUEST; // WP already combined get/post
					break;
				case 'header':
					if($headerSource === false) $headerSource = $this->getheaders(); // populate on first request
					$source = $headerSource;
					break;
				case 'cookie':
					$source = $_COOKIE;
					break;
				case 'session':
					$this->start_session();
					$source = $_SESSION;
					break;
				default:
					$source = $emptySource;
					break;
			}
			
			if(!isset($source[$src])) continue;
			$sourceVal = $source[$src];

			switch($dest_t) {
				case 'cookie':
					$targetDest = &$cookiesDest;
					break;
				case 'session':
					$targetDest = &$sessionDest;
					break;
				case 'header':
					$targetDest = &$headersDest;
					break;
				default:
					$targetDest = &$emptySource;
					break;
			}
			
			$targetDest[empty($dest) ? $src : $dest] = $sourceVal;
		}
		
		### _log($settings, array('session' => $sessionDest, 'headers' => $headersDest, 'cookies' => $cookiesDest));
		
		if(!empty($sessionDest)) {
			$this->start_session();
			$this->set_session($sessionDest);
		}
		if(!empty($headersDest)) {
			$this->headersToSet = $headersDest;
			add_action('send_headers', array(&$this, 'set_headers'));
		}
		if(!empty($cookiesDest)) {
			$this->set_cookies($cookiesDest);
		}

		// remove GET params
		if( isset($settings[CookielanderOptions::F_301]) && $settings[CookielanderOptions::F_301] ) {
			$url = false;
			$redir = false;
			foreach($settings[CookielanderOptions::F_RAW] as $i => $setting) {
				if($setting['src_t'] != 'get' || !isset($_GET[$setting['src']])) continue;

				// https://developer.wordpress.org/reference/functions/remove_query_arg/
				$url = remove_query_arg($src, $url);
				$redir = true;
			}

			if($redir && wp_redirect( $url )) {
				exit;
			}
		}
	}//--	fn	trap

	function getheaders() {
		// or do we need to use the $_SERVER['HTTP_....'] trick?
		// http://stackoverflow.com/questions/541430/how-do-i-read-any-request-header-in-php
		if(function_exists('getallheaders')) return getallheaders();
		
		$headers = array();
		// do we really need to handle the complicated capitalization fixing? ugh
		foreach($_SERVER as $k => $v) {
			$realKey = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($k, 5)))));
			if(strpos($k, 'HTTP_') === 0) $headers[ $realKey ] = $v;
		}
		return $headers;
	}
	
	
	function set_headers() {
		// `send_headers`, not `wp_headers` -- http://wordpress.stackexchange.com/questions/20192/wp-function-filter-for-modifying-http-headers
		foreach($this->headersToSet as $k => $v) {
			header("$k: $v");
		}
	}
	
	function set_session($sessions) {
		// do we need to destroy them later? http://silvermapleweb.com/using-the-php-session-in-wordpress/
		$_SESSION = array_merge($_SESSION, $sessions);
	}
	
	function set_cookies($cookies) {
		$expires = CookielanderOptions::settings(CookielanderOptions::F_EXPIRES);
		
		foreach($cookies as $path => $value) {
			//parse for path, expiration, etc
			$args = wp_parse_args($path, array(
				'name' => $path,
				'expire' => $expires,
				'path' => COOKIEPATH,
				'domain' => COOKIE_DOMAIN,
				'secure' => 'false',
				'httponly' => 'true'
			));
			
			setcookie($args['name'], $value, time() + intval($args['expire']), $args['path'], $args['domain'], $args['secure'] === 'true', $args['httponly'] === 'true');			
		}
	}


	function start_session() {
		// http://silvermapleweb.com/using-the-php-session-in-wordpress/
		if (!session_id()) session_start();
	}

}//---	class	BouwgeniusDateFormat

// engage!
new Cookielander();
