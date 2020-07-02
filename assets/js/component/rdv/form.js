$(document).ready(function () {

    if ($('#rdv_proprietaire').length > 0) {
        majSelectAnimaux();
    }
    $('#rdv_proprietaire').on('change', majSelectAnimaux);

    if ($('#rdv_step2_clinique').length > 0) {
        majSelectVeterinaires();
    }
    $('#rdv_step2_clinique').on('change', majSelectVeterinaires);

});


function majSelectAnimaux() {

    const proprietaireId = $('#rdv_proprietaire').val();
    const $selectAnimaux = $('#rdv_animal');
    const idSelected = $selectAnimaux.val();

    $.get(Routing.generate('api_animal_get_by_proprietaire', {proprietaire: proprietaireId}), function (data) {
        $('option[value!=""]', $selectAnimaux).remove();

        $.each(data.animauxChoices, function (index, choice) {

            const $option = $('<option>');
            $option.attr('value', choice.value);
            $option.attr('data-content', choice.label);
            $option.html(choice.label);
            if (parseInt(idSelected) === parseInt(choice.value)) {
                $option.prop('selected', true);
            }
            $selectAnimaux.append($option);
        });

        $selectAnimaux.selectpicker('refresh');
    });
}

function majSelectVeterinaires() {

    const cliniqueId = $('#rdv_step2_clinique').val();
    const $selectVeterinaire = $('#rdv_step2_veterinaire');
    const idSelected = $selectVeterinaire.val();

    $.get(Routing.generate('api_veterinaire_get_by_clinique', {clinique: cliniqueId}), function (data) {
        $('option[value!=""]', $selectVeterinaire).remove();

        $.each(data.veterinairesChoices, function (index, choice) {

            const $option = $('<option>');
            $option.attr('value', choice.value);
            $option.attr('data-content', choice.label);
            $option.html(choice.label);
            if (parseInt(idSelected) === parseInt(choice.value)) {
                $option.prop('selected', true);
            }
            $selectVeterinaire.append($option);
        });

        $selectVeterinaire.selectpicker('refresh');
    });
}