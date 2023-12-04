import { registerBlockType } from '@wordpress/blocks';
import { InnerBlocks } from '@wordpress/block-editor';
import './styles.css';

import Edit from './edit';

import './cite';
import './time';

registerBlockType('podcasting/podcast-transcript', {
	edit: Edit,
	save: () => <InnerBlocks.Content />,
});
