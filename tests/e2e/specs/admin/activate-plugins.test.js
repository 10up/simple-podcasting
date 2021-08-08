/**
 * WordPress dependencies
 */
import { activatePlugin } from "@wordpress/e2e-test-utils";

describe( 'Admin can login and make sure plugin is activated', () => {

	it( 'Can activate plugin if it is deactivated' , async () => {
		await activatePlugin( 'simple-podcasting' );
	});

} );
