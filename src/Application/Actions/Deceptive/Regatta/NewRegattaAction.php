<?php

namespace App\Application\Actions\Deceptive\Regatta;

use App\Application\Actions\Deceptive\DeceptiveAction;
use App\Domain\Regatta\Regatta;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;

class NewRegattaAction extends DeceptiveAction {

    /**
     * @return Response
     * @throws Exception
     */
    protected function action(): Response {

        $data = json_decode(file_get_contents('php://input'));
        if (!is_object($data)) {
            throw new Exception('Body parameter must be object type.');
        }

        $required = ['name', 'place', 'period', 'wsp'];
        foreach ($required as $index) {
            if (!property_exists($data, $index)) throw new Exception('Missing parameter: ' . $index);
        }

        if (!is_float($data->wsp)) throw new Exception('Wsp parameter must be float type.');

        $regatta = new Regatta($data->name, $data->place, $data->period, $data->wsp);

        $newId = $this->cms->getRegattaMgr()->new($regatta);

        return $this->respondWithData([
            'id' => $newId
        ]);
    }
}
