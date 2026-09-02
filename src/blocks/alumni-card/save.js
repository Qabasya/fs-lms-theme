import { createElement } from '@wordpress/element';
import { useBlockProps, RichText } from '@wordpress/block-editor';

export default function save( { attributes } ) {
	const { imageUrl, imageAlt, scoreText, authorName, quote } = attributes;
	const blockProps = useBlockProps.save( { className: 'fs-alumni-card splide__slide' } );
	const mediaClassName = 'fs-alumni-card__media' + ( imageUrl ? '' : ' fs-placeholder-tile' );

	return (
		<div { ...blockProps }>
			<div className={ mediaClassName }>
				{ imageUrl && <img src={ imageUrl } alt={ imageAlt } /> }
			</div>
			<div className="fs-alumni-card__body">
				<RichText.Content tagName="div" className="fs-alumni-card__score" value={ scoreText } />
				<RichText.Content tagName="div" className="fs-alumni-card__name" value={ authorName } />
				<RichText.Content tagName="p" className="fs-alumni-card__quote" value={ quote } />
			</div>
		</div>
	);
}
