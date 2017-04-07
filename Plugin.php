<?php

namespace Kanboard\Plugin\Timetrackingeditor;

use Kanboard\Core\Translator;
use Kanboard\Core\Plugin\Base;
use Kanboard\Plugin\Timetrackingeditor\Helper\SubtaskHelper;
use Kanboard\Plugin\Timetrackingeditor\Model\SubtaskTimeTrackingModel;
use Kanboard\Plugin\Timetrackingeditor\Console\AllSubtaskTimeTrackingExportCommand;

class Plugin extends Base
{
    public function initialize()
    {
      $this->hook->on("template:layout:css", array("template" => "plugins/Timetrackingeditor/assets/css/timetrackingeditor.css"));
      $this->template->setTemplateOverride('task/time_tracking_details', 'timetrackingeditor:time_tracking_editor');
      $this->template->setTemplateOverride('subtask/table', 'timetrackingeditor:subtask/table');

      # $this->helper->register("subtask", "Kanboard\Plugin\Timetrackingeditor\Helper\SubtaskHelper");

      $this->container["cli"]->add(new AllSubtaskTimeTrackingExportCommand($this->container));
    }

    public function onStartup()
    {
        Translator::load($this->languageModel->getCurrentLanguage(), __DIR__.'/Locale');
    }

    public function getClasses()
    {
      return array(
        'Plugin\Timetrackingeditor\Model' => array(
            'SubtaskTimeTrackingCreationModel',
            'SubtaskTimeTrackingEditModel',
            'SubtaskTimeTrackingModel',
        ),
        'Plugin\Timetrackingeditor\Filter' => array(
          'SubtaskFilter',
          'SubtaskTaskFilter',
          'SubtaskTitleFilter'
        ),
        'Plugin\Timetrackingeditor\Console' => array(
          'AllSubtaskTimeTrackingExportCommand'
        ),
        'Plugin\Timetrackingeditor\Controller' => array(
          'SubtaskStatusController',
          'SubtaskAjaxController',
          'TimeTrackingEditorController'
        ),
        'Plugin\Timetrackingeditor\Export' => array(
          'SubtaskTimeTrackingExport'
        ),
        'Plugin\Timetrackingeditor\Validator' => array(
          'SubtaskTimeTrackingValidator'
        ),
        'Plugin\Timetrackingeditor\Formatter' => array(
          'SubtaskAutoCompleteFormatter'
        ),
      );
    }

    public function getPluginName()
    {
        return 'TimeTrackingEditor';
    }

    public function getPluginDescription()
    {
        return t('Allows Editing of TimeTracking Values');
    }

    public function getPluginAuthor()
    {
        return 'Thomas Stinner';
    }

    public function getPluginVersion()
    {
        return '1.0.15';
    }

    public function getPluginHomepage()
    {
        return 'https://github.com/stinnux/kanboard-timetrackingeditor';
    }
}
