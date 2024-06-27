import 'cypress-localstorage-commands';
const { populatePodcast } = require('../support/functions');

describe('Admin can publish posts with podcast block', () => {
	const taxonomy = 'Remote work';

	before(() => {
		const userId = '1';
		cy.setLocalStorage(
			`WP_DATA_USER_${userId}`,
			JSON.stringify({
				'core/edit-post': {
					preferences: {
						features: {
							welcomeGuide: false,
						},
					},
				},
			})
		);
	});

	if (Cypress.env('HAS_BLOCK_EDITOR')) {
		it('Can insert the block and publish the post', () => {
			cy.login();
			cy.uploadMedia('tests/cypress/fixtures/example.jpg');
			cy.createTerm(taxonomy, 'podcasting_podcasts', {
				beforeSave: () => {
					populatePodcast({
						author: 'Person Doe',
						summary: 'Lorem ipsum dolor',
						category: 'arts:food',
					});
				},
			});

			cy.visit('/wp-admin/post-new.php');
			cy.closeWelcomeGuide();
			cy.get('h1.editor-post-title__input, #post-title-0')
				.first()
				.as('title-input');
			cy.get('@title-input').click().type('Test episode');
			cy.get(
				'.edit-post-header-toolbar__inserter-toggle, .editor-document-tools__inserter-toggle'
			).click();
			cy.get(
				'#components-search-control-0, #block-editor-inserter__search-0'
			)
				.first()
				.as('block-search');
			cy.get('@block-search').click().type('Podcast');
			cy.get('.editor-block-list-item-podcasting-podcast', {
				timeout: 4000,
			}).click();
			cy.get(
				'.edit-post-header-toolbar__inserter-toggle, .editor-document-tools__inserter-toggle'
			).click();
			cy.get(
				'.wp-block-podcasting-podcast input[type="file"]'
			).attachFile('example.mp3');
			cy.get('.wp-block-podcasting-podcast audio')
				.should('have.attr', 'src')
				.and('include', 'example');
			cy.openDocumentSettingsSidebar();
			cy.openDocumentSettingsPanel('Podcasts');
			cy.get('.components-panel__body')
				.contains('Podcasts')
				.parents('.components-panel__body')
				.find('.components-checkbox-control__label')
				.contains(taxonomy)
				.click();
			cy.get('.editor-post-publish-panel__toggle').click();
			cy.get('.editor-post-publish-button').click();
			cy.get('.components-snackbar', { timeout: 10000 }).should(
				'be.visible'
			);
			cy.get('a.components-button.components-snackbar__action').click();
			cy.get('.wp-block-podcasting-podcast audio')
				.should('have.attr', 'src')
				.and('include', 'example');
			cy.visit('/wp-admin/edit.php');
			cy.get('.column-taxonomy-podcasting_podcasts').should(
				'contain.text',
				taxonomy
			);
		});
	}
});
