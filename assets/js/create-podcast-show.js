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
const DEFAULT_QUERY = {
	per_page: -1,
	orderby: 'name',
	order: 'asc',
	_fields: 'id,name,parent',
	context: 'view',
};

const CreatePodcastShowModal = ( { isModalOpen, closeModal } ) => {
	const [ showName, setShowName ] = useState( '' );
	const [ artistName, setArtistName ] = useState( '' );
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
						podcasting_image_url: coverUrl,
						podcasting_talent_name: artistName,
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
				const selectImageBtn = event.target.closest( '.podcasting__select-image-btn' );

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
								className="podcasting__modal-name-field"
								label={ __( 'Podcast name*', 'simple-podcasting' ) }
								help={ __( 'This is the name that listeners will see when searching or subscribing.', 'simple-podcasting' ) }
								value={ showName }
								onChange={ ( val ) => setShowName( val ) }
								required
							/>
						</div>

						<div className="podcasting__modal-field-row" style={ fieldStyle }>
							<TextControl
								className="podcasting__modal-artist-field"
								label={ __( 'Artist name*', 'simple-podcasting' ) }
								help={ __( 'Who’s the artist or author of your podcast show that listeners will see?', 'simple-podcasting' ) }
								value={ artistName }
								onChange={ ( val ) => setArtistName( val ) }
								required
							/>
						</div>

						<div className="podcasting__modal-field-row" style={ fieldStyle }>
							<SelectControl
								className="podcasting__modal-category-field"
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
								className="podcasting__modal-summary-field"
								label={ __( 'Summary*', 'simple-podcasting' ) }
								help={ __( 'Briefly describe to your listeners what your show is about. (No HTML please.)', 'simple-podcasting' ) }
								rows={ 6 }
								value={ summary }
								onChange={ ( val ) => setSummary( val ) }
								required
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
											<BaseControl label={ __( 'Cover Image*', 'simple-podcasting' ) } />
											<Flex justify="normal">
												<FlexItem>
													<Button
														className="podcasting__select-image-btn"
														variant="secondary"
														text={ coverId ? __( 'Replace Image', 'simple-podcasting' ) : __( 'Select Image', 'simple-podcasting' ) }
														onClick={ open }
													/>
												</FlexItem>
												{
													coverId ? (
														<FlexItem>
															<Button
																className="podcasting__remove-image-btn"
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
									className="podcasting__create-podcast-btn"
									variant="primary"
									text={ __( 'Create Podcast', 'simple-podcasting' ) }
									disabled={ ! showName || '' === showCategory || ! artistName || ! summary || ! coverId }
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
			allPodcasts: getEntityRecords( 'taxonomy', 'podcasting_podcasts', DEFAULT_QUERY ) || [],
			attachedPodcasts: getEntityRecords( 'taxonomy', 'podcasting_podcasts', { post: getCurrentPostId() } ) || [],
			currentPostId: getCurrentPostId(),
		}
	} );

	// Remove the default 'Podcast' taxonomy panel.
	useEffect( () => {
		dispatch( editPostStore ).removeEditorPanel( 'taxonomy-panel-podcasting_podcasts' );
	}, [] );

	const [ isModalOpen, setIsModalOpen ] = useState( false );
	const openModal = () => setIsModalOpen( true );
	const closeModal = () => setIsModalOpen( false );
	const initAttachedPodcastIds = attachedPodcasts.map( ( item ) => item.id );
	const [attachedPodcastIds, setAttachedPodcastIds] = useState([]);

	/*
	 * This is a workaround for WP 5.7 to prevent infinite loop
	 * when setting state.
	 *
	 * @todo remove this when the min supported WP version is bumped
	 * to 6.1
	 */
	const [isAttached, setisAttached] = useState( true );


	/**
	 * Attaches the podcast term to the current post if selected.
	 *
	 * @param {Boolean} isChecked If the podcast term checkbox is checked.
	 * @param {Integer} podcastId The podcast term ID.
	 */
	function attachPodcastToPost( isChecked, podcastId ) {
		let updatedAttachedPodcastIds = [ ...initAttachedPodcastIds, ...attachedPodcastIds, podcastId ];

		if ( isChecked ) {
			updatedAttachedPodcastIds = [ ...initAttachedPodcastIds, ...attachedPodcastIds, podcastId ];
		} else {
			updatedAttachedPodcastIds = [...initAttachedPodcastIds,...attachedPodcastIds].filter( ( currentPodcastId ) => currentPodcastId !== podcastId );
		}

		dispatch( coreDataStore ).editEntityRecord(
			'postType',
			'post',
			currentPostId,
			{
				podcasting_podcasts: updatedAttachedPodcastIds,
			}
		)

		setAttachedPodcastIds( updatedAttachedPodcastIds );
		setisAttached( false );
	}

	return (
		<>
			<PluginDocumentSettingPanel
				title={ __( 'Podcasts', 'simple-podcasting' ) }
				className='podcasting__podcast-list'
			>
				{
					allPodcasts.map( ( item, index ) => {
						return (
							<CheckboxControl
								className="podcasting__podcast-list-item"
								key={ index }
								label={ item.name }
								onChange={ ( isChecked ) => attachPodcastToPost( isChecked, item.id ) }
								checked={ isAttached ? initAttachedPodcastIds.includes( item.id ) : attachedPodcastIds.includes( item.id ) }
							/>
						)
					} )
				}
				<Button
					variant="link"
					text={ __( 'Add New Podcast', 'simple-podcasting' ) }
					onClick={ openModal }
					style={ { marginTop: '12px' } }
					className="podcasting__add-new-podcast"
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
