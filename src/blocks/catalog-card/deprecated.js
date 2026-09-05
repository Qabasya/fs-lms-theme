/**
 * BugFix.3 (2026-09-05): страницы, вставленные из `patterns/courses-catalog.php`
 * до этой правки, лежат в БД без строки тегов (`.fs-course-catalog-card__tags`),
 * хотя в комментарии блока атрибут `tags` заполнен. Прежний `save()` рисовал
 * контейнер тегов всегда — разметка расходилась, и редактор показывал «Этот
 * блок имеет неожидаемое или неверное содержимое».
 *
 * Теперь `save()` рисует теги только когда они есть, а эта устаревшая версия
 * (теги не выводятся никогда) подхватывает уже сохранённое содержимое и
 * `migrate()` чистит осиротевший атрибут — вместо ошибки страница открывается
 * как раньше.
 */
import { createElement } from '@wordpress/element';
import { useBlockProps, RichText } from '@wordpress/block-editor';
import metadata from './block.json';
import { softSlug, textSlug } from '../shared/colors';

function saveWithoutTags( { attributes } ) {
	const {
		imageUrl,
		imageAlt,
		badgeText,
		badgeColor,
		formatText,
		title,
		text,
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

export default [
	{
		attributes: metadata.attributes,
		supports: metadata.supports,
		save: saveWithoutTags,
		migrate: ( attributes ) => ( { ...attributes, tags: '' } ),
	},
];
