import '../css/podcasting-onboarding.scss';

( function( $ ) {
	$( function() {
		const selectImageBtn = $( '#simple-podcasting__upload-cover-image' );
		const coverImage = $( 'input[name="podcast-cover-image-id"]' );
		const coverImagePreview = $( '#podcast-cover-image-preview' );
		let uploader_frame = null;

		/** Upload image button handler */
		selectImageBtn.on( 'click', function() {
			uploader_frame = wp.media( {
				multiple: false,
				library: {
					type: 'image'
				}
			} ).on( 'select', function() {
				const { id, url } = uploader_frame.state().get( 'selection' ).first().toJSON();
				coverImagePreview.html( `<img src="${ url }" />` )
				coverImage.val( id );
			} );

			uploader_frame.open();
		} );
	} )
} )( jQuery )