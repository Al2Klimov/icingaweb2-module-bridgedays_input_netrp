<?php

namespace Icinga\Module\Bridgedays_input_netrp;

use Icinga\Repository\IniRepository;

class NetRpsRepo extends IniRepository
{
    protected $queryColumns = ['netrp' => ['name', 'url']];

    protected $triggers = ['netrp'];

    protected $configs = ['netrp' => [
        'name'      => 'modules/bridgedays_input_netrp/netrps',
        'keyColumn' => 'name',
    ]];
}
