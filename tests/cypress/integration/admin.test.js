describe('Admin can login and make sure plugin is activated', () => {
	beforeEach(() => {
		cy.login();
	});

	it('Can activate plugin if it is deactivated', () => {
		cy.deactivatePlugin('simple-podcasting');
		cy.activatePlugin('simple-podcasting');
	});
});
