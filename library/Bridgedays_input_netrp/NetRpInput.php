<?php

namespace Icinga\Module\Bridgedays_input_netrp;

use Icinga\Module\Bridgedays\Intrface\Input;

class NetRpInput implements Input
{
    protected $name;
    protected $url;

    public function __construct($name, $url)
    {
        $this->name = $name;
        $this->url = $url;
    }

    public function getId()
    {
        return md5($this->name);
    }

    public function getName()
    {
        return sprintf(mt('bridgedays_input_netrp', 'NETRP: %s'), $this->name);
    }

    public function getFields()
    {
        return [];
    }
}
