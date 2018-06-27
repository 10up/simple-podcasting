jQuery( document ).ready( function( $ ) {

	// When the term add button is clicked, reset the dropdown fields.
	$( '#submit' ).click( function() {

		var $form = $( 'form#addtag' );

		if ( ! validateForm( $form ) ) {
			return;
		}

		// Add a brief delay to allow the form to submit.
		setTimeout( function() {
			$( '.fm-select select' ).val( 'None' );
		}, 500 );
	} );

	var mediaUploader;

	// Handle media upload buttons.
	$( 'input.podcasting-media-button' ).on( 'click', function( e ) {
		e.preventDefault();

		var $button   = $( e.currentTarget ),
			$hidden   = $( document.getElementById( $button.data( 'slug' ) ) ),
			$wrapper  = $button.parents( '.media-wrapper' ),
			$image    = $wrapper.find( 'img' ),
			$existing = $wrapper.find( '.podasting-existing-image' ),
			$upload   = $wrapper.find( '.podcasting-upload-image' );

		// If the uploader object has already been created, reopen the dialog.
		if (mediaUploader) {
			mediaUploader.open();
			return;
		}
		// Extend the wp.media object.
		mediaUploader = wp.media.frames.file_frame = wp.media( {
			title: $button.data( 'choose' ),
			button: {
				text: $button.data( 'update' )
			},
			multiple: false
		});

		// When a file is selected, grab the URL and set it as the text field's value.
		mediaUploader.off( 'select' );
		mediaUploader.on( 'select', function() {
			attachment = mediaUploader.state().get('selection').first().toJSON();

			// Set the hidden field value.
			$hidden.val( attachment.id );

			// Update the display.
			$upload.addClass('hidden');
			$existing.removeClass('hidden');
			$image.attr( 'src', attachment.url );

		});

		// Open the uploader dialog
		mediaUploader.open();
	});

	// Handle media remove buttons.
	$( '.podcast-media-remove' ).on( 'click', function( e ) {
		e.preventDefault();

		var $link   = $( e.currentTarget ),
			$wrapper  = $link.parents( '.media-wrapper' ),
			$button   = $wrapper.find( '.podcasting-media-button' ),
			$hidden   = $( document.getElementById( $button.data( 'slug' ) ) ),
			$existing = $wrapper.find( '.podasting-existing-image' ),
			$upload   = $wrapper.find( '.podcasting-upload-image' );

			// Update the display.
			$upload.removeClass('hidden');
			$existing.addClass('hidden');
			$hidden.val( '' );

	} );
} );