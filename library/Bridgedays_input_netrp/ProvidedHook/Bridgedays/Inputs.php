<?php

namespace Icinga\Module\Bridgedays_input_netrp\ProvidedHook\Bridgedays;

use Icinga\Module\Bridgedays\Hook\InputsHook;
use Icinga\Module\Bridgedays_input_netrp\NetRpInput;
use Icinga\Module\Bridgedays_input_netrp\NetRpsRepo;

class Inputs extends InputsHook
{
    public function getInputs()
    {
        $inputs = [];

        foreach ((new NetRpsRepo)->select(['name', 'url']) as $netRp) {
            $inputs[] = new NetRpInput($netRp->name, $netRp->url);
        }

        return $inputs;
    }
}
