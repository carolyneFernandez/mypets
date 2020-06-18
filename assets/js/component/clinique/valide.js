$(document).ready(function () {

    const $formRefus = $('#formRefusInscription');
    const $btnSendFormRefus = $('#button-send-formRefus');
    const $motifRefus = $formRefus.find('textarea');

    $btnSendFormRefus.on('click', function () {
        if ($motifRefus.val() === "") {
            $motifRefus.addClass('bg-danger');
        } else {
            $motifRefus.removeClass('bg-danger');
            $formRefus.submit();
        }
    });

    $motifRefus.on('keyup', function () {
        if ($motifRefus.val() === "") {
            $motifRefus.addClass('bg-danger');
        } else {
            $motifRefus.removeClass('bg-danger');
        }
    })

});