/**
 * Internal block libraries
 */
const { __ } = wp.i18n;
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
        edit: props => {
            const { attributes: { id, src, align, caption, podcastTerm, captioned, explicit, podcastEpisode },
                className, setAttributes, isSelected } = props;
            const onSelectAttachment = attachment => {
                setAttributes( {
                    id: attachment.id,
                    src: attachment.url,
                    caption: attachment.title,
                } );
            };
            const onRemoveAttachment = () => {
                setAttributes({
                    id: null,
                    src: null,
                    caption: null,
                });
            }
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

                    { ! id ? (

                        <MediaUpload
                            onSelect={ onSelectAttachment }
                            type="audio"
                            value={ id }
                            render={ ( { open } ) => (
                                <Button
                                    className={ "button button-large" }
                                    onClick={ open }
                                >
                                    { __( 'Upload / Select Podcast' ) }
                                </Button>
                            ) }
                        >
                        </MediaUpload>

                    ) : (

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
                    )}

                </div>
            ];
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
