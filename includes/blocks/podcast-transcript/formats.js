import { registerFormatType, toggleFormat } from '@wordpress/rich-text';
import { BlockControls } from '@wordpress/block-editor';
import { ToolbarGroup, ToolbarButton } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { __ } from '@wordpress/i18n';

const Cite = ({ isActive, onChange, value }) => {
	const selectedBlock = useSelect((select) => {
		return select('core/block-editor').getSelectedBlock();
	}, []);

	if (
		selectedBlock &&
		selectedBlock.name !== 'podcasting/podcast-transcript'
	) {
		return null;
	}

	return (
		<BlockControls>
			<ToolbarGroup>
				<ToolbarButton
					icon="admin-users"
					title={__('Speaker Citation', 'simple-podcasting')}
					onClick={() => {
						onChange(
							toggleFormat(value, {
								type: 'podcasting/transcript-cite',
							})
						);
					}}
					isActive={isActive}
				/>
			</ToolbarGroup>
		</BlockControls>
	);
};

registerFormatType('podcasting/transcript-cite', {
	title: __('Cite', 'simple-podcasting'),
	tagName: 'cite',
	className: null,
	edit: Cite,
});

const Time = ({ isActive, onChange, value }) => {
	const selectedBlock = useSelect((select) => {
		return select('core/block-editor').getSelectedBlock();
	}, []);

	if (
		selectedBlock &&
		selectedBlock.name !== 'podcasting/podcast-transcript'
	) {
		return null;
	}

	return (
		<BlockControls>
			<ToolbarGroup>
				<ToolbarButton
					icon="clock"
					title={__('Timestamp', 'simple-podcasting')}
					onClick={() => {
						onChange(
							toggleFormat(value, {
								type: 'podcasting/transcript-time',
							})
						);
					}}
					isActive={isActive}
				/>
			</ToolbarGroup>
		</BlockControls>
	);
};

registerFormatType('podcasting/transcript-time', {
	title: __('Time', 'simple-podcasting'),
	tagName: 'time',
	className: null,
	edit: Time,
});
