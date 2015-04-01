setTimeout(function() {
    $('.alert.alert-success').fadeOut(1000);
    $('.alert.alert-warning').fadeOut(1000);
}, 3000);

var table = $('#personList').DataTable({
    "lengthChange": false,
    "ajax": {
        "url": Routing.generate('person_get_all_datatable'),
        "type": "GET",
        "dataSrc": ""
    },
    "columns": [
        { "data": "name" },
        { "data": "emails" },
        { "data": "phones" },
        { "data": "addresses" },
        { "data": "createdAt" }
    ],
    "fnInitComplete": function(oSettings, json) {
        $('#personList tr').click(function (e) {
            e.preventDefault();
            var id = $(this).attr('id');

            $.get(Routing.generate("person_get", {'id': id}))
                .done(function(data) {
                    var name = data.name;
                    var emails = data.emails ? data.emails.join('<br />') : '-';
                    var phones = data.phones ? data.phones.join('<br />') : '-';
                    var addresses = data.addresses ? data.addresses.join('<br />') : '-';

                    var dialogHTML = '<div id="entity-dialog" title="' + name + '">' +
                        '<table class="table table-bordered table-hover">' +
                        '<tr><th class="span2">Name</th>' +
                        '<td class="span3">' + name + '</td></tr>' +
                        '<tr><th class="span2">E-mails</th>' +
                        '<td class="span3">' + emails + '</td></tr>' +
                        '<tr><th class="span2">Phones</th>' +
                        '<td class="span3">' + phones + '</td></tr>' +
                        '<tr><th class="span2">Addresses</th>' +
                        '<td class="span3">' + addresses + '</td></tr>' +
                        '</table>';

                    $(dialogHTML).dialog({
                        modal: true,
                        resizable: false,
                        width: 600,
                        open: function() {
                            $('.ui-dialog-titlebar-close').addClass('ui-button ui-widget ui-state-default ui-corner-all ui-button-icon-only');
                            $('.ui-dialog-titlebar-close').append('<span class="ui-button-icon-primary ui-icon ui-icon-closethick"></span><span class="ui-button-text">close</span>');
                        }
                    });
                });
        });

    }
});
