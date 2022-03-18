/**
* jquery.matchHeight-min.js master
* http://brm.io/jquery-match-height/
* License: MIT
*/
(function(c){var n=-1,f=-1,g=function(a){return parseFloat(a)||0},r=function(a){var b=null,d=[];c(a).each(function(){var a=c(this),k=a.offset().top-g(a.css("margin-top")),l=0<d.length?d[d.length-1]:null;null===l?d.push(a):1>=Math.floor(Math.abs(b-k))?d[d.length-1]=l.add(a):d.push(a);b=k});return d},p=function(a){var b={byRow:!0,property:"height",target:null,remove:!1};if("object"===typeof a)return c.extend(b,a);"boolean"===typeof a?b.byRow=a:"remove"===a&&(b.remove=!0);return b},b=c.fn.matchHeight=
function(a){a=p(a);if(a.remove){var e=this;this.css(a.property,"");c.each(b._groups,function(a,b){b.elements=b.elements.not(e)});return this}if(1>=this.length&&!a.target)return this;b._groups.push({elements:this,options:a});b._apply(this,a);return this};b._groups=[];b._throttle=80;b._maintainScroll=!1;b._beforeUpdate=null;b._afterUpdate=null;b._apply=function(a,e){var d=p(e),h=c(a),k=[h],l=c(window).scrollTop(),f=c("html").outerHeight(!0),m=h.parents().filter(":hidden");m.each(function(){var a=c(this);
a.data("style-cache",a.attr("style"))});m.css("display","block");d.byRow&&!d.target&&(h.each(function(){var a=c(this),b="inline-block"===a.css("display")?"inline-block":"block";a.data("style-cache",a.attr("style"));a.css({display:b,"padding-top":"0","padding-bottom":"0","margin-top":"0","margin-bottom":"0","border-top-width":"0","border-bottom-width":"0",height:"100px"})}),k=r(h),h.each(function(){var a=c(this);a.attr("style",a.data("style-cache")||"")}));c.each(k,function(a,b){var e=c(b),f=0;if(d.target)f=
d.target.outerHeight(!1);else{if(d.byRow&&1>=e.length){e.css(d.property,"");return}e.each(function(){var a=c(this),b={display:"inline-block"===a.css("display")?"inline-block":"block"};b[d.property]="";a.css(b);a.outerHeight(!1)>f&&(f=a.outerHeight(!1));a.css("display","")})}e.each(function(){var a=c(this),b=0;d.target&&a.is(d.target)||("border-box"!==a.css("box-sizing")&&(b+=g(a.css("border-top-width"))+g(a.css("border-bottom-width")),b+=g(a.css("padding-top"))+g(a.css("padding-bottom"))),a.css(d.property,
f-b))})});m.each(function(){var a=c(this);a.attr("style",a.data("style-cache")||null)});b._maintainScroll&&c(window).scrollTop(l/f*c("html").outerHeight(!0));return this};b._applyDataApi=function(){var a={};c("[data-match-height], [data-mh]").each(function(){var b=c(this),d=b.attr("data-mh")||b.attr("data-match-height");a[d]=d in a?a[d].add(b):b});c.each(a,function(){this.matchHeight(!0)})};var q=function(a){b._beforeUpdate&&b._beforeUpdate(a,b._groups);c.each(b._groups,function(){b._apply(this.elements,
this.options)});b._afterUpdate&&b._afterUpdate(a,b._groups)};b._update=function(a,e){if(e&&"resize"===e.type){var d=c(window).width();if(d===n)return;n=d}a?-1===f&&(f=setTimeout(function(){q(e);f=-1},b._throttle)):q(e)};c(b._applyDataApi);c(window).bind("load",function(a){b._update(!1,a)});c(window).bind("resize orientationchange",function(a){b._update(!0,a)})})(jQuery);

