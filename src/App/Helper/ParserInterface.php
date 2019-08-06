<?php
namespace App\Helper;

interface ParserInterface
{
    /**
     * @return array
     */
    public function getData(): array;

    /**
     * @return array
     */
    public function getAsAssociateArray(): array;
}