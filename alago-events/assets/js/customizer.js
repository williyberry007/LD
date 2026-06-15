/**
 * Customizer live preview for colours and site title.
 *
 * @package Alago_Events
 */
( function ( $ ) {
	'use strict';

	wp.customize( 'blogname', function ( value ) {
		value.bind( function ( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );

	function setVar( name, value ) {
		document.documentElement.style.setProperty( name, value );
	}

	wp.customize( 'alago_color_accent', function ( value ) {
		value.bind( function ( to ) {
			setVar( '--ae-color-accent', to );
		} );
	} );
	wp.customize( 'alago_color_dark', function ( value ) {
		value.bind( function ( to ) {
			setVar( '--ae-color-dark', to );
		} );
	} );
	wp.customize( 'alago_color_light', function ( value ) {
		value.bind( function ( to ) {
			setVar( '--ae-color-light', to );
		} );
	} );
}( jQuery ) );
