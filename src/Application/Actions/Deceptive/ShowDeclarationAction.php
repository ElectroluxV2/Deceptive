<?php

namespace App\Application\Actions\Deceptive;

use App\Domain\DomainException\AuthException;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Psr7\Stream;

class ShowDeclarationAction extends DeceptiveAction {

    /**
     * @return Response
     * @throws Exception
     */
    protected function action(): Response {

        $fileName = base64_decode($this->args['id']);

        if ((!isset($_SESSION['DecID'])) || ($_SESSION['DecID']!=$fileName)) {
            throw new AuthException('Not enough privileges!');
        }

        $fileExt = '.pdf';
        $dir = $this->c->get('settings')['mpdf']['outputDir'];
        $file = $dir . '/' . $fileName . $fileExt;
        $openFile = fopen($file,'rb');
        $stream = new Stream($openFile);

        return $this->response->withStatus(200)
            ->withHeader('Content-Type', 'application/pdf')
            ->withHeader('Content-Description', 'File Transfer')
            ->withHeader('Content-Transfer-Encoding', 'binary')
            ->withHeader('Content-Disposition', 'attachment; filename="Deklaracja członkowska.pdf"')
            ->withHeader('Expires', '0')
            ->withHeader('Content-Length', filesize($file))
            ->withHeader('Cache-Control', 'must-revalidate')
            ->withHeader('Pragma', 'public')
            ->withBody($stream);
    }
}
