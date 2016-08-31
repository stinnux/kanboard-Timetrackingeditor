<?php

namespace Kanboard\Plugin\Timetrackingeditor;

use Kanboard\Core\Translator;
use Kanboard\Core\Plugin\Base;
use Kanboard\Plugin\Timetrackingeditor\Helper\SubtaskHelper;
use Kanboard\Plugin\Timetrackingeditor\Model\SubtasktimetrackingModel;
use Kanboard\Plugin\Timetrackingeditor\Console\AllSubtaskTimeTrackingExportCommand;

class Plugin extends Base
{
    public function initialize()
    {
      $this->hook->on("template:layout:css", array("template" => "plugins/Timetrackingeditor/assets/css/timetrackingeditor.css"));
      $this->template->setTemplateOverride('task/time_tracking_details', 'timetrackingeditor:time_tracking_editor');
      $this->template->setTemplateOverride('subtask/table', 'timetrackingeditor:subtask/table');

      $this->helper->register("subtask", "Kanboard\Plugin\Timetrackingeditor\Helper\SubtaskHelper");
      $this->container["subtaskTimeTrackingModel"] = function($c) { return new SubtasktimetrackingModel($c); };
      // $this->container["Html"] = function($c) { return new Html($c); };

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
            'SubtasktimetrackingCreationModel',
            'SubtasktimetrackingEditModel',
            'SubtasktimetrackingModel',
        ),
        'Plugin\Timetrackingeditor\Filter' => array(
          'SubtaskFilter',
          'SubtaskTaskFilter',
          'SubtaskTitleFilter'
        ),
        'Plugin\Timetrackingeditor\Console' => array(
          'AllSubtaskTimeTrackingExportCommand'
        ),
        'Plugin\Timetrackingeditor\Export' => array(
          'SubtaskTimeTrackingExport'
        ),
        'Plugin\Timetrackingeditor\Validator' => array(
          'SubtasktimetrackingValidator'
        ),
        'Plugin\Timetrackingeditor\Formatter' => array(
          'SubtaskAutoCompleteFormatter'
        ),
        'Plugin\Timetrackingeditor\Helper' => array(
          'SubtaskHelper'
        )
      );
    }

    public function getPluginName()
    {
        return 'Timetrackingeditor';
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
        return '0.0.1';
    }

    public function getPluginHomepage()
    {
        return 'https://github.com/stinnux/kanboard-timetrackingeditor';
    }
}
