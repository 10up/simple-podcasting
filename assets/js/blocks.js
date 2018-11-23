/**
 * Internal block libraries
 */
const { __ } = wp.i18n;
const {
	registerBlockType,
} = wp.blocks;

// Split the Edit component out.
import Edit from './edit';

/**
 * Register example block
 */
export default registerBlockType(
	'podcasting/podcast',
	{
		title: __( 'Podcast', 'simple-podcasting' ),
		description: __( 'Insert a podcast episode into a post. To add it to a podcast feed, select a podcast in document settings.', 'simple-podcasting' ),
		category: 'common',
		icon: 'microphone',
		supports: {
			multiple: false,
		},
		attributes: {
			id: {
				type: 'number',
			},
			src: {
				type: 'string',
				source: 'attribute',
				selector: 'audio',
				attribute: 'src',
			},
			url: {
				type: 'string',
				source: 'meta',
				meta: 'podcast_url',
			},
			filesize: {
				type: 'number',
				source: 'meta',
				meta: 'podcast_filesize',
			},
			duration: {
				type: 'string',
				source: 'meta',
				meta: 'podcast_duration',
			},
			mime: {
				type: 'string',
				source: 'meta',
				meta: 'podcast_mime',
			},
			caption: {
				type: 'array',
				source: 'children',
				selector: 'figcaption',
			},
			captioned: {
				type: 'boolean',
				source: 'meta',
				meta: 'podcast_captioned',
				default: false,
			},
			explicit: {
				type: 'string',
				source: 'meta',
				meta: 'podcast_explicit',
				default: 'no',
			}
		},

		edit: Edit,

		save: props => {
			const { id, src, caption } = props.attributes;
			return (
				<figure className={ id ? `podcast-${ id }` : null }>
					<audio controls="controls" src={ src } />
					{ caption && caption.length > 0 && <figcaption>{ caption }</figcaption> }
				</figure>
			);
		},
	},
);
