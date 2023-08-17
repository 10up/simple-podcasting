import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { useState, useEffect } from '@wordpress/element';
import apiFetch from '@wordpress/api-fetch';
import { __ } from '@wordpress/i18n';
import { useDebounce } from 'use-debounce';
import {
	Panel,
	PanelBody,
	PanelRow,
	RangeControl,
	SearchControl,
	__experimentalItemGroup as ItemGroup,
	__experimentalItem as Item,
	BaseControl,
	Button,
	ButtonGroup,
	Icon
} from '@wordpress/components';


function Edit( props ) {
	const {
		setAttributes,
		isSelected,
		attributes: {
			showId,
			iconSize,
			align,
		},
	} = props;

	/** State for the search text for the show name. Defaults to empty string. */
	const [ searchText, setSearchText ] = useState( '' );

	/** Debounced search text so that we don't trigger useEffect() for every character change. */
	const [ debouncedSearchText ] = useDebounce( searchText, 300 );

	/** Indicates when the ajax search for podcasts is completed. */
	const [ isSearchCompleted, setIsSearchCompleted ] = useState( false );

	/** State for search results matched by the search text. Defaults to array. */
	const [ searchResults, setSearchResults ] = useState( [] );

	/** State for the icon theme. Defaults to `color`. */
	const [ iconTheme, setIconTheme ] = useState( 'color' );

	/** State for platforms returned for a specific show. Defaults to array. */
	const [ platforms, setPlatforms ] = useState( [] );

	/**
	 * Hits the `/wp/v2/search` endpoint to search for
	 * podcast show by name.
	 */
	useEffect( () => {
		const searchPodcastShow = async () => {
			setIsSearchCompleted( false );

			if ( ! searchText.length ) {
				setSearchResults( [] );
				return;
			}

			/** Query object required by `/wp/v2/search` to search for a term by name. */
			const queryObject = {
				search: searchText,
				type: 'term',
				subtype: 'podcasting_podcasts'
			};

			/** Converts an object to query-string. */
			const queryString = new URLSearchParams( queryObject ).toString();

			/** Returns the results of the search. */
			const searchResults = await apiFetch( {
				path: `/wp/v2/search?${ queryString }`,
			} );

			if ( ! searchResults.length ) {
				setIsSearchCompleted( true );
			}

			setSearchResults( searchResults );
			setIsSearchCompleted( true );
		};

		searchPodcastShow();
	}, [ debouncedSearchText ] );

	/**
	 * Fetches the podcasting platforms for a show whenever
	 * showId updates.
	 */
	useEffect( () => {
		if ( ! showId ) {
			return;
		}

		/**
		 * Responsible to fetch platforms for a show by show ID.
		 * @returns void
		 */
		const fetchPlatforms = async () => {
			const result = await apiFetch( {
				url: `${ ajaxurl }?show_id=${ showId }&action=get_podcast_platforms`,
			} );

			if ( ! result.success ) {
				setPlatforms( [] );
				return;
			}

			const {
				data: { platforms, theme }
			} = result;

			setPlatforms( platforms );
			setIconTheme( theme );
		};

		fetchPlatforms();
	}, [ showId ] );

	/**
	 * Handler to set the attribute showId.
	 *
	 * @param {Int} termId The show ID.
	 * @returns void
	 */
	const onShowSelect = ( termId ) => {
		setAttributes( { showId: termId } );
		setSearchResults( [] );
		setIsSearchCompleted( false );
	};

	/**
	 * Handler to set size of the icon.
	 *
	 * @param {Int} size The icon size in `px`
	 */
	const setIconSize = ( size ) => {
		setAttributes( { iconSize: size } );
	};

	/**
	 * Sets the HTML attributes for the root element.
	 */
	const blockProps = useBlockProps( {
		className: isSelected ? 'simple-podcasting__podcast-platforms simple-podcasting__podcast-platforms--selected' : 'simple-podcasting__podcast-platforms',
	} );

	const platformSlugs = Object.keys( platforms );

	return (
		<>
			<InspectorControls>
				<Panel header={ __( 'Customization Controls', 'simple-podacsting' ) }>
					<PanelBody>
						<BaseControl label={ __( 'Icon size', 'simple-podcasting' ) }/>
						<PanelRow>
							<RangeControl
								min={ 16 }
								max={ 96 }
								step={ 16 }
								value={ iconSize }
								onChange={ setIconSize }
							/>
						</PanelRow>
						<BaseControl label={ __( 'Alignment', 'simple-podcasting' ) }/>
						<PanelRow>
							<ButtonGroup>
								<Button
									isPressed={ align === 'left' }
									variant='ternary'
									icon={ <Icon icon='align-left' /> }
									onClick={ () => setAttributes( { align: 'left' } ) }
								/>
								<Button
									isPressed={ align === 'center' }
									variant='ternary'
									icon={ <Icon icon='align-center' /> }
									onClick={ () => setAttributes( { align: 'center' } ) }
								/>
								<Button
									isPressed={ align === 'right' }
									variant='ternary'
									icon={ <Icon icon='align-right' /> }
									onClick={ () => setAttributes( { align: 'right' } ) }
								/>
							</ButtonGroup>
						</PanelRow>
					</PanelBody>
				</Panel>
			</InspectorControls>
			<div { ...blockProps }>
				{
					platformSlugs.length ? (
						<div className={ `simple-podcasting__podcasting-platform-list simple-podcasting__podcasting-platform-list--${ align }` }>
							{
								platformSlugs.map( ( platform, index ) => {
									return (
										<span key={ index } className='simple-podcasting__podcasting-platform-list-item'>
											<a href={ platforms[ platform ] } target="_blank">
												<img className={ `simple-pocasting__icon-size--${ iconSize }` } src={ `${ podcastingPlatformVars.podcastingUrl }dist/images/icons/${ platform }/${ iconTheme }-100.png` } />
											</a>
										</span>
									);
								} )
							}
						</div>
					) : (
						<div className={ `simple-podcasting__podcasting-platform-list` }>
							<p>{ __( 'No platforms are set for this podcast.', 'simple-podcasting' ) }</p>
						</div>
					)
				}
				{
					isSelected || ! showId ? (
						<div className='simple-podcasting__podcasting-search-controls'>
							<SearchControl
								placeholder={ __( 'Search a Podcast Show', 'simple-podcasting' ) }
								onChange={ ( searchText ) => setSearchText( searchText ) }
								value={ searchText }
							/>
							{
								searchResults.length ?
								(
									<div className='simple-podcasting__podcasting-search-results'>
										<ItemGroup
											isSeparated
										>
											{
												searchResults.map( ( result ) => (
													<Item
														key={ result.id }
														className='simple-podcasting__podcast-search-results'
														onClick={ () => onShowSelect( result.id ) }
													>
														{ result.title }
													</Item>
												) )
											}
										</ItemGroup>
									</div>
								) : (
									! searchResults.length && isSearchCompleted ? (
										<div className='simple-podcasting__podcasting-search-results'>
											<ItemGroup
												isSeparated
											>
												<Item>
													{ __( 'No results found.' , 'simple-podcasting') }
												</Item>
											</ItemGroup>
										</div>
									) : null
								)
							}
						</div>
					) : null
				}
			</div>
		</>
	)
}

export default Edit;