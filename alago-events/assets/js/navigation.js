/**
 * Primary navigation: mobile menu toggle and accessible behaviour.
 *
 * @package Alago_Events
 */
( function () {
	'use strict';

	var toggle = document.querySelector( '.menu-toggle' );
	var nav    = document.querySelector( '.main-navigation' );

	if ( ! toggle || ! nav ) {
		return;
	}

	toggle.addEventListener( 'click', function () {
		var open = nav.classList.toggle( 'is-open' );
		toggle.classList.toggle( 'is-active', open );
		toggle.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
		document.body.classList.toggle( 'menu-open', open );
	} );

	// Close menu when a link is clicked (single-page anchors).
	nav.addEventListener( 'click', function ( e ) {
		if ( e.target.tagName === 'A' && nav.classList.contains( 'is-open' ) ) {
			nav.classList.remove( 'is-open' );
			toggle.classList.remove( 'is-active' );
			toggle.setAttribute( 'aria-expanded', 'false' );
			document.body.classList.remove( 'menu-open' );
		}
	} );

	// Close on Escape.
	document.addEventListener( 'keydown', function ( e ) {
		if ( e.key === 'Escape' && nav.classList.contains( 'is-open' ) ) {
			toggle.click();
		}
	} );
}() );
