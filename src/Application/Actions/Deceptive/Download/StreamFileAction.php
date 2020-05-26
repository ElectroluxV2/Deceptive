<?php

namespace App\Application\Actions\Deceptive\Download;

use App\Application\Actions\Deceptive\DeceptiveAction;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Psr7\Stream;

class StreamFileAction extends DeceptiveAction {

    /**
     * @return Response
     * @throws Exception
     */
    protected function action(): Response {
        $id = $this->args['id'];

        $files = [
            '56df45a4-c75a-4234-8dec-c85f02958d83' => [
                'name' => 'Członkostwo w PoZZ',
                'type' => 'pdf',
            ],
            'f0de5cd5-bb2c-429c-9104-c887bc8b1d13' => [
                'name' => 'KRS-PSKOS',
                'type' => 'pdf'
            ],
            '43206346-8966-49cd-a82f-8d177a04c39a' => [
                'name' => 'NIP i regon',
                'type' => 'pdf'
            ],
            '4e747a26-31be-4819-8e3d-a2e8e7daff4d' => [
                'name' => 'PSKOB Deklaracja Członkowska wersja 1',
                'type' => 'docx'
            ],
            'dbecff10-1808-429c-9381-6d641c448130' => [
                'name' => 'Uchwała nr 474 Z XXXVII Zarządu PZZ z dn. 2017.03.15 w sprawie przyjęcia w poczet członków zwyczajnych PZZ',
                'type' => 'pdf'
            ]
        ];

        $fileName = $id.'.'.$files[$id]['type'];
        $dir = $this->c->get('settings')['uploads']['dir'];
        $file = $dir . '/' . $fileName;
        $openFile = fopen($file,'rb');
        $stream = new Stream($openFile);

        return $this->response->withStatus(200)
            ->withHeader('Content-Type', 'octet-stream')
            ->withHeader('Content-Description', 'File Transfer')
            ->withHeader('Content-Transfer-Encoding', 'binary')
            ->withHeader('Content-Disposition', 'attachment; filename="'.$files[$id]['name'].'.'.$files[$id]['type'].'"')
            ->withHeader('Expires', '0')
            ->withHeader('Content-Length', filesize($file))
            ->withHeader('Cache-Control', 'must-revalidate')
            ->withHeader('Pragma', 'public')
            ->withBody($stream);
    }
}
