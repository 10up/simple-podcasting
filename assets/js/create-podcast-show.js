import { registerPlugin } from "@wordpress/plugins";
import { PluginDocumentSettingPanel } from '@wordpress/edit-post';
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
	Notice
} from "@wordpress/components";
import { MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { useState } from "@wordpress/element";

const CreatePodcastShowModal = ( { isModalOpen, closeModal } ) => {
	const [ showName, setShowName ] = useState( '' );
	const [ showCategory, setShowCategory ] = useState( '' );
	const [ description, setDescription ] = useState( '' );
	const [ coverId, setCoverId ] = useState( 0 );
	const [ coverUrl, setCoverUrl ] = useState( '' );
	const [ ajaxInprogress, setAjaxInProgress ] = useState( false );
	const [ isError, setIsError ] = useState( false );
	const [ responseMessage, setResponseMessage ] = useState( '' )

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
		formData.append( 'podcast-description', description );
		formData.append( 'podcast-category', showCategory );
		formData.append( 'podcast-cover-image-id', coverId );

		const response = await fetch(
			ajaxurl,
			{
				method: 'POST',
				body: formData
			}
		);

		const responseJson = await response.json();

		setIsError( ! responseJson.success );
		setResponseMessage( responseJson.data );
		setAjaxInProgress( false );
	};

	if ( ! isModalOpen ) {
		return false;
	}

	const categoriesOptions = Object.keys( podcastingShowPluginVars.categories ).map( key => ( { value: key, label: podcastingShowPluginVars.categories[key] } ) );

	return (
		<Modal
			title={ __( 'Add New Podcast Show', 'simple-podcasting' ) }
			style={ modalStyle }
			onRequestClose={ ( event ) => {
				const selectImageBtn = event.target.closest( '.simple-podcasting__select-image-btn' );

				if ( selectImageBtn ) {
					return;
				}
				closeModal();
			} }
		>
			<div className="podcasting__modal-field-row" style={ fieldStyle }>
				<TextControl
					label={ __( 'Show name*', 'simple-podcasting' ) }
					help={ __( 'This is the name that listeners will see when searching or subscribing.', 'simple-podcasting' ) }
					value={ showName }
					onChange={ ( val ) => setShowName( val ) }
					required
				/>
			</div>

			<div className="podcasting__modal-field-row" style={ fieldStyle }>
				<SelectControl
					label={ __( 'Show Category*', 'simple-podcasting' ) }
					help={ __( 'Select the category listeners will use to discover your show when browsing  podcatchers. You can also add subcategories later.', 'simple-podcasting' ) }
					options={ categoriesOptions }
					value={ showCategory }
					onChange={ ( val ) => setShowCategory( val ) }
					required
				/>
			</div>

			<div className="podcasting__modal-field-row" style={ fieldStyle }>
				<TextareaControl
					label={ __( 'Show Description', 'simple-podcasting' ) }
					help={ __( 'Briefly describe to your listeners what your show is about. (No HTML please.)', 'simple-podcasting' ) }
					rows={ 6 }
					value={ description }
					onChange={ ( val ) => setDescription( val ) }
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
								<BaseControl label={ __( 'Show Cover Image', 'simple-podcasting' ) } />
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
				<FlexItem>
					{
						responseMessage ? (
							<Notice status={ isError ? 'error' : 'success' } isDismissible={ false }>
								{ responseMessage }
							</Notice>
						) : null
					}
				</FlexItem>
			</Flex>
		</Modal>
	);
};

const CreatePodcastShowPlugin = () => {
	const [ isModalOpen, setIsModalOpen ] = useState( false );
	const openModal = () => setIsModalOpen( true );
	const closeModal = () => setIsModalOpen( false );

	return (
		<>
			<PluginDocumentSettingPanel
				title={ __( 'Podcast Show', 'simple-podcasting' ) }
			>
				<Button
					variant="link"
					text={ __( 'Add New Podcast Show', 'simple-podcasting' ) }
					onClick={ openModal }
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
