import { useBlockProps, RichText } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import {
	RadioControl,
	Card,
	CardBody,
	Placeholder,
} from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { useEntityProp } from '@wordpress/core-data';

const Edit = ({ attributes, setAttributes, isSelected }) => {
	const blockProps = useBlockProps({});

	const postType = useSelect(
		(select) => select('core/editor').getCurrentPostType(),
		[]
	);
	const [meta, setMeta] = useEntityProp('postType', postType, 'meta');

	const { display, linkText } = attributes;
	return (
		<section {...blockProps}>
			{isSelected && (
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

			{(isSelected || display === 'post') && (
				<RichText
					tagName="section"
					value={meta.podcast_transcript}
					onChange={(content) =>
						setMeta({ ...meta, podcast_transcript: content })
					}
					placeholder={__('Transcript', 'simple-podcasting')}
					allowedFormats={[
						'podcasting/transcript-cite',
						'podcasting/transcript-time',
					]}
				/>
			)}
		</section>
	);
};

export default Edit;
