<?php

namespace o0psCore\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Exception\RuntimeException;
use Zend\View\Model\ViewModel;
use o0psCore\Collector\RouteCollector;

/**
 * Class RuleController
 * @package o0psCore\Controller
 */
class RuleController extends AbstractActionController
{
    /**
     * @var \o0psCore\Options\ModuleOptions
     */
    protected $options;

    /**
     * @var \Zend\Mvc\I18n\Translator
     */
    protected $translatorHelper;

    /**
     * @var $viewHelperManager
     */
    protected $viewHelperManager;

    /**
     * @var \o0psCore\Mapper\Rule
     */
    protected $ruleMapper;

    /**
     * @var \Zend\View\Helper\HeadScript
     */
    protected $headScript;

    /**
     * @var \Zend\View\Helper\InlineScript
     */
    protected $inlineScript;

    /**
     * @var \Zend\View\Helper\HeadLink
     */
    protected $headLink;

    /**
     * @return ViewModel
     */
    public function indexAction()
    {
        return $this->redirect()->toRoute(RouteCollector::ROUTE_CMS);
    }

    /**
     * @return ViewModel
     */
    public function resourcesAction()
    {
        $this->getHeadLink()
             ->appendStylesheet('/assets/lib/datatables/css/dataTables.bootstrap.min.css');

        $this->getInlineScript()
             ->appendFile('/assets/lib/datatables/js/jquery.dataTables.min.js')
             ->appendFile('/assets/lib/datatables/js/dataTables.bootstrap.min.js')
             ->appendFile('/assets/lib/datatables/plugins/buttons/js/dataTables.buttons.js')
             ->appendFile('/assets/lib/datatables/plugins/buttons/js/buttons.html5.js')
             ->appendFile('/assets/lib/datatables/plugins/buttons/js/buttons.flash.js')
             ->appendFile('/assets/lib/datatables/plugins/buttons/js/buttons.print.js')
             ->appendFile('/assets/lib/datatables/plugins/buttons/js/buttons.colVis.js')
             ->appendFile('/assets/lib/datatables/plugins/buttons/js/buttons.bootstrap.js')
             ->appendFile('/assets/js/app-tables-datatables.js');

        $this->getInlineScript()->captureStart();
        echo <<<JS
            $(document).ready(function () {
                App.dataTables();
            });
JS;
        $this->getInlineScript()->captureEnd();

        $ruleMapper = $this->getRuleMapper();
        $resources  = $ruleMapper->findResources();

        $viewModel = new ViewModel([
            'resources' => $resources,
        ]);
        $viewModel->setTemplate('o0ps-core/rule/resources');

        return $viewModel;
    }

    /**
     * @return ViewModel
     */
    public function privilegesAction()
    {
        $this->getHeadLink()
             ->appendStylesheet('/assets/lib/datatables/css/dataTables.bootstrap.min.css');

        $this->getInlineScript()
             ->appendFile('/assets/lib/datatables/js/jquery.dataTables.min.js')
             ->appendFile('/assets/lib/datatables/js/dataTables.bootstrap.min.js')
             ->appendFile('/assets/lib/datatables/plugins/buttons/js/dataTables.buttons.js')
             ->appendFile('/assets/lib/datatables/plugins/buttons/js/buttons.html5.js')
             ->appendFile('/assets/lib/datatables/plugins/buttons/js/buttons.flash.js')
             ->appendFile('/assets/lib/datatables/plugins/buttons/js/buttons.print.js')
             ->appendFile('/assets/lib/datatables/plugins/buttons/js/buttons.colVis.js')
             ->appendFile('/assets/lib/datatables/plugins/buttons/js/buttons.bootstrap.js')
             ->appendFile('/assets/js/app-tables-datatables.js');

        $this->getInlineScript()->captureStart();
        echo <<<JS
            $(document).ready(function () {
                App.dataTables();
            });
JS;
        $this->getInlineScript()->captureEnd();

        $ruleMapper = $this->getRuleMapper();
        $privileges = $ruleMapper->findPrivileges();

        $viewModel = new ViewModel([
            'privileges' => $privileges,
        ]);
        $viewModel->setTemplate('o0ps-core/rule/privileges');

        return $viewModel;
    }

