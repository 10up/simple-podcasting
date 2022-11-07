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
	FlexItem
} from "@wordpress/components";
import { useState } from "@wordpress/element";

const CreatePodcastShowModal = ( { isModalOpen, openModal, closeModal } ) => {
	const FieldRow = ( props ) => {
		const style = {
			marginBottom: '26px',
		};

		return (
			<div className="podcasting__modal-field-row" style={ style }>
				{ props.children }
			</div>
		)
	};

	const modalStyle = {
		maxWidth: '645px',
		width: '100%'
	};

	if ( ! isModalOpen ) {
		return false;
	}

	const categoriesOptions = Object.keys( podcastingShowPluginVars.categories ).map( key => ( { label: key, label: podcastingShowPluginVars.categories[key] } ) );

	return (
		<Modal
			title={ __( 'Add New Podcast Show', 'simple-podcasting' ) }
			style={ modalStyle }
			onRequestClose={ closeModal }
		>
			<FieldRow>
				<TextControl
					label={ __( 'Show name*', 'simple-podcasting' ) }
					help={ __( 'This is the name that listeners will see when searching or subscribing.', 'simple-podcasting' ) }
					required
				/>
			</FieldRow>

			<FieldRow>
				<SelectControl
					label={ __( 'Show Category*', 'simple-podcasting' ) }
					help={ __( 'Select the category listeners will use to discover your show when browsing  podcatchers. You can also add subcategories later.', 'simple-podcasting' ) }
					required
					options={ categoriesOptions }
				/>
			</FieldRow>

			<FieldRow>
				<TextareaControl
					label={ __( 'Show Description', 'simple-podcasting' ) }
					help={ __( 'Briefly describe to your listeners what your show is about. (No HTML please.)', 'simple-podcasting' ) }
					rows={ 6 }
				/>
			</FieldRow>

			<FieldRow>
				<BaseControl label={ __( 'Show Cover Image', 'simple-podcasting' ) } />
				<Button
					variant="secondary"
					text={ __( 'Select Image', 'simple-podcasting' ) }
				/>
				<BaseControl help={ __( 'Square images are required to properly display within podcatcher apps.Minimum size: 1400 px x 1400 px. Maximum size: 2048 px x 2048 px.', 'simple-podcasting' ) } />
			</FieldRow>

			<Flex justify="normal" gap={ 9 }>
				<FlexItem>
					<Button
						variant="primary"
						text={ __( 'Create Show', 'simple-podcasting' ) }
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
