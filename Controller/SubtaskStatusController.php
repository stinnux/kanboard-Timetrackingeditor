<?php

namespace Kanboard\Plugin\Timetrackingeditor\Controller;

use Kanboard\Model\SubtaskModel;

/**
 * SubtaskStatusController.
 *
 * @author Thomas Stinner
 */
class SubtaskStatusController extends \Kanboard\Controller\SubtaskStatusController
{
    /**
     * Change status to the next status: Toto -> In Progress -> Done.
     */
    public function change()
    {
        $task = $this->getTask();
        $subtask = $this->getSubtask();

        if ($subtask['status'] == SubtaskModel::STATUS_DONE) {
            $status = SubtaskModel::STATUS_TODO;
        } else {
            $status = SubtaskModel::STATUS_DONE;
        }
        $subtask['status'] = $status;
        $this->subtaskModel->update($subtask);

        if ($this->request->getIntegerParam('refresh-table') === 0) {

            $html = $this->helper->subtask->toggleStatus($subtask, $task['project_id']);
        } else {
            $html = $this->renderTable($task);
        }

        $this->response->html($html);
    }
}
?>