    /**
     * @return ViewModel
     */
    public function rolesAction()
    {
        $this->getHeadLink()
             ->appendStylesheet('/assets/lib/datatables/css/dataTables.bootstrap.min.css');

        $this->getInlineScript()
             ->appendFile('/assets/lib/datatables/js/jquery.dataTables.min.js')
             ->appendFile('/assets/lib/datatables/js/dataTables.bootstrap.min.js')
             ->appendFile('/assets/lib/datatables/plugins/buttons/js/dataTables.buttons.js')
             ->appendFile('/assets/lib/datatables/plugins/buttons/js/buttons.html5.js')
             ->appendFile('/assets/lib/datatables/plugins/buttons/js/buttons.flash.js')
             ->appendFile('/assets/lib/datatables/plugins/buttons/js/buttons.print.js')
             ->appendFile('/assets/lib/datatables/plugins/buttons/js/buttons.colVis.js')
             ->appendFile('/assets/lib/datatables/plugins/buttons/js/buttons.bootstrap.js')
             ->appendFile('/assets/js/app-tables-datatables.js');

        $this->getInlineScript()->captureStart();
        echo <<<JS
            $(document).ready(function () {
                App.dataTables();
            });
JS;
        $this->getInlineScript()->captureEnd();

        $ruleMapper = $this->getRuleMapper();
        $roles      = $ruleMapper->findRoles();

        $viewModel = new ViewModel([
            'roles' => $roles,
        ]);
        $viewModel->setTemplate('o0ps-core/rule/roles');

        return $viewModel;
    }

    /**
     * @param $options
     *
     * @return $this
     */
    public function setOptions($options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * get options
     *
     * @return \o0psCore\Options\ModuleOptions
     */
    protected function getOptions()
    {
        return $this->options;
    }

    /**
     * @param $translatorHelper
     *
     * @return $this
     */
    public function setTranslatorHelper($translatorHelper)
    {
        $this->translatorHelper = $translatorHelper;

        return $this;

    }

    /**
     * get translatorHelper
     *
     * @return  \Zend\Mvc\I18n\Translator
     */
    protected function getTranslatorHelper()
    {
        return $this->translatorHelper;
    }

    /**
     * @param $viewHelperManager
     *
     * @return $this
     */
    public function setViewHelperManager($viewHelperManager)
    {
        $this->viewHelperManager = $viewHelperManager;

        return $this;
    }

    /**
     * @return mixed
     */
    protected function getViewHelperManager()
    {
        if (null === $this->viewHelperManager) {
            throw new RuntimeException('No ViewHelperManager instance provided');
        }

        return $this->viewHelperManager;
    }

    /**
     * @return \o0psCore\Mapper\Rule
     */
    public function getRuleMapper()
    {
        return $this->ruleMapper;
    }

    /**
     * @param \o0psCore\Mapper\Rule $ruleMapper
     *
     * @return $this
     */
    public function setRuleMapper($ruleMapper)
    {
        $this->ruleMapper = $ruleMapper;

        return $this;
    }

    /**
     * @return \Zend\View\Helper\HeadScript
     */
    protected function getHeadScript()
    {
        if (null === $this->headScript) {
            $this->headScript = $this->getViewHelperManager()->get('HeadScript');
        }

        return $this->headScript;
    }

    /**
     * @return \Zend\View\Helper\InlineScript
     */
    protected function getInlineScript()
    {
        if (null === $this->inlineScript) {
            $this->inlineScript = $this->getViewHelperManager()->get('InlineScript');
        }

        return $this->inlineScript;
    }

    /**
     * @return \Zend\View\Helper\HeadLink
     */
    protected function getHeadLink()
    {
        if (null === $this->headLink) {
            $this->headLink = $this->getViewHelperManager()->get('HeadLink');
        }

        return $this->headLink;
    }
}