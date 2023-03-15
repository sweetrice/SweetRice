<!--
var r = 0;
var psrc = '';
var image = new Image();
_(image).bind('load', function() {
    ImagePreview();
});
_(image).bind('error', function() {
    load_error();
});
_('.attimage').bind('click', function() {
    if (!_(this).attr('data')) {
        return false;
    }
    _.dialog({
        'content': '<div style="width:22px;margin:auto;"><img src="../images/loading.gif"></div>',
        'name': 'media',
        'width': 300,
        'layer': true
    });
    psrc = _(this).attr('data');
    image.src = psrc;
    return false;
});

function ZoomImage(event) {
    event = event || window.event;
    if (_('#pimg')) {
        _('#pimg').className = '';
        var iw = parseInt(_('#pimg').css('width'));
        var ih = parseInt(_('#pimg').css('height'));
        if (isNaN(ih) || isNaN(iw)) {
            return false;
        } else {
            if (r == 0) {
                r = parseFloat(iw / ih);
            }
        }
        if (event.wheelDelta) {
            var zx = event.wheelDelta / 12;
        } else {
            var zx = event.detail;
        }
        zx = zx * 2;
        iw = parseInt(r * (ih + zx));
        _('#pimg').css({
            'width': iw + 'px',
            'height': (ih + zx) + 'px'
        });
        _('#imgs').css({
            'width': iw + 'px'
        });
        _('#SweetRice_dialog_media').css({
            'width': (iw < 300 ? 320 : iw + 20) + 'px',
            'top': (_.scrollSize().top + (_.pageSize().windowHeight - (ih < 160 ? 160 : ih + 60)) / 2) + 'px',
            'left': (_.pageSize().pageWidth - (iw < 300 ? 320 : iw + 20)) / 2 + 'px'
        });
        _.stopevent(event);
    }
}

function ImagePreview() {
    _('#SweetRice_dialog_media .SweetRice_dialog_content').html('<div id="imgs" style="margin:auto;"></div>');
    var ih = image.height > 450 ? 450 : image.height;
    if (!isNaN(ih)) {
        r = parseFloat(image.width / image.height);
        var iw = parseInt(ih * r);
    } else {
        var iw = 0;
    }
    _('#imgs').html('<img id="pimg" src="' + psrc + '"/>').css({
        'width': iw + 'px'
    });
    if (iw > 0 && ih > 0) {
        _('#pimg').css({
            'width': iw + 'px',
            'height': ih + 'px'
        });
    } else {
        _('#pimg').addClass('img450');
    }
    _('#SweetRice_dialog_media').css({
        'width': (iw > 300 ? iw + 20 : 320) + 'px',
        'top': (_.scrollSize().top + (_.pageSize().windowHeight - (ih < 160 ? 160 : ih + 60)) / 2) + 'px',
        'left': (_.pageSize().pageWidth - (iw < 300 ? 320 : iw + 20)) / 2 + 'px'
    });
    _('#SweetRice_layer_dialog').refillscreen();
    init_imagePreview(ZoomImage);
}

function init_imagePreview() {
    _('#pimg').bind('DOMMouseScroll', function(event) {
        ZoomImage(event);
    });
    _('#pimg').bind('mousewheel', function(event) {
        ZoomImage(event);
    });
}

function load_error() {
    _('#SweetRice_dialog_media').remove();
    _('#SweetRice_layer_dialog').remove();
}
//-->