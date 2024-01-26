import { useBlockProps, RichText, InnerBlocks } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import {
	RadioControl,
	Card,
	CardBody,
	Placeholder,
} from '@wordpress/components';
import { useSelect, withSelect } from '@wordpress/data';
import { useEffect } from '@wordpress/element';
import { useEntityProp } from '@wordpress/core-data';
import { serialize } from '@wordpress/blocks';

const Edit = withSelect((select, { clientId }) => {
	return {
		innerBlocks: select('core/block-editor').getBlocksByClientId(clientId),
	};
})(({ attributes, setAttributes, isSelected, innerBlocks, clientId }) => {
	const blockProps = useBlockProps({});

	const postType = useSelect(
		(select) => select('core/editor').getCurrentPostType(),
		[]
	);

	const [meta, setMeta] = useEntityProp('postType', postType, 'meta');

	useEffect(() => {
		if (innerBlocks.length) {
			setMeta({
				...meta,
				podcast_transcript: serialize(innerBlocks[0].innerBlocks),
			});
		}
	}, [innerBlocks]);

	const isInnerBlockSelected = useSelect((select) =>
		select('core/block-editor').hasSelectedInnerBlock(clientId)
	);

	console.log(isInnerBlockSelected);

	const { display, linkText } = attributes;
	return (
		<section {...blockProps}>
			{(isSelected || isInnerBlockSelected) && (
				<>
					<Card>
						<CardBody>
							<RadioControl
								label={__(
									'Transcript Display',
									'simple-podcasting'
								)}
								selected={display}
								options={[
									{
										label: __(
											'Display Transcript on Post',
											'simple-podcasting'
										),
										value: 'post',
									},
									{
										label: __(
											'Display Link to Transcript',
											'simple-podcasting'
										),
										value: 'link',
									},
									{
										label: __(
											'Do not display - only show link in RSS feed',
											'simple-podcasting'
										),
										value: 'none',
									},
								]}
								onChange={(value) =>
									setAttributes({ display: value })
								}
							/>
						</CardBody>
					</Card>
					<br />
				</>
			)}

			{display === 'none' && !isSelected && (
				<Placeholder
					icon="microphone"
					label={__('Podcast Transcript', 'simple-podcasting')}
				/>
			)}

			{display === 'link' && (
				<RichText
					tagName="a"
					value={linkText}
					onChange={(content) => setAttributes({ linkText: content })}
					placeholder={__('Transcript Link', 'simple-podcasting')}
					allowedFormats={[]}
				/>
			)}

			{(isSelected || isInnerBlockSelected || display === 'post') && (
				<>
					<section>
						<InnerBlocks
							allowedBlocks={[
								'core/paragraph',
								'podcasting/podcast-transcript-cite',
								'podcasting/podcast-transcript-time',
							]}
						/>
					</section>
				</>
			)}
		</section>
	);
});

export default Edit;
