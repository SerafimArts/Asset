<?php
namespace Asset\Driver;

use App;
use CssMin;
// use scss_compass as Compass;
use scssc as ScssCompiler;

/**
 * Class ScssDriver
 * @package Asset\Driver
 */
class ScssDriver
    extends AbstractDriver
    implements DriverInterface
{
    /**
     * @var string
     */
    protected $type = self::TYPE_CSS;

    /**
     * Build styles
     */
    public function make()
    {
        $this->result = App::environment('production')
            ? $this->cache($this->source, function () {
                return $this->compile();
            })
            : $this->compile();
    }

    /**
     * @return string
     */
    protected function compile()
    {
        $scss = new ScssCompiler();
        $scss->setImportPaths($this->file->getPath());
        // new Compass($scss);
        $result = $scss->compile($this->source);

        if (App::environment('production')) {
            $result = CssMin::minify($result);
        }

        return $result;
    }
}