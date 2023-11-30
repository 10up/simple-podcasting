import { registerBlockType, createBlock } from '@wordpress/blocks';
import { useBlockProps, RichText } from '@wordpress/block-editor';

const Edit = ({
	attributes,
	attributes: { text },
	setAttributes,
	clientId,
	onReplace,
}) => {
	const blockProps = useBlockProps();
	return (
		<RichText
			tagName="cite"
			value={text}
			onChange={(content) => setAttributes({ text: content })}
			allowedFormats={[]}
			withoutInteractiveFormatting
			onSplit={(value, isOriginal) => {
				let block;

				if (isOriginal || value) {
					block = createBlock('podcasting/podcast-transcript-cite', {
						...attributes,
						content: value,
					});
				} else {
					block = createBlock('core/paragraph');
				}

				if (isOriginal) {
					block.clientId = clientId;
				}

				return block;
			}}
			onReplace={onReplace}
			{...blockProps}
		/>
	);
};

registerBlockType('podcasting/podcast-transcript-cite', {
	edit: Edit,
	save: ({ attributes: { text } }) => <cite>{text}</cite>,
});
