import { createElement } from '@wordpress/element';
import { useBlockProps, RichText } from '@wordpress/block-editor';
import { softSlug, textSlug } from '../shared/colors';
import { splitTags } from './tags';

export default function save( { attributes } ) {
	const {
		imageUrl,
		imageAlt,
		badgeText,
		badgeColor,
		formatText,
		title,
		text,
		tags,
		priceAmount,
		priceNote,
		buttonText,
		buttonUrl,
		grade,
	} = attributes;

	const blockProps = useBlockProps.save( {
		className: 'fs-course-catalog-card',
		'data-grade': grade || undefined,
	} );
	const mediaClassName =
		'fs-course-catalog-card__media' + ( imageUrl ? '' : ' fs-placeholder-tile' );

	return (
		<div { ...blockProps }>
			<div className={ mediaClassName }>
				{ imageUrl && <img src={ imageUrl } alt={ imageAlt } /> }
			</div>
			<div className="fs-course-catalog-card__body">
				<div className="fs-course-catalog-card__meta">
					<RichText.Content
						tagName="span"
						className={ `fs-course-catalog-card__badge has-${ textSlug(
							badgeColor
						) }-color has-${ softSlug(
							badgeColor
						) }-background-color has-text-color has-background` }
						value={ badgeText }
					/>
					<RichText.Content
						tagName="span"
						className="fs-course-catalog-card__format"
						value={ formatText }
					/>
				</div>
				<RichText.Content
					tagName="h3"
					className="fs-course-catalog-card__title"
					value={ title }
				/>
				<RichText.Content
					tagName="p"
					className="fs-course-catalog-card__text"
					value={ text }
				/>
				<div className="fs-course-catalog-card__tags">
					{ splitTags( tags ).map( ( tag ) => (
						<span key={ tag } className="fs-course-catalog-card__tag">
							{ tag }
						</span>
					) ) }
				</div>
				<div className="fs-course-catalog-card__footer">
					<div className="fs-course-catalog-card__price">
						<RichText.Content
							tagName="span"
							className="fs-course-catalog-card__price-amount"
							value={ priceAmount }
						/>
						<RichText.Content
							tagName="span"
							className="fs-course-catalog-card__price-note"
							value={ priceNote }
						/>
					</div>
					<div className="fs-course-catalog-card__actions">
						<a
							className="fs-course-catalog-card__button fs-course-catalog-card__button--solid"
							href={ buttonUrl }
						>
							{ buttonText }
						</a>
					</div>
				</div>
			</div>
		</div>
	);
}
