import { registerPlugin } from "@wordpress/plugins";
import { PluginDocumentSettingPanel, store as editPostStore } from '@wordpress/edit-post';
import { __ } from "@wordpress/i18n";
import {
	Button,
	Modal,
	TextControl,
	SelectControl,
	TextareaControl,
	BaseControl,
	Flex,
	FlexItem,
	CheckboxControl,
} from "@wordpress/components";
import { MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { useState, useEffect } from "@wordpress/element";
import { useSelect, dispatch } from "@wordpress/data";

// Due to unsupported versions of React, we're importing stores from the
// `wp` namespace instead of @wordpress NPM packages for the following.
const { store: editorStore } = wp.editor;
const { store: coreDataStore } = wp.coreData;

const CreatePodcastShowModal = ( { isModalOpen, closeModal } ) => {
	const [ showName, setShowName ] = useState( '' );
	const [ showCategory, setShowCategory ] = useState( '' );
	const [ summary, setSummary ] = useState( '' );
	const [ coverId, setCoverId ] = useState( 0 );
	const [ coverUrl, setCoverUrl ] = useState( '' );
	const [ ajaxInprogress, setAjaxInProgress ] = useState( false );
	const [ isPodcastCreated, setIsPodcastCreated ] = useState( false );

	const modalStyle = {
		maxWidth: '645px',
		width: '100%'
	};

	const fieldStyle = {
		marginBottom: '26px',
	};

	const createShow = async () => {
		setAjaxInProgress( true );

		const formData = new FormData();

		formData.append( 'action', 'simple_podcasting_create_podcast' );
		formData.append( 'simple-podcasting-create-show-nonce-field', podcastingShowPluginVars.nonce );
		formData.append( 'podcast-name', showName );
		formData.append( 'podcast-description', summary );
		formData.append( 'podcast-category', showCategory );
		formData.append( 'podcast-cover-image-id', coverId );

		try {
			const podcast = await wp.data.dispatch( coreDataStore ).saveEntityRecord(
				'taxonomy',
				'podcasting_podcasts',
				{
					name: showName,
					meta: {
						podcasting_summary: summary,
						podcasting_category_1: showCategory,
						podcasting_image: coverId,
						podcasting_image_url: coverUrl
					}
				}
			);

			if ( podcast ) {
				setIsPodcastCreated( true );
			}
		} catch ( error ) {
			setAjaxInProgress( false );
		}


		setAjaxInProgress( false );
	};

	if ( ! isModalOpen ) {
		return false;
	}

	const categoriesOptions = Object.keys( podcastingShowPluginVars.categories ).map( key => ( { value: key, label: podcastingShowPluginVars.categories[key] } ) );

	return (
		<Modal
			title={ isPodcastCreated ? __( 'Podcast created!', 'simple-podcasting' ) : __( 'Add New Podcast', 'simple-podcasting' ) }
			style={ modalStyle }
			onRequestClose={ ( event ) => {
				const selectImageBtn = event.target.closest( '.simple-podcasting__select-image-btn' );

				if ( selectImageBtn ) {
					return;
				}
				closeModal();
			} }
		>
			{
				isPodcastCreated ? (
					<>
						<Button
							variant="link"
							text={ __( 'Add another Podcast', 'simple-podcasting' ) }
							onClick={ () => {
								setIsPodcastCreated( false );
								setShowName( '' );
								setShowCategory( '' );
								setSummary( '' );
								setCoverId( 0 );
								setCoverUrl( '' );
							} }
						/>
					</>
				) : (
					<>
						<div className="podcasting__modal-field-row" style={ fieldStyle }>
							<TextControl
								label={ __( 'Podcast name*', 'simple-podcasting' ) }
								help={ __( 'This is the name that listeners will see when searching or subscribing.', 'simple-podcasting' ) }
								value={ showName }
								onChange={ ( val ) => setShowName( val ) }
								required
							/>
						</div>

						<div className="podcasting__modal-field-row" style={ fieldStyle }>
							<SelectControl
								label={ __( 'Category*', 'simple-podcasting' ) }
								help={ __( 'Select the category listeners will use to discover your show when browsing  podcatchers. You can also add subcategories later.', 'simple-podcasting' ) }
								options={ categoriesOptions }
								value={ showCategory }
								onChange={ ( val ) => setShowCategory( val ) }
								required
							/>
						</div>

						<div className="podcasting__modal-field-row" style={ fieldStyle }>
							<TextareaControl
								label={ __( 'Summary', 'simple-podcasting' ) }
								help={ __( 'Briefly describe to your listeners what your show is about. (No HTML please.)', 'simple-podcasting' ) }
								rows={ 6 }
								value={ summary }
								onChange={ ( val ) => setSummary( val ) }
							/>
						</div>

						<div className="podcasting__modal-field-row" style={ fieldStyle }>
							<MediaUploadCheck>
								<MediaUpload
									onSelect={ ( media ) => {
										setCoverId( media.id );
										setCoverUrl( media.url );
									} }
									allowedTypes={ [ 'image' ] }
									value={ coverId }
									render={ ( { open } ) => (
										<>
											<BaseControl label={ __( 'Cover Image', 'simple-podcasting' ) } />
											<Flex justify="normal">
												<FlexItem>
													<Button
														variant="secondary"
														text={ coverId ? __( 'Replace Image', 'simple-podcasting' ) : __( 'Select Image', 'simple-podcasting' ) }
														onClick={ open }
														className="simple-podcasting__select-image-btn"
													/>
												</FlexItem>
												{
													coverId ? (
														<FlexItem>
															<Button
																variant="secondary"
																text={ __( 'Remove', 'simple-podcasting' ) }
																isDestructive
																onClick={ () => {
																	setCoverId( 0 );
																	setCoverUrl( '' );
																} }
															/>
														</FlexItem>
													) : null
												}
											</Flex>
											{
												coverId ? (
													<div className="podcasting-cover-preview" style={ {
														maxWidth: '256px',
														marginTop: '1rem',
													} }>
														<img src={ coverUrl } style={ { width: '100%' } } />
													</div>
												) : null
											}
											<BaseControl help={ __( 'Square images are required to properly display within podcatcher apps.Minimum size: 1400 px x 1400 px. Maximum size: 2048 px x 2048 px.', 'simple-podcasting' ) } />
										</>
									) }
								/>
							</MediaUploadCheck>
						</div>

						<Flex justify="normal" gap={ 9 }>
							<FlexItem>
								<Button
									variant="primary"
									text={ __( 'Create Show', 'simple-podcasting' ) }
									disabled={ ! showName || '' === showCategory }
									onClick={ createShow }
									isBusy={ ajaxInprogress }
								/>
							</FlexItem>
							<FlexItem>
								<Button
									variant="link"
									text={ __( 'Cancel', 'simple-podcasting' ) }
									onClick={ closeModal }
								/>
							</FlexItem>
						</Flex>
					</>
				)
			}
		</Modal>
	);
};

const CreatePodcastShowPlugin = () => {
	const { allPodcasts, attachedPodcasts, currentPostId } = useSelect( ( select ) => {
		const { getEntityRecords } = select( coreDataStore );
		const { getCurrentPostId } = select( editorStore );

		return {
			allPodcasts: getEntityRecords( 'taxonomy', 'podcasting_podcasts' ) || [],
			attachedPodcasts: getEntityRecords( 'taxonomy', 'podcasting_podcasts', { post: getCurrentPostId() } ) || [],
			currentPostId: getCurrentPostId(),
		}
	} );

	// Remove the default 'Podcast' taxonomy panel.
	useEffect( () => {
		dispatch( editPostStore ).removeEditorPanel( 'taxonomy-panel-podcasting_podcasts' );
	}, [] );

	// Sets the podcasts terms already attached to the current post.
	useEffect( () => {
		setAttachedPodcasts( attachedPodcasts.map( ( item ) => item.id ) );
	}, [ attachedPodcasts ] );

	const [ isModalOpen, setIsModalOpen ] = useState( false );
	const openModal = () => setIsModalOpen( true );
	const closeModal = () => setIsModalOpen( false );
	const [ attachedPodcastIds, setAttachedPodcasts ] = useState( attachedPodcasts.map( ( item ) => item.id ) || [] );

	/**
	 * Attaches the podcast term to the current post if selected.
	 *
	 * @param {Boolean} isChecked If the podcast term checkbox is checked.
	 * @param {Integer} podcastId The podcast term ID.
	 */
	function attachPodcastToPost( isChecked, podcastId ) {
		let updatedAttachedPodcastIds = [ ...attachedPodcastIds, podcastId ];

		if ( isChecked ) {
			updatedAttachedPodcastIds = [ ...attachedPodcastIds, podcastId ];
		} else {
			updatedAttachedPodcastIds = attachedPodcastIds.filter( ( currentPodcastId ) => currentPodcastId !== podcastId );
		}

		dispatch( coreDataStore ).editEntityRecord(
			'postType',
			'post',
			currentPostId,
			{
				podcasting_podcasts: updatedAttachedPodcastIds,
			}
		)

		setAttachedPodcasts( updatedAttachedPodcastIds );
	}

	return (
		<>
			<PluginDocumentSettingPanel
				title={ __( 'Podcasts', 'simple-podcasting' ) }
			>
				{
					allPodcasts.map( ( item, index ) => {
						return (
							<CheckboxControl
								key={ index }
								label={ item.name }
								onChange={ ( isChecked ) => attachPodcastToPost( isChecked, item.id ) }
								checked={ attachedPodcastIds.includes( item.id ) }
							/>
						)
					} )
				}
				<Button
					variant="link"
					text={ __( 'Add New Podcast', 'simple-podcasting' ) }
					onClick={ openModal }
					style={ { marginTop: '12px' } }
				/>
			</PluginDocumentSettingPanel>
			<CreatePodcastShowModal
				isModalOpen={ isModalOpen }
				openModal={ openModal }
				closeModal={ closeModal }
			/>
		</>
	)
};

registerPlugin( 'podcasting-create-podcast-show', {
	render: CreatePodcastShowPlugin
} );
