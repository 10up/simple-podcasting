/**
 * External dependencies
 */
import path from 'path';
import fs from 'fs';
import os from 'os';
import { v4 as uuid } from 'uuid';

/**
 * WordPress dependencies
 */
import {
	switchUserToAdmin,
	openDocumentSettingsSidebar,
	publishPost,
	insertBlock,
	visitAdminPage,
	createNewPost,
	openPublishPanel,
} from '@wordpress/e2e-test-utils';

import { deleteAllTaxonomies } from '../../utils/delete-all-taxonomies';

async function upload(selector) {
	await page.waitForSelector(selector);
	const inputElement = await page.$(selector);
	const testImagePath = path.join(__dirname, '..', '..', 'assets', 'example.mp3');
	const filename = uuid();
	const tmpFileName = path.join(os.tmpdir(), filename + '.mp3');
	fs.copyFileSync(testImagePath, tmpFileName);
	await inputElement.uploadFile(tmpFileName);
	return filename;
}

async function waitForPodcast(filename) {
	await page.waitForSelector(
		`.wp-block-podcasting-podcast audio[src$="${filename}.mp3"]`
	);
}

describe('Blocks Editor', () => {
	beforeAll(async () => {
		await switchUserToAdmin();
		await deleteAllTaxonomies();
		await visitAdminPage(
			'edit-tags.php',
			'taxonomy=podcasting_podcasts&podcasts=true'
		);
		await expect(page).toFill('#tag-name', 'Remote work');
		await expect(page).toClick('#submit');
		await expect(page).toMatchElement('.row-title', 'Remote work');
	});

	it('Admin can add new podcast block.', async () => {
		await createNewPost({ title: 'Test Episode' });
		await insertBlock('Podcast');
		const filename = await upload('.wp-block-podcasting-podcast input[type="file"]');
		await waitForPodcast(filename);
		await openDocumentSettingsSidebar();
		await expect(page).toClick('.edit-post-sidebar__panel-tab');

		try {
			await expect(page).toClick('.components-panel__body.is-opened', {
				text: 'Podcasts',
			});
		} catch {}
		await expect(page).toClick('.components-panel__body', { text: 'Podcasts' });

		await expect(page).toClick('.components-checkbox-control__label', {
			text: 'Remote work',
		});
		await publishPost();
		await visitAdminPage(
			'edit-tags.php',
			'taxonomy=podcasting_podcasts&podcasts=true'
		);
		await expect(page).toMatchElement('.posts.column-posts', { text: '1' });
	});
});
