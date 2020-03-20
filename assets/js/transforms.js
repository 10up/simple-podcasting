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
				const { id, src } = attributes;
				console.debug( 'from', attributes );
				return createBlock( 'podcasting/podcast', {
					id,
					src
				} );
			},
		}
	],
	to: [
		{
			type: 'block',
			blocks: [ 'core/audio' ],
			transform: ( attributes ) => {
				const { id, src } = attributes;
				console.debug( 'to', attributes );
				return createBlock( 'core/audio', {
					id,
					src,
					caption: '',
					loop: false,
					autoplay: false,
					preload: 'auto'
				} );
			},
		},
	],
};

export default transforms;
