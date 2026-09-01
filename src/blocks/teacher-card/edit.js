import { createElement, Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import { ImageControl } from '../shared/ImageControl';

export default function Edit( { attributes, setAttributes } ) {
	const { imageId, imageUrl, imageAlt, name, role, bio, profileUrl } = attributes;
	const blockProps = useBlockProps( { className: 'fs-teacher-card' } );
	const mediaClassName = 'fs-teacher-card__media' + ( imageUrl ? '' : ' fs-placeholder-tile' );

	return (
		<Fragment>
			<InspectorControls>
				<PanelBody title={ __( 'Профиль', 'fs-lms-theme' ) }>
					<TextControl
						label={ __( 'Ссылка на профиль (опционально)', 'fs-lms-theme' ) }
						type="url"
						value={ profileUrl }
						onChange={ ( value ) => setAttributes( { profileUrl: value } ) }
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
				<RichText
					tagName="h3"
					className="fs-teacher-card__name"
					value={ name }
					onChange={ ( value ) => setAttributes( { name: value } ) }
					placeholder={ __( 'Имя Фамилия', 'fs-lms-theme' ) }
				/>
				<RichText
					tagName="p"
					className="fs-teacher-card__role"
					value={ role }
					onChange={ ( value ) => setAttributes( { role: value } ) }
					placeholder={ __( 'Роль/предмет', 'fs-lms-theme' ) }
				/>
				<RichText
					tagName="p"
					className="fs-teacher-card__bio"
					value={ bio }
					onChange={ ( value ) => setAttributes( { bio: value } ) }
					placeholder={ __( 'Короткое био', 'fs-lms-theme' ) }
				/>
				{ profileUrl && (
					<span className="fs-teacher-card__link">{ __( 'Профиль →', 'fs-lms-theme' ) }</span>
				) }
			</div>
		</Fragment>
	);
}
