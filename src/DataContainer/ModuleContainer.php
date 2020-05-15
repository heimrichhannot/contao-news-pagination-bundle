<?php

namespace HeimrichHannot\NewsPaginationBundle\DataContainer;


use Contao\Controller;
use Contao\CoreBundle\Framework\ContaoFramework;

class ModuleContainer
{
    /**
     * @var ContaoFramework
     */
    private $framework;

    public function __construct(ContaoFramework $framework)
    {
        $this->framework = $framework;
    }

    public function getPaginationTemplate()
    {
        return $this->framework->getAdapter(Controller::class)->getTemplateGroup('pagination');
    }
}
