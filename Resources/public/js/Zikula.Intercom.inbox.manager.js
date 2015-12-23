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

(function(manager, settings , $, undefined) {
    /*
     * manager properties 
     * 
     */
    //Private Property

    //Public Property
    //manager.core = false;
      manager.page = 1;
      manager.pages = 10;
      manager.conversations = [];
      manager.selected = [];
      manager.busy = false;
      
    /*
     * manager functions  
     * 
     */
    manager.init = function () {
        // view as singelton init
        manager.view = view.getInstance();  	
        //load data true ajax false view
        manager.loadData(false);
        console.log('Zikula.Intercom.InboxManager initiated');
        console.log(manager);
    };


    /*
     * manager config  
     * 
     */
    manager.loadData = function(useAjax) {

        if (useAjax) {
            //console.log('load data using ajax');   		
        } else {
            //console.log('load data from view');
            manager.view.getDataFromView();
        }
    };

    manager.reload = function(url) { 
        
        manager.busy = true;
        manager.view.showBusy();
        $.ajax({
            type: "GET",
            url: url
        }).success(function (result) {
            var html = result;
            manager.conversations = [];
            manager.view.setConversations(html);
            manager.busy = false;
            manager.view.hideBusy();
        }).error(function (result) {
            //manager.view.displayError( result.status + ': ' + result.statusText);
             //manager.view.openModal();
        }).always(function () {
            //manager.view.hideBusy();           
        });
        
        
    };
    
    manager.checkAll = function() {      
        
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
            //container
            var $manager = $('#intercom_inbox');
            //var manager.selected = [];
            var $conversations_holder = $manager.find('#conversations');
            
            console.log($conversations_holder);
            //modal
            //var $modal = $('#intercom_inbox_modal');
            /*
             * manager.view init
             */
            //start listening for actions
            bindViewEvents();
                       
            console.log('Zikula.Intercom.InboxManager.view initialised');
                      
            /*
             * manager.view functions 
             */
            function bindViewEvents() {
                /* bind pager events */
                $manager.find('.pager-actions a').each(function () {
                    //console.log($(this));
                    $(this).on('click', function (e) {
                        e.preventDefault();
                        if(manager.busy){
                          return false;  
                        }
                        //manager.editLanguage(false);
                        var path = $(this).attr('href');
                        manager.reload(path.replace("view", "conversations"));
                    });
                });
                
                /* bind conversation list header events */
                $manager.find('a.conversation-details').each(function () {
                    $(this).on('click', function (e) {
                        //e.preventDefault();
                        //manager.editLanguage(false);
                        console.log($(this));
                    });
                });
                /* bind check all action
                $manager.find('a.edit_language').each(function () {
                    $(this).on('click', function (e) {
                        e.preventDefault();
                        manager.editLanguage($(this).data('languagecode'));
                    });
                });
                /* bind add language event */
                $manager.find('.checkall').each(function () {
                    $(this).on('click', function (e) {
                        e.preventDefault();
                        manager.checkAll();
                    });
                });
              
                console.log('Zikula.Languages.Manager.view events binded');
            }

            /*
             * manager.view functions 
             * Data
             */
            function getDataFromView() {
                
                manager.page = $manager.find('.page-active').data('page');
                //console.log($conversations_holder);
                $conversations_holder.find("div.conversation").each( function () {
                  manager.conversations.push($(this).data('id'));
                });            
                console.log(manager);
                console.log('Zikula.Intercom.InboxManager data loaded');
            }

            //modal
            function setConversations(html) {
                $conversations_holder.html( html );
                manager.loadData(false);
                //start listening for actions
                bindViewEvents();
            }
            //modal
            function openModal() {
                $modal.modal('show');
            }
            function closeModal() {
                $modal.modal('hide');
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
            function setModalButtonSave(language_code) {
                var $buttonSave = $modal.find("button.save");
                $buttonSave.click(function (e) {
                    e.preventDefault();
                    manager.saveLanguage(language_code);
                });
                $buttonSave.removeClass('hide');
            }
            
            //overlay
            function getOverlay() {
                return $("<div id='overlay'><i class='fa fa-circle-o-notch fa-spin fa-5x'></i></div>");
            }
            function removeOverlay() {
                $('#overlay').remove();
            }

            // busy
            function showBusy() {
                $conversations_holder.prepend(getOverlay());
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
             * 
             */
            return {
                openModal: openModal,
                closeModal: closeModal,
                setModalTitle: setModalTitle,
                setModalContent: setModalContent,
                setModalFooter: setModalFooter,
                setModalButtonSave: setModalButtonSave,  
                getDataFromView: getDataFromView,
                showBusy: showBusy,
                hideBusy: hideBusy,
                displayError: displayError,
                setConversations: setConversations
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
    
}(Zikula.Intercom.InboxManager,Zikula.Intercom.settings, jQuery));