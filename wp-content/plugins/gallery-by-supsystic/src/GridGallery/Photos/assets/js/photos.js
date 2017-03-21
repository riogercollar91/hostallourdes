/*global jQuery*/
(function (app, $, ajaxurl) {

    var observer = app.Common.CheckboxObserver();
    var toolbar = app.Ui.Toolbar('#photo-toolbar');

    var Dialog = function (id) {
        this.id = id;
        this.config = {
            modal:    true,
            autoOpen: false,
            width:    350,
            buttons:  {}
        };
    };

    Dialog.prototype.button = function (name, func) {
        this.config.buttons[name] = func;
        return this;
    };

    Dialog.prototype.init = function () {
        if($(this.id).length) {
            $(this.id).dialog(this.config);
        }
    };

    var Controller = function () {
        this.$container = $('[data-container]');
        this.viewType = this.$container.data('container');
    };

    Controller.prototype._initCheckboxes = function () {
        if (this.viewType !== 'block') {
            return false;
        }

        var $entities = $('[data-entity]');
        var $checkboxes = $entities.find('[data-observable]');

        $entities.refresh().hover(function () {
            $(this).find('.gg-check').addClass('hover');
        }, function () {
            if (!$(this).find('[data-observable]').is(':checked')) {
                $(this).find('.gg-check').removeClass('hover');
            }
        });

        $checkboxes.refresh().on('click', function () {
            var _class = 'gg-checked';
            $.each($checkboxes.refresh(), function (index, checkbox) {
                var $container = $(checkbox).parents('.gg-item');
                $(checkbox).is(':checked') ? $container.addClass(_class) : $container.removeClass(_class);
            });
        });

        return true;
    };

    Controller.prototype.initImportDialog = function () {
        if($('#importDialog').length) {
            $dialog = $('#importDialog').dialog({
                autoOpen: false,
                modal:    true,
                width:    570,
                buttons:  {
                    Cancel: function () {
                        $(this).dialog('close');
                    }
                }
            });
            $('#importDialog').on('click', '#gg-btn-upload', function(event) {
                $dialog.dialog('close');
            });
        }

        Controller.prototype.openImportDialog = function () {
            $('#importDialog').dialog('open');
        };
    };

    Controller.prototype._initColorbox = function () {
        $('[data-colorbox]').colorbox({
            rel:         'grid-gallery',
            fixed:       true,
            maxHeight:   '90%',
            innerHeight: '90%',
            scrolling:   false
        });

        $('#colorbox, #cboxOverlay').css({
            zIndex: 100000
        });
    };

    Controller.prototype.initEffectsDialog = function () {
        $('#effectDialog').dialog({
            autoOpen: false,
            modal:    true,
            width:    740,
            buttons:  {
                Cancel: function () {
                    $(this).dialog('close');
                }
            }
        });
    };

    Controller.prototype.openEffectsDialog = function(attachmentId) {
        $('#effectDialog').dialog('open').data('imgCaptionAttachmentId', attachmentId);
    };

    Controller.prototype._initDialogs = function () {
        var GalleryDialog;
        var FolderDialog;
        var RenameDialog;
        var SelectDialog;

        var controller = this;

        GalleryDialog = new Dialog('#newGalleryDialog');
        GalleryDialog.button('Create', createNewGallery);
        GalleryDialog.button('Cancel', closeDialog);
        GalleryDialog.init();

        FolderDialog = new Dialog('#newFolderDialog');
        FolderDialog.button('Create', createNewFolder);
        FolderDialog.button('Cancel', closeDialog);
        FolderDialog.init();

        RenameDialog = new Dialog('#renameFolderDialog');
        RenameDialog.button('Rename', renameFolder);
        RenameDialog.button('Cancel', closeDialog);
        RenameDialog.init();

        SelectDialog = new Dialog('#selectGalleryDialog');
        SelectDialog.button('Add', addToGallery);
        SelectDialog.button('Cancel', closeDialog);
        SelectDialog.config.open = onSelectDialogOpen;
        SelectDialog.config.close = onSelectDialogClose;
        SelectDialog.init();

        this.initImportDialog();
        this.initEffectsDialog();

        //Open import dialog
        $('.import-to-gallery').on('click', function() {
            controller.openImportDialog();
        });

        //Open caption effects dialog
        $('.selectCaptionEffectBtn').on('click', function() {
            var attachmentId = $(this).attr('data-id');
            controller.openEffectsDialog(attachmentId);
        });

        $('.grid-gallery-caption').on('click', function() {
            var attachmentId = parseInt($(this).parents('#effectDialog').data('imgCaptionAttachmentId'));
            if(attachmentId) {
                $('.captionEffectVal[data-id="'+attachmentId+'"]').attr('value', $(this).attr('data-grid-gallery-type'));
                $("form.photo-editor.attachment-"+attachmentId+"").trigger('submit');
                $('#effectDialog').dialog('close').data('imgCaptionAttachmentId', 0);
            }
        });

        function addToGallery() {
            var $list = $('#galleryList');
            var post = app.Ajax.Post({
                module: 'galleries',
                action: 'attach'
            });

            var resources = [];
            $.each($('[data-observable]:checked'), function (index, checkbox) {
                var $entity = app.Common.getParentEntity($(checkbox));
                resources.push({ type: $entity.data('entity-type'), id: $entity.data('entity-id') })
            });

            app.Loader.show();

            post.add('gallery_id', $list.find(':selected').val());
            post.add('resources', resources);
            post.send($.proxy(function (response, request) {
                if (response.message) {
                    $.jGrowl(response.message);
                }

                this.dialog('close');
                app.Loader.hide();
            }, $(this)));
        }

        function closeDialog() {
            $(this).dialog('close');
        }

        function renameFolder() {
            var post = app.Ajax.Post({
                module: 'photos',
                action: 'updateTitle'
            });

            var $folder = app.Common.getParentEntity(observer.getChecked());
            var $form = $('#renameFolderForm');

            post.add('folder_name', $form.find('input#folderName').val());
            post.add('folder_id', $folder.data('entity-id'));

            post.send($.proxy(function (response, request) {
                if (!response.error) {
                    $folder.find('span.folder-title').text(request.folder_name);
                }

                if (response.message) {
                    $.jGrowl(response.message);
                }

                this.dialog('close');
            }, $(this)));
        }

        function createNewFolder() {
            var $container = $('[data-container]');
            var post = app.Ajax.Post({
                module: 'photos',
                action: 'addFolder'
            });

            post.add('folder_name', $('#folder-name').val());
            post.add('view_type', $container.data('container'));
            post.send($.proxy(function (response) {
                if (!response.error) {
                    $container.prepend(response.folder);
                    controller.init();
                    observer.reinit();
                }

                this.dialog('close');
            }, $(this)));
        }

        function createNewGallery() {
            var $form = $('#newGalleryForm');
            var post = app.Ajax.Post({
                module: 'galleries',
                action: 'create'
            });

            post.add('title', $form.find('input#galleryTitle').val());
            post.send($.proxy(function (gallery) {
                if (!gallery.error) {
                    var request = app.Ajax.Post({
                        module: 'galleries',
                        action: 'attach'
                    });

                    app.Loader.show();

                    var resources = [];
                    $.each($('[data-observable]:checked'), function (index, checkbox) {
                        var $entity = app.Common.getParentEntity($(checkbox));
                        resources.push({
                            id:   $entity.data('entity-id'),
                            type: $entity.data('entity-type')
                        })
                    });

                    request.add('gallery_id', gallery.id);
                    request.add('resources', resources);

                    request.send(function (response, request) {
                        $.jGrowl(response.message)
                    });

                    $.jGrowl(gallery.message);

                    this.dialog('close');
                    app.Loader.hide();
                }
            }, $(this)));
        }

        function onSelectDialogClose() {
            var $loader = $('#galleryLoading');
            var $form = $('#galleryForm');
            var $list = $('#galleryList');

            $list.html('');
            $loader.show();
            $form.hide();
        }

        function onSelectDialogOpen() {
            var post = app.Ajax.Post({
                module: 'galleries',
                action: 'list'
            });

            post.send(function (response) {
                if (response.galleries) {
                    var $list = $('#galleryList');
                    var $loader = $('#galleryLoading');
                    var $form = $('#galleryForm');

                    $.each(response.galleries, function (index, gallery) {
                        $list.append('<option value="' + gallery.id + '">' + gallery.title + '</option>');
                    });

                    $loader.hide();
                    $form.show();
                }
            });
        }
    };

    Controller.prototype.changeTab = function (event) {
        event.preventDefault();

        var $currentTarget = $(event.currentTarget),
            target = $currentTarget.attr('href'),
            tabcontent = $currentTarget.parent().data('tabcontent'),
            $current = $(target),
            $inputs  = $(tabcontent);

        $currentTarget.parent()
            .find('a')
            .removeClass('active');

        $currentTarget.addClass('active');

        $inputs.hide();
        $current.show();
    };

    Controller.prototype.init = function () {
        // Initialize checkboxes hover effects on the "block view" pages.
        this._initCheckboxes();
        // Initialize colorbox plugin.
        this._initColorbox();
        // Initialize dialogs.
        this._initDialogs();

        $('.add-new-h2')
            .on('click', this.changeTab);

        $('form.photo-editor').submit(function (event) {
            event.preventDefault();

            $.post(ajaxurl, $(this).serialize(), function (response) {
                $.jGrowl('Information updated.');
            });
        });

        $('form.photo-editor input').on('keyup', function () {
            var $this = $(this);
            clearTimeout($this.data('timer'));
            $this.data('timer', setTimeout(function(){
                $this.removeData('timer');
                $this.parents('form.photo-editor').trigger('submit');
            }, 1500));
        });

        // $('form.photo-editor input:checkbox').on('change', function () {
        //     $(this).parents('form.photo-editor').trigger('submit');
        // });

        $('form.photo-editor input:checkbox').on('ifToggled', function(event) {
            $(this).parents('form.photo-editor').trigger('submit');
        });

        observer.reinit();

        if($('[data-sortable]').length) {
            $('[data-sortable]').sortable({
                start: function (event, ui) {
                    ui.item.draggable();
                },
                stop:  function (event, ui) {
                    ui.item.find('.ui-draggable').css({ zIndex: 1000 });

                    if ($('[data-container]').data('container') === 'block') {
                        ui.item.addClass('animated swing');
                    }

                    ui.item.draggable('destroy');
                }
            });

            $('[data-droppable]').droppable({
                hoverClass: 'folder-highlight',
                drop: function (event, ui) {

                    event.preventDefault();

                    var move = function ($entity, $folder) {

                        this.message = 'Unable to move photo to the selected folder';
                        this.request = app.Ajax.Post({
                            module: 'photos',
                            action: 'move'
                        });

                        this.request.add('folder_id', $folder.data('entity-id'));
                        this.request.add('photo_id', $entity.data('entity-id'));

                        this.request.send($.proxy(function (response) {
                            if (response.error) {
                                $.jGrowl(this.message);
                                return false;
                            }

                            var $counter = $folder.find('.gg-folder-photos-num');

                            $counter.text(function () {
                                return parseInt($counter.text(), 10) + 1;
                            });

                            $entity.remove();

                            return true;

                        }, this));

                    };

                    var
                    // dragged photo
                        $entity = ui.draggable,

                    // folder
                        $folder = $(this),

                    // photos container
                        $container = $entity.parents('[data-container]'),

                    // an array of the selected photos.
                        photos = [];

                    if ($('[data-observable]:checked').length > 0) {
                        $.each($('[data-observable]:checked'), function (index, checkbox) {
                            move(
                                app.Common.getParentEntity(checkbox),
                                $folder
                            );
                        });
                    } else {
                        move($entity, $folder);
                    }
                }
            });
        }
    };

    app.Controller = app.Controller || {};
    app.Controller.Photos = function () {
        return new Controller();
    }

}(window.SupsysticGallery = window.SupsysticGallery || {}, jQuery, ajaxurl));

jQuery(document).ready(function () {
    SupsysticGallery.Controller.Photos().init();

    jQuery('[data-upload]').ggPhotoUploader({
        wp: window.wp = window.wp || 'undefined'
    });
});
