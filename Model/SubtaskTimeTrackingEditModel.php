<?php

namespace Kanboard\Plugin\Timetrackingeditor\Model;

use Kanboard\Core\Base;
use Kanboard\Model\SubtaskModel;
use Kanboard\Model\TaskModel;

/**
 * Task Creation
 *
 * @package  Kanboard\Plugin\Timetrackingeditor\Model
 * @author   Thomas Stinner
 */
class SubtaskTimeTrackingEditModel extends Base
{

  /**
   * GetOpenTimer
   * Returns the Open (started but not finished) Time Tracking entry
   * for a specific user and subtask
   *
   * @access public
   * @param  integer    $user_id   User id
   * @param  integer    $subtask_id Subtask id
   * @return \Picodb\Table
   */
   public function getOpenTimer($user_id, $subtask_id)
   {
     return $this->db 
                  ->table(SubtaskTimeTrackingModel::TABLE)
                  ->columns(
                      SubtaskTimeTrackingModel::TABLE.'.id',
                      SubtaskTimeTrackingModel::TABLE.'.subtask_id',
                      SubtaskTimeTrackingModel::TABLE.'.end',
                      SubtaskTimeTrackingModel::TABLE.'.start',
                      SubtaskTimeTrackingModel::TABLE.'.time_spent',
                      SubtaskTimeTrackingModel::TABLE.'.comment',
                      SubtaskTimeTrackingModel::TABLE.'.is_billable'
                  )
                  ->eq(SubtaskTimeTrackingModel::TABLE.'.subtask_id', $subtask_id)
                  ->eq(SubtaskTimeTrackingModel::TABLE.'.user_id', $user_id)
                  ->eq(SubtaskTimeTrackingModel::TABLE.'.end', 0)
                  ->findOne();
   }

    /**
     * Get by Id
     *
     * @access public
     * @param int $id TimetrackingId
     * @return \PicoDb\Table
     */
     public function getById($id)
     {
       return $this->db
                   ->table(SubtaskTimeTrackingModel::TABLE)
                   ->columns(
                       SubtaskTimeTrackingModel::TABLE.'.id',
                       SubtaskTimeTrackingModel::TABLE.'.subtask_id',
                       SubtaskTimeTrackingModel::TABLE.'.end',
                       SubtaskTimeTrackingModel::TABLE.'.start',
                       SubtaskTimeTrackingModel::TABLE.'.time_spent',
                       SubtaskTimeTrackingModel::TABLE.'.comment',
                       SubtaskTimeTrackingModel::TABLE.'.is_billable',
                       SubtaskModel::TABLE.'.task_id',
                       SubtaskModel::TABLE.'.title AS subtask_title',
                       TaskModel::TABLE.'.title AS task_title',
                       TaskModel::TABLE.'.project_id',
                       TaskModel::TABLE.'.color_id'
                   )
                   ->join(SubtaskModel::TABLE, 'id', 'subtask_id')
                   ->join(TaskModel::TABLE, 'id', 'task_id', SubtaskModel::TABLE)
                   ->eq(SubtaskTimeTrackingModel::TABLE.'.id', $id)
                   ->findOne();

     }

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
     * Update a time tracking event
     *
     * @access public
     * @param array $values
     * @return boolean
     */
     public function update(array $values)
     {
       $this->prepare($values);

       return $this->db->table(SubtaskTimeTrackingModel::TABLE)->eq('id', $values['id'])->update($values);
     }


     /**
      * remove an entry
      *
      * @access public
      * @param int $id
      * @return boolran
      */
      public function remove($id)
      {
        return $this->db->table(SubtaskTimeTrackingModel::TABLE)->eq('id', $id)->remove();

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

        $this->helper->model->removeFields($values, array('project_id', 'task_id', 'opposite_subtask_id', 'subtask', 'add_another',  'old_time_spent', 'old_opposite_subtask_id'));

        // Calculate end time
        $values = $this->dateParser->convert($values, array('start'), true);
        $values["end"] = $values["start"] + ($values['time_spent']*60*60);
    }
}
