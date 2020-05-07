<?php
declare(strict_types=1);

use App\Domain\Deceptive\CMS;
use DI\ContainerBuilder;
use Mpdf\Mpdf;
use function DI\autowire;

return function (ContainerBuilder $containerBuilder) {

    $containerBuilder->addDefinitions([
        //CMS::class => autowire(CMS::class),
        //Mpdf::class => autowire(Mpdf::class),

    ]);
};
