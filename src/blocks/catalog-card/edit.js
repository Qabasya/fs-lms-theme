import { createElement, Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, SelectControl, TextControl } from '@wordpress/components';
import { ImageControl } from '../shared/ImageControl';
import { BADGE_COLORS, softSlug, textSlug } from '../shared/colors';
import { splitTags } from './tags';

export default function Edit( { attributes, setAttributes } ) {
	const {
		imageId,
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

	const blockProps = useBlockProps( { className: 'fs-course-catalog-card' } );
	const mediaClassName =
		'fs-course-catalog-card__media' + ( imageUrl ? '' : ' fs-placeholder-tile' );

	return (
		<Fragment>
			<InspectorControls>
				<PanelBody title={ __( 'Бейдж класса', 'fs-lms-theme' ) }>
					<SelectControl
						label={ __( 'Цвет бейджа', 'fs-lms-theme' ) }
						value={ badgeColor }
						options={ BADGE_COLORS.map( ( c ) => ( { label: c.name, value: c.slug } ) ) }
						onChange={ ( value ) => setAttributes( { badgeColor: value } ) }
					/>
					<TextControl
						label={ __( 'Класс для фильтра', 'fs-lms-theme' ) }
						value={ grade }
						onChange={ ( value ) => setAttributes( { grade: value } ) }
						help={ __(
							'Должно совпадать с адресом чипса фильтра: #grade-11 → 11, #grade-5-8 → 5-8. Пусто — карточка не участвует в фильтрации.',
							'fs-lms-theme'
						) }
					/>
				</PanelBody>
				<PanelBody title={ __( 'Теги', 'fs-lms-theme' ) }>
					<TextControl
						label={ __( 'Теги через запятую', 'fs-lms-theme' ) }
						value={ tags }
						onChange={ ( value ) => setAttributes( { tags: value } ) }
						help={ __( 'Например: Python, Пробники каждый месяц, Группы до 8', 'fs-lms-theme' ) }
					/>
				</PanelBody>
				<PanelBody title={ __( 'Кнопка', 'fs-lms-theme' ) }>
					<TextControl
						label={ __( 'Текст кнопки', 'fs-lms-theme' ) }
						value={ buttonText }
						onChange={ ( value ) => setAttributes( { buttonText: value } ) }
					/>
					<TextControl
						label={ __( 'Ссылка кнопки', 'fs-lms-theme' ) }
						type="url"
						value={ buttonUrl }
						onChange={ ( value ) => setAttributes( { buttonUrl: value } ) }
						help={ __( 'Страница направления, например /inf_ege/.', 'fs-lms-theme' ) }
					/>
				</PanelBody>
			</InspectorControls>
			<div { ...blockProps }>
				<ImageControl
					className={ mediaClassName }
					imageId={ imageId }
					imageUrl={ imageUrl }
					imageAlt={ imageAlt }
					onSelect={ ( media ) => setAttributes( media ) }
					onRemove={ () => setAttributes( { imageId: 0, imageUrl: '', imageAlt: '' } ) }
				/>
				<div className="fs-course-catalog-card__body">
					<div className="fs-course-catalog-card__meta">
						<RichText
							tagName="span"
							className={ `fs-course-catalog-card__badge has-${ textSlug(
								badgeColor
							) }-color has-${ softSlug(
								badgeColor
							) }-background-color has-text-color has-background` }
							value={ badgeText }
							onChange={ ( value ) => setAttributes( { badgeText: value } ) }
							placeholder={ __( 'Класс', 'fs-lms-theme' ) }
							allowedFormats={ [] }
						/>
						<RichText
							tagName="span"
							className="fs-course-catalog-card__format"
							value={ formatText }
							onChange={ ( value ) => setAttributes( { formatText: value } ) }
							placeholder={ __( 'Формат занятий', 'fs-lms-theme' ) }
							allowedFormats={ [] }
						/>
					</div>
					<RichText
						tagName="h3"
						className="fs-course-catalog-card__title"
						value={ title }
						onChange={ ( value ) => setAttributes( { title: value } ) }
						placeholder={ __( 'Название направления', 'fs-lms-theme' ) }
					/>
					<RichText
						tagName="p"
						className="fs-course-catalog-card__text"
						value={ text }
						onChange={ ( value ) => setAttributes( { text: value } ) }
						placeholder={ __( 'Описание программы', 'fs-lms-theme' ) }
					/>
					{ splitTags( tags ).length > 0 && (
						<div className="fs-course-catalog-card__tags">
							{ splitTags( tags ).map( ( tag ) => (
								<span key={ tag } className="fs-course-catalog-card__tag">
									{ tag }
								</span>
							) ) }
						</div>
					) }
					<div className="fs-course-catalog-card__footer">
						<div className="fs-course-catalog-card__price">
							<RichText
								tagName="span"
								className="fs-course-catalog-card__price-amount"
								value={ priceAmount }
								onChange={ ( value ) => setAttributes( { priceAmount: value } ) }
								allowedFormats={ [] }
							/>
							<RichText
								tagName="span"
								className="fs-course-catalog-card__price-note"
								value={ priceNote }
								onChange={ ( value ) => setAttributes( { priceNote: value } ) }
								allowedFormats={ [] }
							/>
						</div>
						<div className="fs-course-catalog-card__actions">
							<span className="fs-course-catalog-card__button fs-course-catalog-card__button--solid">
								{ buttonText }
							</span>
						</div>
					</div>
				</div>
			</div>
		</Fragment>
	);
}
