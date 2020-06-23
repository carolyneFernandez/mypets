$(document).ready(function () {

    if ($('#rdv_proprietaire').length > 0) {
        majSelectAnimaux();
    }
    $('#rdv_proprietaire').on('change', majSelectAnimaux)

});


function majSelectAnimaux() {
    const proprietaireId = $('#rdv_proprietaire').val();
    const $selectAnimaux = $('#rdv_animal');
    $.get(Routing.generate('api_animal_get_by_proprietaire', {proprietaire: proprietaireId}), function (data) {
        $('option[value!=""]', $selectAnimaux).remove();
        $.each(data.animauxChoices, function (index, choice) {

            const $option = $('<option>');
            $option.attr('value', choice.value);
            $option.attr('data-content', choice.label);
            $option.html(choice.label);
            $selectAnimaux.append($option);
        });
        console.log($selectAnimaux);
        $selectAnimaux.selectpicker('refresh');
    });
}