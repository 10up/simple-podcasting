describe('Admin can create and update podcast taxonomy', () => {
	it('Can see taxonomy menu item', () => {
		cy.visitAdminPage();
		cy.get(
			'#toplevel_page_edit-tags-taxonomy-podcasting_podcasts-amp-podcasts-true .wp-menu-name'
		).should('have.text', 'Podcasts');
	});

	it('Can visit taxonomy page', () => {
		cy.visitAdminPage('edit-tags.php?taxonomy=podcasting_podcasts&podcasts=true');
		cy.get('.wp-heading-inline').should('have.text', 'Podcasts');
		cy.get('.form-wrap h2').should('have.text', 'Add New Podcast');
	});

	it('Can add a new taxonomy', () => {
		cy.visitAdminPage('edit-tags.php?taxonomy=podcasting_podcasts&podcasts=true');
		cy.get('#tag-name').click().type('Remote work{enter}');
	});
});
