_().ready(function() {
    var top = 10,
        top_parent = 0;
    _('.app_nav a').each(function() {
        if (_(this).attr('parentid') == 0) {
            top = 10;
            top_parent = _(this).attr('menuid');
        }
        if (_(this).attr('parentid') != 0) {
            _(this).addClass('child').attr('top_parent', top_parent);
        }
        if (_(this).attr('title')) {
            curr_nav(this);
        }
    }).bind('mouseover', function() {
        if (_(this).attr('parentid') != 0) {
            return;
        }
        curr_nav(this);
    });
    _('#app_demo').css({
        'height': '300px',
        'line-height': '300px',
        'font-size': '30px',
        'width': '300px',
        'margin': '140px auto',
        'text-align': 'center',
        'border-top-left-radius': '2px',
        'background-color': '#f0f0f0',
        'border-color': '#69c',
        'box-shadow': '1px 1px 2px #222'
    }).attr('toggle', 0).bind('click', function() {
        if (_(this).attr('toggle') == 0) {
            _(this).stop(true, true).animate({
                'background-color': '#690',
                'color': '#fff',
                'border-top-left-radius': '80px',
                'border-top-right-radius': '10px',
                'border-bottom-left-radius': '10px',
                'border-bottom-right-radius': '10px'
            }, 800, function() {
                _(this).attr('toggle', 1);
                setTimeout(function() {
                    _('#app_demo').run('click');
                }, 8000);
            });
        } else {
            _(this).stop(true, true).animate({
                'border-top-left-radius': '2px',
                'border-top-right-radius': '2px',
                'border-bottom-left-radius': '2px',
                'border-bottom-right-radius': '2px',
                'background-color': '#f0f0f0',
                'color': '#690',
                'border-color': '#69c'
            }, 800, function() {
                _(this).attr('toggle', 0);
                setTimeout(function() {
                    _('#app_demo').run('click');
                }, 8000);
            });
        }
    });
    _('#app_demo').run('click');
});

function curr_nav(obj) {
    _('.curr_line').animate({
        'width': _(obj).width() + 'px',
        'left': (_(obj).position().left - _(obj).parent().position().left) + 'px'
    }, 200);
    var menuid = _(obj).attr('menuid');
    var parentid = _(obj).attr('parentid');
    var level = _(obj).attr('level');
    _('.curr_child').html('');
    _('.app_nav .child').each(function() {
        if (_(this).attr('top_parent') == menuid) {
            _('.curr_child').html(_('.curr_child').html() + this.outerHTML);
        }
    });
    _('.curr_child').css({
        'left': (_(obj).position().left - _(obj).parent().position().left - 15) + 'px'
    }).show();
    var top = 0;
    _('.curr_child .child').each(function() {
        _(this).addClass('child').css({
            'left': _(this).attr('level') * 15 + 'px',
            'top': top + 'px'
        });
        top += 20;
    });
}