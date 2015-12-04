<?php
class F3iFieldFormatOptions {
	// generated via http://wpsettingsapi.jeroensormani.com/

	const N = 'f3i_ff_settings';
	const X = 'wordpress';

	const FIELD_DELIM = ',';
	const REGEX_DELIM = '|||';

	const F_FIELDS = 'fields';
	const F_PATTERNS = 'patterns';
	const F_REPLACEMENTS = 'replacements';

	public static function settings() {
		return get_option( self::N );
	}

	var $root;

	public function __construct($root) {
		$this->root = $root;
		add_action( 'admin_menu', array(&$this, 'add_admin_menu') );
		add_action( 'admin_init', array(&$this, 'settings_init') );
	}

	const CAPABILITY = 'manage_options';

	function add_admin_menu(  ) { 
		add_options_page(
			'Forms 3rdparty Submission Formatter',
			'Forms 3rdparty Formatter',
			self::CAPABILITY,
			self::N,
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
	
		$url = esc_url_raw(admin_url('options-general.php?page=' . self::N));
	
		$settings_link = '<a title="Capability ' . self::CAPABILITY . ' required" href="' . esc_attr( $url ) . '">'
			. esc_html( __( 'Settings', self::X ) ) . '</a>';
	
		array_unshift( $links, $settings_link );
	
		return $links;
	}

	function settings_init(  ) { 

		register_setting( 'pluginPage', self::N );

		add_settings_section(
			'f3i_ff_pluginPage_section', 
			__( 'Field Formatting Replacement', self::X ), 
			array(&$this, 'section'), 
			'pluginPage'
		);

		add_settings_field( 
			self::F_FIELDS, 
			__( 'Submission Field Name(s)', self::X ), 
			array(&$this, 'render_names'), 
			'pluginPage', 
			'f3i_ff_pluginPage_section' 
		);

		add_settings_field( 
			self::F_PATTERNS, 
			__( 'Given Format (regex pattern)', self::X ), 
			array(&$this, 'render_pattern'), 
			'pluginPage', 
			'f3i_ff_pluginPage_section' 
		);

		add_settings_field( 
			self::F_REPLACEMENTS, 
			__( 'Expected Format (regex replacement)', self::X ), 
			array(&$this, 'render_replace'), 
			'pluginPage', 
			'f3i_ff_pluginPage_section' 
		);
	}//--	settings_init

	function _render($field) {
		$options = self::settings();
		?>
		<input type='text' name='f3i_ff_settings[<?php echo $field ?>]' value='<?php echo $options[$field]; ?>'>
		<?php
	}

	function render_names(  ) { 
		$this->_render(self::F_FIELDS);
	}


	function render_pattern(  ) { 
		$this->_render(self::F_PATTERNS);
		?>
		<p><em>Example:</em> <code>/(\d+)\/(\d+)\/(\d+)/</code><p>
		<?php

	}


	function render_replace(  ) { 
		$this->_render(self::F_REPLACEMENTS);
		?>
		<p><em>Example:</em> <code>$2-$1-$3</code><p>
		<?php

	}


	function section(  ) { 

		echo '<p>', __( 'Enter the field name(s), comma-separated, which will be parsed and rearranged according to the given patterns and replacements.', self::X ), '</p>';
		echo '<p>', sprintf( __( 'Separate multiple patterns and replacements with %s', self::X ), '<code>' . self::REGEX_DELIM . '</code>'), '</p>';

	}


	function options_page(  ) { 

		?>
		<form action='options.php' method='post'>
			
			<h2>Forms 3rdparty Submission Formatter</h2>
			
			<?php
			settings_fields( 'pluginPage' );
			do_settings_sections( 'pluginPage' );
			submit_button();
			?>
			
		</form>
		<?php

	}

}//---	F3iFieldFormatOptions


