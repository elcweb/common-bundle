<?php

namespace Elcweb\CommonBundle\Date;

use DateTime as BaseDateTime;

class DateTime extends BaseDateTime
{
    public function __toString()
    {
        return $this->format('Y-m-d h:i:s');
    }
}