<?php


namespace App\Service;


use App\Entity\Rental;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Twig\Environment as Templating;

class PDFService
{
    /**
     * @var ParameterBagInterface
     */
    private $params;
    /**
     * @var RentalService
     */
    private $rentalService;


    private $templating;


    /**
     * PDFService constructor.
     */
    public function __construct(
        ParameterBagInterface $params,
        Templating $templating,
        RentalService $rentalService
    ) {
        $this->params = $params;
        $this->rentalService = $rentalService;
        $this->templating = $templating;
    }

    public function generatePDF($body, $outputPath)
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->set('isHtml5ParserEnabled', 'true');
        $pdfOptions->set('defaultFont', 'sans-serif');
        $pdfOptions->set('isRemoteEnabled', true);

        // Instantiate Dompdf with our options
        $domPDF = new Dompdf($pdfOptions);

        // Load HTML to Dompdf
        $domPDF->loadHtml($body);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $domPDF->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $domPDF->render();

        $output = $domPDF->output();

        // Write file to the desired path
        file_put_contents($outputPath, $output);
    }


    public function generateContract(
        Rental $rental,
        string $city,
        string $signature
    ): Rental {
        $pdfFilepath = $this->params->get('contract_directory').uniqid().'.pdf';

        $template = $this->templating->render(
            'emails/contract.html.twig',
            [
                'rental' => $rental,
                'rentalService' => $this->rentalService,
                'city' => $city,
                'signature' => $signature,
            ]
        );

        $this->generatePDF($template, $pdfFilepath);

        $rental->addPdf('contract', $pdfFilepath);

        return $rental;
    }
}
