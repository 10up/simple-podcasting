import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import ServerSideRender from '@wordpress/server-side-render';

export default registerBlockType(
	'podcasting/podcast-duration', {
		title: __( 'Podcast Duration', 'simple-podcasting' ),
		description: __( 'show podcast duration.', 'simple-podcasting' ),
		category: 'common',
		icon: 'clock',
		attributes: {
			postId: {
				type: 'integer'
			}
		},
		ancestor: ['core/query'],
		usesContext: [ 'postId' ],
		edit: ({
			context: { postId },
			attributes,
			setAttributes
		}) => {

			setAttributes({ postId });

			return attributes.postId ? (
				<ServerSideRender
					block="podcasting/podcast-duration"
					attributes={ attributes }
				/>
			) : '';

		}
	}
)

