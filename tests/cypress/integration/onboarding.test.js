const { randomName, populatePodcast } = require('../support/functions');

describe('Onboarding tests', () => {
	before(() => {
		cy.login();
	});

	beforeEach(() => {
		cy.uploadMedia('tests/cypress/fixtures/example.jpg');
		cy.activatePlugin('simple-podcasting');
		cy.deleteAllTerms('podcasting_podcasts');
		cy.deactivatePlugin('simple-podcasting');
		cy.visit('/wp-admin/options.php');
		cy.get('body').then(($body) => {
			if ($body.find('#simple_podcasting_onboarding').length !== 0) {
				cy.get('#simple_podcasting_onboarding')
					.click()
					.type('{selectAll}no');
				cy.get('.submit input[type=submit]').click();
			}
		});
		cy.activatePlugin('simple-podcasting');
	});

	it('Should indicate errors', () => {
		cy.url().should('include', 'simple-podcasting-onboarding');
		cy.url().should('include', 'step=1');

		cy.get('#simple-podcasting__create-podcast-button')
			.closest('form')
			.submit();

		cy.get('input[name="podcast-name"]').then(($input) => {
			expect($input[0].validationMessage).to.eq(
				'Please fill out this field.'
			);
		});
	});

	it('Should pass onboarding', () => {
		cy.url().should('include', 'simple-podcasting-onboarding');
		cy.url().should('include', 'step=1');

		const podcastName = 'Onboarding ' + randomName();
		cy.get('input[name=podcast-name]').click().type(podcastName);
		populatePodcast({
			author: 'Person Doe',
			summary: 'Lorem ipsum dolor',
			category: 'Arts',
			onboarding: true,
		});
		cy.get('#simple-podcasting__create-podcast-button')
			.closest('form')
			.submit();

		cy.url().should('include', 'step=2');

		cy.visit(
			'/wp-admin/edit-tags.php?taxonomy=podcasting_podcasts&podcasts=true'
		);

		// Podcast was created.
		cy.contains(podcastName);

		// Option was saved.
		cy.visit('/wp-admin/options.php');
		cy.get('#simple_podcasting_onboarding').should(
			'have.value',
			'completed'
		);
	});
});
