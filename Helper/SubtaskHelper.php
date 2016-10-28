<?php

namespace Kanboard\Plugin\Timetrackingeditor\Helper;

use Kanboard\Model\SubtaskModel;

/**
 * SubtaskHelper
 *
 * @author Thomas Stinner
 */

class SubtaskHelper extends \Kanboard\Helper\SubtaskHelper
{

  /**
   * Get the link to toggle subtask status
   *
   * @access public
   * @param  array    $subtask
   * @param  integer  $project_id
   * @param  boolean  $refresh_table
   * @return string
   */
  public function toggleStatus(array $subtask, $project_id, $refresh_table = false)
  {
      if (! $this->helper->user->hasProjectAccess('SubtaskController', 'edit', $project_id)) {
          return $this->getTitle($subtask);
      }

      $params = array('project_id' => $project_id, 'task_id' => $subtask['task_id'], 'subtask_id' => $subtask['id'], 'refresh-table' => (int) $refresh_table, 'plugin' => 'Timetrackingeditor');

      if ($subtask['status'] == 0 && isset($this->sessionStorage->hasSubtaskInProgress) && $this->sessionStorage->hasSubtaskInProgress) {
          return $this->helper->url->link($this->getTitle($subtask), 'SubtaskRestrictionController', 'show', $params, false, 'popover');
      }

      if ($subtask['status'] == SubtaskModel::STATUS_TODO) {
        return $this->helper->url->link($this->getTitle($subtask), 'TimeTrackingEditorController', 'start', $params, false, 'popover');
      } else if ($subtask['status'] == SubtaskModel::STATUS_INPROGRESS) {
        return $this->helper->url->link($this->getTitle($subtask), 'TimeTrackingEditorController', 'stop', $params, false, 'popover');
      } else if ($subtask['status'] == SubtaskModel::STATUS_DONE) {
        $class = 'subtask-toggle-status '.($refresh_table ? 'subtask-refresh-table' : '');
        unset($params['plugin']);
        return $this->helper->url->link($this->getTitle($subtask), 'SubtaskStatusController', 'change', $params, false, $class);
      }

  }


}
