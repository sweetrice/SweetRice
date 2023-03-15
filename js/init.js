/**
 * SweetRice javascript initialization.
 *
 * @package SweetRice
 * @Dashboard core
 * @since 1.2.1
 */
_().ready(function() {
    _('.post_info .readmore').bind('click', function() {
        location.href = this.title;
    });
});