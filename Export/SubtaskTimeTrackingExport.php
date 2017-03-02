<?php

namespace Kanboard\Plugin\Timetrackingeditor\Export;

use Kanboard\Core\Base;
use Kanboard\Model\TaskModel;
use Kanboard\Model\SubtaskModel;
use Kanboard\Model\UserModel;
use Kanboard\Model\SubtaskTimeTrackingModel;
use Kanboard\Model\ProjectModel;

/**
 * SubtaskTimeTracking Export
 *
 * @package  export
 * @author   Thomas Stinner
 */
class SubtaskTimeTrackingExport extends Base
{
    /**
     * Fetch subtasks time tracking and return the prepared CSV
     *
     * @access public
     * @param  integer    $project_id      Project id
     * @param  mixed      $from            Start date (timestamp or user formatted date)
     * @param  mixed      $to              End date (timestamp or user formatted date)
     * @return array
     */
    public function export($project_id, $from, $to)
    {
        $subtaskstt = $this->getSubtasksTimeTracking($project_id, $from, $to);
        $results = array($this->getColumns());

        foreach ($subtaskstt as $subtasktt) {
            $results[] = $this->format($subtasktt);
        }

        return $results;
    }

    public function exportAll()
    {
      $subtaskstt = $this->getAllSubtasksTimeTracking();
      $results = array($this->getFormats());
      $results[] = $this->getColumns();

      foreach ($subtaskstt as $subtasktt) {
          $results[] = $this->format($subtasktt);
      }

      return $results;
  }

    /**
     * Get column titles
     *
     * @access public
     * @return string[]
     */
    public function getColumns()
    {
        return array(
            e('TimeTracking Id'),
            e('User Id'),
            e('Subtask Id'),
            e('start'),
            e('end'),
            e('Time Spent'),
            e('Is Billable?'),
            e('Comment'),
            e('Task Id'),
            e('Task Title'),
            e('Subtask Title'),
            e('Project Id'),
            e('Project Name'),
            e('Color Id'),
            e('Username'),
            e('User Fullname'),
        );
    }

    /**
     * Get Format of the getColumns
     *
     * @access public
     * @return string[]
     */
     public function getFormats()
     {
         return array(
             'num',
             'num',
             'num',
             'date',
             'date',
             'dec',
             'bool',
             'text',
             'num',
             'text',
             'text',
             'num',
             'text',
             'num',
             'text',
             'text'
         );
     }

    /**
     * Format the output of a subtask array
     *
     * @access public
     * @param  array     $subtask        Subtask properties
     * @return array
     */
    public function format(array $subtasktt)
    {
        $values = array();
        $values[] = $subtasktt['id'];
        $values[] = $subtasktt['user_id'];
        $values[] = $subtasktt['subtask_id'];
        $values[] = $this->helper->dt->date($subtasktt['start']);
        $values[] = $this->helper->dt->date($subtasktt['end']);
        $values[] = str_replace(".",",",$subtasktt['time_spent']);
        $values[] = $subtasktt['is_billable'];
        $values[] = $this->helper->text->markdown($subtasktt['comment']);
        $values[] = $subtasktt['task_id'];
        $values[] = $subtasktt['task_title'];
        $values[] = $subtasktt['subtask_title'];
        $values[] = $subtasktt['project_id'];
        $values[] = $subtasktt['project_name'];
        $values[] = $subtasktt['color_id'];
        $values[] = $subtasktt['username'];
        $values[] = $subtasktt['user_fullname'];
        return $values;
    }

    /**
     * Get all time tracking events for a given project
     *
     * @access public
     * @param  integer   $project_id    Project id
     * @param  mixed     $from          Start date (timestamp or user formatted date)
     * @param  mixed     $to            End date (timestamp or user formatted date)
     * @return array
     */
    public function getSubtasksTimeTracking($project_id, $from, $to)
    {
        if (! is_numeric($from)) {
            $from = $this->dateParser->removeTimeFromTimestamp($this->dateParser->getTimestamp($from));
        }

        if (! is_numeric($to)) {
            $to = $this->dateParser->removeTimeFromTimestamp(strtotime('+1 day', $this->dateParser->getTimestamp($to)));
        }

        return $this->db->table(SubtaskTimeTrackingModel::TABLE)
                        ->eq('project_id', $project_id)
                        ->columns(
                            SubtaskTimeTrackingModel::TABLE.'.id',
                            SubtaskTimeTrackingModel::TABLE.'.user_id',
                            SubtaskTimeTrackingModel::TABLE.'.subtask_id',
                            SubtaskTimeTrackingModel::TABLE.'.start',
                            SubtaskTimeTrackingModel::TABLE.'.end',
                            SubtaskTimeTrackingModel::TABLE.'.time_spent',
                            SubtaskTimeTrackingModel::TABLE.'.is_billable',
                            SubtaskTimeTrackingModel::TABLE.'.comment',
                            SubtaskModel::TABLE.'.task_id',
                            SubtaskModel::TABLE.'.title AS subtask_title',
                            TaskModel::TABLE.'.project_id',
                            ProjectModel::TABLE.'.name AS project_name',
                            TaskModel::TABLE.'.title AS task_title',
                            TaskModel::TABLE.'.color_id',
                            UserModel::TABLE.'.username',
                            UserModel::TABLE.'.name AS user_fullname'
                        )
                        ->join(SubtaskModel::TABLE, 'id', 'subtask_id')
                        ->join(TaskModel::TABLE, 'id', 'task_id', SubtaskModel::TABLE)
                        ->join(UserModel::TABLE, 'id', 'user_id', SubtaskTimeTrackingModel::TABLE)
                        ->join(ProjectModel::TABLE, 'id', 'project_id', TaskModel::TABLE)
                        ->gte(SubtaskTimeTrackingModel::TABLE.'.start', $from)
                        ->lte(SubtaskTimeTrackingModel::TABLE.'.start', $to)
                        ->eq(TaskModel::TABLE.'.project_id', $project_id)
                        ->findAll();
    }

    public function getAllSubtasksTimeTracking()
    {

              return $this->db->table(SubtaskTimeTrackingModel::TABLE)
                              ->columns(
                                  SubtaskTimeTrackingModel::TABLE.'.id',
                                  SubtaskTimeTrackingModel::TABLE.'.user_id',
                                  SubtaskTimeTrackingModel::TABLE.'.subtask_id',
                                  SubtaskTimeTrackingModel::TABLE.'.start',
                                  SubtaskTimeTrackingModel::TABLE.'.end',
                                  SubtaskTimeTrackingModel::TABLE.'.time_spent',
                                  SubtaskTimeTrackingModel::TABLE.'.is_billable',
                                  SubtaskTimeTrackingModel::TABLE.'.comment',
                                  SubtaskModel::TABLE.'.task_id',
                                  SubtaskModel::TABLE.'.title AS subtask_title',
                                  TaskModel::TABLE.'.project_id',
                                  ProjectModel::TABLE.'.name AS project_name',
                                  TaskModel::TABLE.'.title AS task_title',
                                  TaskModel::TABLE.'.color_id',
                                  UserModel::TABLE.'.username',
                                  UserModel::TABLE.'.name AS user_fullname'
                              )
                              ->join(SubtaskModel::TABLE, 'id', 'subtask_id')
                              ->join(TaskModel::TABLE, 'id', 'task_id', SubtaskModel::TABLE)
                              ->join(UserModel::TABLE, 'id', 'user_id', SubtaskTimeTrackingModel::TABLE)
                              ->join(ProjectModel::TABLE, 'id', 'project_id', TaskModel::TABLE)
                              ->findAll();


    }
}
