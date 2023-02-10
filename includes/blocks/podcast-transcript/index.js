import { registerBlockType } from '@wordpress/blocks';
import './styles.css';

import Edit from './edit';

import './formats';

registerBlockType('podcasting/podcast-transcript', {
	edit: Edit,
	save: () => null,
});
