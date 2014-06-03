<?php
namespace Asset\Driver;

use App;
use CoffeeScript\Compiler as CSCompiler;
use JSMin;

/**
 * Class CoffeeDriver
 * @package Asset\Driver
 */
class CoffeeDriver
    extends AbstractDriver
    implements DriverInterface
{
    /**
     * @var string
     */
    protected $type = self::TYPE_JS;

    /**
     * Build stylesheets
     */
    public function make()
    {
        $this->result = $this->cache($this->source, function () {
            $result = CSCompiler::compile(
                $this->source, ['filename' => $this->file->getFilename()]
            );

            if (App::environment('production')) {
                $result = JSMin::minify($result);
            }

            return $result;
        });
    }
}