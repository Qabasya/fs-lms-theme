import { createElement, Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, SelectControl, TextControl } from '@wordpress/components';
import { ImageControl } from '../shared/ImageControl';
import { BADGE_COLORS, softSlug, textSlug } from '../shared/colors';

export default function Edit( { attributes, setAttributes } ) {
	const { imageId, imageUrl, imageAlt, badgeText, badgeColor, title, caption, price, priceUnit, buttonText, buttonUrl } = attributes;
	const blockProps = useBlockProps( { className: 'fs-course-card' } );
	const mediaClassName = 'fs-course-card__media' + ( imageUrl ? '' : ' fs-placeholder-tile' );

	return (
		<Fragment>
			<InspectorControls>
				<PanelBody title={ __( 'Бейдж', 'fs-lms-theme' ) }>
					<SelectControl
						label={ __( 'Цвет бейджа', 'fs-lms-theme' ) }
						value={ badgeColor }
						options={ BADGE_COLORS.map( ( c ) => ( { label: c.name, value: c.slug } ) ) }
						onChange={ ( value ) => setAttributes( { badgeColor: value } ) }
					/>
				</PanelBody>
				<PanelBody title={ __( 'Цена', 'fs-lms-theme' ) }>
					<TextControl
						label={ __( 'Цена', 'fs-lms-theme' ) }
						value={ price }
						onChange={ ( value ) => setAttributes( { price: value } ) }
						help={ __( 'Пусто — строка цены не выводится.', 'fs-lms-theme' ) }
					/>
					<TextControl
						label={ __( 'Единица', 'fs-lms-theme' ) }
						value={ priceUnit }
						onChange={ ( value ) => setAttributes( { priceUnit: value } ) }
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
						help={ __( 'Заглушка «#» — реальный маршрут (страница курса/заявки плагина) подключается в Фазе 7.', 'fs-lms-theme' ) }
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
				<div className="fs-course-card__body">
					<RichText
						tagName="span"
						className={ `fs-course-card__badge has-${ textSlug( badgeColor ) }-color has-${ softSlug( badgeColor ) }-background-color has-text-color has-background` }
						value={ badgeText }
						onChange={ ( value ) => setAttributes( { badgeText: value } ) }
						placeholder={ __( 'Бейдж', 'fs-lms-theme' ) }
						allowedFormats={ [] }
					/>
					<RichText
						tagName="h3"
						className="fs-course-card__title"
						value={ title }
						onChange={ ( value ) => setAttributes( { title: value } ) }
						placeholder={ __( 'Название курса', 'fs-lms-theme' ) }
					/>
					<RichText
						tagName="p"
						className="fs-course-card__caption"
						value={ caption }
						onChange={ ( value ) => setAttributes( { caption: value } ) }
						placeholder={ __( 'Формат/расписание', 'fs-lms-theme' ) }
					/>
					<div className="fs-course-card__footer">
						{ price && (
							<div className="fs-course-card__price">
								<RichText
									tagName="span"
									className="fs-course-card__price-amount"
									value={ price }
									onChange={ ( value ) => setAttributes( { price: value } ) }
									allowedFormats={ [] }
								/>
								<RichText
									tagName="span"
									className="fs-course-card__price-unit"
									value={ priceUnit }
									onChange={ ( value ) => setAttributes( { priceUnit: value } ) }
									allowedFormats={ [] }
								/>
							</div>
						) }
						<span className="fs-course-card__button wp-element-button">{ buttonText }</span>
					</div>
				</div>
			</div>
		</Fragment>
	);
}
