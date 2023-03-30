export const randomName = () => Math.random().toString(16).substring(7);

export const getFirstImage = () => {
	cy.get('#menu-item-browse').click();
	cy.get('ul.attachments li:first-of-type').click();
	cy.get('.media-button-select').click();
};

/**
 * Populates a podcast taxonomy.
 * @param {Object} args The podcast data to populate.
 * @param {string} args.typeOfShowName The type of show.
 * @param {string} args.author The author of the podcast.
 * @param {string} args.summary The summary of the podcast.
 * @param {string} args.category The category of the podcast.
 * @param {boolean} args.onboarding Whether this is onboarding or not.
 */
export const populatePodcast = (args) => {
	for (const [key, value] of Object.entries(args)) {
		switch (key) {
			case 'typeOfShowName':
				cy.get('#podcasting_type_of_show').select(value);
				break;
			case 'author':
				if (args.onboarding) {
					cy.get('input[name="podcast-artist"]').type(value);
				} else {
					cy.get('#podcasting_talent_name').type(value);
				}
				break;
			case 'summary':
				if (args.onboarding) {
					cy.get('textarea[name="podcast-description"]').type(value);
				} else {
					cy.get('#podcasting_summary').type(value);
				}
				break;
			case 'category':
				if (args.onboarding) {
					cy.get('select[name=podcast-category]').select(value);
				} else {
					cy.get('#podcasting_category_1').select(value);
				}
				break;
		}
	}
	// Get first image from media library.
	if (args.onboarding) {
		cy.get('#simple-podcasting__upload-cover-image').click();
	} else {
		cy.get('#image-podcasting_image').click();
	}
	getFirstImage();
};
