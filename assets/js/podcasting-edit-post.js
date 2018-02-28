jQuery( document ).ready( function( $ ) {

	var isEnclosure = false;

	$( '#podcasting-enclosure-button' ).click( function( e ) {
		var postID = parseInt( Podcasting.postID );
		isEnclosure = true;
		tb_show( '', Podcasting.modalUrl );
		e.preventDefault();
	} );

	window.original_send_to_editor = window.send_to_editor;

	window.send_to_editor = function( html ) {
		if ( isEnclosure ) {
			// Strip audio shortcode if present.
			html = html.replace( /^\[audio\s/, '' );
			html = html.replace( /(\s)*\]$/, '' );

			var $html  = $( html ),
				source = '';

			// TODO This is a FAIL when the user selects link to attachment URL
			if ( $html.is( 'a' ) )
				source = $html.attr( 'href' );
			else
				source = html;

			$( '#podcasting-enclosure-url' ).val( source );
			tb_remove();
		} else {
			window.original_send_to_editor( html );
		}
	}

} );