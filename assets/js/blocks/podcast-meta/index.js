import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import { BlockControls, InspectorControls } from '@wordpress/block-editor';
import ServerSideRender from '@wordpress/server-side-render';
import { SelectControl, PanelBody, PanelRow } from '@wordpress/components';
import { Fragment, useEffect } from '@wordpress/element';
import { useSelect } from '@wordpress/data';

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
			setAttributes,
			clientId
		}) => {

			const parent = useSelect(select => select('core/block-editor').getBlockParents(clientId))
			console.log(parent);

			setAttributes({ postId });

			// useEffect(() => {
			// 	setAttributes({ metaName })

			// 	console.log(metaName, postId);

			// }, [postId])

			// const { meta } = useSelect((select) => select('core').getEntityRecord('postType', 'post', postId))

			// console.log(meta)

			return (
				<Fragment>
					<BlockControls group="block">
						{
							[ 'podcast_season_number', 'podcast_episode_number' ].includes( metaName ) && (
								<HeadingLevelDropdown
									selectedLevel={ level }
									onChange={ ( newLevel ) =>
										setAttributes( { level: newLevel } )
									}
								/>
							)
						}
					</BlockControls>
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
					{ postId && metaName && (
						<ServerSideRender
							block="podcasting/podcast-meta"
							attributes={ { metaName, postId } }
						/>
					) }
					{/* {meta[metaName]} */}
				</Fragment>
			)

		}
	}
)

