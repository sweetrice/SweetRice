/**
 * SweetRice javascript function.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 0.5.4
 */
<!--
function CheckEmail(e) {
    var reg = /^\w+([-+.]*\w+)*@\w+([-.]*\w+)*\.\w+([-.]\w+)*$/;
    if (!reg.test(e) || e == '') {
        return false;
    } else {
        return true;
    }
}

function doLang(lang) {
    if (!lang || lang == null) {
        return;
    }
    var query = new Object();
    query.lang = escape(lang);
    _.dialog({
        'content': '<img src="images/loading.gif">',
        'layer': true
    });
    _.ajax({
        'type': 'POST',
        'data': query,
        'url': './?action=lang',
        'success': function(result) {
            if (typeof(result) == 'object') {
                if (result['status'] == '1') {
                    window.location.reload();
                }
            }
        }
    });
}

function doTheme(theme) {
    var query = new Object();
    query.theme = escape(theme);
    _.dialog({
        'content': '<img src="images/loading.gif">',
        'layer': true
    });
    _.ajax({
        'type': 'POST',
        'data': query,
        'url': './?action=theme',
        'success': function(result) {
            if (typeof(result) == 'object') {
                if (result['status'] == '1') {
                    window.location.reload();
                }
            }
        }
    });
}
//-->