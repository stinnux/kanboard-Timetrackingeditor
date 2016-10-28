<?php

namespace Kanboard\Plugin\Timetrackingeditor\Model;

use Kanboard\Core\Base;
use Kanboard\Event\TaskEvent;
use Kanboard\Model\SubtaskTimeTrackingModel;

/**
 * Task Creation
 *
 * @package  Kanboard\Plugin\Timetrackingeditor\Model
 * @author   Thomas Stinner
 */
class SubtaskTimeTrackingCreationModel extends Base
{
    /**
     * Create a time tracking event
     *
     * @access public
     * @param  array    $values   Form values
     * @return integer
     */
    public function create(array $values)
    {

        $this->prepare($values);
        $subtrackingid = $this->db->table(SubtaskTimeTrackingModel::TABLE)->persist($values);

        return (int) $subtrackingid;
    }

    /**
     * Prepare data
     *
     * @access public
     * @param  array    $values    Form values
     */
    public function prepare(array &$values)
    {
        if ($this->userSession->isLogged()) {
            $values['user_id'] = $this->userSession->getId();
        }

        $values["subtask_id"] = $values["opposite_subtask_id"];

        $this->helper->model->removeFields($values, array('project_id', 'task_id', 'opposite_subtask_id', 'subtask', 'add_another'));

        // Calculate end time
        $values = $this->dateParser->convert($values, array('start'), true);
        $values["end"] = $values["start"] + ($values['time_spent']*60*60);
    }
}
