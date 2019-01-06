jQuery(document).ready(function ($) {
	$(document).on('click', '.js-image-upload', function (e) {
		e.preventDefault();
		var $button = $(this);

		var file_frame = wp.media.frames.file_frame = wp.media({
			title: 'Select or Upload an Image',
			library: {
				type: 'image' // mime type
			},
			button: {
				text: 'Select Image'
			},
			multiple: false
		});

		file_frame.on('select', function() {
			var attachment = file_frame.state().get('selection').first().toJSON();
			$button.siblings('.image-upload').val(attachment.url);
		});

		file_frame.open();
	});

});
jQuery(document).ready(function($) {
	function initColorPicker( widget ) {
		widget.find( '.color-picker' ).wpColorPicker( {
		change: _.throttle( function() { // For Customizer
		$(this).trigger( 'change' );
		}, 3000 ),
		clear: _.throttle( function() { // For Customizer
		$(this).trigger( 'change' );
		}, 4000 )
		});
	}
	function textColorPicker( widget ) {
		widget.find( '.text-color-picker' ).wpColorPicker( {
		change: _.throttle( function() { // For Customizer
		$(this).trigger( 'change' );
		}, 3000 ),
		clear: _.throttle( function() { // For Customizer
		$(this).trigger( 'change' );
		}, 4000 )
		});
	}
		function onFormUpdate( event, widget ) {
			initColorPicker( widget );
			textColorPicker( widget );
		}

		$( document ).on( 'widget-added widget-updated', onFormUpdate );

		$( document ).ready( function() {
			$( '.widget:has(.color-picker)' ).each( function () {
				initColorPicker( $( this ) );
			} );
			$( '.widget:has(.text-color-picker)' ).each( function () {
				textColorPicker( $( this ) );
			} );
		} );
});
