const { randomName } = require('../support/functions');

describe('Admin can create and update podcast taxonomy', () => {
	before(() => {
		cy.login();
		cy.deleteAllTerms('podcasting_podcasts');
	});

	after(() => {
		cy.deleteAllTerms('podcasting_podcasts');
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
		cy.get('h1').should('have.text', 'Podcasts');
		cy.get('.form-wrap h2').should('have.text', 'Add New Podcast');
		cy.get('.notice').should(
			'contain.text',
			'Once at least one podcast exists'
		);
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
		cy.url().should('contain', 'http://localhost:8889/wp-admin/term.php');
		cy.get('#name').click().clear();
		cy.get('#name').type('Distributed');
		cy.get('#slug').click().clear();
		cy.get('input[type="submit"]').click();
		cy.url().should('contain', 'http://localhost:8889/wp-admin/term.php');
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
		cy.url().should('contain', 'http://localhost:8889/wp-admin/term.php');
		cy.on('window:confirm', () => true);
		cy.get('.delete').click();
		cy.url().should(
			'contain',
			'http://localhost:8889/wp-admin/edit-tags.php'
		);
		cy.get('.wp-list-table').should('contain.text', 'No podcasts found');
	});

	it('Can add taxonomy with type of show', () => {
		let podcastName = 'Podcast ' + randomName();
		cy.createTerm(podcastName, 'podcasting_podcasts', {
			beforeSave: () => {
				cy.get('#podcasting_type_of_show').select('n/a');
			},
		});
		cy.get('.notice').contains('Item added.');
		cy.get('.row-title').contains(podcastName).click();
		cy.get('#podcasting_type_of_show').should('have.value', '0');

		podcastName = 'Podcast ' + randomName();
		cy.createTerm(podcastName, 'podcasting_podcasts', {
			beforeSave: () => {
				cy.get('#podcasting_type_of_show').select('Serial');
			},
		});
		cy.get('.notice').contains('Item added.');
		cy.get('.row-title').contains(podcastName).click();
		cy.get('#podcasting_type_of_show').should('have.value', 'serial');
	});
});
