describe('Admin can publish posts with podcast block', () => {
	it('Can insert the block and publish the post', () => {
		cy.visitAdminPage('post-new.php');
		cy.get('button[aria-label="Close dialog"]').click();
		cy.get('#post-title-0').click().type('Test episode');
		cy.get('.edit-post-header-toolbar__inserter-toggle').click();
		cy.get('#block-editor-inserter__search-0').type('Podcast');
		cy.get('.editor-block-list-item-podcasting-podcast').click();
		cy.get('.edit-post-header-toolbar__inserter-toggle').click();
		cy.get('.wp-block-podcasting-podcast input[type="file"]').attachFile(
			'example.mp3'
		);
		cy.get('.editor-post-publish-panel__toggle').click();
		cy.get('.editor-post-publish-button').click();
		cy.get('.post-publish-panel__postpublish-buttons a').click();
		cy.get('.wp-block-podcasting-podcast audio')
			.should('have.attr', 'src')
			.and('include', 'example');
	});
});
