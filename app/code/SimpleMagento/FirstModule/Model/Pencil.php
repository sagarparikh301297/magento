<?php

/**
* 
*/
namespace SimpleMagento\FirstModule\Model;
use SimpleMagento\FirstModule\Api\PencilInterface;
use SimpleMagento\FirstModule\Api\Color;
use SimpleMagento\FirstModule\Api\Size;

class Pencil implements PencilInterface
{
    protected $color;
    protected $size;
    protected $name;
    protected $school;
	public function __construct(Color $color, Size $size, $name = null, $school = null)
	{
        $this->color = $color;
        $this->size = $size;
        $this->name = $name;
        $this->school = $school;
	}
	public function getPencilType()
	{
		return "Pencil has ".$this->color->getColor()." color and ".$this->size->getSize()." size.";
	}
}
?>