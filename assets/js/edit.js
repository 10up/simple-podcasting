const { __ } = wp.i18n;
const { Component } = wp.element;
const {
	BlockControls,
	InspectorControls,
	MediaPlaceholder,
	MediaReplaceFlow,
	MediaUpload,
	MediaUploadCheck,
	RichText,
} = wp.blockEditor;
const {
	FormToggle,
	PanelBody,
	PanelRow,
	SelectControl,
	TextControl,
	RadioControl,
} = wp.components;
const { Fragment } = wp.element;

const { apiFetch } = wp;
const ALLOWED_MEDIA_TYPES = ['audio'];

import { Button } from '@wordpress/components';
import { dispatch, useSelect, useDispatch } from '@wordpress/data';
import { createBlock } from '@wordpress/blocks';

/*
 * Import hierarchical term selector.
 *
 * @TODO Import from `@wordpress/editor` once minimum WP version is 6.0.
 */
import HierarchicalTermSelector from './term-selector/hierarchical-term-selector';

function useFeaturedImage() {
    const featuredImageId = useSelect((select) => select('core/editor').getEditedPostAttribute('featured_media'), []);
    const { editPost } = useDispatch('core/editor');

    const featuredImageUrl = useSelect((select) => {
        const { getMedia } = select('core');
        const image = getMedia(featuredImageId);
        return image?.source_url;
    }, [featuredImageId]);

    const setFeaturedImage = (imageId) => {
        editPost({ featured_media: imageId });
    };

	const removeFeaturedImage = () => {
		editPost({ featured_media: 0 });
	};

    return { featuredImageUrl, setFeaturedImage, removeFeaturedImage, featuredImageId };
}

class Edit extends Component {
	constructor({ className }) {
		super(...arguments);
		// edit component has its own src in the state so it can be edited
		// without setting the actual value outside of the edit UI
		this.state = {
			src: this.props.attributes.src ? this.props.attributes.src : null,
			className,
		};
	}

	/**
	 * When the component is removed, we'll remove any assigned Podcast taxonomies.
	 */
	componentWillUnmount() {
		wp.data.dispatch('core/editor').editPost({ podcasting_podcasts: [] });
	}

