import { createElement, Fragment } from '@wordpress/element';
import { __, sprintf } from '@wordpress/i18n';
import { useBlockProps, RichText, InspectorControls, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { PanelBody, RangeControl, Button } from '@wordpress/components';
import { Rating } from '../shared/rating';
import { getInitials } from '../shared/text';

export default function Edit( { attributes, setAttributes } ) {
	const { imageId, imageUrl, imageAlt, authorName, authorRole, quote, rating } = attributes;
	const blockProps = useBlockProps( { className: 'fs-testimonial-card' } );
	const initials = getInitials( authorName );
	const avatarClassName = 'fs-testimonial-card__avatar' + ( imageUrl ? '' : ' has-accent-700-color has-accent-soft-background-color has-text-color has-background' );

	return (
		<Fragment>
			<InspectorControls>
				<PanelBody title={ __( 'Рейтинг', 'fs-lms-theme' ) }>
					<RangeControl
						label={ __( 'Оценка', 'fs-lms-theme' ) }
						value={ rating }
						min={ 0 }
						max={ 5 }
						onChange={ ( value ) => setAttributes( { rating: value } ) }
					/>
				</PanelBody>
				<PanelBody title={ __( 'Аватар', 'fs-lms-theme' ) }>
					{ imageUrl && (
						<Button variant="link" isDestructive onClick={ () => setAttributes( { imageId: 0, imageUrl: '', imageAlt: '' } ) }>
							{ __( 'Удалить фото (вернуться к инициалам)', 'fs-lms-theme' ) }
						</Button>
					) }
				</PanelBody>
			</InspectorControls>
			<div { ...blockProps }>
				<Rating value={ rating } />
				<span className="screen-reader-text">
					{ sprintf( __( 'Оценка %1$d из %2$d', 'fs-lms-theme' ), rating, 5 ) }
				</span>
				<RichText
					tagName="p"
					className="fs-testimonial-card__quote"
					value={ quote }
					onChange={ ( value ) => setAttributes( { quote: value } ) }
					placeholder={ __( 'Текст отзыва', 'fs-lms-theme' ) }
				/>
				<div className="fs-testimonial-card__author">
					<MediaUploadCheck>
						<MediaUpload
							onSelect={ ( media ) => setAttributes( { imageId: media.id, imageUrl: media.url, imageAlt: media.alt || '' } ) }
							allowedTypes={ [ 'image' ] }
							value={ imageId }
							render={ ( { open } ) => (
								<button type="button" className={ avatarClassName } onClick={ open }>
									{ imageUrl ? <img src={ imageUrl } alt={ imageAlt } /> : initials }
								</button>
							) }
						/>
					</MediaUploadCheck>
					<div>
						<RichText
							tagName="div"
							className="fs-testimonial-card__name"
							value={ authorName }
							onChange={ ( value ) => setAttributes( { authorName: value } ) }
							placeholder={ __( 'Имя Фамилия', 'fs-lms-theme' ) }
						/>
						<RichText
							tagName="div"
							className="fs-testimonial-card__role"
							value={ authorRole }
							onChange={ ( value ) => setAttributes( { authorRole: value } ) }
							placeholder={ __( 'Родитель, 11 класс', 'fs-lms-theme' ) }
						/>
					</div>
				</div>
			</div>
		</Fragment>
	);
}
