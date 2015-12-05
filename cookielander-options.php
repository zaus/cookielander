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
	var $capability = 'general'; // general, manage_options

	/**
	 * Namespace
	 */
	const N = 'cookielander';

	/**
	 * Settings option group
	 * 
	 * @remarks don't necessarily need to change
	 */
	const OPTION_GROUP = 'pluginPage';

	#endregion ------------- customization --------------


	#region ------- fields etc ----------

	const FIELD_DELIM = ',';
	const REGEX_DELIM = '|||';

	const F_FIELDS = 'fields';
	const F_PATTERNS = 'patterns';
	const F_REPLACEMENTS = 'replacements';


	function add_settings($group) {

		$section = 'f3i_ff_pluginPage_section';

		add_settings_section(
			$section,
			__( 'Field Formatting Replacement', static::X ), 
			array(&$this, 'section'), 
			$group
		);

		add_settings_field( 
			self::F_FIELDS, 
			__( 'Submission Field Name(s)', static::X ), 
			array(&$this, 'render_names'), 
			$group, 
			$section 
		);

		add_settings_field( 
			self::F_PATTERNS, 
			__( 'Given Format (regex pattern)', static::X ), 
			array(&$this, 'render_pattern'), 
			$group, 
			$section 
		);

		add_settings_field( 
			self::F_REPLACEMENTS, 
			__( 'Expected Format (regex replacement)', static::X ), 
			array(&$this, 'render_replace'), 
			$group, 
			$section 
		);
	}


	function section(  ) { 
		echo '<p>', __( 'Enter the field name(s), comma-separated, which will be parsed and rearranged according to the given patterns and replacements.', static::X ), '</p>';
		echo '<p>', sprintf( __( 'Separate multiple patterns and replacements with %s', static::X ), '<code>' . self::REGEX_DELIM . '</code>'), '</p>';
	}

	function render_names(  ) { 
		$this->renderInput(self::F_FIELDS);
	}


	function render_pattern(  ) { 
		$this->renderInput(self::F_PATTERNS);
		?>
		<p><em>Example:</em> <code>/(\d+)\/(\d+)\/(\d+)/</code><p>
		<?php

	}


	function render_replace(  ) { 
		$this->renderInput(self::F_REPLACEMENTS);
		?>
		<p><em>Example:</em> <code>$2-$1-$3</code><p>
		<?php

	}

	#endregion ------- fields etc ----------



	#region ------------- settings, singleton --------------

	public static function settings() {
		return get_option( instance()->N );
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

	var $root;

	protected function __construct($root) {
		$this->root = $root;
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
			$this->CAPABILITy,
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
		register_setting( static::OPTION_GROUP, static::N/*, sanitize_callback */ );

		$this->add_settings(static::OPTION_GROUP);
	}//--	settings_init


	function options_page(  ) { 

		?>
		<form action='options.php' method='post'>
			
			<h2><?php _e($this->title, static::X) ?></h2>
			
			<?php
			settings_fields( static::OPTION_GROUP );
			do_settings_sections( static::OPTION_GROUP );
			submit_button();
			?>
			
		</form>
		<?php

	}



	protected function renderInput($field) {
		$options = self::settings();
		?>
		<input type='text' name='<?php echo static::N, '[', $field ?>]' value='<?php echo $options[$field]; ?>'>
		<?php
	}


}//---	CookielanderOptions


