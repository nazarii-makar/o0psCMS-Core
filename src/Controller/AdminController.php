<?php

namespace o0psCore\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Exception\RuntimeException;
use Zend\View\Model\ViewModel;

class AdminController extends AbstractActionController
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
        $this->getHeadLink()
            ->appendStylesheet('/assets/lib/jquery.vectormap/jquery-jvectormap-1.2.2.css')
            ->appendStylesheet('/assets/lib/jquery.gritter/css/jquery.gritter.css');

        $this->getInlineScript()
            ->appendFile('/assets/lib/jquery-flot/jquery.flot.js')
            ->appendFile('/assets/lib/jquery-flot/jquery.flot.pie.js')
            ->appendFile('/assets/lib/jquery-flot/jquery.flot.resize.js')
            ->appendFile('/assets/lib/jquery-flot/plugins/jquery.flot.orderBars.js')
            ->appendFile('/assets/lib/jquery-flot/plugins/curvedLines.js')
            ->appendFile('/assets/lib/jquery.sparkline/jquery.sparkline.min.js')
            ->appendFile('/assets/lib/jquery-ui/jquery-ui.min.js')
            ->appendFile('/assets/lib/jquery.vectormap/jquery-jvectormap-1.2.2.min.js')
            ->appendFile('/assets/lib/jquery.vectormap/maps/jquery-jvectormap-us-merc-en.js')
            ->appendFile('/assets/lib/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js')
            ->appendFile('/assets/lib/jquery.vectormap/maps/jquery-jvectormap-uk-mill-en.js')
            ->appendFile('/assets/lib/jquery.vectormap/maps/jquery-jvectormap-fr-merc-en.js')
            ->appendFile('/assets/lib/jquery.vectormap/maps/jquery-jvectormap-us-il-chicago-mill-en.js')
            ->appendFile('/assets/lib/jquery.vectormap/maps/jquery-jvectormap-au-mill-en.js')
            ->appendFile('/assets/lib/jquery.vectormap/maps/jquery-jvectormap-in-mill-en.js')
            ->appendFile('/assets/lib/jquery.vectormap/maps/jquery-jvectormap-map.js')
            ->appendFile('/assets/lib/jquery.vectormap/maps/jquery-jvectormap-ca-lcc-en.js')
            ->appendFile('/assets/lib/countup/countUp.min.js')
            ->appendFile('/assets/lib/chartjs/Chart.min.js')
            ->appendFile('/assets/js/app-dashboard.js')
            ->appendFile('/assets/lib/jquery.gritter/js/jquery.gritter.js');

        $this->getInlineScript()->captureStart();
        echo <<<JS
            $(document).ready(function () {
                App.dashboard();
            });
JS;
        $this->getInlineScript()->captureEnd();

        $viewModel = new ViewModel();
        $viewModel->setTemplate('o0ps-core/admin/index');
        return $viewModel;
    }

    /**
     * @param $options
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
        if (null === $this->viewHelperManager)
            throw new RuntimeException('No ViewHelperManager instance provided');

        return $this->viewHelperManager;
    }

    /**
     * @return \Zend\View\Helper\HeadScript
     */
    protected function getHeadScript()
    {
        if (null === $this->headScript)
            $this->headScript = $this->getViewHelperManager()->get('HeadScript');

        return $this->headScript;
    }

    /**
     * @return \Zend\View\Helper\InlineScript
     */
    protected function getInlineScript()
    {
        if (null === $this->inlineScript)
            $this->inlineScript = $this->getViewHelperManager()->get('InlineScript');

        return $this->inlineScript;
    }

    /**
     * @return \Zend\View\Helper\HeadLink
     */
    protected function getHeadLink()
    {
        if (null === $this->headLink)
            $this->headLink = $this->getViewHelperManager()->get('HeadLink');

        return $this->headLink;
    }
}