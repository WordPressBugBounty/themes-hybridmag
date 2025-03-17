jQuery( document ).ready( function() {

    jQuery( ".customize-control .th-responsive-switches button" ).on( "click", function () {
        var _this               = jQuery(this),
            switches            = jQuery( ".th-responsive-switches" ),
            device              = _this.data("device"),
            control             = jQuery( ".customize-control.th-has-switches" ),
            wp_overlay          = jQuery( ".wp-full-overlay" ),
            wp_overlay_devices  = jQuery( ".wp-full-overlay-footer .devices" );

        switches.find("button").removeClass("active");
        switches.find("button.preview-" + device ).addClass("active");
        control.find(".th-control-wrap").removeClass("active");
        control.find(".th-control-wrap." + device ).addClass("active");
        wp_overlay.removeClass("preview-desktop preview-tablet preview-mobile").addClass("preview-" + device);
        wp_overlay_devices.find("button").removeClass("active").attr("aria-pressed", !1);
        wp_overlay_devices.find("button.preview-" + device).addClass("active").attr("aria-pressed", !0);
    });

    jQuery(".wp-full-overlay-footer .devices button").on("click", function() {
        var _this       = jQuery(this),
            switches    = jQuery( ".customize-control.th-has-switches .th-responsive-switches" ),
            device      = _this.data("device"),
            control     = jQuery( ".customize-control.th-has-switches" );

        switches.find("button").removeClass("active");
        switches.find("button.preview-" + device).addClass("active");
        control.find(".th-control-wrap").removeClass("active");
        control.find(".th-control-wrap." + device).addClass("active");
    });

} );


( function ( $ ) {
	wp.customize.bind( 'ready', function () {
		// Lets you jump to specific sections in the Customizer
		$( [ 'control', 'section', 'panel' ] ).each( function ( i, type ) {
			$( 'a[rel="goto-' + type + '"]' ).click( function ( e ) {
				e.preventDefault();
				const id = $( this ).attr( 'href' ).replace( '#', '' );
				if ( wp.customize[ type ].has( id ) ) {
					wp.customize[ type ].instance( id ).focus();
				}
			} );
		} );
	} );

    wp.customize.bind( 'ready', function() {
        $( '#hybridmag_jump_to_nav_menus' ).on( 'click', function( e ) {
            e.preventDefault();
            wp.customize.panel( 'nav_menus' ).focus(); // Opens the Menus panel
        });
    });
} )( jQuery );

/**
 * global imagePosition
 */
(function ($) {
    wp.customize('hybridmag_entries_layout', function (value) {
        value.bind(function (newVal) {
            if (newVal === 'grid') {
                wp.customize.control('hybridmag_archive_thumbnail_position').setting.set('before-header');
            } else {
                wp.customize.control('hybridmag_archive_thumbnail_position').setting.set('beside-article');
            }
        });
    });
})(jQuery);