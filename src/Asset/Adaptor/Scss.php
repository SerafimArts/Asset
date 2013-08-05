<?php
/**
 * @author Serafim <serafim@sources.ru>
 * @link http://rudev.org/
 * @date 06.08.13 2:09
 * @copyright 2008-2013 RuDev
 * @package Scss.php
 * @since 1.0
 */
namespace Asset\Adaptor;

/**
 * Class Scss
 * @package Asset\Adaptor
 */
class Scss
    extends AbstractAdaptor
    implements AdaptorInterface
{
    /**
     * @var string
     */
    private $_result = '';

    /**
     * @param $sources
     */
    public function __construct($sources)
    {
        $this->_result = (new \scssc())->compile($sources);
    }

    /**
     * @return string
     */
    public function getResult()
    {
        return $this->_result;
    }
}
