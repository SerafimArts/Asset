<?php
namespace Asset\Driver;

use JSMin;
use App;

class JsDriver
    extends AbstractDriver
    implements DriverInterface
{
    protected $type = self::TYPE_JS;

    public function make()
    {
        $this->result = $this->cache($this->source, function(){
            $result = $this->source;
            if (App::environment('production')) {
                $result = JSMin::minify($result);
            }
            return $result;
        });
    }
}