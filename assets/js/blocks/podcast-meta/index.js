import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import ServerSideRender from '@wordpress/server-side-render';
import { SelectControl, PanelBody, PanelRow } from '@wordpress/components';
import { Fragment } from '@wordpress/element';

export default registerBlockType(
	'podcasting/podcast-meta', {
		title: __( 'Podcast Meta', 'simple-podcasting' ),
		description: __( 'show podcast related data.', 'simple-podcasting' ),
		category: 'common',
		icon: 'info',
		attributes: {
			postId: {
				type: 'integer'
			},
			metaName: {
				type: 'string'
			}
		},
		ancestor: ['core/query'],
		usesContext: [ 'postId' ],
		edit: ({
			context: { postId },
			attributes: { metaName },
			setAttributes
		}) => {

			setAttributes({ postId });

			return (
				<Fragment>
					<InspectorControls>
						<PanelBody title={ __( 'Podcast Meta', 'simple-podcasting' ) }>
							<PanelRow>
								<SelectControl
									label={ __( 'Meta Name', 'simple-podcasting' ) }
									value={ metaName }
									onChange={ metaName => setAttributes( { metaName } )  }
									options={ [
										{ value: '', label: __( 'Select a podcast data type','simple-podcasting' ) },
										{ value: 'podcast_season_number', label: __( 'Season Number','simple-podcasting' ) },
										{ value: 'podcast_episode_number', label: __( 'Episode Number', 'simple-podcasting' ) },
										{ value: 'podcast_duration', label: __( 'Podcast Duration', 'simple-podcasting' ) }
									] }
								/>
							</PanelRow>
						</PanelBody>
					</InspectorControls>
					{ postId && (
						<ServerSideRender
							block="podcasting/podcast-meta"
							attributes={ { metaName, postId } }
						/>
					) }
				</Fragment>
			)

		}
	}
)

