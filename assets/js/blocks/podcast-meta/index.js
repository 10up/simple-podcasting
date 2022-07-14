import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import { InspectorControls } from '@wordpress/block-editor';
import {
	SelectControl,
	PanelBody,
	PanelRow,
	__experimentalToggleGroupControl as ToggleGroupControl,
    __experimentalToggleGroupControlOption as ToggleGroupControlOption
} from '@wordpress/components';
import { Fragment, useEffect, useCallback } from '@wordpress/element';
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
			},
			headingSize: {
				type: 'string',
				default: 'h2'
			}
		},
		ancestor: ['core/query'],
		usesContext: [ 'postId' ],
		edit: ({
			context: { postId },
			attributes,
			setAttributes
		}) => {

			useEffect(() => {
				setAttributes({ postId });
			}, [postId])

			const { metaName, headingSize, className } = attributes;
			const { meta } = useSelect((select) => select('core').getEntityRecord('postType', 'post', postId) )

			const getPodcastDuration = useCallback(
				() => {

					let hours, minutes, seconds;
					const values = meta[metaName].trim().split(':');

					if(values.length === 3) {
						[hours, minutes, seconds] = values
					}else if( values.length === 2 ) {
						[minutes, seconds] = values
					}

					const invalidValues = ['0', '00']
					let duration = [];

					if(hours && ! invalidValues.includes(hours)) {
						duration.push(`${hours} hr`)
					}

					if(minutes && ! invalidValues.includes(minutes)) {
						duration.push(`${minutes} min`)
					}

					if(seconds && ! invalidValues.includes(seconds)) {
						duration.push(`${seconds} sec`)
					}

					return duration.join(' ')
				},
				[meta]
			);

			const PodcastMeta = () => {
				const classNames = [metaName.replaceAll('_', '-')];

				if(className){
					classNames.push(className)
				}

				let TagName = 'span';
				let value = meta[metaName];

				switch(metaName){
					case 'podcast_season_number':
					case 'podcast_episode_number':
						TagName = headingSize;
						break;
					case 'podcast_duration':
						value = getPodcastDuration();
						break;
				}
				return <TagName className={classNames.join(' ')}>{value}</TagName>
			}

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
										{ value: '', label: __( 'Select a podcast meta','simple-podcasting' ) },
										{ value: 'podcast_season_number', label: __( 'Season Number','simple-podcasting' ) },
										{ value: 'podcast_episode_number', label: __( 'Episode Number', 'simple-podcasting' ) },
										{ value: 'podcast_duration', label: __( 'Podcast Duration', 'simple-podcasting' ) }
									] }
								/>
							</PanelRow>
							{
								['podcast_season_number', 'podcast_episode_number'].includes(metaName) && (
									<PanelRow>
										<ToggleGroupControl
											label={__('Heading Level', 'simple-podcasting')}
											value={headingSize}
											onChange={(headingSize) => setAttributes({headingSize})}
											isBlock>
											{
												['h1', 'h2', 'h3', 'h4'].map((level) => {
													return (
														<ToggleGroupControlOption
															value={level}
															label={level.toUpperCase()}
															key={level}
														/>
													)
												})
											}
										</ToggleGroupControl>
									</PanelRow>
								)
							}
						</PanelBody>
					</InspectorControls>
					<PodcastMeta />
				</Fragment>
			)
		}
	}
)
