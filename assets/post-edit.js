/* jshint unused:false */
jQuery ( document ).ready( function( $ ) {
	'use strict';
	$( '#yoast_wpseo_newssitemap-keywords' ).on( 'keyup', function() {
		if ($(this).val().split(',').length > 9) {
			$( this ).addClass( 'wpseo-news-input-error' );
			$( this ).parent().find( 'div:first' ).addClass( 'wpseo-news-error-message' );
		} else {
			$( this ).removeClass( 'wpseo-news-input-error' );
			$( this ).parent().find( 'div:first' ).removeClass( 'wpseo-news-error-message' );
		}
	});
});
