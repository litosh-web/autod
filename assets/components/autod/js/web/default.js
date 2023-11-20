var autod = {

    initialize: function (config) {

        // if (!jQuery().jGrowl) {
        //     document.write('<script src="' + config['assetsUrl'] + 'js/web/lib/jquery.jgrowl.min.js"><\/script>');
        // }

        if (!jQuery.ui) {
            return autod.loadJQUI(autod.initialize, config);
        }

        var forms = $('.autod__form');
        var inputs = $(forms).find('input');
        $(inputs).on('input', function (e) {
            $(e.target).removeClass('error');
        });

        autod.initInputs();

        for (var i = 0; i < forms.length; i++) {
            var form = forms[i];
            $(form).on('submit', function (e) {
                autod.form.submit(e);
            });
        }

    },
    form: {
        submit: function (e) {
            e.preventDefault();

            var form = $(e.target);
            var data = $(form).serializeArray();

            data.push({name: 'action', value: 'generate'});

            $.ajax({
                url: "/assets/components/autod/action.php",
                dataType: "json",
                method: "POST",
                data: data,
                success: function (data) {

                    if (data.success === true) {
                        $(document).trigger('ad_complete', data);
                        autod.form.response(data, form);
                    } else {
                        autod.form.addErrors(data);
                    }
                }
            });
        },
        response: function (data, form) {
            var km = data.object.km;
            var minutes = data.object.minutes;
            var time = Math.floor(minutes / 60) + ":" + minutes % 60;

            $(form).find('.audot__km').html(km);
            $(form).find('.audot__minutes').html(minutes);
            $(form).find('.audot__time').html(time);
        },
        addErrors: function (data) {
            var errors = data.object.errors;
            if (errors) {
                for (i = 0; i < errors.length; i++) {
                    $("." + errors[i]).addClass('error');
                }
            }
        }
        // response: function (data, form) {
        //     data = JSON.parse(data);
        //     var errors = $(form).find('.autod__error');
        //
        //     $(errors).removeClass('autod__error');
        //
        //     if (data.success === true) {
        //         var total = data.object.total;
        //         $(form).find('.autod__total-num').html(total);
        //     } else {
        //         var errors = data.object.errors;
        //         if (errors) {
        //             for (i = 0; i < errors.length; i++) {
        //                 var elem = $(form).find("input[name='" + errors[i] + "']");
        //                 $(elem).addClass('autod__error');
        //             }
        //         }
        //
        //         $.jGrowl(data.message, {theme: 'autod__message-error'});
        //     }
        // },
    },
    loadJQUI: function (callback, config) {
        $('<link/>', {
            rel: 'stylesheet',
            type: 'text/css',
            href: config['cssUrl'] + 'web/lib/jquery-ui.min.css'
        }).appendTo('head');

        return $.getScript(config['jsUrl'] + 'web/lib/jquery-ui.min.js', function () {
            if (typeof callback == 'function') {
                callback(config);
            }
        });
    },

    initInputs: function () {
        var maxResults = 5;

        $(".autod__from, .autod__to").autocomplete({
            source: function (request, response) {

                $.ajax({
                    url: "/assets/components/autod/action.php",
                    method: "POST",
                    dataType: "json",
                    data: {
                        action: 'getlist',
                        q: request.term,
                        onlyCountries: ['RU'],
                    },
                    success: function (data) {
                        var r = JSON.parse(data.object.html);
                        response(r);
                    }
                });
            },
            open: function (event, ui) {
                var width = $(this).outerWidth();
                $(this).autocomplete("widget").css("width", width);
                $(this).data("uiAutocomplete").menu.element.children().slice(maxResults).remove();
            },
        });
    }

};
