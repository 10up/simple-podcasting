const { __ } = wp.i18n;
const { Component } = wp.element;
const {
	BlockControls,
	InspectorControls,
	MediaPlaceholder,
	RichText,
} = wp.blockEditor;
const {
	FormToggle,
	IconButton,
	PanelBody,
	PanelRow,
	SelectControl,
	TextControl,
	Toolbar,
	Button,
} = wp.components;
const {
	Fragment
} = wp.element;

const { apiFetch } = wp;

class Edit extends Component {
	constructor( { className } ) {
		super( ...arguments );
		// edit component has its own src in the state so it can be edited
		// without setting the actual value outside of the edit UI
		this.state = {
			editing: ! this.props.attributes.src,
			src: this.props.attributes.src ? this.props.attributes.src : null,
			className,
		};
	}

	/**
	 * When the component is removed, we'll set the the post meta to null so it is deleted on save.
	 */
	componentWillUnmount() {
		const { setAttributes } = this.props;
		setAttributes( {
			id: null,
			src: null,
			url: null,
			mime: null,
			filesize: null,
			duration: null,
			caption: null,
		} );

		// Let's also remove any assigned Podcast taxonomies.
		wp.data.dispatch( 'core/editor' ).editPost( { [ 'podcasting_podcasts' ]:[] } );
	}


	render() {

		const { setAttributes, isSelected, attributes } = this.props;
		const { caption, explicit } = attributes;
		const duration = attributes.duration || '';
		const captioned = attributes.captioned || '';
		const { editing, className, src } = this.state;

		const switchMode = () => {
			this.setState( { editing: ! editing } );
		};

		const onSelectAttachment = ( attachment ) => {
			// Upload and Media Library return different attachment objects.
			// Therefore, we need to check the existence of some entries.
			let mime, filesize, duration;

			if ( attachment.mime ) {
				mime = attachment.mime;
			} else if ( attachment.mime_type ) {
				mime = attachment.mime_type;
			}

			if ( attachment.filesizeInBytes ) {
				filesize = attachment.filesizeInBytes;
			} else if ( attachment.media_details && attachment.media_details.filesize ) {
				filesize = attachment.media_details.filesize;
			}

			if ( attachment.fileLength ) {
				duration = attachment.fileLength;
			} else if ( attachment.media_details && attachment.media_details.length_formatted ) {
				duration = attachment.media_details.length_formatted;
			}

			setAttributes( {
				id: attachment.id,
				src: attachment.url,
				url: attachment.url,
				mime,
				filesize,
				duration,
				caption: attachment.title,
			} );
			this.setState( { editing: false, src: attachment.url } );
		};

		const onSelectURL = ( newSrc ) => {
			if ( newSrc !== src ) {
				apiFetch({
					path: `simple-podcasting/v1/external-url/?url=${newSrc}`,
				}).then( res => {
					if ( res.success ) {
						const { mime, filesize, duration } = res.data;
						setAttributes({
							src: newSrc,
							url: newSrc,
							id: null,
							mime: mime,
							filesize: filesize,
							duration: duration,
							caption: '',
						});
					}
				}).catch( err => {
					// eslint-disable-next-line no-console
					console.error( err );
				});

				this.setState( { src: newSrc } );
			}
			this.setState( { editing: false } );
		};
		const toggleCaptioned = () => setAttributes( { captioned: ! captioned } );

		const controls = (
			<BlockControls key="controls">
				<Toolbar>
					{ ! editing ? (
						<IconButton
							className="components-icon-button components-toolbar__control"
							label={ __( 'Edit Podcast', 'simple-podcasting' ) }
							onClick={ switchMode }
							icon="edit"
						/>
					) : (
						<IconButton
							className="components-icon-button components-toolbar__control"
							label={ __( 'Cancel Editing Podcast', 'simple-podcasting' ) }
							onClick={ switchMode }
							icon="no-alt"
						/>
					)}
				</Toolbar>
			</BlockControls>
		);

		const containerStyles = {
			border: '1px solid #ccc',
			borderRadius: '0.5rem',
			padding: '1rem',
			margin: '0.5rem 0',
			fontSize: '13px',
			fontFamily: 'sans-serif',
		};

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
					<Fragment>
						{ src ? (
							<div style={containerStyles}>
								<Button
									isSecondary
									onClick={ switchMode }
								>
									{ __( 'Cancel', 'simple-podcasting' ) }
								</Button>
								&nbsp;
								<span>{ __( 'This will retain your current podcast audio and settings.', 'simple-podcasting' ) }</span>
							</div>
						) : null }
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
							allowedTypes={ [ 'audio' ] }
							value={ this.props.attributes }
						/>
					</Fragment>

				)}

			</div>
		];
	}
}

export default Edit;
