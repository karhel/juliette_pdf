<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Response;
use Nucleos\DompdfBundle\Factory\DompdfFactoryInterface;
use Nucleos\DompdfBundle\Wrapper\DompdfWrapperInterface;

final class PdfGenerator
{


    public function __construct(
        private DompdfFactoryInterface $factory,
        private DompdfWrapperInterface $wrapper
    ) {}

    public function output(string $html): string
    {
        $dompdf = $this->factory->create();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4');
        $dompdf->render();
        return $dompdf->output();
    }

    public function getStreamResponse(string $html, string $filename): Response
    {
        return $this->wrapper->getStreamResponse($html, $filename);
    }
}