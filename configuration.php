<?php

/** @var \Icinga\Application\Modules\Module $this */

$this->provideConfigTab('netrps', [
    'url'   => 'config',
    'label' => $this->translate('NETRPs'),
    'title' => $this->translate('NETRP instances'),
    'icon'  => 'calendar'
]);
