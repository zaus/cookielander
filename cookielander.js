(function ($, R, L) {
	if (!console) console = {};
	if (!console.log) console.log = function () { }
	
	function init(options) {
		
		var keyShowRaw = 'showRaw',
			keyItems = 'items';
		
		//#region -------- DOM setup, helper functions --------------
		
		function toggleEditor(show) {
			$rawEditor.parents('tr').toggle(show);
		}
		function getRaw() {
			return JSON.parse($rawEditor.val());
		}
		function setRaw(val) {
			$rawEditor.val(JSON.stringify(val));
		}
		

		var $rawEditor = $(options.dataSrc);
		
		// before saving the page, grab the current settings
		$('#submit').on('click', function () {
			if(!editor.get(keyShowRaw)) setRaw(editor.get(keyItems));
		})

		// prepare ractive data
		var d = $.extend({
			items: getRaw(),
			showRaw: false
		}, options);
				
		// initial state - hide raw in favor of showing editor
		toggleEditor(d[keyShowRaw]);
		
		//#endregion -------- DOM setup, helper functions --------------
		
		var addableDecorator = Ractive.decorators.addable;
		// wp styling
		addableDecorator.addClass = addableDecorator.addClass.replace('btn', 'button');
		addableDecorator.remClass = addableDecorator.remClass.replace('btn', 'button');
		// in a table
		addableDecorator.addStyle = addableDecorator.rootSelector + ' .header-actions';
		addableDecorator.remStyle = '.row-actions';
		
		console.log('cookielander source data:', d);
		
		var editor = new R({
			el: '#' + options.n + '-editor',
			template: '#t-editor',
			data: d,
			// inline interactivity
			toggleRaw: function () {
				this.toggle(keyShowRaw);
				var show = this.get(keyShowRaw);
				
				// send current values back and forth
				if (show) setRaw(this.get(keyItems));
				else this.set(keyItems, getRaw());
				
				toggleEditor(show);
			}
		});
		
		return editor;
	}
	
	// expose
	L.init = init;
})(jQuery, Ractive, cookielander);