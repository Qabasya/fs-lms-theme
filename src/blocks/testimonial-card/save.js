import { createElement } from '@wordpress/element';
import { useBlockProps, RichText } from '@wordpress/block-editor';
import { Rating } from '../shared/rating';
import { getInitials } from '../shared/text';

export default function save( { attributes } ) {
	const { imageUrl, imageAlt, authorName, authorRole, quote, rating } = attributes;
	const blockProps = useBlockProps.save( { className: 'fs-testimonial-card' } );
	const initials = getInitials( authorName );
	const avatarClassName = 'fs-testimonial-card__avatar' + ( imageUrl ? '' : ' has-accent-700-color has-accent-soft-background-color has-text-color has-background' );

	return (
		<div { ...blockProps }>
			<Rating value={ rating } />
			<span className="screen-reader-text">{ `Оценка ${ rating } из 5` }</span>
			<RichText.Content tagName="p" className="fs-testimonial-card__quote" value={ quote } />
			<div className="fs-testimonial-card__author">
				<div className={ avatarClassName }>
					{ imageUrl ? <img src={ imageUrl } alt={ imageAlt } /> : initials }
				</div>
				<div>
					<RichText.Content tagName="div" className="fs-testimonial-card__name" value={ authorName } />
					<RichText.Content tagName="div" className="fs-testimonial-card__role" value={ authorRole } />
				</div>
			</div>
		</div>
	);
}
