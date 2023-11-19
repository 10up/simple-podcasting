import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, RichText } from '@wordpress/block-editor';

const Edit = ({ attributes: { text }, setAttributes }) => {
	const blockProps = useBlockProps();
	return (
		<RichText
			{...blockProps}
			tagName="time"
			value={text}
			onChange={(content) => setAttributes({ text: content })}
			allowedFormats={[]}
		/>
	);
};

registerBlockType('podcasting/podcast-transcript-time', {
	edit: Edit,
	save: ({ attributes: { text } }) => <time>{text}</time>,
});
