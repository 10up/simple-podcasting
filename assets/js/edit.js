const { __ } = wp.i18n;
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
	ToggleControl,
	PanelBody,
	PanelRow,
	SelectControl,
	TextControl,
	RadioControl,
} = wp.components;
const { Fragment } = wp.element;

const { apiFetch } = wp;
const ALLOWED_MEDIA_TYPES = ['audio'];
const { select } = wp.data;

import { Button } from '@wordpress/components';
import { useState, useEffect } from '@wordpress/element';
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

function Edit( props ) {
	const {
		className,
		setAttributes,
		isSelected,
		attributes,
		featuredImageUrl,
		setFeaturedImage,
		removeFeaturedImage,
		featuredImageId
	} = props;

	const {
		caption,
		explicit,
		displayDuration,
		displayShowTitle,
		displayEpisodeTitle,
		displayArt,
		displayExplicitBadge,
		displaySeasonNumber,
		displayEpisodeNumber,
		displayEpisodeType
	} = attributes;

	const duration = attributes.duration || '';
	const captioned = attributes.captioned || '';
	const seasonNumber = attributes.seasonNumber || '';
	const episodeNumber = attributes.episodeNumber || '';
	const episodeType = attributes.episodeType || '';

	const [ src, setSrc ] = useState( props.attributes.src );

	useEffect( () => () => wp.data.dispatch('core/editor').editPost({ podcasting_podcasts: [] }) );
	const postTitle = useSelect( ( select ) => select( 'core/editor' ).getEditedPostAttribute( 'title' ) );

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
		setSrc( attachment.url );
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
							displayDurationValue: duration,
							caption: '',
						});
					}
				})
				.catch((err) => {
					// eslint-disable-next-line no-console
					console.error(err);
				});

			setSrc( newSrc );
		}
	};

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

	const { getCurrentPost } = select('core/editor');
	const postDetails = getCurrentPost();

	const showId = postDetails ? postDetails.podcasting_podcasts[0] : null;

	const show = select('core').getEntityRecords('taxonomy', 'podcasting_podcasts', {
		per_page: 1,
		term_id: showId,
	});

	const showName = show ? show[0].name : null;
	const showImage = show ? show[0].meta.podcasting_image_url : null;

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
						<ToggleControl
							id="podcast-captioned-form-toggle"
							label={__(
								'Closed Captioned',
								'simple-podcasting'
							)}
							checked={captioned}
							onChange={() => setAttributes({ captioned: !captioned})}
						/>
					</PanelRow>
					<PanelRow>
						<ToggleControl
							label={__(
								'Display Listen Time',
								'simple-podcasting'
							)}
							checked={displayDuration}
							onChange={() => setAttributes({ displayDuration: !displayDuration})}
						/>
					</PanelRow>
					<PanelRow>
						<ToggleControl
							label={__(
								'Display Show Title',
								'simple-podcasting'
							)}
							checked={displayShowTitle}
							onChange={() => setAttributes({ displayShowTitle: !displayShowTitle})}
						/>
					</PanelRow>
					<PanelRow>
						<ToggleControl
							label={__(
								'Display Episode Title',
								'simple-podcasting'
							)}
							checked={displayEpisodeTitle}
							onChange={() => setAttributes({ displayEpisodeTitle: !displayEpisodeTitle})}
						/>
					</PanelRow>
					<PanelRow>
						<ToggleControl
							label={__(
								'Display Show Art',
								'simple-podcasting'
							)}
							checked={displayArt}
							onChange={() => setAttributes({ displayArt: !displayArt})}
						/>
					</PanelRow>
					<PanelRow>
						<ToggleControl
							label={__(
								'Display Explicit Badge',
								'simple-podcasting'
							)}
							checked={displayExplicitBadge}
							onChange={() => setAttributes({ displayExplicitBadge: !displayExplicitBadge})}
						/>
					</PanelRow>
					<PanelRow>
						<ToggleControl
							label={__(
								'Display Season Number',
								'simple-podcasting'
							)}
							checked={displaySeasonNumber}
							onChange={() => setAttributes({ displaySeasonNumber: !displaySeasonNumber})}
						/>
					</PanelRow>
					<PanelRow>
						<ToggleControl
							label={__(
								'Display Episode Number',
								'simple-podcasting'
							)}
							checked={displayEpisodeNumber}
							onChange={() => setAttributes({ displayEpisodeNumber: !displayEpisodeNumber})}
							help={
								! displayEpisodeTitle
								&& __( 'The "Display Episode Title" setting should be enabled for this to display the episode number.', 'simple-podcasting' )
							}
						/>
					</PanelRow>
					<PanelRow>
						<ToggleControl
							label={__(
								'Display Episode Type',
								'simple-podcasting'
							)}
							checked={displayEpisodeType}
							onChange={() => setAttributes({ displayEpisodeType: !displayEpisodeType})}
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
			<div className="wp-block-podcasting-podcast-outer">
				{src ? (
					<>
						<div className="wp-block-podcasting-podcast__container">
							{showImage && displayArt && (
								<div className="wp-block-podcasting-podcast__show-art">
									<div className="wp-block-podcasting-podcast__image">
										<img
											src={showImage}
											alt={showName}
										/>
									</div>
								</div>
							)}

							<div className="wp-block-podcasting-podcast__details">

								{displayEpisodeTitle && postTitle && (
									<h3 className="wp-block-podcasting-podcast__show-title">
										{displayEpisodeNumber && episodeNumber && (
											<span>
												{episodeNumber}.
											</span>
										)}
										{postTitle}
									</h3>
								)}

								<div className="wp-block-podcasting-podcast__show-details">
									{displayShowTitle && (
										<span className="wp-block-podcasting-podcast__title">
											{showName}
										</span>
									)}
									{displaySeasonNumber && seasonNumber && (
										<span className="wp-block-podcasting-podcast__season">
											{__(
												'Season: ',
												'simple-podcasting'
											)}
											{seasonNumber}
										</span>
									)}
									{displayEpisodeNumber && episodeNumber && (
										<span className="wp-block-podcasting-podcast__episode">
											{__('Episode: ', 'simple-podcasting')}
											{episodeNumber}
										</span>
									)}
								</div>

								<div className="wp-block-podcasting-podcast__show-details">
									{displayDuration && duration && (
										<span className="wp-block-podcasting-podcast__duration">
											{__('Listen Time: ', 'simple-podcasting')}
											{duration}
										</span>
									)}
									{displayEpisodeType && (episodeType !== 'none') && (
										<span className="wp-block-podcasting-podcast__episode-type">
											{__(
												'Episode type: ',
												'simple-podcasting'
											)}
											{episodeType}
										</span>
									)}
									{displayExplicitBadge && (
										<span className="wp-block-podcasting-podcast__explicit-badge">
											{__(
												'Explicit: ',
												'simple-podcasting'
											)}
											{explicit}
										</span>
									)}
								</div>
							</div>
						</div>

						<figure key="audio" className={className}>
							{((caption && caption.length) || !!isSelected) && (
								<RichText
									tagName="figcaption"
									placeholder={__(
										'Write caption…',
										'simple-podcasting'
									)}
									className="wp-block-podcasting-podcast__caption"
									value={caption}
									onChange={(value) =>
										setAttributes({ caption: value })
									}
									isSelected={isSelected}
								/>
							)}
							<audio controls="controls" src={src} />
						</figure>
					</>
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
						value={attributes}
					/>
				)}
			</div>
		</Fragment>
	);
}

function PodcastBlockWithHooks(props) {
    const featuredImageProp = useFeaturedImage();

    return <Edit {...props} {...featuredImageProp} />;
}

export default PodcastBlockWithHooks;
