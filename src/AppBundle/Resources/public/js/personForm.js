var types = {
    'email': 'appbundle_person_emails',
    'phone': 'appbundle_person_phones',
    'address': 'appbundle_person_addresses'
};

var errorDiv = '<span class="help-block" style="display: none"><span class="glyphicon glyphicon-exclamation-sign red"></span> <span class="errorMessage red"></span></span>';

function toggleError(input) {
    var text = 'Please provide a value!';
    var val = input.val();
    var type = input.attr('element');

    if (val) {
        input.parent().find('.help-block').remove();
        if (type == 'email') {
            if (!validateEmail(val)) {
                text = 'Please provide a valid e-mail address!';
                var eDiv = $(errorDiv);
                eDiv.find('.errorMessage').text(text);
                input.after(eDiv);
                eDiv.show();

                return false;
            }
        }

        return true;
    }
    else {
        input.parent().find('.help-block').remove();
        var eDiv = $(errorDiv);
        eDiv.find('.errorMessage').text(text);
        input.after(eDiv);
        eDiv.show();

        return false;
    }
}

$(document).ready(function() {
    var nameElement = $('#appbundle_person_name');
    nameElement.attr('element', 'name');
    nameElement.parent().wrap('<fieldset class="well the-fieldset"></fieldset>');
    var valid = true;

    $(document).submit(function(e){
        e.preventDefault();

        var inputs = $('input[type="text"]');
        inputs.each(function() {
            var status = toggleError($(this));
            if (!status) {
                valid = false;
            }
        });

        if (valid) {
            var ret = {
                'name': '',
                'email': [],
                'address': [],
                'phone': []
            };

            inputs.each(function() {
                var type = $(this).attr('element');
                var val = $(this).val();

                if (type == 'name') {
                    ret.name = val;
                }
                else {
                    ret[type].push(val);
                }
            });

            $.post(Routing.generate("person_create"), ret)
                .done(function(data) {
                    console.log("Data Loaded: " + data);
                });
        }

        valid = true;
    });

    for (var type in types) {
        var addButton = $('<a href="#" style="clear: both;" element="' + type + '" class="pull-right add_link btn btn-xs btn-info"><span class="glyphicon glyphicon-plus" style="vertical-align: text-top;"></span> Add a new ' + type + '</a>');
        addButton.wrap('<div class="form-group"></div>');

        var formElementId = types[type];
        var collectionHolder = $('#' + formElementId);
        collectionHolder.append(addButton);
        collectionHolder.data('index', collectionHolder.find(':input').length);
        collectionHolder.parent().wrap('<fieldset class="well"></fieldset>');

        addButton.on('click', function(e) {
            e.preventDefault();

            addFormElement($(this), $(this).attr('element'));
        });
    }
});

function addFormElement(addButton, type) {
    var formElementId = types[type];
    var collectionHolder = $('#' + formElementId);
    var index = collectionHolder.data('index');

    var newForm = $('<input type="text" id="' + formElementId + '_' + index + '" class="' + formElementId + ' form-control" name="' + formElementId + '_' + index + '" element="' + type + '" maxlength="255">');
    var newFormElement = $('<div class="form-group"></div>').append(newForm);

    newFormElement.append('<a href="#" style="margin-bottom: 10px;" class="pull-right remove-data btn btn-xs btn-danger"><span class="glyphicon glyphicon-remove" style="vertical-align:text-top"></span> Remove ' + type + '</a>');
    addButton.before(newFormElement);

    collectionHolder.data('index', index + 1);

    $('.remove-data').click(function(e) {
        e.preventDefault();

        $(this).parent().remove();

        return false;
    });
}

function validateEmail(email) {
    var re = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
    return re.test(email);
}