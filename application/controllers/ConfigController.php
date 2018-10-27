<?php

namespace Icinga\Module\Bridgedays_input_netrp\Controllers;

use Icinga\Exception\NotFoundError;
use Icinga\Module\Bridgedays_input_netrp\Forms\ConfigForm;
use Icinga\Module\Bridgedays_input_netrp\NetRpsRepo;
use Icinga\Web\Controller;
use Icinga\Web\Url;
use Icinga\Web\Widget\Tabs;

class ConfigController extends Controller
{
    public function indexAction()
    {
        $this->assertPermission('config/modules');

        $this->view->repo = (new NetRpsRepo)->select(['name', 'url'])->order('name');
        $this->view->tabs = $this->Module()->getConfigTabs()->activate('netrps');
    }

    public function addAction()
    {
        $this->assertPermission('config/modules');

        $this->processForm($this->mkForm()->add());

        $this->mkTabs('add', 'plus', $this->translate('Add NETRP'), $this->translate('Add NETRP instance'));
    }

    public function editAction()
    {
        $this->assertPermission('config/modules');

        $this->processForm($this->mkForm()->edit($this->params->getRequired('name')));

        $this->mkTabs('edit', 'edit', $this->translate('Edit NETRP'), $this->translate('Edit NETRP instance'));
    }

    public function removeAction()
    {
        $this->assertPermission('config/modules');

        $this->processForm($this->mkForm()->remove($this->params->getRequired('name')));

        $this->mkTabs('remove', 'trash', $this->translate('Remove NETRP'), $this->translate('Remove NETRP instance'));
    }

    protected function mkForm()
    {
        return (new ConfigForm)
            ->setRepository(new NetRpsRepo)
            ->setRedirectUrl('bridgedays_input_netrp/config');
    }

    protected function processForm(ConfigForm $form)
    {
        try {
            $form->handleRequest();
        } catch (NotFoundError $_) {
            $this->httpNotFound($this->translate('No such NETRP instance'));
        }

        $this->view->form = $form;
    }

    protected function mkTabs($id, $icon, $label, $title)
    {
        $this->view->tabs = (new Tabs)->add($id, [
            'label'  => $label,
            'title'  => $title,
            'icon'   => $icon,
            'url'    => Url::fromRequest(),
            'active' => true
        ]);
    }
}
