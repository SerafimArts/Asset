<?php
namespace Asset\Driver;

use CssMin;
use App;

class CssDriver
    extends AbstractDriver
    implements DriverInterface
{
    protected $type = self::TYPE_CSS;

    public function make()
    {
        $this->result = $this->cache($this->source, function(){
            $result = $this->source;
            if (App::environment('production')) {
                $result = CssMin::minify($result);
            }
            return $result;
        });
    }
}