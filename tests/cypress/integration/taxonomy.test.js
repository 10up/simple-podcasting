describe('Admin can create and update podcast taxonomy', () => {
	before(() => {
		cy.login();
	});

	it('Can see taxonomy menu item', () => {
		cy.visit('/wp-admin');
		cy.get(
			'#toplevel_page_edit-tags-taxonomy-podcasting_podcasts-amp-podcasts-true .wp-menu-name'
		).should('have.text', 'Podcasts');
	});

	it('Can visit taxonomy page', () => {
		cy.visit(
			'/wp-admin/edit-tags.php?taxonomy=podcasting_podcasts&podcasts=true'
		);
		cy.get('.wp-heading-inline').should('have.text', 'Podcasts');
		cy.get('.form-wrap h2').should('have.text', 'Add New Podcast');
		cy.get('.notice').should(
			'contain.text',
			'Once at least one podcast exists'
		);
	});

	it('Can delete all taxonomies', () => {
		cy.deleteAllTerms('podcasting_podcasts');
	});

	it('Can add a new taxonomy', () => {
		cy.createTerm('Remote work', 'podcasting_podcasts');
		cy.get('.row-title').should('have.text', 'Remote work');
	});

	it('Can edit taxonomy', () => {
		cy.visit(
			'/wp-admin/edit-tags.php?taxonomy=podcasting_podcasts&podcasts=true'
		);
		cy.get('.row-title').should('have.text', 'Remote work').click();
		cy.url().should('contains', 'http://localhost:8889/wp-admin/term.php');
		cy.get('#name').click().clear();
		cy.get('#name').type('Distributed');
		cy.get('#slug').click().clear();
		cy.get('#edittag').submit();
		cy.url().should('contains', 'http://localhost:8889/wp-admin/term.php');
		cy.visit(
			'/wp-admin/edit-tags.php?taxonomy=podcasting_podcasts&podcasts=true'
		);

		cy.get('.row-title').should('have.text', 'Distributed');
	});

	it('Can delete taxonomy', () => {
		cy.visit(
			'/wp-admin/edit-tags.php?taxonomy=podcasting_podcasts&podcasts=true'
		);
		cy.get('.row-title').should('have.text', 'Distributed').click();
		cy.url().should('contains', 'http://localhost:8889/wp-admin/term.php');
		cy.on('window:confirm', () => true);
		cy.get('.delete').click();
		cy.url().should(
			'contains',
			'http://localhost:8889/wp-admin/edit-tags.php'
		);
		cy.get('.wp-list-table').should('contain.text', 'No podcasts found');
	});
});
