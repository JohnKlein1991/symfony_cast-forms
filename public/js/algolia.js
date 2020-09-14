$(document).ready(function () {
    $('.edit_article__author_email').autocomplete({ hint: false }, {
        source: function (query, cb) {
            cb([
                {value: 'foo'},
                {value: 'goo'},
            ])
        }
    })
});