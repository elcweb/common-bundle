<?php

namespace Elcweb\CommonBundle\Form\DataTransformer;

use DateTime;
use Symfony\Component\Form\DataTransformerInterface;

class TextToDateTransformer implements DataTransformerInterface
{
    private $type = 'datetime';

    public function __construct($type = 'datetime')
    {
        $this->type = $type;
    }

    public function reverseTransform($date)
    {
        $_date = new DateTime($date);

        if ($this->type == 'timestamp') {
            return $_date->getTimestamp();
        }

        return $_date;
    }

    public function transform($date)
    {
        if (null === $date) {
            return "";
        }

        if ($this->type == 'datetime') {
            return $date->format('Y-m-d H:i:s');
        } elseif ($this->type == 'timestamp') {
            $_date = new DateTime();
            $_date->setTimestamp($date);
        } else {
            $_date = $date;
        }

        return $_date->format('Y-m-d');
    }
}
