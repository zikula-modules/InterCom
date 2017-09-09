/*
 * InterCom Module for Zikula
 *
 * @copyright  InterCom Team
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package    InterCom
 * @see https://github.com/zikula-modules/InterCom
 */

var Zikula = Zikula || {};
Zikula.Intercom = Zikula.Intercom || {};
(function ($) {
    Zikula.Intercom.MessageManager = (function () {
// Init
        var data = {
            log: null
        };
        var settings = {
            ajax_timeout: 10000
        }
        ;
        function init()
        {
            initSender();
            initRecipients();
            log('init done.');
        }
        ;
        function readSettings()
        {
//            settings.ajax_timeout = parseInt($("#ajax_timeout").val());
            log('Upgrade settings updated.');
        }
        ;
        function log(log)
        {
            if (log === '') {
            } else if (log === null) {
            } else if (log.constructor === Array) {
                console.log(log.join('&#xA;') + '&#xA;');
            } else {
                console.log(log + '&#xA;');
            }
        }
        ;
        function initSender()
        {
            $('#sender_select').addClass('hide');
            log('Upgrade settings updated.');
        }
        ;

        function initRecipients()
        {
            // real form text input comma separated
            var $recipientUsers_select = $('#recipients_select .users_selected');
            var $recipientGroups_select = $('#recipients_select .groups_selected');
            $recipientUsers_select.parent().addClass('hide');
            $('#auto_search').removeClass('hide');
            // selected
            var selected = $recipientUsers_select.val().split(',').filter(x => x);
            $.each(selected, function (index, value) {
                var $label = $('<span class="label label-primary"> </span>')
                        .append('<i class="fa fa-user"> </i> ' + value + ' <a href="#" class="remove text-danger" data-recipient="' + value + '"><i class="fa fa-close"> </i></a>');
                $('#recipients_selected').append($label);
            });
            // search
            var $recipients_search = $('#recipients_search');
            $recipients_search.autocomplete({/*appendTo: "#someElem"*/});
            $recipients_search.autocomplete("option", "minLength", 2);
            $recipients_search.autocomplete("option", "delay", 500);
//            $recipients_search.autocomplete("option", "position", {my: "right top", at: "right bottom"});
            $recipients_search.autocomplete("option", "source", function (request, response) {
                response([{loading: true, text: Translator.__('Loading...'), icon: 'fa fa-refresh fa-spin fa-fw'}]);
                $.ajax({
                    dataType: "json",
                    method: "POST",
                    url: Routing.generate('zikulaintercommodule_user_getrecipients'),
                    data: {fragment: request.term}
                }).success(function (data) {
                    if (data.recipients.length === 0) {
                        return response([{loading: true, text: Translator.__('No results found!'), icon: 'fa fa-exclamation-circle'}]);
                    }

                    return response(data.recipients);
                });
            }
            );
            $recipients_search.autocomplete("option", "open", function (event, ui) {
                $(this).autocomplete('widget').css({
                    width: $(this).outerWidth() + 'px'
                });
                return true;
            }
            );
            $recipients_search.autocomplete("option", "select", function (event, ui) {
                var selected = $recipientUsers_select.val().split(',').filter(x => x);
                if ($.inArray(ui.item.uname, selected) === -1) {
                    selected.push(ui.item.uname);
                    $recipientUsers_select.val(selected.join());
                    //show element in view
                    var $label = $('<span class="label label-primary"> </span>')
                            .append('<i class="fa fa-user"> </i> ' + ui.item.uname + ' <a href="#" class="remove text-danger" data-recipient="' + ui.item.uname + '"><i class="fa fa-close"> </i></a>');
                    $('#recipients_selected').append($label);
                } else {
                    $('<div class="alert alert-warning" role="alert"></div>', {style: 'display:none'})
                            .html(Translator.__('Recipient is already selected!'))
                            .appendTo($('#recipients_selected'))
                            .fadeIn('slow',
                                    function () {
                                        var el = $(this);
                                        setTimeout(function () {
                                            el.fadeOut('slow',
                                                    function () {
                                                        $(this).remove();
                                                    });
                                        }, 2500);
                                    });
                }
                $recipients_search.val('');

                return false;
            }
            );
            $recipients_search.on('focus', function () {
                // Size and position menu
                var $ul = $(this).autocomplete('widget');
                $ul.empty();
                $ul.show();
                $ul.css({
                    width: $(this).outerWidth() + 'px'
//                    display: 'block'
                });
                $ul.position($.extend({
                    of: $(this)
                }
                , {
                    my: "left top",
                    at: "left bottom",
                    collision: "none"
                }
                ));
                var exists = $ul.find(".info");
                if (exists.length > 0) {

                } else {
                    $("<li class='info'></li>").append("<i class='fa fa-refresh fa-info'></i> "
                            + Translator.__('Enter two chatacters to start search')
                            + " <span class='sr-only'> "
                            + Translator.__('Enter two chatacters to start search')
                            + "</span>"
                            ).prependTo($ul);
                }
            }
            );
            var $recipients_instance = $recipients_search.autocomplete('instance');
            $recipients_instance._renderItem = function (ul, item) {
                if (item.loading) {
                    var $li = $("<li></li>");
                    return $li.append("<i class='" + item.icon + "'></i>" + item.text + "<span class='sr-only'>" + item.text + "</span>").appendTo(ul);
                } else {
                    return $('<li class="suggestion">')
                            .append('<div class="media">\n\
                 <div class="media-left"><a href="javascript:void(0)"><i class="fa fa-user fa-3x"> </i></a></div>\n\
                 <div class="media-body"><p class="media-heading">' + item.uname + '</p></div>\n\
                 </li>')
                            .appendTo(ul);
                }
                ;
            }
            ;
            $('#recipients_selected').on("click", ".remove", function (e) {
                e.preventDefault();
                var recipient = $(this).data("recipient");
                console.log(recipient);
                var selected = $recipientUsers_select.val().split(',').filter(x => x);
                if ($.inArray(recipient, selected) > -1) {
                    var index = selected.indexOf(recipient);
                    selected.splice(index, 1);
                    $recipientUsers_select.val(selected.join());
                }
                $(this).parent().remove();
            });

            log('Recipients init done.');
        }
        ;
        //ajax util
        function importAjax(url, data) {
            console.log(data);
            return $.ajax({
                type: 'POST',
                url: url,
                data: JSON.stringify(data),
                timeout: settings.ajax_timeout,
                contentType: "application/json",
                dataType: 'json'
            });
        }

        //return this and init when ready
        return {
            init: init
        };
    })();
    $(function () {
        Zikula.Intercom.MessageManager.init();
    });
}
)(jQuery);
