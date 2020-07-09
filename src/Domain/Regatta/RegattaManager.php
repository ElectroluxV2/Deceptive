<?php
declare(strict_types=1);

namespace App\Domain\Regatta;

use Exception;
use Medoo\Medoo;

class RegattaManager {


    /**
     * Database framework
     * @var Medoo
     */
    private Medoo $database;

    /**
     * RegattaManager constructor.
     * @param Medoo $medoo
     */
    public function __construct(Medoo $medoo) {
        $this->database = $medoo;
    }

    /**
     * @param Regatta $regatta
     * @return int Id of new regatta
     * @throws Exception
     */
    public function new(Regatta $regatta): int {

        $inBase = $this->database->get('regatta', ['id'], [
            'name' => $regatta->name,
            'period' => $regatta->period,
            'place' => $regatta->place,
            'wsp' => $regatta->wsp
        ]);

        if ($inBase) {
            return (int) $inBase['id'];
        }

        $result = $this->database->insert('regatta', [
           'name' => $regatta->name,
           'period' => $regatta->period,
           'place' => $regatta->place,
           'wsp' => $regatta->wsp
        ]);

        if (!$result) {
            throw new Exception('Database error: ');
        }

        return (int) $this->database->id();
    }

    public function delete(int $id): bool {
        return (bool) $this->database->delete('regatta', [
            'id' => $id
        ]);
    }

    public function update(int $id, Regatta $newData): bool {

        return (bool) $this->database->update('regatta', [
            'name' => $newData->name,
            'period' => $newData->period,
            'place' => $newData->place,
            'wsp' => $newData->wsp
        ], [
            'id' => $id
        ]);
    }

    public function list($limit, int $pageToken): iterable {

        $data = $this->database->select('regatta', [
            'id',
            'name',
            'period',
            'place',
            'wsp'
        ], [
            'id[>]' => $pageToken,
            'ORDER' => ['id' => 'ASC'],
            'LIMIT' => $limit
        ]);

        if (count($data) == 0) {
            return [
                'data' => []
            ];
        }

        return [
            'data' => $data,
            'pageToken' => $data[count($data)-1]['id']
        ];
    }
}