/**
 * WordPress dependencies
 */
import { visitAdminPage } from '@wordpress/e2e-test-utils';

export async function deleteAllTaxonomies() {
	await page.waitForSelector('#wpwrap');
	await visitAdminPage('edit-tags.php', 'taxonomy=podcasting_podcasts&podcasts=true');
	await expect(page).toClick('#cb-select-all-1');
	await expect(page).toSelect('#bulk-action-selector-top', 'Delete');
	await expect(page).toClick('#doaction');

	return page.waitForSelector('.wp-list-table.tags');
}
