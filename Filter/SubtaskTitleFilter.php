<?php

namespace Kanboard\Plugin\TimetrackingEditor\Filter;

use Kanboard\Core\Filter\FilterInterface;
use Kanboard\Filter\BaseFilter;
use Kanboard\Model\SubtaskModel;

/**
 * Filter Subtasks by title
 *
 * @package filter
 * @author  Thomas Stinner
 */
class SubtaskTitleFilter extends BaseFilter implements FilterInterface
{
    /**
     * Get search attribute
     *
     * @access public
     * @return string[]
     */
    public function getAttributes()
    {
        return array('title');
    }

    /**
     * Apply filter
     *
     * @access public
     * @return FilterInterface
     */
    public function apply()
    {
        if (ctype_digit($this->value) || (strlen($this->value) > 1 && $this->value{0} === '#' && ctype_digit(substr($this->value, 1)))) {
            $this->query->beginOr();
            $this->query->eq(SubtaskModel::TABLE.'.id', str_replace('#', '', $this->value));
            $this->query->ilike(SubtaskModel::TABLE.'.title', '%'.$this->value.'%');
            $this->query->closeOr();
        } else {
            $this->query->ilike(SubtaskModel::TABLE.'.title', '%'.$this->value.'%');
        }

        return $this;
    }
}
