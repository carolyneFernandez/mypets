<?php

namespace App\EventSubscriber;

use App\Data\FiltreEntretienData;
use App\Data\FiltreRdv;
use App\Entity\Entretien;
use App\Entity\Rdv;
use App\Repository\EntretienRepository;
use App\Repository\RdvRepository;
use CalendarBundle\CalendarEvents;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\WebProfilerBundle\Profiler\TemplateManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class CalendarSubscriber implements EventSubscriberInterface
{
    private $rdvRepository;
    private $router;
    private $security;
    private $container;
    private $twig;

    public function __construct(RdvRepository $rdvRepository, UrlGeneratorInterface $router, ContainerInterface $container, Security $security, Environment $twig)
    {
        $this->rdvRepository = $rdvRepository;
        $this->router = $router;
        $this->security = $security;
        $this->container = $container;
        $this->twig = $twig;
    }

    public static function getSubscribedEvents()
    {
        return [
            CalendarEvents::SET_DATA => 'onCalendarSetData',
        ];
    }

    public function onCalendarSetData(CalendarEvent $calendar)
    {
        $filters = $calendar->getFilters();

        $filtres = new FiltreRdv();

        if (isset($filters['veterinaires'])) {
            $filtres->veterinaires = $filters['veterinaires'];
        }


        $rdvs = $this->rdvRepository->getByFilter($filtres);


        /** @var Rdv $rdv */
        foreach ($rdvs as $rdv) {

            $nomEven = $rdv->getAnimal()
                           ->getNom() . ' - ' . $rdv->getAnimal()
                                                    ->getType()
                                                    ->getNom()
            ;

            if (!$rdv->getValide()) {
                $nomEven .= " <span class=\"badge badge-pill badge-warning\">A valider</span>";
            }

            /** @var \DateTime $dateFin */
            $dateFin = clone $rdv->getDate();
            $dateFin->modify('+30 minutes');
            // this create the events with your data (here booking data) to fill calendar
            $rdvEvent = new Event($nomEven, $rdv->getDate(), $dateFin// If the end date is null or not defined, a all day event is created.
            );

            try {
                $descHtml = $this->twig->render('rdv/include/_popover.html.twig', [
                    'rdv' => $rdv
                ]);
            } catch (\Exception $e) {
                $descHtml = $rdv->getVeterinaire()
                                ->getNomPrenom()
                ;
            }

            $rdvEvent->setOptions([
                'backgroundColor' => ($rdv->getVeterinaire()
                                          ->getColorRdv() ? $rdv->getVeterinaire()
                                                                ->getColorRdv() : '#008cba'),
                'borderColor' => ($rdv->getVeterinaire()
                                      ->getColorRdv() ? $rdv->getVeterinaire()
                                                            ->getColorRdv() : '#008cba'),
                'textColor' => $this->getContrastColor(($rdv->getVeterinaire()
                                                            ->getColorRdv() ? $rdv->getVeterinaire()
                                                                                  ->getColorRdv() : '#008cba')),
                'description' => $descHtml,

            ]);

            $url = $this->router->generate('rdv_show', [
                'id' => $rdv->getId(),
            ]);


            $rdvEvent->addOption('url', $url);
            $rdvEvent->addOption('textEscape', false);

            // finally, add the event to the CalendarEvent to fill the calendar
            $calendar->addEvent($rdvEvent);
        }


    }

    private function getContrastColor($hexcolor)
    {
        $r = hexdec(substr($hexcolor, 1, 2));
        $g = hexdec(substr($hexcolor, 3, 2));
        $b = hexdec(substr($hexcolor, 5, 2));
        $yiq = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;

        return ($yiq >= 128) ? '#000000' : '#ffffff';
    }

}