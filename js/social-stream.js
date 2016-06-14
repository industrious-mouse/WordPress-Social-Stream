function load_social_stream() {
    var data = {
        'action': 'load_ss_template'
    };

    jQuery.post(ajaxurl, data, function(data) {
        jQuery('.ss-container').html(data);
    });
}
