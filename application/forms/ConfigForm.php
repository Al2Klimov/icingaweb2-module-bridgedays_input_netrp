<?php

namespace Icinga\Module\Bridgedays_input_netrp\Forms;

use Icinga\Data\Filter\Filter;
use Icinga\Forms\RepositoryForm;

class ConfigForm extends RepositoryForm
{
    protected function createCommonElements()
    {
        $this->addElement('text', 'url', [
            'label'       => $this->translate('URL'),
            'description' => $this->translate('NETRP instance URL'),
            'required'    => true,
        ]);
    }

    protected function createInsertElements(array $formData)
    {
        $this->addElement('text', 'name', [
            'label'       => $this->translate('Name'),
            'description' => $this->translate('NETRP instance name'),
            'required'    => true,
        ]);

        $this->createCommonElements();
        $this->setSubmitLabel($this->translate('Add'));
    }

    protected function createUpdateElements(array $formData)
    {
        $this->createCommonElements();
        $this->setSubmitLabel($this->translate('Save'));
    }

    protected function createDeleteElements(array $formData)
    {
        $this->setSubmitLabel($this->translate('Remove'));
    }

    protected function createFilter()
    {
        return Filter::where('name', $this->getIdentifier());
    }

    protected function getInsertMessage($success)
    {
        return $success
            ? $this->translate('Successfully added NETRP instance')
            : $this->translate('Failed to add NETRP instance');
    }

    protected function getUpdateMessage($success)
    {
        return $success
            ? $this->translate('Successfully changed NETRP instance')
            : $this->translate('Failed to change NETRP instance');
    }

    protected function getDeleteMessage($success)
    {
        return $success
            ? $this->translate('Successfully removed NETRP instance')
            : $this->translate('Failed to remove NETRP instance');
    }
}
