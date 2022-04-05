describe('Admin can login and make sure plugin is activated', () => {
	before(() => {
		cy.login();
	});

	it('Can activate plugin if it is deactivated', () => {
		cy.visit('/wp-admin/plugins.php');
		cy.get('#deactivate-simple-podcasting').click();
		cy.get('#activate-simple-podcasting').click();
		cy.get('#deactivate-simple-podcasting').should('be.visible');
	});
});
