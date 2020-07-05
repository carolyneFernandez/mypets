import {Calendar} from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import frLocale from '@fullcalendar/core/locales/fr';
import bootstrapPlugin from '@fullcalendar/bootstrap/main.min';
import Popover from "bootstrap/js/src/popover";
import Tooltip from "bootstrap/js/src/tooltip";

// import Tooltip from "bootstrap/js/src/tooltip";
const divParameters = $("#div-parameter");
const urlLoadEvent = divParameters.data('url_load_events');
var calendarEl = document.getElementById('calendar-holder');

var id = $("#filtre_entretien_data_id");
var numeroContratMaintenance = $("#filtre_entretien_data_contratMaintenance");
var chargeaffaire = $("#filtre_entretien_data_chargeAffaires");
var enseignes = $("#filtre_entretien_data_enseignes");
var statut = $("#filtre_entretien_data_statuts");


var valueChargeaffaire = null;
var valueId = id.val();
var valueContratMaintenance = numeroContratMaintenance.val();
var valueStatut = statut.val();
var valueenseignes = enseignes.val();

if (chargeaffaire) {
    valueChargeaffaire = chargeaffaire.val();
}

afficherCalendrier();


if (chargeaffaire) {
    chargeaffaire.on('change', function () {
        calendarEl.innerText = "";
        valueChargeaffaire = chargeaffaire.val();
        afficherCalendrier();
    });
}

//
function afficherCalendrier() {
    let calendar = new Calendar(calendarEl, {
        locale: frLocale,
        defaultView: 'timeGridWeek',
        editable: false,
        eventSources: [
            {
                url: urlLoadEvent,
                method: "POST",
                extraParams: {
                    filters: JSON.stringify({'enseignes': valueenseignes, 'chargeAffaires': valueChargeaffaire, 'id': valueId, 'statuts': valueStatut, 'contratMaintenance': valueContratMaintenance,})
                },
                failure: (err) => {
                    // alert("Une erreur est survenue lors de la génération du calendrier !" );
                },
            },
        ],

        eventRender: function (info) {
            console.log(info.event.extendedProps);
            $(info.el).popover({
                title: info.event.title,
                content: info.event.extendedProps.description,
                trigger: 'hover',
                placement: 'top',
                container: 'body',
                html: true,
                animation: true,
            });
            var title = $(info.el).find('.fc-title');
            title.html(title.text());
            // if (info.event.extendedProps != null) {
            //
            // }
        },
        //
        // eventRender: function(info) {
        //     var tooltip = new Tooltip(info.el, {
        //         title: info.event.extendedProps.description,
        //         placement: 'top',
        //         trigger: 'hover',
        //         container: 'body'
        //     });
        // },


        header: {
            left: 'prev,next today',
            center: 'title',
            // right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek',
            right: 'dayGridMonth,timeGridWeek,listMonth,timeGridOneDay',
        },
        plugins: [
            dayGridPlugin,
            timeGridPlugin,
            listPlugin,
            bootstrapPlugin
        ], // https://fullcalendar.io/docs/plugin-index
        timeZone: 'local',
        minTime: '00:00:00',
        maxTime: '24:00:00',
        scrollTime: '08:00:00',
        nowIndicator: true,
        slotDuration: '00:30:00',
        weekNumbers: true,
        height: 'parent',
        themeSystem: 'bootstrap',


        views: {
            timeGridOneDay: {
                type: 'timeGrid',
                duration: {days: 3},
                buttonText: '3 jours'
            }
        }
    });
    calendar.render();


}