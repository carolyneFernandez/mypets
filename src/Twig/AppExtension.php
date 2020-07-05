<?php

namespace App\Twig;

use App\Form\ItemPaginationType;
use App\Service\FileUploader;
use Symfony\Bundle\TwigBundle\DependencyInjection\TwigExtension;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormFactoryInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private $factory;
    private $container;
    private $fileUploader;

    /**
     * AppExtension constructor.
     * @param FormFactoryInterface $factory
     * @param ContainerInterface $container
     */
    public function __construct(FormFactoryInterface $factory, ContainerInterface $container, FileUploader $fileUploader)
    {
        $this->factory = $factory;
        $this->container = $container;
        $this->fileUploader = $fileUploader;
    }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('base64', [
                $this,
                'base64Encode'
            ]),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getFormItemPagination', [
                $this,
                'getFormItemPagination'
            ]),
            new TwigFunction('get_file_content', [
                $this,
                'getFileContent'
            ]),
            new TwigFunction('replaceVariableFolderClient', [
                $this,
                'replaceVariableFolderClient'
            ]),
            new TwigFunction('getContrastColor', [
                $this,
                'getContrastColor'
            ]),
        ];
    }

    public function getFormItemPagination()
    {

        $data['maxItemPerPage'] = $this->container->get('session')
                                                  ->get('itemPerPage', $this->container->getParameter('itemPerPage'))
        ;
        $form = $this->factory->create(ItemPaginationType::class, $data);

        return $form->createView();
        // ...
    }

    public function getFileContent($filename)
    {
        $filename = $this->container->getParameter('kernel.project_dir') . '/public' . $filename;
//        dump($filename);
//        dump(file_exists($filename));
        if (file_exists($filename)) {
//            dump(file_get_contents($filename));
            return file_get_contents($filename, true);
        }

        return null;
    }

    public function base64Encode($string)
    {
        return base64_encode($string);

    }

    public function getContrastColor($hexcolor)
    {
        $r = hexdec(substr($hexcolor, 1, 2));
        $g = hexdec(substr($hexcolor, 3, 2));
        $b = hexdec(substr($hexcolor, 5, 2));
        $yiq = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;

        return ($yiq >= 128) ? '#000000' : '#ffffff';
    }

}
