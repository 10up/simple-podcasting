describe('Create podcast setting panel', () => {
	before(() => {
		cy.login();
		cy.visit(
			'/wp-admin/edit-tags.php?taxonomy=podcasting_podcasts&podcasts=true'
		);
		cy.get('body').then(($body) => {
			if (
				$body.find('[aria-label="Delete “Family People”"]').length > 0
			) {
				cy.get('[aria-label="Delete “Family People”"]').click({
					force: true,
				});
			}
		});
	});

	it('Podcast setting panel exists', () => {
		cy.visit('/wp-admin/post-new.php');
		cy.get('.podcasting__podcast-list').should('exist');
	});

	it('Create podcast popup shows when clicking creating a podcast', () => {
		cy.visit('/wp-admin/post-new.php');
		cy.get('.podcasting__podcast-list').click();
		cy.get('.podcasting__add-new-podcast').click();
		cy.get('.components-modal__content').should('exist');
	});

	it('Create podcast popup has valid fields', () => {
		cy.visit('/wp-admin/post-new.php');
		cy.get('.podcasting__podcast-list').click();
		cy.get('.podcasting__add-new-podcast').click();

		cy.get('.podcasting__modal-name-field input').should(
			'have.attr',
			'required'
		);
		cy.get('.podcasting__modal-artist-field input').should(
			'have.attr',
			'required'
		);
		cy.get('.podcasting__modal-category-field select').should(
			'have.attr',
			'required'
		);
		cy.get('.podcasting__modal-summary-field textarea').should(
			'have.attr',
			'required'
		);
	});

	it('Create podcast using popup', () => {
		cy.visit('/wp-admin/post-new.php');
		cy.get('.podcasting__podcast-list').click();
		cy.get('.podcasting__add-new-podcast').click();

		cy.get('.podcasting__modal-name-field input').type('Family People');
		cy.get('.podcasting__modal-artist-field input').type('Alex Neason');
		cy.get('.podcasting__modal-category-field select').select(
			'society-culture:documentary'
		);
		cy.get('.podcasting__modal-summary-field textarea').type(
			'At the 1998 Olympics in Nagano, Japan, one athlete pulled a move that, as far as we know, no one else had ever attempted.'
		);

		cy.get('.podcasting__select-image-btn').click();
		cy.get('[aria-label="example"]').first().click();
		cy.get('.media-button-select').click();
		cy.get('.podcasting__select-image-btn').should(
			'have.text',
			'Replace Image'
		);
		cy.get('.podcasting__remove-image-btn').should('exist');
		cy.get('.podcasting-cover-preview').should('exist');
		cy.get('.podcasting__create-podcast-btn').should(
			'not.have.attr',
			'disabled'
		);

		cy.get('.podcasting__remove-image-btn').click();
		cy.get('.podcasting__select-image-btn').should(
			'have.text',
			'Select Image'
		);
		cy.get('.podcasting__create-podcast-btn').should(
			'have.attr',
			'disabled'
		);

		cy.get('.podcasting__select-image-btn').click();
		cy.get('[aria-label="example"]').first().click();
		cy.get('.media-button-select').click();

		cy.get('.podcasting__create-podcast-btn').click();
		cy.get('.components-modal__header-heading').should(
			'have.text',
			'Podcast created!'
		);

		cy.get('button').contains('Add another Podcast').click();
		cy.get('.components-modal__content').should('exist');
		cy.get('.podcasting__podcast-list-item label').should(
			'have.text',
			'Family People'
		);
	});

	it('Verify created podcast', () => {
		cy.visit(
			'/wp-admin/edit-tags.php?taxonomy=podcasting_podcasts&podcasts=true'
		);
		cy.get('a').contains('Family People').click();

		cy.get('#name').should('have.value', 'Family People');
		cy.get('#podcasting_talent_name').should('have.value', 'Alex Neason');
		cy.get('#podcasting_summary').should(
			'have.value',
			'At the 1998 Olympics in Nagano, Japan, one athlete pulled a move that, as far as we know, no one else had ever attempted.'
		);
		cy.get('.podasting-existing-image img')
			.should('have.attr', 'src')
			.then((src) => {
				expect(src).to.match(/example(-\d)?.jpg/);
			});

		cy.get('#podcasting_category_1').should(
			'have.value',
			'society-culture:documentary'
		);
	});
});
