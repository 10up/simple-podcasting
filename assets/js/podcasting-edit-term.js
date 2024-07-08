/*global jQuery, validateForm*/
import '../css/podcasting-edit-term.css';

jQuery( document ).ready( function( $ ) {

	// Clear Image Field.
	function clearImageField( el ) {
		var $link   = $( el ),
			$wrapper  = $link.parents( '.media-wrapper' ),
			$button   = $wrapper.find( '.podcasting-media-button' ),
			$hidden   = $( document.getElementById( $button.data( 'slug' ) ) ),
			$existing = $wrapper.find( '.podasting-existing-image' ),
			$upload   = $wrapper.find( '.podcasting-upload-image' );

		// Update the display.
		$upload.removeClass('hidden');
		$existing.addClass('hidden');
		$hidden.val( '' );
	}

	// When the term add button is clicked, reset the dropdown fields.
	$( '#submit' ).click( function() {

		var $form = $( 'form#addtag' );

		if ( ! validateForm( $form ) ) {
			return;
		}

		// Add a brief delay to allow the form to submit.
		setTimeout( function() {
			$( '.fm-select select' ).val( 'None' );
			clearImageField( '.podcast-media-remove' );
			$( '#podcasting_category_1,#podcasting_category_2,#podcasting_category_3' ).val( '' );
			window.scrollTo(0,0);
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
		// eslint-disable-next-line camelcase
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
			var attachment = mediaUploader.state().get('selection').first();

			// Set the hidden field value.
			$hidden.val( attachment.get('id') );

			// Update the display.
			$upload.addClass('hidden');
			$existing.removeClass('hidden');
			$image.attr( 'src', attachment.get('url') );

		});

		// Open the uploader dialog
		mediaUploader.open();
	});

	// Handle media remove buttons.
	$( '.podcast-media-remove' ).on( 'click', function( e ) {
		e.preventDefault();
		clearImageField( e.currentTarget );
	} );

	const iconThemeRadioEl = $( 'input[name="podcasting_icon_theme"]' );
	const iconWrappers = $( '.simple_podcasting__platforms-icon' );

	iconThemeRadioEl.on( 'change', function() {
		const current = $( this );
		const selected = current.val();

		if ( 'white' === selected ) {
			iconWrappers.addClass( 'simple_podcasting__platforms-icon--darken-bg' );
		} else {
			iconWrappers.removeClass( 'simple_podcasting__platforms-icon--darken-bg' );
		}

		iconWrappers.each( ( index, icon ) => {
			const imgEl = $( icon ).find( 'img' );
			const platform = imgEl.data( 'platform' );
			imgEl.attr( 'src', `${ podcastingEditPostVars.iconUrl }/${ platform }/${ selected }-100.png` );
		});
	} );
} );

