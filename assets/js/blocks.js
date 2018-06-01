/**
 * Internal block libraries
 */
const { __ } = wp.i18n;
const { Component } = wp.element;
const {
    registerBlockType,
    Editable,
    MediaUpload,
    RichText,
    InspectorControls,
    BlockControls,
} = wp.blocks;
const {
    Button,
    FormToggle,
    PanelBody,
    PanelRow,
    Placeholder,
} = wp.components;

/**
 * Register example block
 */
export default registerBlockType(
    'podcasting/podcast',
    {
        title: __( 'Podcast' ),
		description: __( 'Insert a podcast episode into a post and add to a podcast feed.' ),
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
            caption: {
                type: 'array',
                source: 'children',
                selector: 'figcaption',
            },
            podcastTerm: {
                type: 'string',
            },
            captioned: {
                type: 'boolean',
                source: 'meta',
                meta: 'podcast_captioned',
                default: false,
            },
            explicit: {
                type: 'boolean',
                source: 'meta',
                meta: 'podcast_explicit',
                default: false,
            },
            podcastEpisode: {
                type: 'string',
                source: 'meta',
                meta: 'podcast_episode'
            }
        },

        edit: class extends Component {
            constructor( { className } ) {
                super( ...arguments );
                // edit component has its own src in the state so it can be edited
                // without setting the actual value outside of the edit UI
                this.state = {
                    editing: ! this.props.attributes.src,
                    src: this.props.attributes.src,
                    className,
                };
            }
        
            render() {
                const { id, align, caption, podcastTerm, captioned, explicit, podcastEpisode } = this.props.attributes;
                const { setAttributes, isSelected } = this.props;
                const { editing, className, src } = this.state;
                const onSelectAttachment = ( attachment ) => {
                    setAttributes( {
                        id: attachment.id,
                        src: attachment.url,
                        caption: attachment.title,
                    } );

                    this.setState( { editing: false } );
                };
                const onRemoveAttachment = () => {
                    setAttributes({
                        id: null,
                        src: null,
                        caption: null,
                    });

                    this.setState( { editing: true } );
                }
                const onSelectUrl = ( event ) => {
                    event.preventDefault();
                    if ( src ) {
                        // set the block's src from the edit component's state, and switch off the editing UI
                        setAttributes( { src } );
                        this.setState( { editing: false } );
                    }
                    return false;
                };

                const toggleExplicit  = () => setAttributes( { explicit: ! explicit } );
                const toggleCaptioned = () => setAttributes( { captioned: ! captioned } );

                return [
                    isSelected && (
                        <InspectorControls>
                            <PanelBody
                              title={ __( 'Podcast Settings' ) }
                            >
                                <PanelRow>
                                    <label
                                        htmlFor="podcast-captioned-form-toggle"
                                    >
                                        { __( 'Closed Captioned' ) }
                                    </label>
                                    <FormToggle
                                        id="podcast-captioned-form-toggle"
                                        label={ __( 'Closed Captioned' ) }
                                        checked={ captioned }
                                        onChange={ toggleCaptioned }
                                    />
                                </PanelRow>
                                <PanelRow>
                                    <label
                                        htmlFor="podcast-explicit-form-toggle"
                                    >
                                        { __( 'Explicit Content' ) }
                                    </label>
                                    <FormToggle
                                        id="podcast-explicit-form-toggle"
                                        label={ __( 'Explicit Content' ) }
                                        checked={ explicit }
                                        onChange={ toggleExplicit }
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
                                label={ __( 'Podcast' ) }
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
