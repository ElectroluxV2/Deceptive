<?php
declare(strict_types=1);

namespace App\Application\Actions\Deceptive;

use App\Application\Actions\Action;
use App\Domain\Deceptive\CMS;
use Mpdf\Mpdf;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

abstract class DeceptiveAction extends Action {

    /**
     * @var CMS
     */
    protected CMS $cms;
    /**
     * @var Mpdf
     */
    protected Mpdf $mpdf;
    /**
     * @var ContainerInterface
     */
    protected ContainerInterface $c;

    /**
     * DeceptiveAction constructor.
     * @param LoggerInterface $logger
     * @param CMS $cms
     * @param Mpdf $mpdf
     * @param ContainerInterface $c
     */
    public function __construct(LoggerInterface $logger, CMS $cms, Mpdf $mpdf, ContainerInterface $c) {
        parent::__construct($logger);
        $this->cms = $cms;
        $this->mpdf = $mpdf;
        $this->c = $c;
    }
}
