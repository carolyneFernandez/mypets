<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    private $container;
    private $slugger;

    public function __construct(ContainerInterface $container, SluggerInterface $slugger)
    {
        $this->container = $container;
        $this->slugger = $slugger;
    }

    public function upload(UploadedFile $file, $dir, $uniqueFilename = true)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        if ($uniqueFilename || in_array($safeFilename, ['image'])) {
            $filename = uniqid() . '.' . $file->guessExtension();
        } else {
            $filename = $safeFilename . '.' . $file->guessExtension();;
        }

        if (!is_dir($dir))
            mkdir($dir, 0777, true);

        try {
            $file->move($dir, $filename);
            if ($uniqueFilename) {
                $this->container->get('session')
                                ->getFlashBag()
                                ->add('success', 'Le fichier a bien été importé.')
                ;
            } else {
                $this->container->get('session')
                                ->getFlashBag()
                                ->add('success', 'Le fichier ' . $filename . ' a bien été importé.')
                ;
            }

        } catch (FileException $e) {
            $this->container->get('session')
                            ->getFlashBag()
                            ->add('danger', 'Une erreur s\'est produite lors de l\'importation du fichier ' . $filename . '.')
            ;

            return false;
        }

        return $filename;
    }


    public function scanDir($dir)
    {
        $files = scandir($dir);

        $filesToReturn = [];

        foreach ($files as $key => $file) {
            if (in_array($file, [
                '.',
                '..',
                '.DS_Store'
            ])) {
                unset($files[$key]);
            } else {

                $f = pathinfo($dir . '/' . $file);
                $filesToReturn[] = $f;
            }
        }

//        $files = array_values($files);

        return $filesToReturn;

    }

}
