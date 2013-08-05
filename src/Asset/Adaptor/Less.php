<?php
/**
 * @author Serafim <serafim@sources.ru>
 * @link http://rudev.org/
 * @date 06.08.13 2:11
 * @copyright 2008-2013 RuDev
 * @package Less.php
 * @since 1.0
 */
namespace Asset\Adaptor;

/**
 * Class Less
 * @package Asset\Adaptor
 */
class Less
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
        $this->_result = (new \lessc())->compile($sources);
    }

    /**
     * @return string
     */
    public function getResult()
    {
        return $this->_result;
    }
}
