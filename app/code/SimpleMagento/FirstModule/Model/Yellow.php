<?php


namespace SimpleMagento\FirstModule\Model;
use SimpleMagento\FirstModule\Api\Color;
use SimpleMagento\FirstModule\Api\Brightness;

class Yellow implements Color
{
    protected $brightness;
    public function __construct(Brightness $brightness)
    {
      $this->brightness = $brightness;
    }
 public function getColor()
 {
     // TODO: Implement getColor() method.
     return "Yellow";
 }
}