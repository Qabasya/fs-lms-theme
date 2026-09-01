import { createElement, Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText, InspectorControls, PanelColorSettings, useSetting } from '@wordpress/block-editor';
import { PanelBody, SelectControl } from '@wordpress/components';
import { FeatureIcon, ICON_OPTIONS } from '../shared/icons';

export default function Edit( { attributes, setAttributes } ) {
	const { icon, iconColor, iconBackground, title, text } = attributes;
	const blockProps = useBlockProps( { className: 'fs-feature-card' } );
	const palette = useSetting( 'color.palette' ) || [];

	const colorValue = ( slug ) => palette.find( ( c ) => c.slug === slug )?.color;
	const slugFromColor = ( color, fallback ) => palette.find( ( c ) => c.color === color )?.slug || fallback;

	return (
		<Fragment>
			<InspectorControls>
				<PanelBody title={ __( 'Иконка', 'fs-lms-theme' ) }>
					<SelectControl
						label={ __( 'Иконка', 'fs-lms-theme' ) }
						value={ icon }
						options={ ICON_OPTIONS }
						onChange={ ( value ) => setAttributes( { icon: value } ) }
					/>
				</PanelBody>
				<PanelColorSettings
					title={ __( 'Цвет', 'fs-lms-theme' ) }
					colorSettings={ [
						{
							value: colorValue( iconColor ),
							onChange: ( color ) => setAttributes( { iconColor: slugFromColor( color, iconColor ) } ),
							label: __( 'Цвет иконки', 'fs-lms-theme' ),
							colors: palette,
						},
						{
							value: colorValue( iconBackground ),
							onChange: ( color ) => setAttributes( { iconBackground: slugFromColor( color, iconBackground ) } ),
							label: __( 'Подложка', 'fs-lms-theme' ),
							colors: palette,
						},
					] }
				/>
			</InspectorControls>
			<div { ...blockProps }>
				<span className={ `fs-feature-card__icon has-${ iconColor }-color has-${ iconBackground }-background-color has-text-color has-background` }>
					<FeatureIcon icon={ icon } />
				</span>
				<RichText
					tagName="h3"
					className="fs-feature-card__title"
					value={ title }
					onChange={ ( value ) => setAttributes( { title: value } ) }
					placeholder={ __( 'Заголовок', 'fs-lms-theme' ) }
				/>
				<RichText
					tagName="p"
					className="fs-feature-card__text"
					value={ text }
					onChange={ ( value ) => setAttributes( { text: value } ) }
					placeholder={ __( 'Описание', 'fs-lms-theme' ) }
				/>
			</div>
		</Fragment>
	);
}
