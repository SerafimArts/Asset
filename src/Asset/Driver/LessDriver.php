<?php
namespace Asset\Driver;

use lessc as LessCompiler;
use CssMin;
use App;

class LessDriver
    extends AbstractDriver
    implements DriverInterface
{
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
        $less = new LessCompiler();
        $result = $less->compile($this->source);

        if (App::environment('production')) {
            $result = CssMin::minify($result);
        }

        return $result;
    }
}