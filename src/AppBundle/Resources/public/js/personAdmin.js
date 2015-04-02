setTimeout(function() {
    $('.alert.alert-success').fadeOut(1000);
    $('.alert.alert-warning').fadeOut(1000);
}, 3000);

var table = $('#personList').DataTable({
    "bPaginate": false,
    "ajax": {
        "url": Routing.generate('person_get_all_admin_datatable'),
        "type": "GET",
        "dataSrc": ""
    },
    "columns": [
        { "data": "name" },
        { "data": "emails" },
        { "data": "phones" },
        { "data": "addresses" },
        { "data": "createdAt" },
        { "data": "updatedAt" },
        { "data": "operations" }
    ],
    "columnDefs": [
        {
            "render": function (data, type, row) {
                return '<a href="' + Routing.generate('person_edit', {'id': data }) + '"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a> <span class="glyphicon glyphicon-remove deletePerson" data-id="' + data + '" data-name="' + row.name + '" aria-hidden="true"></span>';
            },
            "targets": 6
        }
    ],
    "fnInitComplete": function(oSettings, json) {
        $('.deletePerson').click(function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            var name = $(this).data('name');
            var dialogHTML = '<div id="entity-delete-confirm-dialog" title="Delete ' + name + '"><p><span class="ui-icon ui-icon-alert" style="float: left; margin: 0 7px 20px 0;"></span>Are you sure you want to delete ' + name + '?</p></div>';

            $(dialogHTML).dialog({
                modal: true,
                resizable: false,
                buttons: [
                    {
                        text: 'Delete',
                        click: function () {
                            $(this).dialog('close');
                            $(this).remove();

                            $.ajax({
                                url: Routing.generate('person_delete', { id: id }),
                                type: 'DELETE',
                                success: function(result) {
                                    table.ajax.reload();
                                }
                            });
                        }
                    },
                    {
                        text: 'Cancel',
                        click: function () {
                            $(this).dialog('close');
                            $(this).remove();
                        }
                    }
                ],
                open: function() {
                $('.ui-dialog-titlebar-close').addClass('ui-button ui-widget ui-state-default ui-corner-all ui-button-icon-only');
                $('.ui-dialog-titlebar-close').append('<span class="ui-button-icon-primary ui-icon ui-icon-closethick"></span><span class="ui-button-text">close</span>');
                    $(this).parents('.ui-dialog').attr('tabindex', -1)[0].focus();
            }
            });
        });

    }
});
