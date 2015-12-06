<?php
class CookielanderOptions {
	// initially generated via http://wpsettingsapi.jeroensormani.com/

	const X = 'wordpress';

	#region ------------- customization --------------

	var $title = 'Cookielander - Landing-page to Cookie';
	var $menu_title = 'Cookielander';

	/**
	 * Who can use it
	 */
	var $capability = 'manage_options'; // general, manage_options

	/**
	 * Namespace
	 */
	const N = 'cookielander';

	#endregion ------------- customization --------------


	#region ------- fields etc ----------

	const F_RAW = 'json';

	function add_settings($page) {

		$section = implode('_', array(static::N, static::N/**/, 'section'));

		add_settings_section(
			$section,
			__( 'Raw Configuration', static::X ), 
			array(&$this, 'section'), 
			$page
		);

		add_settings_field( 
			self::F_RAW, 
			__( 'Raw JSON', static::X ), 
			array(&$this, 'render_raw'), 
			$page, 
			$section 
		);
	}


	function section(  ) { 
		?><p><?php _e( 'Determine which what referral variables to look for: in the querystring, in headers.', static::X ) ?></p>
		<p><?php _e('List them out in JSON format, like:', static::X) ?>
			<pre>
[
	{ "get": "url-parameter-1", "cookie": null },
	{ "get": "url-parameter-2", "cookie": "some-other-name" },
	{ "header": "x-referral", "cookie": "crm.xref" },
	{ "get": "ref", "cookie": "crm.ref" }
]
			</pre>
		</p>
		<p>The above will save:
<br /> * the querystring parameter (like `?url-parameter-1=VALUE`) to a cookie of the same name
<br /> * the querystring parameter `url-parameter-2` to a cookie named `some-other-name`
<br /> * the request header `x-referral` to a cookie named `crm` whose value is an array, at key `xref`
<br /> * the querystring parameter `ref` to the same cookie above at key `ref`
		</p>
		<?php
	}

	function render_raw(  ) {
		// dump all the setings out as JSON

		$field = self::F_RAW;
		$options = self::settings();

		// TODO: codemirror...
		?>
		<textarea class='large-text code' rows='10' name='<?php echo static::N, '[', $field ?>]'><?php echo esc_html(json_encode($options, JSON_PRETTY_PRINT)); ?></textarea>
		<?php
	}

	function sanitize($val) {
		### _log('sanitizing ' . static::N . '.' . self::F_RAW, $val);

		// pull out json and turn into array
		$newval = json_decode($val[self::F_RAW], true);

		### _log('sanitized ' . static::N . '.' . self::F_RAW, $val);

		// okay?
		$error = json_last_error();
		if($error === JSON_ERROR_NONE) return $newval;

		add_settings_error(
			// setting name
			static::N,
			// html id
			static::N,
			// what went wrong
			sprintf(__('Invalid JSON (code %s)', static::X), $error),
			// css class: error, updated
			'error'
		);
		$this->failed();

		return $val;
	}

	#endregion ------- fields etc ----------






	#region ------------- settings, singleton --------------

	public static function settings() {
		return get_option( static::N );
	}

	private static $instance;
	/**
	 * Returns the *Singleton* instance of this class.
	 *
	 * @see http://www.phptherightway.com/pages/Design-Patterns.html#singleton
	 * @return Singleton The *Singleton* instance.
	 */
	public static function instance($root = null) {
		if (null === static::$instance) {
			static::$instance = new static(empty($root) ? __FILE__ : $root);
			//static::$instance->root = empty($root) ? __FILE__ : $root;
		}

		return static::$instance;
	}
	#endregion ------------- settings, singleton --------------

	/**
	 * Plugin options 'root' path (<c>__FILE__</c>), used to create plugin listing settings link
	 */
	var $root;

	/**
	 * Create a new instance of the plugin; consider also the singleton <c>::instance($root)</c> instead
	 * @remarks technically should be `private`
	 */
	public function __construct($root) {
		$this->root = $root;

		if(!is_admin()) return;

		// TODO: multisite? https://codex.wordpress.org/Creating_Options_Pages#Pitfalls
		add_action( 'admin_menu', array(&$this, 'add_admin_menu') );
		add_action( 'admin_init', array(&$this, 'settings_init') );
	}

	function add_admin_menu(  ) { 
		add_options_page(
			// page title
			$this->title,
			// menu title
			$this->menu_title,
			// access
			$this->capability,
			// namespace/option
			static::N,
			// callback
			array(&$this, 'options_page')
		);

		//add plugin entry settings link
		add_filter( 'plugin_action_links', array(&$this, 'plugin_action_links'), 10, 2 );
	}//--	add_admin_menu

	/**
	 * HOOK - Add the "Settings" link to the plugin list entry
	 * @param $links
	 * @param $file
	 */
	function plugin_action_links( $links, $file ) {
		if ( $file != plugin_basename($this->root ) )
			return $links;
	
		$url = esc_url_raw(admin_url('options-general.php?page=' . static::N));
	
		$settings_link = '<a title="Capability ' . $this->CAPABILITy . ' required" href="' . esc_attr( $url ) . '">'
			. esc_html( __( 'Settings', static::X ) ) . '</a>';
	
		array_unshift( $links, $settings_link );
	
		return $links;
	}


	function settings_init(  ) { 
		register_setting( static::N/**/, static::N, array(&$this, 'sanitize') );

		$this->add_settings(static::N/**/);
	}//--	settings_init


	function options_page(  ) { 

		?>
		<form action='options.php' method='post'>
			
			<h2><?php _e($this->title, static::X) ?></h2>
			
			<?php
			settings_fields( static::N/**/ );
			do_settings_sections( static::N/**/ );
			submit_button();
			?>
			
		</form>
		<?php

	}

	function failed() {
		add_action( 'admin_notices', array(&$this, 'option_failed') );
	}
	function option_failed() {
		settings_errors(static::N);
	}




	protected function renderInput($field) {
		$options = self::settings();
		?>
		<input type='text' class='regular-text' name='<?php echo static::N, '[', $field ?>]' value='<?php echo $options[$field]; ?>'>
		<?php
	}
	protected function renderText($field) {
		$options = self::settings();

		// TODO: codemirror...
		?>
		<textarea class='large-text code' rows='10' name='<?php echo static::N, '[', $field ?>]'><?php echo esc_html($options[$field]); ?></textarea>
		<?php
	}


}//---	CookielanderOptions


