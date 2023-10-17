const {
	randomName,
	populatePodcast,
	deleteAllTerms,
} = require('../support/functions');

describe('Admin can create and update podcast taxonomy', () => {
	beforeEach(() => {
		cy.login();
	});

	before(() => {
		deleteAllTerms();
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
		cy.uploadMedia('tests/cypress/fixtures/example.jpg');
		cy.createTerm('Remote work', 'podcasting_podcasts', {
			beforeSave: () => {
				populatePodcast({
					author: 'Person Doe',
					summary: 'Lorem ipsum dolor',
					category: 'arts:food',
				});
			},
		});
		cy.get('.row-title').first().should('have.text', 'Remote work');
	});

	it('Can edit taxonomy', () => {
		cy.visit(
			'/wp-admin/edit-tags.php?taxonomy=podcasting_podcasts&podcasts=true'
		);
		cy.get('.row-title').contains('Remote work').click();
		cy.url().should('contain', 'http://localhost:8889/wp-admin/term.php');
		cy.get('#name').click().clear();
		cy.get('#name').type('Distributed');
		cy.get('#slug').click().clear();
		cy.get('input[type="submit"]').click();
		cy.url().should('contain', 'http://localhost:8889/wp-admin/term.php');
		cy.visit(
			'/wp-admin/edit-tags.php?taxonomy=podcasting_podcasts&podcasts=true'
		);

		cy.get('.row-title').first().should('have.text', 'Distributed');
	});

	it('Can delete taxonomy', () => {
		cy.visit(
			'/wp-admin/edit-tags.php?taxonomy=podcasting_podcasts&podcasts=true'
		);
		cy.get('.row-title').contains('Distributed').click();
		cy.url().should('contain', 'http://localhost:8889/wp-admin/term.php');
		cy.on('window:confirm', () => true);
		cy.get('.delete').click();
		cy.url().should(
			'contain',
			'http://localhost:8889/wp-admin/edit-tags.php'
		);
		cy.visit(
			'/wp-admin/edit-tags.php?taxonomy=podcasting_podcasts&podcasts=true'
		);
		cy.get('.row-title').contains('Distributed').should('not.exist');
	});

	const tests = {
		0: 'n/a',
		serial: 'Serial',
		episodic: 'Episodic',
	};

	for (const [typeOfShowKey, typeOfShowName] of Object.entries(tests)) {
		it(`Can add taxonomy with ${typeOfShowName} type of show`, () => {
			const podcastName = 'Podcast ' + randomName();
			cy.uploadMedia('tests/cypress/fixtures/example.jpg');
			cy.createTerm(podcastName, 'podcasting_podcasts', {
				beforeSave: () => {
					populatePodcast({
						typeOfShowName,
						author: 'Person Doe',
						summary: 'Lorem ipsum dolor',
						category: 'arts:food',
					});
				},
			}).then((term) => {
				cy.visit(
					`/wp-admin/term.php?taxonomy=podcasting_podcasts&tag_ID=${term.term_id}`
				);
				cy.get('#podcasting_type_of_show').should(
					'have.value',
					typeOfShowKey
				);
			});
		});
	}
});
