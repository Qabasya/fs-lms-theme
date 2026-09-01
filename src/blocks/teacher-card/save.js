import { createElement } from '@wordpress/element';
import { useBlockProps, RichText } from '@wordpress/block-editor';

export default function save( { attributes } ) {
	const { imageUrl, imageAlt, name, role, bio, profileUrl } = attributes;
	const blockProps = useBlockProps.save( { className: 'fs-teacher-card' } );
	const mediaClassName = 'fs-teacher-card__media' + ( imageUrl ? '' : ' fs-placeholder-tile' );

	return (
		<div { ...blockProps }>
			<div className={ mediaClassName }>
				{ imageUrl && <img src={ imageUrl } alt={ imageAlt } /> }
			</div>
			<RichText.Content tagName="h3" className="fs-teacher-card__name" value={ name } />
			<RichText.Content tagName="p" className="fs-teacher-card__role" value={ role } />
			<RichText.Content tagName="p" className="fs-teacher-card__bio" value={ bio } />
			{ profileUrl && (
				<a className="fs-teacher-card__link" href={ profileUrl }>Профиль →</a>
			) }
		</div>
	);
}
