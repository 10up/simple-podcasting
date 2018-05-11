// ( function( wp ) {
// 	/**
// 	 * Registers a new block provided a unique name and an object defining its behavior.
// 	 * @see https://github.com/WordPress/gutenberg/tree/master/blocks#api
// 	 */
// 	var registerBlockType = wp.blocks.registerBlockType;
// 	/**
// 	 * Returns a new element of given type. Element is an abstraction layer atop React.
// 	 * @see https://github.com/WordPress/gutenberg/tree/master/element#element
// 	 */
// 	var el = wp.element.createElement;
// 	/**
// 	 * Retrieves the translation of text.
// 	 * @see https://github.com/WordPress/gutenberg/tree/master/i18n#api
// 	 */
// 	var __ = wp.i18n.__;

// 	var MediaUploadButton = wp.blocks.MediaUploadButton;

// 	/**
// 	 * Every block starts by registering a new block type definition.
// 	 * @see https://wordpress.org/gutenberg/handbook/block-api/
// 	 */
// 	registerBlockType( 'podcasting/podcast', {
// 		/**
// 		 * This is the display title for your block, which can be translated with `i18n` functions.
// 		 * The block inserter will show this name.
// 		 */
// 		title: __( 'Podcast' ),

// 		description: __( 'Insert a podcast episode into a post and add to a podcast feed.' ),

// 		/**
// 		 * Blocks are grouped into categories to help users browse and discover them.
// 		 * The categories provided by core are `common`, `embed`, `formatting`, `layout` and `widgets`.
// 		 */
// 		category: 'common',

// 		icon: 'microphone',

// 		/**
// 		 * Optional block extended support features.
// 		 */
// 		supports: {
// 			// Removes support for an HTML mode.
// 			html: false,
// 		},

// 		attributes: {
// 			src: {
// 				type: 'string',
// 				source: 'attribute',
// 				selector: 'audio',
// 				attribute: 'src',
// 			},
// 			align: {
// 				type: 'string',
// 			},
// 			caption: {
// 				type: 'array',
// 				source: 'children',
// 				selector: 'figcaption',
// 			},
// 			id: {
// 				type: 'number',
// 			},
// 		},

// 		/**
// 		 * The edit function describes the structure of your block in the context of the editor.
// 		 * This represents what the editor will render when the block is used.
// 		 * @see https://wordpress.org/gutenberg/handbook/block-edit-save/#edit
// 		 *
// 		 * @param {Object} [props] Properties passed from the editor.
// 		 * @return {Element}       Element to render.
// 		 */
// 		edit: function( props ) {
// 			return el(
// 				'div',
// 				{ className: props.className },
// 				el( blocks.MediaUploadButton, {
// 					buttonProps: {
// 						className: attributes.mediaID
// 							? 'image-button'
// 							: 'components-button button button-large',
// 					},
// 					onSelect: onSelectImage,
// 					type: 'image',
// 					value: attributes.mediaID,
// 				},
// 				attributes.mediaID
// 					? el( 'img', { src: attributes.mediaURL } )
// 					: 'Upload Image'
// 				),
// 			);
// 		},

// 		/**
// 		 * The save function defines the way in which the different attributes should be combined
// 		 * into the final markup, which is then serialized by Gutenberg into `post_content`.
// 		 * @see https://wordpress.org/gutenberg/handbook/block-edit-save/#save
// 		 *
// 		 * @return {Element}       Element to render.
// 		 */
// 		save: function() {
// 			return el(
// 				'p',
// 				{},
// 				__( 'Hello from the saved content!' )
// 			);
// 		}
// 	} );
// } )(
// 	window.wp
// );


/**
 * Internal block libraries
 */
const { __ } = wp.i18n;
const {
    registerBlockType,
    Editable,
    MediaUpload,
} = wp.blocks;
const {
    Button,
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
        attributes: {
            imgURL: {
                type: 'string',
                source: 'attribute',
                attribute: 'src',
                selector: 'img',
            },
            imgID: {
                type: 'number',
            },
            imgAlt: {
                type: 'string',
                source: 'attribute',
                attribute: 'alt',
                selector: 'img',
            }
        },
        edit: props => {
            const { attributes: { imgID, imgURL, imgAlt },
                className, setAttributes, isSelected } = props;
            const onSelectImage = img => {
                setAttributes( {
                    imgID: img.id,
                    imgURL: img.url,
                    imgAlt: img.alt,
                } );
            };
            const onRemoveImage = () => {
                setAttributes({
                    imgID: null,
                    imgURL: null,
                    imgAlt: null,
                });
            }
            return (
                <div className={ className }>

                    { ! imgID ? (

                        <MediaUpload
                            onSelect={ onSelectImage }
                            type="image"
                            value={ imgID }
                            render={ ( { open } ) => (
                                <Button
                                    className={ "button button-large" }
                                    onClick={ open }
                                >
                                    { __( 'Upload Image' ) }
                                </Button>
                            ) }
                        >
                        </MediaUpload>

                    ) : (

                        <p class="image-wrapper">
                            <img
                                src={ imgURL }
                                alt={ imgAlt }
                            />

                            { isSelected ? (

                                <Button
                                    className="remove-image"
                                    onClick={ onRemoveImage }
                                >
                                    { __( 'Remove' ) }
                                </Button>

                            ) : null }

                        </p>
                    )}

                </div>
            );
        },
        save: props => {
            const { imgURL, imgAlt } = props.attributes;
            return (
                <p>
                    <img
                        src={ imgURL }
                        alt={ imgAlt }
                    />
                </p>
            );
        },
    },
);
