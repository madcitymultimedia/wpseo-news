/* jshint unused:false */
jQuery( document ).ready( function( $ ) {
	'use strict';
	$( '#yoast_wpseo_newssitemap-keywords' ).on( 'keyup', function() {
		var $keywordsField = $( this ),
			$keywordsFieldParent = $keywordsField.parent(),
			$keywordsFieldDesc = $keywordsFieldParent.find( 'div:first' );

		if ( $keywordsField.val().split( ',' ).length > 10 ) {
			$keywordsFieldParent.addClass( 'form-invalid' );
			$keywordsFieldDesc.addClass( 'error-message' );
		} else {
			$keywordsFieldParent.removeClass( 'form-invalid' );
			$keywordsFieldDesc.removeClass( 'error-message' );
		}
	});
});
