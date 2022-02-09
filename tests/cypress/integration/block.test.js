describe( 'Admin can publish posts with podcast block', () => {
	const taxonomy = 'Remote work';
	before( () => {
		cy.createTaxonomy( taxonomy, 'podcasting_podcasts' );
	} );
	it( 'Can insert the block and publish the post', () => {
		cy.visitAdminPage( 'post-new.php' );
		cy.get( 'button[aria-label="Close dialog"]' ).click();

		cy.get('body')
			.then(
				($body) =>
					$body.find('h1.editor-post-title__input').length
						? 'h1.editor-post-title__input' // WordPress 5.9
						: '#post-title-0' // 5.8.1 and below
			)
			.then((selector) => {
				cy.get(selector).click().type('Test episode');
			});

		cy.get('.edit-post-header-toolbar__inserter-toggle').click();

		cy.get('body')
			.then(
				($body) =>
					$body.find('#components-search-control-0').length
						? '#components-search-control-0' // WordPress 5.9
						: '#block-editor-inserter__search-0' // 5.8.1 and below
			)
			.then((selector) => {
				cy.get(selector).type('Podcast');
			});

		cy.get( '.editor-block-list-item-podcasting-podcast' ).click();
		cy.get( '.edit-post-header-toolbar__inserter-toggle' ).click();
		cy.get( '.wp-block-podcasting-podcast input[type="file"]' ).attachFile(
			'example.mp3'
		);
		cy.get( '.wp-block-podcasting-podcast audio' )
			.should( 'have.attr', 'src' )
			.and( 'include', 'example' );
		cy.openDocumentSettingsPanel( 'Podcasts' );
		cy.get( '.components-panel__body' )
			.contains( 'Podcasts' )
			.parents( '.components-panel__body' )
			.find( '.components-checkbox-control__label' )
			.contains( taxonomy )
			.click();
		cy.get( '.editor-post-publish-panel__toggle' ).click();
		cy.get( '.editor-post-publish-button' ).click();
		cy.get( '.components-snackbar', { timeout: 10000 } ).should(
			'be.visible'
		);
		cy.get( 'a.components-button.components-snackbar__action' ).click();
		cy.get( '.wp-block-podcasting-podcast audio' )
			.should( 'have.attr', 'src' )
			.and( 'include', 'example' );
		cy.visitAdminPage( 'edit.php' );
		cy.get( '.column-taxonomy-podcasting_podcasts' ).should(
			'contain.text',
			taxonomy
		);
	} );
} );
