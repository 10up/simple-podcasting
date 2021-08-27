describe( 'Admin can login and make sure plugin is activated', () => {
	it( 'Can activate plugin if it is deactivated', () => {
		cy.visitAdminPage( 'plugins.php' );
		cy.get( 'body' ).then( ( $body ) => {
			if ( $body.find( '#deactivate-simple-podcasting' ).length > 0 ) {
				cy.get( '#deactivate-simple-podcasting' ).click();
			}
		} );
		cy.get( '#activate-simple-podcasting' ).click();
		cy.get( '#deactivate-simple-podcasting' ).should( 'be.visible' );
	} );
} );
