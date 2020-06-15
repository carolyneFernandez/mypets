$(document).ready(function () {

    $(".delete-collection").on("click", function (e) {
        $(this).closest('.item-collection').remove();
    });

    $('.add-another-collection-lien-widget').click(addCollection);


});

function addCollection() {

    let list = $($(this).attr('data-list-selector'));
    // Try to find the counter of the list or use the length of the list
    let counter = $(list).attr('data-widget-counter') || list.children().length;
    // grab the prototype template
    let newWidget = list.attr('data-prototype');
    // replace the "__name__" used in the id and name of the prototype
    // with a number that's unique to your emails
    // end name attribute looks like name="contact[emails][2]"
    if (list.hasClass('proto-n2')) {
        newWidget = newWidget.replace(/__name_second__/g, counter);
    } else {
        newWidget = newWidget.replace(/__name__/g, counter);
    }

    // Increase the counter
    counter++;
    // And store it, the length cannot be used if deleting widgets is allowed
    $(list).attr('data-widget-counter', counter);

    // create a new list element and add it to the list
    let newElem = $(list.attr('data-widget-tags')).html(newWidget);
    newElem.find('.input-rang').val(counter);
    newElem.appendTo(list);
    $('.counter-item', newElem).val(counter);
    $('.counter-item', newElem).html(counter);

    $(".delete-collection").on("click", function (e) {
        $(this).closest('.item-collection').remove();
    });

    $('.add-another-collection-lien-widget', newElem).click(addCollection);

    // stickybits('.sticky-box', {useStickyClasses: true});

    $('select', newElem).selectpicker();


}