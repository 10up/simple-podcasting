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
} );