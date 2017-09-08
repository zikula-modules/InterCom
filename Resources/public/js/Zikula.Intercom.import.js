/**
 * Zikula.Intercom.import.js
 */

// in case not exists
var Zikula = Zikula || {};
Zikula.Intercom = Zikula.Intercom || {};
(function ($) {
    Zikula.Intercom.import = (function () {
        // Init
        var data = {
            imported: 0,
            rejected: 0
        };
        var settings = {
            limit: 100,
            ajax_timeout: 10000
        }
        ;
        function init()
        {
            if (parseInt($('#importing').val()) === 0) {
                return;
            }
            readData();
            log(Translator.__('Data read done.'));
            log(Translator.__('Detected data source with ' + data.messages_count + ' messages to import.'));
            readSettings();
            log(Translator.__('Settings updated.'));
            calcPages();
            $('<p class="text-muted"></p>')
                    .append('<i class="fa fa-terminal" aria-hidden="true"></i> ' + Translator.__('Total pages to import: ') + ' <span id="total_pages">' + data.pages + '</span>')
                    .appendTo($('#import_logs'));
            log(Translator.__('Import init done.'));
            $("#import_limit").on("change paste keyup", function (e) {
                e.preventDefault();
                readSettings();
                calcPages();
                $("#total_pages").html(data.pages);
            });
            log(Translator.__('Start import?'));
            $("#start_import_yes").click(function (e) {
                e.preventDefault();
                startImport(data);
            });
            $('#start_import').removeClass('hide');
            $("#remove_current_data").click(function (e) {
                e.preventDefault();
                clearData();
            });
            if (data.current_data > 0) {
                $('#import_clear_data').removeClass('hide');
            }
        }
        ;
        function readData()
        {
            data.messages_source = parseInt($("#messages_source").val());
            data.messages_count = parseInt($("#messages_to_import").val());
            data.current_data = parseInt($("#current_data").val());
        }
        ;
        function readSettings()
        {
            settings.limit = parseInt($("#import_limit").val());
            settings.ajax_timeout = parseInt($("#import_ajax_timeout").val());
        }
        ;
        function log(log)
        {
            var log_string = '';
            if (log === '') {
            } else if (log === null) {
                log_string = Translator.__('Unknown log request!.');
            } else if (log.constructor === Array) {
                log_string = log.join('&#xA;') + '&#xA;';
            } else {
                log_string = log + '&#xA;';
            }
            $('<p class="text-muted"></p>').append('<i class="fa fa-terminal" aria-hidden="true"></i> ' + log_string).appendTo($('#import_logs'));
        }

        function startImport(data) {
            $('#start_import').addClass('hide');
            $('<p class="text-info"></p>')
                    .append('<i id="status_importing_icon" class="fa fa-refresh fa-spin fa-fw" aria-hidden="true"></i> ')
                    .append('<span id="status_importing">' + Translator.__('Importing... ' + '</span>'))
                    .append('<span class="text-success">' + Translator.__('Imported messages: ') + ' <span id="total_imported"> ' + data.imported + ' </span></span> ')
                    .append('<span class="text-warning">' + Translator.__(' Rejected messages: ') + '<span id="total_rejected">' + data.rejected + ' </span></span>')
                    .appendTo($('#import_logs'));
            $('#import_progress').removeClass('hide');
            $('#import_rejected').removeClass('hide');
            // call import
            messagesImport(data).done(function () {
                $("#status_importing_icon").removeClass('fa-refresh fa-spin fa-fw')
                        .addClass('fa-check-circle');
                $("#import_progress").find('.progress-bar').removeClass('progress-bar-info').addClass('progress-bar-success');
                $('#import_clear_data').removeClass('hide');
            });
        }

        function calcPages() {
            if (data.messages_count > 0) {
                data.pageSize = settings.limit;
                data.pages = Math.ceil(data.messages_count / data.pageSize);

                return;
            }
            data.pages = 0;

            return;
        }

        function messagesImport(data) {
            //console.log(data);
            var def = $.Deferred();
            def.progress(function (data) {
                $("#total_imported").html(data.imported);
                $("#total_rejected").html(data.rejected);
                $.each(data.rejected_items, function (index, item) {
                    var reason = item.reason === 0 ? Translator.__('Empty text') : Translator.__('Already exists.');
                    $(' <span class="text-muted small"></span> ')
                            .append('<i class="fa fa-hashtag text-danger" title="' + reason + '" aria-hidden="true"></i>')
                            .append('<span class="rejected_id">' + item.pn_msg_id + ' </span>')
                            .appendTo($('#import_rejected'));
                });

                var percent = 100 * data.page / data.pages;
                $("#import_progress").find('.progress-bar').css('width', percent + '%').attr('aria-valuenow', percent);
                $("#import_progress").find('.info').text('Importing ' + data.page + ' page from ' + data.pages).css('color', '#000');
            });
            data.page = 0; // first page 0-49
            calcPages(); // once again
            (function loop(data, def) {
                if (data.page < data.pages || data.pages === 0) {
                    importAjax(Routing.generate('zikulaintercommodule_import_import'), data).done(function (data) {
                        data.page++;
                        def.notify(data);
                        loop(data, def);
                    });
                } else {
                    def.resolve(data);
                }
            })(data, def);
            return def.promise();
        }

        function clearData() {
            alert(Translator.__('Only manual data removal available at the moment.'));
        }

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
        Zikula.Intercom.import.init();
    });
}
)(jQuery);
