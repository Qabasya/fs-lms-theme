import { createElement, Fragment } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, Button } from '@wordpress/components';
import { ImageControl } from '../shared/ImageControl';

export default function Edit( { attributes, setAttributes } ) {
	const { imageId, imageUrl, imageAlt, scoreText, authorName, quote } = attributes;
	const blockProps = useBlockProps( { className: 'fs-alumni-card' } );
	const mediaClassName = 'fs-alumni-card__media' + ( imageUrl ? '' : ' fs-placeholder-tile' );

	return (
		<Fragment>
			<InspectorControls>
				<PanelBody title={ __( 'Фото', 'fs-lms-theme' ) }>
					{ imageUrl && (
						<Button variant="link" isDestructive onClick={ () => setAttributes( { imageId: 0, imageUrl: '', imageAlt: '' } ) }>
							{ __( 'Удалить фото', 'fs-lms-theme' ) }
						</Button>
					) }
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
				<div className="fs-alumni-card__body">
					<RichText
						tagName="div"
						className="fs-alumni-card__score"
						value={ scoreText }
						onChange={ ( value ) => setAttributes( { scoreText: value } ) }
						placeholder={ __( '98 баллов', 'fs-lms-theme' ) }
						allowedFormats={ [] }
					/>
					<RichText
						tagName="div"
						className="fs-alumni-card__name"
						value={ authorName }
						onChange={ ( value ) => setAttributes( { authorName: value } ) }
						placeholder={ __( 'Имя Фамилия', 'fs-lms-theme' ) }
						allowedFormats={ [] }
					/>
					<RichText
						tagName="p"
						className="fs-alumni-card__quote"
						value={ quote }
						onChange={ ( value ) => setAttributes( { quote: value } ) }
						placeholder={ __( 'Короткий отзыв выпускника', 'fs-lms-theme' ) }
					/>
				</div>
			</div>
		</Fragment>
	);
}
