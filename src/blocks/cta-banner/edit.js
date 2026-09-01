import { createElement, Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl, __experimentalToggleGroupControl as ToggleGroupControl, __experimentalToggleGroupControlOption as ToggleGroupControlOption } from '@wordpress/components';

export default function Edit( { attributes, setAttributes } ) {
	const { heading, text, primaryText, primaryUrl, secondaryText, secondaryUrl, variant } = attributes;
	const blockProps = useBlockProps( { className: `fs-cta-banner is-${ variant }` } );

	return (
		<Fragment>
			<InspectorControls>
				<PanelBody title={ __( 'Вид', 'fs-lms-theme' ) }>
					<ToggleGroupControl
						label={ __( 'Фон', 'fs-lms-theme' ) }
						isBlock
						value={ variant }
						onChange={ ( value ) => setAttributes( { variant: value } ) }
					>
						<ToggleGroupControlOption value="solid" label={ __( 'Заливка', 'fs-lms-theme' ) } />
						<ToggleGroupControlOption value="soft" label={ __( 'Светлый', 'fs-lms-theme' ) } />
					</ToggleGroupControl>
				</PanelBody>
				<PanelBody title={ __( 'Основная кнопка', 'fs-lms-theme' ) }>
					<TextControl label={ __( 'Текст', 'fs-lms-theme' ) } value={ primaryText } onChange={ ( v ) => setAttributes( { primaryText: v } ) } />
					<TextControl label={ __( 'Ссылка', 'fs-lms-theme' ) } type="url" value={ primaryUrl } onChange={ ( v ) => setAttributes( { primaryUrl: v } ) } />
				</PanelBody>
				<PanelBody title={ __( 'Вторая кнопка (опционально)', 'fs-lms-theme' ) } initialOpen={ false }>
					<TextControl label={ __( 'Текст', 'fs-lms-theme' ) } value={ secondaryText } onChange={ ( v ) => setAttributes( { secondaryText: v } ) } />
					<TextControl label={ __( 'Ссылка', 'fs-lms-theme' ) } type="url" value={ secondaryUrl } onChange={ ( v ) => setAttributes( { secondaryUrl: v } ) } />
				</PanelBody>
			</InspectorControls>
			<div { ...blockProps }>
				<RichText
					tagName="h2"
					className="fs-cta-banner__heading"
					value={ heading }
					onChange={ ( v ) => setAttributes( { heading: v } ) }
					placeholder={ __( 'Заголовок', 'fs-lms-theme' ) }
				/>
				<RichText
					tagName="p"
					className="fs-cta-banner__text"
					value={ text }
					onChange={ ( v ) => setAttributes( { text: v } ) }
					placeholder={ __( 'Текст', 'fs-lms-theme' ) }
				/>
				<div className="fs-cta-banner__actions">
					<span className="fs-cta-banner__button fs-cta-banner__button--primary">{ primaryText }</span>
					{ secondaryText && <span className="fs-cta-banner__button fs-cta-banner__button--secondary">{ secondaryText }</span> }
				</div>
			</div>
		</Fragment>
	);
}