// as the page loads, call these scripts
jQuery(document).ready(function ($) {
	/**
     * Load the Foundatoin Library
     */
	jQuery( document ).foundation();

    /**
     * Loads the events into the calendar when the user clicks the load more button, if it is active
     * which occurs of the total events is greater than the events to show is set to
     */
	$( 'body' ).on( 'click', '#event-archive-load-more-btn.active', function ( e ) {

		$( this ).addClass( 'working' ).text( 'Loading More...' );

		var data_excludes    = '';
		var data_filters     = $( '#event-filters' );
		var data_month       = data_filters.attr( 'data-month' );
		var data_startdate   = data_filters.attr( 'data-startdate' );
		var data_enddate     = data_filters.attr( 'data-enddate' );
		var data_searchquery = data_filters.attr( 'data-searchquery' );

		var data = {
			action:          'load_more_events',
			data_month:       data_month,
			data_startdate:   data_startdate,
			data_enddate:     data_enddate,
            data_excludes:    data_excludes,
			data_searchquery: data_searchquery,
			data_category:    data_filters.data( 'term-id' ),
			last_month:       $( '.event-archive-month' ).last().data( 'format' ),
			page:             $( '#event-archive-load-more-btn' ).data( 'page' ),
		};

		load_more_events( data );
	});

    /**
     * This function replaces the current set of events with a new set of events 
     * using the selected filter criteria into the calendar
     */
	function load_more_events( data ) {

		var loadMoreBtn  = $( '#event-archive-load-more-btn' );
		var total_events = parseInt( 0 + loadMoreBtn.data( 'total' ) );

		loadMoreBtn.data( 'page', parseInt( loadMoreBtn.data('page') ) + 1 );

		$.ajax({
			type: 'POST',
			url:  ajaxurl,
			data: data,
			success: function (response) {
				$( '#event-results-inner' ).append( response );

				if ( $( '.event-archive-box' ).length >= total_events ) {
					$( '#event-archive-load-more-btn' )
						.removeClass( 'working active' )
						.addClass( 'all-loaded' )
						.html( 'All Loaded' );
				} else {
					$( '#event-archive-load-more-btn' )
						.removeClass ('working' )
						.html( 'Load More' );
				}
			},
			complete: function ( response ) {
				Foundation.reInit( 'equalizer' );
			},
		});
	}

    /**
     * Settings for the calendar's date filters
     */
	$( '.input-daterange input' ).each( function () {
		$( this ).datepicker( {
			autoclose:       true,
			todayHighlight:  true,
			keepEmptyValues: true,
		});
	});

    /**
     * Updates the calendar automatically when the date is changed in the date picker
     */
	$('.input-daterange input').on('changeDate', function ( e ) {

		new_date           = $(this).val();
		var data_startdate = '';
		var data_enddate   = '';
		var data_filters   = $('#event-filters');

		if ( $(this).hasClass( 'date-picker-left' ) ) {
			data_filters.attr( 'data-startdate', new_date );
		} else if ( $( this ).hasClass('date-picker-right') ) {
			data_filters.attr( 'data-enddate', new_date );
		}

		data_startdate   = data_filters.attr('data-startdate');
		data_enddate     = data_filters.attr('data-enddate');
		data_searchquery = data_filters.attr('data-searchquery');
		data_layout      = $('#event-results-inner').attr('data-layout');
		data_cat         = data_filters.attr('data-term-id');

		var url = [location.protocol, '//', location.host, location.pathname].join( '' );
		url += '?layout='    + data_layout;
		url += '&startdate=' + data_startdate;
		url += '&enddate='   + data_enddate;
		url += '&category='  + data_cat;

		if (data_searchquery !== '') {
			url += '&search=' + data_searchquery;
		}

		window.location.href = url;
	});

    /**
     * Submits the search form on the calendar when the user clicks the search bar icon
     */
	$( 'body' ).on( 'click', '#search-events-archive', function ( e ) {

		var data_filters = $( '#event-filters' );
		var search_query = $( '#archive-search-query-val' ).val();

		data_filters.attr( 'data-searchquery', search_query );

		data_startdate   = data_filters.attr( 'data-startdate' );
		data_enddate     = data_filters.attr( 'data-enddate' );
		data_searchquery = data_filters.attr( 'data-searchquery' );
		data_layout      = $( '#event-results-inner' ).attr( 'data-layout' );
		data_cat         = data_filters.attr( 'data-term-id' );

		var url = [location.protocol, '//', location.host, location.pathname].join( '' );
		url += '?layout='    + data_layout;
		url += '&startdate=' + data_startdate;
		url += '&enddate='   + data_enddate;
		url += '&category='  + data_cat;

		if ( search_query !== '' ) {
			url += '&search=' + data_searchquery;
		}

		window.location.href = url;
	});

    /**
     * Updates the events n the calendar when a new category select choice is made
     */
	$( 'body' ).on( 'click', '.event-cat-switch', function ( e ) {
		$( '.event-cat-switch' ).parent().removeClass( 'active' );
		$( this ).parent().addClass( 'active' );

		var data_filters = $( '#event-filters' );
		var search_query = $( '#archive-search-query-val' ).val();

		data_category    = $(this).attr( 'data-cat' );
		data_filters.attr( 'data-term-id', data_category );

		data_startdate   = data_filters.attr( 'data-startdate' );
		data_enddate     = data_filters.attr( 'data-enddate' );
		data_searchquery = data_filters.attr( 'data-searchquery' );
		data_layout      = $( '#event-results-inner' ).attr( 'data-layout' );
		data_cat         = data_filters.attr( 'data-term-id' );

		var url = [location.protocol, '//', location.host, location.pathname].join( '' );
		url += '?layout='    + data_layout;
		url += '&startdate=' + data_startdate;
		url += '&enddate='   + data_enddate;
		url += '&category='  + data_cat;

		if (search_query !== '') {
			url += '&search=' + data_searchquery;
		}

		window.location.href = url;
	});

    /**
     * Switches the calendar into list view
     */
	$( 'body' ).on( 'click', '#view-list', function ( e ) {
		$( '.event-filters-inner-bottom-right' ).find( 'i' ).removeClass( 'active' );
		$( '#event-results-inner' )
			.removeClass( 'events-grid' )
			.addClass( 'events-rows' );
		$(this).addClass( 'active' );
		$( '#event-results-inner' ).attr( 'data-layout', 'rows' );
		setTimeout(function () {
			Foundation.reInit( 'equalizer' );
		}, 100);
	});

    /**
     * Switches the calendar into grid view
     */
	$('body').on( 'click', '#view-grid', function ( e ) {
		$( '.event-filters-inner-bottom-right' ).find( 'i' ).removeClass( 'active' );
		$( '#event-results-inner' )
			.removeClass( 'events-rows' )
			.addClass( 'events-grid' );
		$( this ).addClass( 'active' );
		$ ('#event-results-inner' ).attr( 'data-layout', 'grid' );
		setTimeout( function () {
			Foundation.reInit( 'equalizer' );
		}, 100 );
	});

	/**
     * Makes all og the calendar grid view boxes the same height upon document loaded
     */
	$( '.event-gallery-box' ).matchHeight();

    /**
     * Enables hitting the return key to submit the calendar search form
     */
	$( '#event-search-phrase' ).keypress(function (e) {
		if ( e.keyCode == 13 ) {
			$( '#search-events-archive' ).click();
			e.preventDefault();
		}
	});

}); /* end of as page load scripts */
