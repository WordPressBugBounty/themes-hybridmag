jQuery(document).ready(function($) {
    'use strict';
    var pluginInstallerObj = hybridmag_plugins_object;

    $(document).on('click', '.themezhut-plugin-install', function(event) {
        event.preventDefault();
        var button = $(this);
        var slug = button.data('slug');
        button.text(pluginInstallerObj.installing + '...').addClass('updating-message');
        wp.updates.installPlugin({
            slug: slug,
            success: function(data) {
                button.attr('href', data.activateUrl);
                button.text(pluginInstallerObj.activating + '...');
                button.removeClass('button-secondary updating-message themezhut-plugin-install');
                button.addClass('button-primary themezhut-plugin-activate');
                button.trigger('click');
            },
            error: function(data) {
                button.removeClass('updating-message');
                button.text(pluginInstallerObj.error);
            },
        
        });
    });


    $(document).on('click', '.themezhut-plugin-activate', function(event) {
        event.preventDefault();
        var button = $(this);
        var url = button.attr('href');
        if (typeof url !== 'undefined') {
            // Request plugin activation.
            jQuery.ajax({
                async: true,
                type: 'GET',
                url: url,
                beforeSend: function() {
                    button.text(pluginInstallerObj.activating + '...');
                    button.removeClass('button-secondary');
                    button.addClass('button-primary activate-now updating-message');
                },
                success: function(data) {
                    location.reload();
                }
            });
        }
    });
});