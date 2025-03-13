export default [
	{
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
			}
		},
		supports: {
			multiple: false,
		},
		save: props => {
			const {
				id,
				src,
				caption
			} = props.attributes;

			return (
				<figure className={ id ? `podcast-${ id }` : null }>
					<audio controls="controls" src={ src } />
					{ caption && caption.length > 0 && <figcaption>{ caption }</figcaption> }
				</figure>
			);
		},
	},
];
