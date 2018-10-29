/**
 * Internal block libraries
 */
const { __ } = wp.i18n;
const { Component } = wp.element;
const {
	registerBlockType,
	Editable,
	BlockAlignmentToolbar,
} = wp.blocks;
const {
	BlockControls,
	InspectorControls,
	MediaPlaceholder,
	MediaUpload,
	RichText,
} = wp.editor;
const {
	Button,
	FormToggle,
	IconButton,
	PanelBody,
	PanelRow,
	SelectControl,
	TextControl,
	Toolbar,
} = wp.components;

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
		useOnce: true,

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

		edit: class extends Component {
			constructor( { className } ) {
				super( ...arguments );
				// edit component has its own src in the state so it can be edited
				// without setting the actual value outside of the edit UI
				this.state = {
					editing: ! this.props.attributes.src,
					src: ! this.props.attributes.id ? this.props.attributes.src : null,
					className,
				};
			}

			render() {
				const { id, align, caption, podcastTerm, captioned, explicit, url, mime, duration } = this.props.attributes;
				const { setAttributes, isSelected } = this.props;
				const { editing, className, src } = this.state;

				const switchToEditing = () => {
					this.setState( { editing: true } );
				};

				const onSelectAttachment = ( attachment ) => {
					this.setState( { src: undefined } );

					setAttributes( {
						id: attachment.id,
						src: attachment.url,
						url: attachment.url,
						mime: attachment.mime,
						filesize: attachment.filesizeInBytes,
						duration: attachment.fileLength,
						caption: attachment.title,
					} );
					this.setState( { editing: false } );
				};
				const onSelectURL = ( newSrc ) => {
					if ( newSrc !== src ) {
						setAttributes({
							src: newSrc,
							url: newSrc,
							id: null,
							mime: null,
							filesize: null,
							duration: null,
							caption: '',

						});
					}
					this.setState( { editing: false } );
				};
				const toggleCaptioned = () => setAttributes( { captioned: ! captioned } );

				const controls = (
					<BlockControls key="controls">
						<Toolbar>
							<IconButton
								className="components-icon-button components-toolbar__control"
								label={ __( 'Edit Podcast', 'simple-podcasting' ) }
								onClick={ switchToEditing }
								icon="edit"
							/>
						</Toolbar>
					</BlockControls>
				);

				return [
					controls,
					(
						<InspectorControls>
							<PanelBody
								title={ __( 'Podcast Settings', 'simple-podcasting' ) }
							>
								<PanelRow>
									<label
										htmlFor="podcast-captioned-form-toggle"
									>
										{ __( 'Closed Captioned', 'simple-podcasting' ) }
									</label>
									<FormToggle
										id="podcast-captioned-form-toggle"
										label={ __( 'Closed Captioned', 'simple-podcasting' ) }
										checked={ captioned }
										onChange={ toggleCaptioned }
									/>
								</PanelRow>
								<PanelRow>
									<SelectControl
										label={ __( 'Explicit Content', 'simple-podcasting' ) }
										value={ explicit }
										options={ [
											{ value: 'no', label: __( 'No', 'simple-podcasting' ) },
											{ value: 'yes', label: __( 'Yes', 'simple-podcasting' ) },
											{ value: 'clean', label: __( 'Clean', 'simple-podcasting' ) },
										] }
										onChange={ explicit => setAttributes( { explicit } ) }
									/>
								</PanelRow>
								<PanelRow>
									<TextControl
										label={ __( 'Length (MM:SS)', 'simple-podcasting' ) }
										value={ duration }
										onChange={ duration => setAttributes( { duration } ) }
									/>
								</PanelRow>
							</PanelBody>
						</InspectorControls>
					),
					<div className={ className }>

						{ ! editing ? (

							<figure key="audio" className={ className }>
								<audio controls="controls" src={ src } />
								{ ( ( caption && caption.length ) || !! isSelected ) && (
									<RichText
										tagName="figcaption"
										placeholder={ __( 'Write caption…' ) }
										value={ caption }
										onChange={ ( value ) => setAttributes( { caption: value } ) }
										isSelected={ isSelected }
									/>
								) }
							</figure>

						) : (

							<MediaPlaceholder
								icon="microphone"
								labels={ {
									title: __( 'Podcast', 'simple-podcasting' ),
									name: __( 'a podcast episode', 'simple-podcasting' ),
								} }
								className={ className }
								onSelect={ onSelectAttachment }
								onSelectURL={ onSelectURL }
								accept="audio/*"
								type="audio"
								value={ this.props.attributes }
							/>

						)}

					</div>
				];
			}
		},

		save: props => {
			const { id, src, align, caption, podcastTerm, captioned, explicit, podcastEpisode } = props.attributes;
			return (
				<figure className={ id ? `podcast-${ id }` : null }>
					<audio controls="controls" src={ src } />
					{ caption && caption.length > 0 && <figcaption>{ caption }</figcaption> }
				</figure>
			);
		},
	},
);
