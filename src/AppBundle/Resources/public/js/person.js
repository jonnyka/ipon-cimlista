setTimeout(function() {
    $('.alert.alert-success').fadeOut(1000);
    $('.alert.alert-warning').fadeOut(1000);
}, 3000);

$(document).ready(function() {
    $('.form-group').find('label:contains("0")').remove();
});

/*
var $addTagLink = $('<a href="#" class="add_persondata_link btn btn-info"><span class="glyphicon glyphicon-plus" style="vertical-align:text-top"></span> Add more data</a>');
var $newLinkLi = $('<div class="form-group"></div>').append($addTagLink);

$(document).ready(function() {
    $('.form-group').find('label:contains("0")').remove();
    $('.form-group').find('label:contains("Person data")').remove();

    var $collectionHolder = $('.personData');
    $collectionHolder.append($newLinkLi);
    $collectionHolder.data('index', $collectionHolder.find(':input').length);
    $collectionHolder.insertBefore('#appbundle_person_submit');

    $addTagLink.on('click', function(e) {
        e.preventDefault();

        addTagForm($collectionHolder, $newLinkLi);
    });
});

function addTagForm($collectionHolder, $newLinkLi) {
    var prototype = $collectionHolder.data('prototype');
    var index = $collectionHolder.data('index');
    var newForm = prototype.replace(/__name__/g, index);

    $collectionHolder.data('index', index + 1);

    var $newFormLi = $('<fieldset class="well the-fieldset"><div class="form-group"></div></fieldset>').append(newForm);
    $newFormLi.append('<a href="#" class="remove-data btn btn-danger"><span class="glyphicon glyphicon-remove" style="vertical-align:text-top"></span> Remove data</a>');
    $newLinkLi.before($newFormLi);

    $('.remove-data').click(function(e) {
        e.preventDefault();

        $(this).parent().remove();

        return false;
    });
}
    */