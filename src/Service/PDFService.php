<?php


namespace App\Service;


use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Html2Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PDFService
{
    /** @var Html2Pdf */
    private $pdf;

    private $container;

    /**
     * PDFService constructor.
     * @param $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }


    public function create($orientation = null, $format = null, $lang = null, $unicode = false, $encoding = null, $margin = null)
    {
        $this->pdf = new Html2Pdf($orientation ? $orientation : $this->orientation, $format ? $format : $this->format, $lang ? $lang : $this->lang, $unicode ? $unicode : false, $encoding ? $encoding : $this->encoding, $margin ? $margin : $this->margin);
//        $this->pdf->addFont("Nunito Sans", "normal", $this->container->getParameter('kernel.project_dir'). '/public/assets/fonts/Nunitos/NunitoSans-Regular.ttf');
//        $this->pdf->setDefaultFont("Nunito Sans");
    }

    public function generatePdf($template, $name, $option = 'I')
    {
        $this->pdf->writeHTML($template);
        $this->pdf->pdf->SetProtection(array(
            'print',
            'copy'
        ));
        try {
            return $this->pdf->Output($name . '.pdf', $option);
        } catch (Html2PdfException $e) {
        }

        return false;
    }

}