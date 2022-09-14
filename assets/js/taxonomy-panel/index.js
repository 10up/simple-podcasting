/**
 * WordPress dependencies
 *
 * This is inspired by: https://github.com/WordPress/gutenberg/blob/1c3bc0ac022310bd0013dd572ba0368c80374806/packages/edit-post/src/components/sidebar/post-taxonomies/index.js
 */
import {
	PostTaxonomies as PostTaxonomiesForm,
	PostTaxonomiesCheck,
} from '@wordpress/editor';

/**
 * Internal dependencies
 */
import TaxonomyPanel from './taxonomy-panel';

function PostTaxonomies() {
	return (
		<PostTaxonomiesCheck>
			<PostTaxonomiesForm
				taxonomyWrapper={ ( content, taxonomy ) => {
					if( 'podcasting_podcasts' !==  taxonomy.slug ) {
						return;
					}
					return (
						<TaxonomyPanel taxonomy={ taxonomy }>
							{ content }
						</TaxonomyPanel>
					);
				} }
			/>
		</PostTaxonomiesCheck>
	);
}

export default PostTaxonomies;
