/**
 * WordPress dependencies
 */
const { select } = wp.data;
const { createBlock } = wp.blocks;

/**
 * Transforms
 */
const transforms = {
	from: [
		{
			type: 'block',
			blocks: [ 'core/audio' ],
			transform: ( attributes ) => {
				return createBlock( 'podcasting/podcast', {
					id: attributes.id,
					src: attributes.src
				} );
			},
		},
	],
	to: [
		{
			type: 'block',
			blocks: [ 'core/audio' ],
			isMatch: ( { id } ) => {
				if ( ! id ) {
					return false;
				}
				const { getMedia } = select( 'core' );
				const media = getMedia( id );
				return !! media && media.mime_type.includes( 'audio' );
			},
			transform: ( attributes ) => {
				return createBlock( 'core/audio', {
					src: attributes.src,
					id: attributes.id
				} );
			},
		},
	],

};

export default transforms;
