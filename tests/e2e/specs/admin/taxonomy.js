/**
 * WordPress dependencies
 */
import { switchUserToAdmin, visitAdminPage } from '@wordpress/e2e-test-utils';

describe('The podcast taxonomy is working as expected', () => {
	beforeAll(async () => {
		await switchUserToAdmin();

		await visitAdminPage(
			'edit-tags.php',
			'taxonomy=podcasting_podcasts&podcasts=true'
		);
		await expect(page).toClick('#cb-select-all-1');
		await expect(page).toSelect('#bulk-action-selector-top', 'Delete');
		await expect(page).toClick('#doaction');
	});

	it('Admin can see taxonomy menu', async () => {
		await visitAdminPage('index.php');
		await expect(page).toMatchElement('div.wp-menu-name', { text: 'Podcasts' });
	});

	it('Admin can visit taxonomy page', async () => {
		await visitAdminPage(
			'edit-tags.php',
			'taxonomy=podcasting_podcasts&podcasts=true'
		);
		await expect(page).toMatchElement('h1.wp-heading-inline', { text: 'Podcasts' });
		await expect(page).toMatchElement('h2', { text: 'Add New Podcast' });
	});

	it('Admin can add new taxonomy', async () => {
		await visitAdminPage(
			'edit-tags.php',
			'taxonomy=podcasting_podcasts&podcasts=true'
		);
		await expect(page).toFill('#tag-name', 'Remote work');
		await expect(page).toClick('#submit');
		await expect(page).toMatchElement('.row-title', 'Remote work');
		await expect(page).toMatchElement('.slug', 'remote-work');
	});

	it('Admin can edit taxonomy', async () => {
		await visitAdminPage(
			'edit-tags.php',
			'taxonomy=podcasting_podcasts&podcasts=true'
		);
		const editLink = await page.$('.row-title');
		const editUrl = await editLink.evaluate((node) => node.href);
		await page.goto(editUrl);

		await expect(page).toFill('#name', 'Distributed');
		await expect(page).toFill('#slug', '');
		await expect(page).toClick('input[type="submit"]');

		await visitAdminPage(
			'edit-tags.php',
			'taxonomy=podcasting_podcasts&podcasts=true'
		);
		await expect(page).toMatchElement('.row-title', 'Distributed');
		await expect(page).toMatchElement('.slug', 'distributed');
	});

	it('Admin can delete taxonomy', async () => {
		await visitAdminPage(
			'edit-tags.php',
			'taxonomy=podcasting_podcasts&podcasts=true'
		);

		await page.click('a.row-title');
		await page.waitForSelector('#edittag');

		const dialog = await expect(page).toDisplayDialog(async () => {
			await expect(page).toClick('.delete');
		});

		await dialog.accept();

		await page.waitForSelector('.wp-list-table.tags');

		await expect(page).toMatch('No podcasts found');
	});
});
