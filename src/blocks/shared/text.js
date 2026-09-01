/**
 * Инициалы из имени — фолбэк-аватар для fs-lms/testimonial-card, когда
 * редактор не загрузил фото. Одна и та же функция вызывается в edit.js и
 * save.js, чтобы разметка на фронте и в редакторе совпадала.
 */

export function getInitials( name ) {
	return ( name || '' )
		.replace( /<[^>]*>/g, '' )
		.trim()
		.split( /\s+/ )
		.filter( Boolean )
		.slice( 0, 2 )
		.map( ( word ) => word.charAt( 0 ).toUpperCase() )
		.join( '' );
}
