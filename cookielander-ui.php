<p><?php _e( 'Determine which \'referral\' variables to look for: in the querystring, in headers, etc, and where to save them.', static::X ) ?></p>

<div class="developers raw hidden">
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
	<a class="button" on-click="toggleRaw()">Toggle {{#showRaw}}Editor{{else}}Raw{{/}}</a>

	<table class="wp-list-table widefat fixed striped {{#showRaw}}hidden{{/}}">
		<thead>
			<tr>
				<th scope="col" class="column-links">#</th>
				<th scope="col">Entry <span class="header-actions"></span></th>
			</tr>
		</thead>
		<tbody>

			{{#items}}
			<tr decorator="addable">
				<th scope="row" class="column-links">
					<strong>{{@index+1}}: </strong>
					<span class="row-actions"></span>
				</th>
				<td>
					<table class="form-table">
						<tr>
							<th scope="row"><label for='src-{{@index}}'>Source</label></th>
							<td>	
								<div class="radios">
									<label><input type='radio' value='get' checked name='{{.src_t}}' /> <b>Request</b></label>
									<label><input type='radio' value='cookie' name='{{.src_t}}' /> <b>Cookie</b></label>
									<label><input type='radio' value='session' name='{{.src_t}}' /> <b>Session</b></label>
									<label><input type='radio' value='header' name='{{.src_t}}' /> <b>Header</b></label>
								</div>
									
								<label><b>Key:</b> <input id='src-{{@index}}' value='{{.src}}' /></label>
							</td>
						</tr>
						<tr>
							<th scope="row"><label for='dest-{{@index}}'>Destination</label></th>
							<td>	
								<div class="radios">
									<label><input type='radio' value='session' checked name='{{.dest_t}}' /> <b>Session</b></label>
									<label><input type='radio' value='cookie' name='{{.dest_t}}' /> <b>Cookie</b></label>
									<label><input type='radio' value='header' name='{{.dest_t}}' /> <b>Header</b></label>
								</div>
									
								<label><b>Key:</b> <input id='dest-{{@index}}' value='{{.dest}}' /></label>
								
								<p><em>Leave blank to reuse 'Source' key</em></p>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			{{else}}
			<tr>
				<td colspan=2>
					<a on-click='push("items", {})' class="button add">Add</a>
				</td>
			</tr>
			{{/items}}

		</tbody>
	</table>
</script>