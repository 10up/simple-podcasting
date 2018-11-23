const { __ } = wp.i18n;
const { Component } = wp.element;
const {
	BlockControls,
	InspectorControls,
	MediaPlaceholder,
	RichText,
} = wp.editor;
const {
	FormToggle,
	IconButton,
	PanelBody,
	PanelRow,
	SelectControl,
	TextControl,
	Toolbar,
} = wp.components;

class Edit extends Component {
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
		const { id, align, caption, podcastTerm, captioned, explicit, url, mime, duration } = this.props.attributes;
		const { setAttributes, isSelected } = this.props;
		const { editing, className, src } = this.state;

		const switchToEditing = () => {
			this.setState( { editing: true } );
		};

		const onSelectAttachment = ( attachment ) => {
			setAttributes( {
				id: attachment.id,
				src: attachment.url,
				url: attachment.url,
				mime: attachment.mime,
				filesize: attachment.filesizeInBytes,
				duration: attachment.fileLength,
				caption: attachment.title,
			} );
			this.setState( { editing: false, src: attachment.url } );
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
				this.setState( { src: newSrc } );
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
}

export default Edit;
