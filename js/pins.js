var moreNum = 0;
var isMax = 0;
var pinsAjax = false;
_(window).bind('scroll', function() {
    if ((_.pageSize().pageHeight > _.pageSize().windowHeight + _.scrollSize().top) || (isMax == 1) || (moreNum == 5) || pinsAjax) {
        return;
    }
    moreNum += 1;
    _('#pins_loader').html('<img src="images/loading.gif">').fadeIn(500);
    pinsAjax = true;
    query.m = 'pins';
    query.moreNum = moreNum;
    _.ajax({
        'type': 'GET',
        'data': query,
        'url': './',
        'success': function(result) {
            _('#pins_loader').fadeOut('500');
            if (typeof(result) == 'object') {
                pinsAjax = false;
                isMax = result['isMax'];
                _('#' + bodyId).html(_('#' + bodyId).html() + result['body']);
                if (!query.action || query.action == 'category') {
                    _('.readmore').unbind('click').bind("click", function() {
                        location.href = this.title;
                    });
                }
                if (query.action == 'comment') {
                    query.last_no = result.last_no;
                }
            }
        }
    });
});