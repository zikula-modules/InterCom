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
    /*
     * manager functions  
     * 
     */
    manager.init = function () {
        // view as singelton init
        manager.view = view.getInstance();  	
        //load data true ajax false view
        loadData(false);
        console.log('Zikula.Intercom.InboxManager initiated');
        
        console.log($(this));
    };


    /*
     * manager config  
     * 
     */
    function loadData(useAjax) {

        if (useAjax) {
            //console.log('load data using ajax');   		
        } else {
            //console.log('load data from view');
            manager.view.getDataFromView();
        }
        console.log('Zikula.Intercom.InboxManager data loaded');
        console.log(manager);
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
            var $manager = $('div#intercom_inbox');
            //var manager.selected = [];
            var $conversations_holder = $manager.find('div#conversations.conversations-list');
            
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
                /* bind conversation list header events */
                $('a.conversation-details').each(function () {
                    console.log($(this));
                    $(this).on('click', function (e) {
                        //e.preventDefault();
                        //manager.editLanguage(false);
                        console.log($(this));
                    });
                });
                /* bind add language event 
                $manager.find('a.edit_language').each(function () {
                    $(this).on('click', function (e) {
                        e.preventDefault();
                        manager.editLanguage($(this).data('languagecode'));
                    });
                });*/
                /* bind add language event 
                $manager.find('a.remove_language').each(function () {
                    $(this).on('click', function (e) {
                        e.preventDefault();
                        manager.removetLanguage($(this).data('languagecode'));
                    });
                });
              */
                console.log('Zikula.Languages.Manager.view events binded');
            }

            /*
             * manager.view functions 
             * Data
             */
            function getDataFromView() {
                
                console.log($manager);
                
                $( "li.active" ).css( "border", "13px solid red" );
                manager.page = $manager.find('li.active.page-active').data('page');
                $conversations_holder.find("div.conversations").each( function () {
                  manager.conversations.push('11');
                  console.log($(this));
                });
                console.log('Zikula.Languages.Manager.view data loaded 2');
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
                //return $("<div id='overlay'><i class='fa fa-circle-o-notch fa-spin'></i></div>");
            }
            function removeOverlay() {
                //$('#overlay').remove();
            }

            // busy
            function showBusy() {
                //$('#kmgallery_manager').append(getOverlay());
            }
            function hideBusy() {
                //$('#overlay').remove();
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
                displayError: displayError
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
    
    $(document).ready(manager.init());
    
}(Zikula.Intercom.InboxManager,Zikula.Intercom.settings, jQuery));