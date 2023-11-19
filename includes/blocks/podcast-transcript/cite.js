import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps, RichText } from '@wordpress/block-editor';

const Edit = ({ attributes: { text }, setAttributes }) => {
	const blockProps = useBlockProps();
	return (
		<RichText
			{...blockProps}
			tagName="cite"
			value={text}
			onChange={(content) => setAttributes({ text: content })}
			allowedFormats={[]}
		/>
	);
};

registerBlockType('podcasting/podcast-transcript-cite', {
	edit: Edit,
	save: ({ attributes: { text } }) => <cite>{text}</cite>,
});
