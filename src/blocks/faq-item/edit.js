import { createElement, Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, ToggleControl } from '@wordpress/components';

export default function Edit( { attributes, setAttributes } ) {
	const { question, answer, defaultOpen } = attributes;
	const blockProps = useBlockProps( { className: 'fs-faq-item' } );

	return (
		<Fragment>
			<InspectorControls>
				<PanelBody title={ __( 'Поведение', 'fs-lms-theme' ) }>
					<ToggleControl
						label={ __( 'Открыт по умолчанию на фронте', 'fs-lms-theme' ) }
						checked={ defaultOpen }
						onChange={ ( value ) => setAttributes( { defaultOpen: value } ) }
					/>
				</PanelBody>
			</InspectorControls>
			{ /* В редакторе — без <details>: сворачивание неудобно редактировать, вопрос/ответ всегда видны. */ }
			<div { ...blockProps }>
				<div className="fs-faq-item__question">
					<RichText
						tagName="span"
						className="fs-faq-item__question-text"
						value={ question }
						onChange={ ( value ) => setAttributes( { question: value } ) }
						placeholder={ __( 'Вопрос', 'fs-lms-theme' ) }
						allowedFormats={ [] }
					/>
				</div>
				<RichText
					tagName="p"
					className="fs-faq-item__answer"
					value={ answer }
					onChange={ ( value ) => setAttributes( { answer: value } ) }
					placeholder={ __( 'Ответ', 'fs-lms-theme' ) }
				/>
			</div>
		</Fragment>
	);
}
