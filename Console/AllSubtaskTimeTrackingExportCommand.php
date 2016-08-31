<?php

namespace Kanboard\Plugin\Timetrackingeditor\Console;

use Kanboard\Plugin\Timetrackingeditor\Html;
use Kanboard\Model\SubtaskTimeTrackingModel;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Kanboard\Console\BaseCommand;

class AllSubtaskTimeTrackingExportCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('export:allsubtaskstimetracking')
            ->setDescription('Subtasks Time Tracking CSV export for all events');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $data = $this->subtaskTimeTrackingExport->exportAll();

        if (is_array($data)) {
            Html::output($data);
        }
    }
}
