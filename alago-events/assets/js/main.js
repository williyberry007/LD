/**
 * Front-end interactions: sticky header, scroll reveal, testimonial slider,
 * back-to-top button and smooth anchor scrolling.
 *
 * @package Alago_Events
 */
( function () {
	'use strict';

	document.addEventListener( 'DOMContentLoaded', function () {
		setupHeader();
		setupReveal();
		setupTestimonials();
		setupBackToTop();
		setupSmoothScroll();
	} );

	/* Sticky / transparent header. */
	function setupHeader() {
		var header = document.querySelector( '.site-header' );
		if ( ! header ) {
			return;
		}
		var transparent = header.getAttribute( 'data-transparent' ) === 'is-transparent';

		function onScroll() {
			var scrolled = window.scrollY > 60;
			header.classList.toggle( 'is-scrolled', scrolled );
			if ( transparent ) {
				header.classList.toggle( 'is-transparent', ! scrolled );
			}
		}
		onScroll();
		window.addEventListener( 'scroll', onScroll, { passive: true } );
	}

	/* Scroll-reveal animations. */
	function setupReveal() {
		var items = document.querySelectorAll( '.ae-reveal' );
		if ( ! items.length || ! ( 'IntersectionObserver' in window ) ) {
			items.forEach( function ( el ) {
				el.classList.add( 'is-visible' );
			} );
			return;
		}
		var observer = new IntersectionObserver( function ( entries ) {
			entries.forEach( function ( entry ) {
				if ( entry.isIntersecting ) {
					entry.target.classList.add( 'is-visible' );
					observer.unobserve( entry.target );
				}
			} );
		}, { threshold: 0.12 } );

		items.forEach( function ( el ) {
			observer.observe( el );
		} );
	}

	/* Testimonial slider. */
	function setupTestimonials() {
		var track = document.querySelector( '.ae-testimonials__track' );
		if ( ! track ) {
			return;
		}
		var slides = track.querySelectorAll( '.ae-testimonial' );
		var dots   = track.querySelectorAll( '.ae-testimonials__dots button' );
		if ( slides.length < 2 ) {
			return;
		}
		var current = 0;
		var delay   = parseInt( track.getAttribute( 'data-autoplay' ), 10 ) || 6000;
		var timer;

		function show( i ) {
			slides[ current ].classList.remove( 'is-active' );
			if ( dots[ current ] ) {
				dots[ current ].classList.remove( 'is-active' );
			}
			current = ( i + slides.length ) % slides.length;
			slides[ current ].classList.add( 'is-active' );
			if ( dots[ current ] ) {
				dots[ current ].classList.add( 'is-active' );
			}
		}

		function next() {
			show( current + 1 );
		}

		function start() {
			timer = setInterval( next, delay );
		}

		function stop() {
			clearInterval( timer );
		}

		dots.forEach( function ( dot ) {
			dot.addEventListener( 'click', function () {
				stop();
				show( parseInt( dot.getAttribute( 'data-slide' ), 10 ) );
				start();
			} );
		} );

		track.addEventListener( 'mouseenter', stop );
		track.addEventListener( 'mouseleave', start );
		start();
	}

	/* Back-to-top button. */
	function setupBackToTop() {
		var btn = document.querySelector( '.ae-to-top' );
		if ( ! btn ) {
			return;
		}
		window.addEventListener( 'scroll', function () {
			btn.classList.toggle( 'is-visible', window.scrollY > 600 );
		}, { passive: true } );
		btn.addEventListener( 'click', function () {
			window.scrollTo( { top: 0, behavior: 'smooth' } );
		} );
	}

	/* Smooth scroll for same-page anchor links, accounting for fixed header. */
	function setupSmoothScroll() {
		document.querySelectorAll( 'a[href^="#"]' ).forEach( function ( link ) {
			link.addEventListener( 'click', function ( e ) {
				var id = link.getAttribute( 'href' );
				if ( id.length < 2 ) {
					return;
				}
				var target = document.querySelector( id );
				if ( ! target ) {
					return;
				}
				e.preventDefault();
				var offset = 80;
				var top    = target.getBoundingClientRect().top + window.scrollY - offset;
				window.scrollTo( { top: top, behavior: 'smooth' } );
			} );
		} );
	}
}() );