	render() {
		const {
			setAttributes,
			isSelected,
			attributes,
			featuredImageUrl,
			setFeaturedImage,
			removeFeaturedImage,
			featuredImageId
		} = this.props;
		const { caption, explicit } = attributes;
		const duration = attributes.duration || '';
		const captioned = attributes.captioned || '';
		const seasonNumber = attributes.seasonNumber || '';
		const episodeNumber = attributes.episodeNumber || '';
		const episodeType = attributes.episodeType || '';
		const { className, src } = this.state;

		const onSelectAttachment = (attachment) => {
			// Upload and Media Library return different attachment objects.
			// Therefore, we need to check the existence of some entries.
			let mime, filesize, duration;

			if (attachment.mime) {
				mime = attachment.mime;
			} else if (attachment.mime_type) {
				mime = attachment.mime_type;
			}

			if (attachment.filesizeInBytes) {
				filesize = attachment.filesizeInBytes;
			} else if (
				attachment.media_details &&
				attachment.media_details.filesize
			) {
				filesize = attachment.media_details.filesize;
			}

			if (attachment.fileLength) {
				duration = attachment.fileLength;
			} else if (
				attachment.media_details &&
				attachment.media_details.length_formatted
			) {
				duration = attachment.media_details.length_formatted;
			}

			setAttributes({
				id: attachment.id,
				src: attachment.url,
				url: attachment.url,
				mime,
				filesize,
				duration,
				caption: attachment.title,
				enclosure: attachment.url + '\n' + filesize + '\n' + mime,
			});
			this.setState({ src: attachment.url });
		};

		const onSelectURL = (newSrc) => {
			if (newSrc !== src) {
				apiFetch({
					path: `simple-podcasting/v1/external-url/?url=${newSrc}`,
				})
					.then((res) => {
						if (res.success) {
							const { mime, filesize, duration } = res.data;
							setAttributes({
								src: newSrc,
								url: newSrc,
								id: null,
								mime,
								filesize,
								duration,
								caption: '',
							});
						}
					})
					.catch((err) => {
						// eslint-disable-next-line no-console
						console.error(err);
					});

				this.setState({ src: newSrc });
			}
		};
		const toggleCaptioned = () => setAttributes({ captioned: !captioned });

		const controls = (
			<BlockControls key="controls">
				{src ? (
					<MediaReplaceFlow
						mediaURL={attributes.src}
						allowedTypes={ALLOWED_MEDIA_TYPES}
						accept="audio/*"
						onSelect={onSelectAttachment}
						onSelectURL={onSelectURL}
					/>
				) : null}
			</BlockControls>
		);

		const onUpdateImage = (image) => {
            setFeaturedImage(image.id);
        };

		return (
			<Fragment>
				{controls}
				<InspectorControls>
					<PanelBody
						title={__('Podcast Settings', 'simple-podcasting')}
					>
						<PanelRow>
							<div id="hierar-podcasting_podcasts">
								<HierarchicalTermSelector slug="podcasting_podcasts" />
							</div>
						</PanelRow>
						<PanelRow>
							<label htmlFor="podcast-captioned-form-toggle">
								{__('Closed Captioned', 'simple-podcasting')}
							</label>
							<FormToggle
								id="podcast-captioned-form-toggle"
								label={__(
									'Closed Captioned',
									'simple-podcasting'
								)}
								checked={captioned}
								onChange={toggleCaptioned}
							/>
						</PanelRow>
						<PanelRow>
							<SelectControl
								label={__(
									'Explicit Content',
									'simple-podcasting'
								)}
								value={explicit}
								options={[
									{
										value: 'no',
										label: __('No', 'simple-podcasting'),
									},
									{
										value: 'yes',
										label: __('Yes', 'simple-podcasting'),
									},
									{
										value: 'clean',
										label: __('Clean', 'simple-podcasting'),
									},
								]}
								onChange={(explicit) =>
									setAttributes({ explicit })
								}
							/>
						</PanelRow>
						<PanelRow>
							<TextControl
								label={__(
									'Length (MM:SS)',
									'simple-podcasting'
								)}
								value={duration}
								onChange={(duration) =>
									setAttributes({ duration })
								}
							/>
						</PanelRow>
						<PanelRow>
							<TextControl
								label={__('Season Number', 'simple-podcasting')}
								value={seasonNumber}
								onChange={(seasonNumber) =>
									setAttributes({ seasonNumber })
								}
							/>
						</PanelRow>
						<PanelRow>
							<TextControl
								label={__(
									'Episode Number',
									'simple-podcasting'
								)}
								value={episodeNumber}
								onChange={(episodeNumber) =>
									setAttributes({ episodeNumber })
								}
							/>
						</PanelRow>
						<PanelRow>
							<RadioControl
								label={__('Episode Type', 'simple-podcasting')}
								selected={episodeType}
								options={[
									{
										label: __('None', 'simple-podcasting'),
										value: 'none',
									},
									{
										label: __('Full', 'simple-podcasting'),
										value: 'full',
									},
									{
										label: __(
											'Trailer',
											'simple-podcasting'
										),
										value: 'trailer',
									},
									{
										label: __('Bonus', 'simple-podcasting'),
										value: 'bonus',
									},
								]}
								onChange={(episodeType) =>
									setAttributes({ episodeType })
								}
							/>
						</PanelRow>
						<PanelRow>
							<Button
								variant="secondary"
								onClick={() =>
									dispatch('core/block-editor').insertBlocks(
										createBlock(
											'podcasting/podcast-transcript'
										)
									)
								}
							>
								{__('Add Transcript', 'simple-podcasting')}
							</Button>
						</PanelRow>
						<h3 style={{marginTop: '20px'}}>{__('Cover Image', 'simple-podcasting')}</h3>
						<p>{__('The featured image of the current post is used as the episode cover art. Please select a featured image to set it.', 'simple-podcasting')}</p>
						<PanelRow className="cover-art-container">
							{featuredImageUrl && (
								<img src={featuredImageUrl} alt="Cover Image" />
							)}

							<MediaUploadCheck>
								<MediaUpload
									onSelect={onUpdateImage}
									allowedTypes={['image']}
									render={({ open }) => (
										<Button isSecondary onClick={open}>
											{featuredImageUrl ?
											__('Replace Cover Art', 'simple-podcasting')
											:
											__('Select Cover Art', 'simple-podcasting')
											}
										</Button>
									)}
									value={featuredImageId}
								/>
							</MediaUploadCheck>
							{featuredImageUrl && (
								<Button isLink isDestructive onClick={removeFeaturedImage}>{__('Delete Cover Art', 'simple-podcasting')}</Button>
							)}
						</PanelRow>
					</PanelBody>
				</InspectorControls>
				<div className={className}>
					{src ? (
						<figure key="audio" className={className}>
							<audio controls="controls" src={src} />
							{((caption && caption.length) || !!isSelected) && (
								<RichText
									tagName="figcaption"
									placeholder={__(
										'Write caption…',
										'simple-podcasting'
									)}
									value={caption}
									onChange={(value) =>
										setAttributes({ caption: value })
									}
									isSelected={isSelected}
								/>
							)}
						</figure>
					) : (
						<MediaPlaceholder
							icon="microphone"
							labels={{
								title: __('Podcast', 'simple-podcasting'),
								name: __(
									'a podcast episode',
									'simple-podcasting'
								),
							}}
							className={className}
							onSelect={onSelectAttachment}
							onSelectURL={onSelectURL}
							accept="audio/*"
							allowedTypes={ALLOWED_MEDIA_TYPES}
							value={this.props.attributes}
						/>
					)}
				</div>
			</Fragment>
		);
	}
}

function PodcastBlockWithHooks(props) {
    const featuredImageProp = useFeaturedImage();

    return <Edit {...props} {...featuredImageProp} />;
}

export default PodcastBlockWithHooks;
