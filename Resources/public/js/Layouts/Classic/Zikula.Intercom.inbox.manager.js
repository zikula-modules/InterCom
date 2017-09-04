/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var Zikula = Zikula || {};
Zikula.Intercom = Zikula.Intercom || {};
Zikula.Intercom.InboxManager = Zikula.Intercom.InboxManager || {};

/**
 * Inbox manager
 */

(function (manager, settings, $, undefined) {
    /*
     * manager properties 
     */
    manager.conversations = [];
    manager.selected = [];
    manager.selectAll = false;
    manager.busy = false;
    manager.request = [];
    manager.request.current = [];
    manager.request.new = [];
    manager.pager = [];
    /*
     * manager init 
     */
    manager.init = function () {
        // view as singelton init
        manager.view = view.getInstance();
        //load data true ajax false view
        manager.loadData(false);
        //console.log('Zikula.Intercom.InboxManager initiated');
    };
    /*
     * manager config  
     */
    manager.loadData = function (useAjax) {
        if (useAjax) {
        } else {
            manager.view.getDataFromView();
        }
    };
    /*
     * manager 
     */
    manager.pushSelected = function (id) {
        if (manager.selected.indexOf(id) === -1) {
            manager.selected.push(id);
        }
    };
    manager.unsetSelected = function (id) {
        if (manager.selected.indexOf(id) !== -1) {
            manager.selected.splice(manager.selected.indexOf(id), 1);
        }
    };
    manager.pushConversation = function (id) {
        if (typeof manager.conversations[id] === 'undefined') {
            manager.conversations.push(id);
        }
    };
    manager.removeConversation = function (id) {

    };
    manager.updateRequest = function () {
        manager.request.current = manager.request.new;
    };
    manager.updatePager = function (pager) {
        manager.pager.page = pager.page;
        manager.pager.total = pager.total;
    };
    manager.decodeUrl = function (url) {
        var inboxStr = url.indexOf('/inbox/');
        var paramsStr = url.substring(inboxStr + 7);
        var params = paramsStr.split('/');
        // manager.request.func = //(typeof(params[0]) !== 'undefined') ? params[0] : 'view' ;
        manager.request.new.page = (typeof (params[1]) !== 'undefined') ? params[1] : '1';
        manager.request.new.sortby = (typeof (params[2]) !== 'undefined') ? params[2] : 'send';
        manager.request.new.sortorder = (typeof (params[3]) !== 'undefined') ? params[3] : 'DESC';
        manager.request.new.limit = (typeof (params[4]) !== 'undefined') ? params[4] : settings.get('messages_perpage');
        //console.log(manager.request);
    };
    manager.generateUrl = function (request) {
        return Routing.generate('zikulaintercommodule_inbox_view', request);
    };
    manager.load = function (url) {
        manager.busy = true;
        manager.view.showBusy();
        manager.decodeUrl(url);
        $.ajax({
            type: "GET",
            dataType: "json",
            url: manager.generateUrl(manager.request.new)
        }).success(function (result) {
            manager.updateRequest();
            manager.updatePager(result.pager);
            manager.view.setConversations(result.html);
            manager.view.updateToCurrent();
            manager.busy = false;
            manager.view.hideBusy();
        }).error(function (result) {

        }).always(function () {
            manager.view.hideBusy();
        });
    };
    manager.toggleSelect = function () {
        if (manager.selected.length > 0 || manager.selectAll) {
            manager.selected.length = 0;
            manager.selectAll = false;
        } else {
            manager.selectAll = true;
        }
        manager.view.setSelected();
        manager.view.readSelected();
        manager.view.setSelectIcon();
    };

    manager.performMultiAction = function (action) {
        switch (action) {
            case 'unselect-selected':
                manager.toggleSelect();
                break;
            case 'delete-selected':
                manager.deleteSelected();
                break;
            case 'save-selected':
                manager.saveSelected();
                break;
            case 'markread-selected':
                manager.markreadSelected();
            default:
                break;
        }

    };

    //Multi
    manager.saveSelected = function () {
        manager.view.setModalTitle('Save selected messages');
        manager.view.setModalContent('<p>Click continue to save selected messages or cancel to abort.</p>');
        manager.view.addModalButton('Save', 'btn-success', 'fa fa-save', 'selected' ,manager.performMultiSave);
        manager.view.openModal();
    };
    manager.markreadSelected = function () {
        manager.view.setModalTitle('Mark selected messages');
        manager.view.setModalContent('<p>Click continue to save selected messages or cancel to abort.</p>');
        manager.view.addModalButton('Save', 'btn-success', 'fa fa-save', 'selected' ,manager.performMultiMark);
        manager.view.openModal();
    };
    manager.deleteSelected = function () {
        manager.view.setModalTitle('Delete selected messages');
        manager.view.setModalContent('<p>Click continue to save selected messages or cancel to abort.</p>');
        manager.view.addModalButton('Save', 'btn-success', 'fa fa-save', 'selected' ,manager.performMultiDelete);
        manager.view.openModal();
    };    
       
    manager.performMultiSave = function (items) {
        if (items === 'selected') {
            console.log('save selected');
        } else {
            console.log('save' + items);
        }
    };
    manager.performMultiMark = function (items) {
        if (items === 'selected') {
            console.log('save selected');
        } else {
            console.log('save' + items);
        }
    };
    manager.performMultiDelete = function (items) {
        if (items === 'selected') {
            console.log('save selected');
        } else {
            console.log('save' + items);
        }
    };   
    //Conversation
    manager.saveConversation = function (id) {
        manager.view.setModalTitle('Save selected messages');
        manager.view.setModalContent('<p>Click continue to save selected messages or cancel to abort.</p>');
        manager.view.addModalButton('Save', 'btn-success', 'fa fa-save', id ,manager.performSaveConversation);
        manager.view.openModal();
    };
    manager.markreadConversation = function (id) {
        manager.performMarkreadConversation(id);
    };
    manager.deleteConversation = function (id) {
        manager.view.setModalTitle('Delete conversation?');
        manager.view.setModalContent('<p>Click continue to delete conversation or cancel to abort.</p>');
        manager.view.addModalButton('Delete', ' btn-warning', 'fa fa-trash', id, manager.performDeleteConversation);
        manager.view.openModal();
    };    
    manager.forwardConversation = function (id) {
        manager.view.setModalTitle('Forward conversation');
        manager.view.setModalContent('<p>Click continue to save selected messages or cancel to abort.</p>');
        manager.view.addModalButton('Save', 'btn-success', 'fa fa-save', id ,manager.performForwardConversation);
        manager.view.openModal();
    };    
    manager.printConversation = function (id) {
        manager.view.setModalTitle('Print conversation');
        manager.view.setModalContent('<p>Click continue to save selected messages or cancel to abort.</p>');
        manager.view.addModalButton('Save', 'btn-success', 'fa fa-save', id ,manager.performPrintConversation);
        manager.view.openModal();
    };
    manager.sendReply = function (id) {
        manager.performSendReply(id);
    };    
    manager.cancelReply = function (id) {
        manager.performCancelReply(id);
    };
    
    manager.performSaveConversation = function (id) {
        console.log('save ' + id);
    };
    manager.performMarkreadConversation = function (id) {
        console.log('mark ' + id);
    };
    manager.performDeleteConversation = function (id) {
        console.log('delete ' + id);
    };
    manager.performForwardConversation = function (id) {
        console.log('forward ' + id);
    };   
    manager.performPrintConversation = function (id) {
        console.log('print ' + id);
    };    
    manager.performSendReply= function (id) {
        console.log('reply ' + id);
    };    
    manager.performCancelReply = function (id) {
        console.log('cancel ' + id);
    };
    /*
     * manager.view
     */
    var view = (function () {

        // Instance stores a reference to the Singleton
        var instance;
        function Init() {
            /*
             * manager.view properties
             */
            var $manager = $('#intercom_inbox');
            var $modal = $('#intercom_module_modal');
            $modal.on('hidden.bs.modal', function () {
                closeModal();
            });
            /*
             * manager.view init
             */
            //start listening for actions
            bindViewEvents();
            //console.log('Zikula.Intercom.InboxManager.view initialised');
            /*
             * manager.view functions 
             */
            function bindViewEvents() {
                /* bind header events */
                $manager.find('a.header-action').each(function () {
                    $(this).on('click', function (e) {
                        e.preventDefault();
                        if (manager.busy) {
                            return false;
                        }
                        var url = $(this).attr('href');
                        manager.load(url);
                    });
                });
                $manager.find('.multi-actions button').each(function () {
                    $(this).on('click', function (e) {
                        e.preventDefault();
                        if (manager.busy) {
                            return false;
                        }
                        manager.performMultiAction($(this).data('action'));
                    });
                });
                $manager.find('.multi-toggle-select').each(function () {
                    $(this).on('click', function (e) {
                        e.preventDefault();
                        if (manager.busy) {
                            return false;
                        }
                        manager.toggleSelect();
                    });
                });
                /* bind pager events */
                $manager.find('.pager-actions a').each(function () {
                    $(this).on('click', function (e) {
                        e.preventDefault();
                        if (manager.busy) {
                            return false;
                        }
                        var url = $(this).attr('href');
                        manager.load(url);
                    });
                });
                bindContentEvents();
                //console.log('Zikula.Languages.Manager.view events binded');
            }

            function bindContentEvents() {
                /* bind conversation details click */
                $manager.find('.conversation-details').each(function () {
                    $(this).on('click', function (e) {
                        if ($(this).data('seen') === false) {
                            manager.markreadConversation($(this).data('id'));
                        }
                    });
                });
                /* bind conversation select */
                $manager.find('input.conversation-select').each(function () {
                    $(this).on('click', function (e) {
                        manager.selectAll = false;
                        setSelectIcon();
                        if ($(this).is(':checked')) {
                            manager.pushSelected($(this).data('id'));
                        } else {
                            manager.unsetSelected($(this).data('id'));
                        }
                        showSelected();
                    });
                });

                /* bind conversation options */
                $manager.find('.delete-conversation').each(function () {
                    $(this).on('click', function (e) {
                        manager.deleteConversation($(this).data('id'));
                    });
                });
                /* bind conversation options */
                $manager.find('.save-conversation').each(function () {
                    $(this).on('click', function (e) {
                        manager.saveConversation($(this).data('id'));
                    });
                });
                /* bind conversation options */
                $manager.find('.forward-conversation').each(function () {
                    $(this).on('click', function (e) {
                        manager.forwardConversation($(this).data('id'));
                    });
                });
                /* bind conversation options */
                $manager.find('.print-conversation').each(function () {
                    $(this).on('click', function (e) {
                        manager.printConversation($(this).data('id'));
                    });
                });
                /* bind conversation options */
                $manager.find('.send-reply').each(function () {
                    $(this).on('click', function (e) {
                        manager.sendReply($(this).data('id'));
                    });
                });
                /* bind conversation options */
                $manager.find('.cancel-reply').each(function () {
                    $(this).on('click', function (e) {
                        manager.cancelReply($(this).data('id'));
                    });
                });
            }


            /*
             * manager.view functions 
             * Data
             */
            function getDataFromView() {
                readConversations();
                readSelected();
            }
            /*
             * Read conversations from view
             */
            function readConversations() {
                var $conversations_holder = $manager.find('#conversations');
                $conversations_holder.find("div.conversation").each(function () {
                    manager.pushConversation($(this).data('id'));
                });
                //console.log('Zikula.Intercom.InboxManager fetched conversations');
            }
            /*
             * Read selected from view
             */
            function readSelected() {
                var $conversations_holder = $manager.find('#conversations');
                $conversations_holder.find(".conversation-select:checked").each(function () {
                    manager.pushSelected($(this).data('id'));
                });
                //console.log('Zikula.Intercom.InboxManager fetched selected');
                showSelected();
            }
            /*
             * set selected 
             */
            function setSelected() {
                var $conversations_holder = $manager.find('#conversations');
                $conversations_holder.find(".conversation-select").each(function () {
                    if (manager.selected.indexOf($(this).data('id')) !== -1 || manager.selectAll) {
                        $(this).prop("checked", true);
                    } else {
                        $(this).prop("checked", false);
                    }
                });
                //console.log('Zikula.Intercom.InboxManager fetched selected');
                showSelected();
            }
            /*
             * show selected box
             */
            function showSelected() {
                var $selected = $manager.find('div.list-selected');
                $selected.find('.selected-messages-count').html(manager.selected.length);
                if (manager.selected.length > 0) {
                    $selected.removeClass('hide');
                } else {
                    $selected.addClass('hide');
                }
            }
            function setSelectIcon() {
                var $select = $manager.find('.multi-toggle-select');
                if (manager.selectAll) {
                    $select.find('i').removeClass('fa-check-square').addClass('fa-square');
                } else {
                    $select.find('i').removeClass('fa-square').addClass('fa-check-square');
                }
            }
            //modal
            function setConversations(html) {
                $manager.find('#conversations').html(html);
                manager.loadData(false);
                //start listening for actions
                bindContentEvents();
            }
            //update header and pager url's and icons
            function updateToCurrent() {
                //current pager and request data           
                var filter = {page: +manager.request.current.page,
                    sortby: manager.request.current.sortby,
                    sortorder: manager.request.current.sortorder,
                    limit: +manager.request.current.limit};
                var pager = {page: +manager.pager.page,
                    total: +manager.pager.total};
                //sender 
                //subject
                var $subject = $manager.find('a.orderby-subject');
                var subjectRequest = $.extend({}, filter);
                if (filter.sortby === 'subject') {
                    subjectRequest.sortorder = (subjectRequest.sortorder === 'ASC') ? 'DESC' : 'ASC';
                    var $icon = (subjectRequest.sortorder === 'ASC') ? $('<i class="fa fa-sort-asc"> </i>') : $('<i class="fa fa-sort-desc"> </i>');
                    $subject.html($icon);
                } else {
                    $subject.html('<i class="fa fa-sort"> </i>');
                }
                subjectRequest.sortby = 'subject';
                $subject.attr("href", manager.generateUrl(subjectRequest));
                //send
                var $send = $manager.find('a.orderby-send');
                var sendRequest = $.extend({}, filter);
                if (filter.sortby === 'send') {
                    sendRequest.sortorder = (sendRequest.sortorder === 'ASC') ? 'DESC' : 'ASC';
                    var $iconSend = (sendRequest.sortorder === 'ASC') ? $('<i class="fa fa-sort-asc"> </i>') : $('<i class="fa fa-sort-desc"> </i>');
                    $send.html($iconSend);
                } else {
                    $send.html('<i class="fa fa-sort"> </i>');
                }
                sendRequest.sortby = 'send';
                $send.attr("href", manager.generateUrl(sendRequest));
                //pager
                var $pager = $manager.find('ul.pager-actions');
                // remove pager if 
                if (pager.total <= 1) {
                    $pager.html('');
                    return;
                }
                //first page   
                var iscurrent = (pager.page === 1) ? ' class="disabled"' : '';
                var pageRequest = $.extend({}, filter);
                pageRequest.page = 1;
                $pager.html('<li' + iscurrent + '><a href=' + manager.generateUrl(pageRequest) + ' >«</a></li>');
                //pages
                for (var n = 1; n < pager.total + 1; ++n) {
                    iscurrent = (pager.page === n) ? ' class="active page-active"' : '';
                    var pageRequest = $.extend({}, filter);
                    pageRequest.page = n;
                    $pager.append('<li' + iscurrent + '><a href=' + manager.generateUrl(pageRequest) + ' >' + n + '</a></li>');
                }
                //lastpage           
                var iscurrent = (pager.page === pager.total) ? ' class="disabled"' : '';
                var pageRequest = $.extend({}, filter);
                pageRequest.page = pager.total;
                $pager.append('<li' + iscurrent + '><a href=' + manager.generateUrl(pageRequest) + ' >»</a></li>');
                /* bind pager events */
                $manager.find('.pager-actions a').each(function () {
                    $(this).on('click', function (e) {
                        e.preventDefault();
                        if (manager.busy) {
                            return false;
                        }
                        var url = $(this).attr('href');
                        manager.load(url);
                    });
                });

                setSelected();
            }

            //modal
            function openModal() {
                $modal.modal('show');
            }
            function closeModal() {
                $modal.modal('hide');
                setModalTitle('');
                setModalContent('');
                setModalFooter('');
            }
            function setModalTitle(html) {
                $modal.find('.modal-title').html(html);
            }
            function setModalContent(html) {
                $modal.find('.modal-body').html(html);
            }
            function setModalFooter(html) {
                $modal.find('.modal-footer').html(html);
            }
            function addModalButton(text, cssClass, icon, callbackOptions, callback) {
                var $icon = (icon === '') ? '' : '<i class="' + icon + '"> </i>';
                var $button = $('<button type="button" class="btn ' + cssClass + 'hide" data-dismiss="modal">' + $icon + ' ' + text + '</button>');
                $modal.find('.modal-footer').append($button);
                $button.click(function () {
                    if (typeof callback === "function") {
                        callback(callbackOptions);
                    }
                });
                $button.removeClass('hide');
            }
            //overlay
            function getOverlay() {
                return $("<div id='overlay'><i class='fa fa-circle-o-notch fa-spin fa-5x'></i></div>");
            }
            function removeOverlay() {
                $('#overlay').remove();
            }
            //busy
            function showBusy() {
                $manager.find('#conversations').prepend(getOverlay());
            }
            function hideBusy() {
                $('#overlay').remove();
            }
            //errors
            function displayError(html) {

            }
            ;
            /*
             * manager.view public
             */
            return {
                openModal: openModal,
                closeModal: closeModal,
                setModalTitle: setModalTitle,
                setModalContent: setModalContent,
                setModalFooter: setModalFooter,
                addModalButton: addModalButton,
                getDataFromView: getDataFromView,
                showBusy: showBusy,
                hideBusy: hideBusy,
                displayError: displayError,
                setConversations: setConversations,
                updateToCurrent: updateToCurrent,
                setSelected: setSelected,
                readSelected: readSelected,
                setSelectIcon: setSelectIcon
            };
        }
        ;
        return {
            // Get the Singleton instance if one exists
            // or create one if it doesn't
            getInstance: function () {
                if (!instance) {
                    instance = Init();
                }
                return instance;
            }
        };
    })();
}(Zikula.Intercom.InboxManager, Zikula.Intercom.settings, jQuery));