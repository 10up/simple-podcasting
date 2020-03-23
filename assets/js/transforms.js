/**
 * WordPress dependencies
 */
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
			transform: ( attributes ) => {
				return createBlock( 'core/audio', {
					id: attributes.id,
					src: attributes.src
				} );
			},
		},
	],
};

export default transforms;
