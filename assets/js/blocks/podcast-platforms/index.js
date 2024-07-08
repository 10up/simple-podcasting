/**
 * Internal block libraries
 */
import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';

import Edit from './edit';
import './index.scss';

/**
 * Register Podcast Platforms block
 */
export default registerBlockType(
	'podcasting/podcast-platforms',
	{
		title: __( 'Podcast Platforms', 'simple-podcasting' ),
		description: __( 'Displays the list of platforms where the selected show is available.', 'simple-podcasting' ),
		category: 'common',
		icon: 'microphone',
		supports: {
			multiple: false,
		},
		attributes: {
			showId: {
				type: 'number',
				default: 0,
			},
			iconSize: {
				type: 'number',
				default: 48,
			},
			align: {
				type: 'string',
				default: 'center',
			}
		},

		edit: Edit,

		save: () => null,
	},
);
