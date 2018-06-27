jQuery( document ).ready( function( $ ) {
	$( '#podcasting-enclosure-button' ).click( function( e ) {
		e.preventDefault();

		var $this = $( this ),
			$input = $( 'input#podcasting-enclosure-url' ),
			mediaUploader;

		console.log( $this );
		
		// If the uploader object has already been created, reopen the dialog.
		if ( mediaUploader ) {
			mediaUploader.open();
			return;
		}
		// Extend the wp.media object.
		mediaUploader = wp.media.frames.file_frame = wp.media( {
			title: $this.data( 'modalTitle' ),
			button: {
				text: $this.data( 'modalButton' )
			},
			library: {
				type: 'audio'
			},
			multiple: false
		});

		// When a file is selected, grab the URL and set it as the text field's value.
		mediaUploader.off( 'select' );
		mediaUploader.on( 'select', function() {
			var attachment = mediaUploader.state().get('selection').first(),
				attachmentUrl = attachment.get('url');

			$input.val( attachmentUrl );

		});

		// Open the uploader dialog
		mediaUploader.open();
	} );
} );