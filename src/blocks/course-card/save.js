import { createElement } from '@wordpress/element';
import { useBlockProps, RichText } from '@wordpress/block-editor';
import { softSlug, textSlug } from '../shared/colors';

export default function save( { attributes } ) {
	const { imageUrl, imageAlt, badgeText, badgeColor, title, caption, buttonText, buttonUrl } = attributes;
	const blockProps = useBlockProps.save( { className: 'fs-course-card' } );
	const mediaClassName = 'fs-course-card__media' + ( imageUrl ? '' : ' fs-placeholder-tile' );

	return (
		<div { ...blockProps }>
			<div className={ mediaClassName }>
				{ imageUrl && <img src={ imageUrl } alt={ imageAlt } /> }
			</div>
			<div className="fs-course-card__body">
				<RichText.Content
					tagName="span"
					className={ `fs-course-card__badge has-${ textSlug( badgeColor ) }-color has-${ softSlug( badgeColor ) }-background-color has-text-color has-background` }
					value={ badgeText }
				/>
				<RichText.Content tagName="h3" className="fs-course-card__title" value={ title } />
				<RichText.Content tagName="p" className="fs-course-card__caption" value={ caption } />
				<a className="fs-course-card__button wp-element-button" href={ buttonUrl }>{ buttonText }</a>
			</div>
		</div>
	);
}
