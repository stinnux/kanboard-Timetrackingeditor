<?php

namespace Kanboard\Plugin\Timetrackingeditor;

use Kanboard\Core\Translator;
use Kanboard\Core\Plugin\Base;

class Plugin extends Base
{
    public function initialize()
    {
      $this->hook->on("template:layout:css", array("template" => "plugins/Timetrackingeditor/assets/css/timetrackingeditor.css"));
      $this->template->setTemplateOverride('task/time_tracking_details', 'timetrackingeditor:time_tracking_editor');
      $this->template->setTemplateOverride('subtask/table', 'timetrackingeditor:subtask/table');
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
            'SubtasktimetrackingEditModel'
        ),
        'Plugin\Timetrackingeditor\Filter' => array(
          'SubtaskFilter',
          'SubtaskTaskFilter',
          'SubtaskTitleFilter'
        ),
        'Plugin\Timetrackingeditor\Validator' => array(
          'SubtasktimetrackingValidator'
        ),
        'Plugin\Timetrackingeditor\Formatter' => array(
          'SubtaskAutoCompleteFormatter'
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
