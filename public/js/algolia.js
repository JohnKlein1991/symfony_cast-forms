const usersUrl = '/admin/utility/users';
$(document).ready(function () {
    $('.edit_article__author_email').autocomplete({ hint: false }, {
        source: function (query, cb) {
            $.ajax({
                url: usersUrl + '?query=' + query
            })
                .then(function (data) {
                    cb(data.users)
                })
        },
        displayKey: 'email'
    })
});