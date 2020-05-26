<?php

namespace App\Application\Actions\Deceptive\Declaration;

use App\Application\Actions\Deceptive\DeceptiveAction;
use Exception;
use Mpdf\Output\Destination;
use Psr\Http\Message\ResponseInterface as Response;
use stdClass;

class GenerateAction extends DeceptiveAction {

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

        $requiredFields = [
            "city",
            "date",
            "name",
            "surname",
            "birthDate",
            "pesel",
            "road",
            "addr1",
            "addr2",
            "postal",
            "phone1",
            "email1",
            "club",
            "function",
            "name2",
            "surname2",
            "phone2",
            "email2",
            "id"
        ];

        $templateData = get_object_vars($formData);
        foreach ($templateData as $key => $value) {

            if (!in_array($key, $requiredFields)) {
                throw new Exception("Wrong param '".$key."'");
            }

            if (empty($value)) {
                $templateData[$key] = '—';
            }
        }

        $parsedData = new stdClass();
        foreach ($requiredFields as $field) {
            $parsedData->$field = htmlspecialchars($templateData[$field], ENT_QUOTES | ENT_HTML5 | ENT_SUBSTITUTE);
        }

        include_once "DeclarationTemplate.php";
        $template = makeTemplate($parsedData);

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
