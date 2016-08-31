<?php

namespace Kanboard\Console;

use Kanboard\Core\Csv;
use Kanboard\Model\SubtaskTimeTrackingModel;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SubtaskTimeTrackingExportCommand extends BaseCommand
{
    protected function configure()
    {
        $this
            ->setName('export:subtaskstimetracking')
            ->setDescription('Subtasks Time Tracking CSV export')
            ->addArgument('project_id', InputArgument::REQUIRED, 'Project id')
            ->addArgument('start_date', InputArgument::REQUIRED, 'Start date (YYYY-MM-DD)')
            ->addArgument('end_date', InputArgument::REQUIRED, 'End date (YYYY-MM-DD)');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $data = $this->subtaskTimeTrackingExport->export(
            $input->getArgument('project_id'),
            $input->getArgument('start_date'),
            $input->getArgument('end_date')
        );

        if (is_array($data)) {
            Html::output($data);
        }
    }
}
