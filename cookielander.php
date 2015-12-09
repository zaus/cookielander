<?php
/*

Plugin Name: Cookie-Lander
Plugin URI: https://github.com/zaus/cookielander
Description: Save referral variables to temporary storage (cookies)
Author: zaus
Version: 0.2
Author URI: http://drzaus.com
Changelog:
	0.1	initial
	0.2 semi-standard options page
*/

class Cookielander {

	const N = 'Cookielander';

	public function __construct() {
		// only on frontend pages
		if(is_admin()) return;

		add_action('init', array(&$this, 'trap'));
	}

	public function trap() {
		$settings = CookielanderOptions::settings();

		_log(__CLASS__, $settings);

		// foreach
		// if session
		$this->start_session();


	}//--	fn	date_format


	function start_session() {
		// http://silvermapleweb.com/using-the-php-session-in-wordpress/
		if (!session_id()) session_start();
	}

}//---	class	BouwgeniusDateFormat

// engage!
new Cookielander();

require('cookielander-options.php');
CookielanderOptions::instance(__FILE__);