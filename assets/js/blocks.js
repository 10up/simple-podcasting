/**
 * Internal block libraries
 */
import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';
const { select } = wp.data;

// Split the Edit component out.
import Edit from './edit';
import transforms from './transforms';
import '../css/podcasting-editor-screen.css';

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
				type: 'string',
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
			},
			enclosure: {
				type: 'string',
				source: 'meta',
				meta: 'enclosure',
			},
			seasonNumber: {
				type: 'string',
				source: 'meta',
				meta: 'podcast_season_number',
			},
			episodeNumber: {
				type: 'string',
				source: 'meta',
				meta: 'podcast_episode_number',
			},
			episodeType: {
				type: 'string',
				source: 'meta',
				meta: 'podcast_episode_type',
			},
			displayDuration: {
				type: 'boolean',
				default: false,
			},
			displayShowTitle: {
				type: 'boolean',
				default: false,
			},
			displayEpisodeTitle: {
				type: 'boolean',
				default: false,
			},
			displayArt: {
				type: 'boolean',
				default: false,
			},
			displayExplicitBadge: {
				type: 'boolean',
				default: false,
			},
			displaySeasonNumber: {
				type: 'boolean',
				default: false,
			},
			displayEpisodeNumber: {
				type: 'boolean',
				default: false,
			},
			displayEpisodeType: {
				type: 'boolean',
				default: false,
			}
		},
		transforms,

		edit: Edit,

		save: props => {
			const {
				id,
				src,
				caption
			} = props.attributes;

			return (
				<figure className={ id ? `podcast-${ id }` : null }>
					{ caption && caption.length > 0 && <figcaption className="wp-block-podcasting-podcast__caption">{ caption }</figcaption> }
					<audio controls="controls" src={ src } />
				</figure>
			);
		},
	},
);
