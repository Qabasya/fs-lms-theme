import { createElement } from '@wordpress/element';
import { useBlockProps, RichText } from '@wordpress/block-editor';

export default function save( { attributes } ) {
	const { question, answer, defaultOpen } = attributes;
	const blockProps = useBlockProps.save( { className: 'fs-faq-item' } );

	return (
		<details { ...blockProps } open={ defaultOpen }>
			<summary className="fs-faq-item__question">
				<RichText.Content tagName="span" className="fs-faq-item__question-text" value={ question } />
			</summary>
			<RichText.Content tagName="p" className="fs-faq-item__answer" value={ answer } />
		</details>
	);
}
