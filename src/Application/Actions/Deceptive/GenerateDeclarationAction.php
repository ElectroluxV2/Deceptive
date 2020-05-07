<?php

namespace App\Application\Actions\Deceptive;

use Exception;
use Mpdf\Output\Destination;
use Psr\Http\Message\ResponseInterface as Response;

class GenerateDeclarationAction extends DeceptiveAction {

    /**
     * @return Response
     * @throws Exception
     */
    protected function action(): Response {

        $formData = json_decode(file_get_contents('php://input'));
        if (!is_object($formData)) {
            throw new Exception('FormData parameter must be object type.');
        }

        setlocale(LC_TIME, "pl_PL");
        $formData->date = strftime('%e %b %G');

        $fileName = uniqid("D",true);
        $formData->id = $fileName;

        $templateData = get_object_vars($formData);
        foreach ($templateData as $key => $value) {
            if (empty($value)) {
                $templateData[$key] = '—';
            }
        }

        include_once "DeclarationTemplate.php";
        $template = makeTemplate($templateData);

        $this->mpdf->WriteHTML($template);
        $fileExt = '.pdf';

        $dir = $this->c->get('settings')['mpdf']['outputDir'];
        $this->mpdf->Output($dir . '/' . $fileName . $fileExt,Destination::FILE);

        $_SESSION['DecID'] = $fileName;

        $data = [
            'ID' => $fileName
        ];
        return $this->respondWithData($data);
    }
}
