/*
	SweetRice dashboard control center
*/
_.ready(function() {
    _('form').each(function() {
        var input = document.createElement('input');
        _(input).attr({
            'name': '_tkv_',
            'type': 'hidden'
        }).addClass('_tkv_').val(_('#_tkv_').attr('value'));
        _(this).append(input);
    });
    var form_token_expired = parseInt(_('#form_token_expired').attr('content'));
    var form_token_timer = setInterval(function() {
        form_token_expired -= 1;
        if (form_token_expired < 5) {
            _.ajax({
                'type': 'post',
                'data': {
                    '_tkv_': _('#_tkv_').attr('value')
                },
                'url': './?type=form_token',
                'success': function(result) {
                    if (result['status'] == 1) {
                        if (result['form_token'] != _('#_tkv_').attr('value')) {
                            _('#_tkv_').attr('value', result['form_token']);
                            _('._tkv_').val(result['form_token']);
                        }
                        form_token_expired = parseInt(_('#form_token_expired').attr('content'));
                    } else if (result['status'] != 0) {
                        window.clearInterval(form_token_timer);
                    }
                }
            });
        }
    }, 1000);
    if (_('.sign_form').size() > 0) {
        _('#toggle_nav').remove();
        _('#top_image').addClass('top_image');
        _('#div_center').css({
            'min-height': '280px'
        });
        _('#top_line').css({
            'width': '100%'
        });
    }
    if (!_.getCookie('dashboad_bg')) {
        var color = _.randomColor(0x88);
        _.setCookie({
            'name': 'dashboad_bg',
            'value': color
        });
    } else {
        var color = _.getCookie('dashboad_bg');
    }
    _(document.body).css({
        'background-color': color
    });
    _('#toggle_nav').bind('click', function() {
        _('#dashboard_nav').toggle();
        if (_('#dashboard_nav').css('display') == 'block') {
            _('#dashboard_nav ul li div div').addClass('show_');
        }
        _(document.body).scrollTop(0);
    });
    _(document.body ? document.body : document.documentElement).bind('click', function(e) {
        var event = e ? e : window.event;
        var target = event.target ? event.target : event.srcElement;
        if (target.id != 'admin_left' && _('#toggle_nav').css('display') == 'block') {
            _('#admin_left').hide();
        }
        if (target.id == 'toggle_nav') {
            _('#admin_left').show();
        }
    });
    _('#admin_right').css({
        'min-height': _.pageSize().windowHeight + 'px'
    });
    _('#dashboard_nav ul li div div').bind('mouseout', function() {
        _(this).removeClass('show_').addClass('hidden_', function() {
            _('#admin_right').css({
                'min-height': _('#admin_left').height() + 'px'
            });
        });
    });
    _('#dashboard_nav ul li div').bind('mouseover', function() {
        _(this).find('div').removeClass('hidden_').addClass('show_', function() {
            _('#admin_right').css({
                'min-height': _('#admin_left').height() + 'px'
            });
        });
    });
    _('#top_line').bind('click', function() {
        var top_height = _(this).attr('data');
        if (top_height != 'normal') {
            _.setCookie({
                'name': 'top_height',
                'value': 'normal'
            });
        } else {
            _.setCookie({
                'name': 'top_height',
                'value': 'small'
            });
        }
        window.location.reload();
    });
    _('.toggle').bind('click', function() {
        _(_(this).attr('data')).toggle();
    });
    _('.ncr').bind('click', function(event) {
        _.dialog({
            name: 'ncrpop',
            url: _(this).attr('href')
        });
        _.stopevent(event);
    });
    _('.back').bind('click', function() {
        if (_(this).attr('url')) {
            location.href = _(this).attr('url');
        }
    });
});
_(window).bind('resize', function() {
    if (_('#toggle_nav').css('display') == 'none' && _('#dashboard_nav').css('display') == 'none') {
        _('#dashboard_nav').show();
    }
    if (_('#toggle_nav').css('display') == 'block' && _('#dashboard_nav').css('display') == 'block') {
        _('#dashboard_nav').hide();
    }
    if (_('#toggle_nav').css('display') == 'none') {
        _('#admin_left').show();
    }
});