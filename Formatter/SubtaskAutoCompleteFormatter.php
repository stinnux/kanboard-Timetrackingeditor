<?php

namespace Kanboard\Plugin\Timetrackingeditor\Formatter;

use Kanboard\Core\Filter\FormatterInterface;
use Kanboard\Model\ProjectModel;
use Kanboard\Model\SubtaskModel;
use Kanboard\Model\TaskModel;
use Kanboard\Formatter\BaseFormatter;

/**
 * Subtask AutoComplete Formatter
 *
 * @package formatter
 * @author  Thomas Stinner
 */
class SubtaskAutoCompleteFormatter extends BaseFormatter implements FormatterInterface
{
    /**
     * Apply formatter
     *
     * @access public
     * @return array
     */
    public function format()
    {
        $subtasks = $this->query->columns(
            SubtaskModel::TABLE.'.id',
            SubtaskModel::TABLE.'.title'
        )->asc(SubtaskModel::TABLE.'.id')->findAll();

        foreach ($subtasks as &$subtask) {
            $subtask['value'] = $subtask['title'];
            $subtask['label'] = ' > #'.$subtask['id'].' '.$subtask['title'];
        }

        return $subtasks;
    }
}
