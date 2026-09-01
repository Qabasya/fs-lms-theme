import { createElement } from '@wordpress/element';
import { useBlockProps, RichText } from '@wordpress/block-editor';

export default function save( { attributes } ) {
	const { heading, text, primaryText, primaryUrl, secondaryText, secondaryUrl, variant } = attributes;
	const blockProps = useBlockProps.save( { className: `fs-cta-banner is-${ variant }` } );

	return (
		<div { ...blockProps }>
			<RichText.Content tagName="h2" className="fs-cta-banner__heading" value={ heading } />
			<RichText.Content tagName="p" className="fs-cta-banner__text" value={ text } />
			<div className="fs-cta-banner__actions">
				<a className="fs-cta-banner__button fs-cta-banner__button--primary" href={ primaryUrl }>{ primaryText }</a>
				{ secondaryText && <a className="fs-cta-banner__button fs-cta-banner__button--secondary" href={ secondaryUrl }>{ secondaryText }</a> }
			</div>
		</div>
	);
}
