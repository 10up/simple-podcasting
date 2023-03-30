export const randomName = () => Math.random().toString(16).substring(7);

/**
 * Populates a podcast taxonomy.
 * @param {Object} args The podcast data to populate.
 * @param {string} args.typeOfShowName The type of show.
 * @param {string} args.author The author of the podcast.
 * @param {string} args.summary The summary of the podcast.
 * @param {string} args.category The category of the podcast.
 */
export const populatePodcast = (args) => {
	for (const [key, value] of Object.entries(args)) {
		switch (key) {
			case 'typeOfShowName':
				cy.get('#podcasting_type_of_show').select(value);
				break;
			case 'author':
				cy.get('#podcasting_talent_name').type(value);
				break;
			case 'summary':
				cy.get('#podcasting_summary').type(value);
				break;
			case 'category':
				cy.get('#podcasting_category_1').select(value);
				break;
		}
	}
	// Get first image from media library.
	cy.get('#image-podcasting_image').click();
	cy.get('#menu-item-browse').click();
	cy.get('.attachments-wrapper li:first-of-type').click();
	cy.get('.media-button-select').click();
};
