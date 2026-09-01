import { createElement, Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText, InspectorControls, PanelColorSettings, useSetting } from '@wordpress/block-editor';

export default function Edit( { attributes, setAttributes } ) {
	const { value, label, description, accentColor } = attributes;
	const blockProps = useBlockProps( { className: 'fs-stat-tile' } );
	const palette = useSetting( 'color.palette' ) || [];

	return (
		<Fragment>
			<InspectorControls>
				<PanelColorSettings
					title={ __( 'Цвет', 'fs-lms-theme' ) }
					colorSettings={ [
						{
							value: palette.find( ( c ) => c.slug === accentColor )?.color,
							onChange: ( color ) => setAttributes( { accentColor: palette.find( ( c ) => c.color === color )?.slug || accentColor } ),
							label: __( 'Цвет числа', 'fs-lms-theme' ),
							colors: palette,
						},
					] }
				/>
			</InspectorControls>
			<div { ...blockProps }>
				<RichText
					tagName="div"
					className={ `fs-stat-tile__value has-${ accentColor }-color has-text-color` }
					value={ value }
					onChange={ ( v ) => setAttributes( { value: v } ) }
					placeholder={ __( '100', 'fs-lms-theme' ) }
					allowedFormats={ [] }
				/>
				<RichText
					tagName="div"
					className="fs-stat-tile__label"
					value={ label }
					onChange={ ( v ) => setAttributes( { label: v } ) }
					placeholder={ __( 'подпись', 'fs-lms-theme' ) }
					allowedFormats={ [] }
				/>
				<RichText
					tagName="p"
					className="fs-stat-tile__description"
					value={ description }
					onChange={ ( v ) => setAttributes( { description: v } ) }
					placeholder={ __( 'Описание', 'fs-lms-theme' ) }
				/>
			</div>
		</Fragment>
	);
}
