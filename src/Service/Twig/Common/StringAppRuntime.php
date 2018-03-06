<?php
namespace App\Service\Twig\Common;

use App\Common\Traits\String\MaxLengthTrait;

/**
 * Class AppRuntime
 * @package App\Service\Twig\Common
 */
class StringAppRuntime
{

    use MaxLengthTrait;

    /**
     * AppRuntime constructor.
     * @author Frogg <admin@frogg.fr>
     */
    public function __construct()
    {
    }
}
