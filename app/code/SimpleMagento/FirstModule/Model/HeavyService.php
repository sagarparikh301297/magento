<?php


namespace SimpleMagento\FirstModule\Model;


class HeavyService
{
    public function __construct()
    {
        echo "service initiate"."</br>";
    }
    public function printHeavyServiceMes(){
        echo "Message from HS";
    }
}