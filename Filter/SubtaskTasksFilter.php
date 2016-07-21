<?php

namespace Kanboard\Plugin\Timetrackingeditor\Filter;


use Kanboard\Core\Filter\FilterInterface;
use Kanboard\Filter\BaseFilter;
use Kanboard\Model\ProjectModel;
use Kanboard\Model\SubtaskModel;

/**
 * Filter subtasks by task
 *
 * @package filter
 * @author  Thomas Stinner
 */
class SubtaskTasksFilter extends BaseFilter implements FilterInterface
{
    /**
     * Get search attribute
     *
     * @access public
     * @return string[]
     */
    public function getAttributes()
    {
        return array('task');
    }

    /**
     * Apply filter
     *
     * @access public
     * @return FilterInterface
     */
    public function apply()
    {
        if (is_int($this->value) || ctype_digit($this->value)) {
            $this->query->eq(SubtaskModel::TABLE.'.task_id', $this->value);
        } else {
            $this->query->ilike(TaskModel::TABLE.'.name', $this->value);
        }

        return $this;
    }
}
