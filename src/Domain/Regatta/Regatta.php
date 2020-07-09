<?php
declare(strict_types=1);

namespace App\Domain\Regatta;


use phpDocumentor\Reflection\Types\Integer;

class Regatta {
    private Integer $id;
    public String $name;
    public String $place;
    public String $period;
    public Float $wsp;

    public function __construct(String $name, String $place, String $period, Float $wsp) {
        $this->name = $name;
        $this->place = $place;
        $this->period = $period;
        $this->wsp = $wsp;
    }

    public function getId(): Integer {
        return $this->id;
    }
}