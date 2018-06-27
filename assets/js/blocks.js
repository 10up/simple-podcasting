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
	MediaUpload,
	RichText,
} = wp.editor;
const {
	Button,
	FormToggle,
	IconButton,
	PanelBody,
	PanelRow,
	Placeholder,
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
		title: __( 'Podcast', 'podcasting' ),
		description: __( 'Insert a podcast episode into a post. To add it to a podcast feed, select a podcast in document settings.', 'podcasting' ),
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
					this.setState( { src: null } );

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
				const onSelectUrl = ( event ) => {
					event.preventDefault();
					if ( src ) {
						setAttributes({
							src: src,
							url: src,
							id: null,
							mime: '',
							filesize: 0,
							duration: 0,
							caption: null,

						});
						this.setState( { editing: false } );
					}
					return false;
				};
				const toggleCaptioned = () => setAttributes( { captioned: ! captioned } );

				const controls = (
					<BlockControls key="controls">
						<Toolbar>
							<IconButton
								className="components-icon-button components-toolbar__control"
								label={ __( 'Edit Podcast', 'podcasting' ) }
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
							  title={ __( 'Podcast Settings', 'podcasting' ) }
							>
								<PanelRow>
									<label
										htmlFor="podcast-captioned-form-toggle"
									>
										{ __( 'Closed Captioned', 'podcasting' ) }
									</label>
									<FormToggle
										id="podcast-captioned-form-toggle"
										label={ __( 'Closed Captioned', 'podcasting' ) }
										checked={ captioned }
										onChange={ toggleCaptioned }
									/>
								</PanelRow>
								<PanelRow>
									<SelectControl
										label={ __( 'Explicit Content', 'podcasting' ) }
										value={ explicit }
										options={ [
											{ value: 'no', label: __( 'No', 'podcasting' ) },
											{ value: 'yes', label: __( 'Yes', 'podcasting' ) },
											{ value: 'clean', label: __( 'Clean', 'podcasting' ) },
										] }
										onChange={ explicit => setAttributes( { explicit } ) }
									/>
								</PanelRow>
								<PanelRow>
									<TextControl
										label={ __( 'Length (MM:SS)', 'podcasting' ) }
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

							<Placeholder
								key="placeholder"
								icon="microphone"
								label={ __( 'Podcast', 'podcasting' ) }
								instructions={ __( 'Select an audio file from your library, or upload a new one' ) }
								className={ className }>
								<form onSubmit={ onSelectUrl }>
									<input
										type="url"
										className="components-placeholder__input"
										placeholder={ __( 'Enter URL of audio file here…' ) }
										onChange={ event => this.setState( { src: event.target.value } ) }
										value={ src || '' } />
									<Button
										isLarge
										type="submit">
										{ __( 'Use URL' ) }
									</Button>
								</form>
								<MediaUpload
									onSelect={ onSelectAttachment }
									type="audio"
									value={ id }
									render={ ( { open } ) => (
										<Button isLarge onClick={ open }>
											{ __( 'Add from Media Library' ) }
										</Button>
									) }
								/>
							</Placeholder>
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
