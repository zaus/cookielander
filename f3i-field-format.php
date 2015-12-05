<?php
/*

Plugin Name: Cookie-Lander
Plugin URI: https://github.com/zaus/cookielander
Description: Save referral variables to temporary storage (cookies)
Author: zaus
Version: 0.1
Author URI: http://drzaus.com
Changelog:
	0.1	initial
*/

class Cookielander {

	const N = 'Cookielander';

	public function __construct() {
		// hook early to clean stuff out before other plugins
		add_filter(self::B.'_get_submission', array(&$this, 'field_format'), 22, 3);
	}

	public function field_format($submission, $form, $service) {
		$settings = CookielanderOptions::settings();

		$fields = explode(CookielanderOptions::FIELD_DELIM, $settings[CookielanderOptions::F_FIELDS]);

		// regex - pattern, replace
		$pattern = explode(CookielanderOptions::REGEX_DELIM, $settings[CookielanderOptions::F_PATTERNS]); // '/(\d+)\/(\d+)\/(\d+)/';
		$replace = explode(CookielanderOptions::REGEX_DELIM, $settings[CookielanderOptions::F_REPLACEMENTS]); //'$2-$1-$3';

		### _log('bouwgenius-date', $fields, $submission); 

		foreach($fields as $field) {
			if(isset($submission[$field]) && !empty($submission[$field])) {
				$x = preg_replace($pattern, $replace, $submission[$field]);

				### _log($submission[$field], $x, $field);

				$submission[$field] = $x;
			}
		}

		return $submission;
	}//--	fn	date_format
}//---	class	BouwgeniusDateFormat

// engage!
new Cookielander();

require('cookielander-options.php');
new CookielanderOptions(__FILE__);