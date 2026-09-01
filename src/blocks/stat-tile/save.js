import { createElement } from '@wordpress/element';
import { useBlockProps, RichText } from '@wordpress/block-editor';

export default function save( { attributes } ) {
	const { value, label, description, accentColor } = attributes;
	const blockProps = useBlockProps.save( { className: 'fs-stat-tile' } );

	return (
		<div { ...blockProps }>
			<RichText.Content tagName="div" className={ `fs-stat-tile__value has-${ accentColor }-color has-text-color` } value={ value } />
			<RichText.Content tagName="div" className="fs-stat-tile__label" value={ label } />
			<RichText.Content tagName="p" className="fs-stat-tile__description" value={ description } />
		</div>
	);
}
