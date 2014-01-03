<?php
/**
 * @author Serafim <serafim@sources.ru>
 * @link http://rudev.org/
 * @date 20.08.13 0:50
 * @copyright 2008-2013 RuDev
 * @since 2.0.0
 */
namespace Asset;

/**
 * Class Finder
 * @package Asset
 */
class Finder
{
    /**
     * @var string
     */
    private $_dir = '/';

    /**
     * @var bool
     */
    private $_search = false;

    /**
     * @var string
     */
    private $_ext = '';

    /**
     * @param $rule
     */
    public function __construct($rule)
    {
        $rule = str_replace('\\', '/', $rule);
        $this->setDir($rule)
            ->needSearch($rule)
            ->withExt($rule);
    }

    /**
     * @return array
     */
    public function search()
    {
        $this->_dir = trim($this->_dir);
        if ($this->_search) {
            $result = [];
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator(
                    $this->_dir,
                    \RecursiveDirectoryIterator::SKIP_DOTS
                )
            );
            foreach ($iterator as $r) {
                if (!$this->_ext || $this->_ext != $r->getExtension()) { continue; }
                $result[] = $r->getRealPath();
            }
            return $result;
        } else if (strstr($this->_dir, '*')) {
            return glob($this->_dir);
        }

        return [$this->_dir];
    }

    /**
     * @param $rule
     * @return $this
     */
    private function setDir($rule)
    {
        $rule = str_replace('\\', '/', $rule);
        preg_match_all('#(.*?)\*/#isu', $rule, $m);
        $this->_dir = isset($m[1][0]) ? $m[1][0]: $rule;
        return $this;
    }

    /**
     * @param $rule
     * @return $this
     */
    private function needSearch($rule)
    {
        preg_match_all('#(.*?)\*[^\.]#isu', $rule, $m);
        $this->_search = (bool)isset($m[0][0]);
        return $this;
    }

    /**
     * @param $rule
     * @return $this
     */
    private function withExt($rule)
    {
        preg_match_all('#\*\.([a-zA-Z]+)#isu', $rule, $m);
        $this->_ext = isset($m[1][0])
            ? $m[1][0] : '';
        return $this;
    }
}