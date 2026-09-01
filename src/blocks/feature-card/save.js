import { createElement } from '@wordpress/element';
import { useBlockProps, RichText } from '@wordpress/block-editor';
import { FeatureIcon } from '../shared/icons';

export default function save( { attributes } ) {
	const { icon, iconColor, iconBackground, title, text } = attributes;
	const blockProps = useBlockProps.save( { className: 'fs-feature-card' } );

	return (
		<div { ...blockProps }>
			<span className={ `fs-feature-card__icon has-${ iconColor }-color has-${ iconBackground }-background-color has-text-color has-background` }>
				<FeatureIcon icon={ icon } />
			</span>
			<RichText.Content tagName="h3" className="fs-feature-card__title" value={ title } />
			<RichText.Content tagName="p" className="fs-feature-card__text" value={ text } />
		</div>
	);
}
