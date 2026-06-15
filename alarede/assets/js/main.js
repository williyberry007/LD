/**
 * Front-end interactions: sticky header, scroll reveal, testimonial slider,
 * back-to-top button and smooth anchor scrolling.
 *
 * @package Alarede
 */
( function () {
	'use strict';

	document.addEventListener( 'DOMContentLoaded', function () {
		setupHeader();
		setupReveal();
		setupHeroVideo();
		setupHeroSlider();
		setupTestimonials();
		setupBackToTop();
		setupSmoothScroll();
		setupFaq();
		setupGalleryFilter();
		setupJobFilter();
		setupBookingSlots();
	} );

	/* Booking form: load available time slots when a date is chosen. */
	function setupBookingSlots() {
		var form = document.querySelector( '.ae-booking-form' );
		if ( ! form || typeof window.alaredeBooking === 'undefined' ) {
			return;
		}
		var dateInput = form.querySelector( 'input[name="ae_date"]' );
		var timeSel   = form.querySelector( 'select[name="ae_time"][data-slots]' );
		if ( ! dateInput || ! timeSel ) {
			return;
		}

		dateInput.addEventListener( 'change', function () {
			if ( ! dateInput.value ) {
				return;
			}
			var body = new FormData();
			body.append( 'action', 'alarede_slots' );
			body.append( 'date', dateInput.value );

			fetch( window.alaredeBooking.ajax, { method: 'POST', body: body } )
				.then( function ( r ) { return r.json(); } )
				.then( function ( res ) {
					if ( ! res || ! res.success ) {
						return;
					}
					var previous = timeSel.value;
					// Remove existing slot options, keep the first placeholder.
					[].slice.call( timeSel.querySelectorAll( 'option[data-slot]' ) ).forEach( function ( o ) { o.remove(); } );
					timeSel.options[ 0 ].textContent = window.alaredeBooking.pick;
					res.data.forEach( function ( slot ) {
						var opt = document.createElement( 'option' );
						opt.value = slot.time;
						opt.setAttribute( 'data-slot', '1' );
						opt.textContent = slot.full ? slot.time + ' — ' + window.alaredeBooking.full : slot.time;
						opt.disabled = !! slot.full;
						if ( slot.time === previous && ! slot.full ) {
							opt.selected = true;
						}
						timeSel.appendChild( opt );
					} );
				} )
				.catch( function () {} );
		} );
	}

	/* Ensure self-hosted hero videos autoplay (force muted + play). */
	function setupHeroVideo() {
		var videos = document.querySelectorAll( '.ae-hero__video' );
		videos.forEach( function ( video ) {
			video.muted = true;
			video.setAttribute( 'muted', '' );
			video.playsInline = true;
			var attempt = video.play();
			if ( attempt && typeof attempt.catch === 'function' ) {
				attempt.catch( function () {
					// Retry once on first user interaction if the browser blocked it.
					var resume = function () {
						video.play();
						document.removeEventListener( 'click', resume );
						document.removeEventListener( 'touchstart', resume );
					};
					document.addEventListener( 'click', resume, { once: true } );
					document.addEventListener( 'touchstart', resume, { once: true } );
				} );
			}
		} );
	}

	/* Hero slider: fade between slides, dots + arrows, optional autoplay. */
	function setupHeroSlider() {
		var hero = document.querySelector( '.ae-hero--slider' );
		if ( ! hero ) {
			return;
		}
		var slides = hero.querySelectorAll( '.ae-hero__slide' );
		var dots   = hero.querySelectorAll( '.ae-hero__dots button' );
		var prev   = hero.querySelector( '.ae-hero__nav--prev' );
		var next   = hero.querySelector( '.ae-hero__nav--next' );
		if ( slides.length < 2 ) {
			return;
		}
		var current = 0;
		var delay   = parseInt( hero.getAttribute( 'data-autoplay' ), 10 ) || 0;
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
		function go( i ) { stop(); show( i ); start(); }
		function start() { if ( delay > 0 ) { timer = setInterval( function () { show( current + 1 ); }, delay ); } }
		function stop() { clearInterval( timer ); }

		if ( prev ) { prev.addEventListener( 'click', function () { go( current - 1 ); } ); }
		if ( next ) { next.addEventListener( 'click', function () { go( current + 1 ); } ); }
		dots.forEach( function ( dot ) {
			dot.addEventListener( 'click', function () { go( parseInt( dot.getAttribute( 'data-slide' ), 10 ) ); } );
		} );
		hero.addEventListener( 'mouseenter', stop );
		hero.addEventListener( 'mouseleave', start );
		start();
	}

	/* FAQ accordion. */
	function setupFaq() {
		var items = document.querySelectorAll( '.ae-faq__item' );
		items.forEach( function ( item ) {
			var q = item.querySelector( '.ae-faq__q' );
			var a = item.querySelector( '.ae-faq__a' );
			if ( ! q || ! a ) {
				return;
			}
			q.setAttribute( 'aria-expanded', 'false' );
			q.addEventListener( 'click', function () {
				var open = item.classList.toggle( 'is-open' );
				q.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
				a.style.maxHeight = open ? a.scrollHeight + 'px' : null;
			} );
		} );
	}

	/* Gallery category filter. */
	function setupGalleryFilter() {
		var filters = document.querySelectorAll( '.ae-gallery__filter' );
		var items   = document.querySelectorAll( '.ae-gallery__item' );
		if ( ! filters.length ) {
			return;
		}
		filters.forEach( function ( btn ) {
			btn.addEventListener( 'click', function () {
				var target = btn.getAttribute( 'data-filter' );
				filters.forEach( function ( b ) { b.classList.remove( 'is-active' ); } );
				btn.classList.add( 'is-active' );
				items.forEach( function ( item ) {
					var show = target === '*' || item.getAttribute( 'data-cat' ) === target;
					item.classList.toggle( 'is-hidden', ! show );
				} );
			} );
		} );
	}

	/* Careers list category filter (a job may belong to several categories). */
	function setupJobFilter() {
		var filters = document.querySelectorAll( '.ae-jobs__filter' );
		var items   = document.querySelectorAll( '.ae-job[data-cat]' );
		if ( ! filters.length ) {
			return;
		}
		filters.forEach( function ( btn ) {
			btn.addEventListener( 'click', function () {
				var target = btn.getAttribute( 'data-filter' );
				filters.forEach( function ( b ) { b.classList.remove( 'is-active' ); } );
				btn.classList.add( 'is-active' );
				items.forEach( function ( item ) {
					var cats = ( item.getAttribute( 'data-cat' ) || '' ).split( ' ' );
					var show = target === '*' || cats.indexOf( target ) !== -1;
					item.classList.toggle( 'is-hidden', ! show );
				} );
			} );
		} );
	}

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
