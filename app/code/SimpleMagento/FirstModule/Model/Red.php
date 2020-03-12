<?php


namespace SimpleMagento\FirstModule\Model;
use SimpleMagento\FirstModule\Api\Brightness;
use SimpleMagento\FirstModule\Api\Color;

class Red implements Color
{
    protected $brightness;
    public function __construct(Brightness $brightness)
    {
        $this->brightness = $brightness;
    }

    public function getColor()
    {
        // TODO: Implement getColor() method.
        return "Red";
    }
}