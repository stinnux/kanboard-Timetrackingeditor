<?php

namespace Kanboard\Plugin\Timetrackingeditor\Filter;

use Kanboard\Core\Filter\FilterInterface;
use Kanboard\Filter\BaseFilter;
use Kanboard\Model\SubtaskModel;

/**
 * Filter subtasks by id
 *
 * @package filter
 * @author  Thomas Stinner
 */
class SubtaskIdFilter extends BaseFilter implements FilterInterface
{
    /**
     * Get search attribute
     *
     * @access public
     * @return string[]
     */
    public function getAttributes()
    {
        return array('id');
    }

    /**
     * Apply filter
     *
     * @access public
     * @return FilterInterface
     */
    public function apply()
    {
        $this->query->eq(SubtaskModel::TABLE.'.id', $this->value);
        return $this;
    }
}
