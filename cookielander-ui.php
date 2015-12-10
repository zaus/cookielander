<p><?php _e( 'Determine which what referral variables to look for: in the querystring, in headers.', static::X ) ?></p>

<div class="raw">
	<p><?php _e('List them out in JSON format, like:', static::X) ?>
		<pre><?php
		
		$example = array(
			array(
				'src_t' => 'get',
				'src' => 'url-parameter-1',
				'dest_t' => 'cookie',
				'dest' => null,
			),
			
			array(
				'src_t' => 'get',
				'src' => 'url-parameter-2',
				'dest_t' => 'cookie',
				'dest' => 'some-other-name',
			),
			
			array(
				'src_t' => 'header',
				'src' => 'x-referral',
				'dest_t' => 'cookie',
				'dest' => 'crm.xref',
			),
			
			array(
				'src_t' => 'get',
				'src' => 'ref',
				'dest_t' => 'cookie',
				'dest' => 'crm.ref',
			),
		);
		
		echo esc_html(json_encode($example, JSON_PRETTY_PRINT));
		
		?></pre>
	</p>
	<p>The above will save:
		<br /> * the querystring parameter (like `?url-parameter-1=VALUE`) to a cookie of the same name
		<br /> * the querystring parameter `url-parameter-2` to a cookie named `some-other-name`
		<br /> * the request header `x-referral` to a cookie named `crm` whose value is an array, at key `xref`
		<br /> * the querystring parameter `ref` to the same cookie above at key `ref`
	</p>
</div>
<div class="gui" id="cookielander-editor"></div>


<script id="t-editor" type="text/template">
	<a class="button" on-click="toggleRaw()">Toggle Raw</a>
	
	<section>
		{{#each items}}
			<div decorator="addable">
				<fieldset class="control-group radios">
					<legend>Source</legend>
					
					<label><input type='radio' value='get' name='{{.src_t}}' /> <b>Get</b></label>
					<label><input type='radio' value='header' name='{{.src_t}}' /> <b>Header</b></label>
				</fieldset>
				<label><b>Source Key:</b> <input value='{{.src}}' /></label>
				
				<fieldset class="control-group radios">
					<legend>Destination</legend>
					
					<label><input type='radio' value='session' name='{{.dest_t}}' /> <b>Session</b></label>
					<label><input type='radio' value='cookie' name='{{.dest_t}}' /> <b>Cookie</b></label>
				</fieldset>
				<label><b>Destination Key:</b> <input value='{{.dest}}' /></label>
			</div>
		{{/each}}
		{{^items}}
			<a on-click="(items = [])" class="btn add"><i></i></a>
		{{/items}}

	</section>
	
</script>