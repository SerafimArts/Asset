<?php
/**
 * @author Serafim <serafim@sources.ru>
 * @link http://rudev.org/
 * @date 06.08.13 2:12
 * @copyright 2008-2013 RuDev
 * @package AdaptorInterface.php
 * @since 1.0
 */
namespace Asset\Adaptor;

/**
 * Class AdaptorInterface
 * @package Asset\Adaptor
 */
interface AdaptorInterface
{
    /**
     * @param $sources
     */
    public function __construct($sources);

    /**
     * @return mixed
     */
    public function getResult();
}