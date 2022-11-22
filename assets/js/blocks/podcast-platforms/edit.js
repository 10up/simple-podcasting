import { useBlockProps } from '@wordpress/block-editor';
import {
	Button,
	SearchControl,
	Dropdown,
	Spinner,
	__experimentalItemGroup as ItemGroup,
	__experimentalItem as Item
} from '@wordpress/components';
import { useState, useEffect } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import { __ } from '@wordpress/i18n';

function Edit( props ) {
	const {
		setAttributes,
		isSelected,
		attributes: {
			showId
		},
	} = props;

	const [ searchText, setSearchText ] = useState( '' );
	const [ searchResults, setSearchResults ] = useState( [] );
	const [ iconTheme, setIconTheme ] = useState( 'color' );
	const [ platforms, setPlatforms ] = useState( [] );

	useEffect( () => {
		const searchPodcastShow = async () => {
			if ( ! searchText.length ) {
				setSearchResults( [] );
				return;
			}

			const queryObject = {
				search: searchText,
				type: 'term',
				subtype: 'podcasting_podcasts'
			};
	
			const queryString = new URLSearchParams( queryObject ).toString();
	
			const searchResults = await apiFetch( {
				path: `/wp/v2/search?${ queryString }`,
			} );

			setSearchResults( searchResults );
		};

		searchPodcastShow();
	}, [ searchText ] );

	useEffect( () => {
		if ( ! showId ) {
			return;
		}

		const fetchPlatforms = async () => {
			const result = await apiFetch( {
				url: `${ ajaxurl }?show_id=${ showId }&action=get_podcast_platforms`,
			} );

			if ( ! result.success ) {
				return;
			}

			const {
				data: { platforms }
			} = result;

			setPlatforms( platforms )
		};

		fetchPlatforms();
	}, [ showId ] );

	const onShowSelect = ( termId ) => {
		setAttributes( { showId: termId } );
	};

	const blockProps = useBlockProps( {
		className: isSelected ? 'simple-podcasting__podcast-platforms simple-podcasting__podcast-platforms--selected' : 'simple-podcasting__podcast-platforms',
	} );

	const platformSlugs = Object.keys( platforms );

	return (
		<div { ...blockProps }>
			{
				platformSlugs.length ? (
					<div className='simple-podcasting__podcasting-platform-list'>
						{
							platformSlugs.map( ( platform, index ) => {
								return (
									<span key={ index } className='simple-podcasting__podcasting-platform-list-item'>
										<a href={ platforms[ platform ] } target="_blank">
											<img src={ `${ podcastingPlatformVars.podcastingUrl }dist/images/icons/${ platform }/${ iconTheme }-100.png` } />
										</a>
									</span>
								);
							} )
						}
					</div>
				) : <p>{ __( 'No platforms set for this show.', 'simple-podcasting' ) }</p>
			}
			{
				isSelected || ! platformSlugs.length ? (
					<Dropdown
						className="simple-podcasting__select-show-popover"
						contentClassName="simple-podcasting__select-show-popover"
						position="bottom right"
						onClose={ () => setSearchText( '' ) }
						renderToggle={ ( { isOpen, onToggle } ) => (
							<Button
								variant="primary"
								onClick={ onToggle }
								aria-expanded={ isOpen }
								text={ __( 'Select a show', 'simple-podcasting' ) }
							/>
						) }
						renderContent={ ( { isOpen, onToggle, onClose } ) => (
							<div>
								<SearchControl
									placeholder={ __( 'Search a Podcast Show', 'simple-podcasting' ) }
									onChange={ ( searchText ) => setSearchText( searchText ) }
									value={ searchText }
								/>
								<ItemGroup
									isSeparated
								>
									{
										searchResults.length ? (
											searchResults.map( ( result ) => (
												<Item
													key={ result.id }
													className='simple-podcasting__podcast-search-results'
													onClick={ () => {
														onShowSelect( result.id );
														onClose();
													} }
												>
													{ result.title }
												</Item>
											) )
										) : false
									}
								</ItemGroup>
							</div>
						) }
					/>
				) : null
			}
		</div>
	)
}

export default Edit;