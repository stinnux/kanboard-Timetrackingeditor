<?php

namespace Kanboard\Plugin\Timetrackingeditor\Controller;
use Kanboard\Controller\BaseController;
use Kanboard\Controller\SubtaskStatusController;
use Kanboard\Model\SubtaskTimeTrackingModel;
use Kanboard\Plugin\Timetrackingeditor\Model\SubtasktimetrackingEditModel;
use Kanboard\Plugin\Timetrackingeditor\Model\SubtasktimetrackingCreationModel;
use Kanboard\Plugin\Timetrackingeditor\Validator\SubtasktimetrackingValidator;

/**
 * Column Controller
 *
 * @package  Kanboard\Plugin\Timetrackingeditor\Controller
 * @author   Frederic Guillot
 */
class TimeTrackingEditorController extends BaseController
{

/**
 * Show Form to start the timer
 * @access public
 * @param array $values
 * @param arry $errors
 */

 public function start(array $values = array(), array $errors = array())
 {
   $project = $this->getProject();

   if (empty($values)) {
     $values = array('project_id' => $project['id'],
                     'task_id' => $this->request->getIntegerParam('task_id'),
                     'subtask_id' => $this->request->getIntegerParam('subtask_id')
                   );
   }

   $values['subtask'] = $this->subtaskModel->getById($values['subtask_id']);

   $this->response->html($this->template->render('Timetrackingeditor:start', array(
     'values' => $values,
     'errors' => $errors,
     'project' => $project,
     'title' => t('Start a new timer')
   )));
 }

 /**
  * Show Form to stop the timer
  * @access public
  * @param array $values
  * @param arry $errors
  */

  public function stop(array $values = array(), array $errors = array())
  {
    $project = $this->getProject();

    if (empty($values)) {
      $values = array('project_id' => $project['id'],
                      'task_id' => $this->request->getIntegerParam('task_id'),
                      'subtask_id' => $this->request->getIntegerParam('subtask_id')
                    );
    }

    $values['subtask'] = $this->subtaskModel->getById($values['subtask_id']);

    $timetracking = $this->subtaskTimeTrackingEditModel
                      ->getOpenTimer(
                            $this->userSession->getId(),
                            $values['subtask_id']
                          );

    $values['comment'] = $timetracking["comment"];
    $values['is_billable'] = $timetracking['is_billable'];

    $this->response->html($this->template->render('Timetrackingeditor:stop', array(
      'values' => $values,
      'errors' => $errors,
      'project' => $project,
      'title' => t('Stop a timer')
    )));
  }


/**
 * Start the timer and save comment and is_billable
 * @access public
 *
 */

 public function startsave()
 {
   $values = $this->request->getValues();
   $project = $this->getProject();
   $task = $this->getTask();

   if (!$this->subtaskTimeTrackingModel->logStartTimeExtended(
        $values['subtask_id'],
        $this->userSession->getId(),
        $values['comment'],
        $values['is_billable'] ?: 0)) {
          // TODO: Best way to display the errors?
          $this->flash->failure("Another Timer is already running");
          return false;
        }

  $this->subtaskStatusModel->toggleStatus($values['subtask_id']);

   return $this->response->redirect($this->helper->url->to('SubtaskStatusController', 'change', array(
     'refresh-table' => 1,
     'project_id' => $project['id'],
     'task_id' => $task['id'],
     'subtask_id' => $values['subtask_id']
   )), true);
 }

 /**
  * Stop the timer and save comment and is_billable
  *
  * @access public
  */
  public function stopsave()
  {

    $values = $this->request->getValues();
    $project = $this->getProject();
    $task = $this->getTask();

    $this->subtaskTimeTrackingModel->logEndTimeExtended(
         $values['subtask_id'],
         $this->userSession->getId(),
         $values['comment'],
         $values['is_billable'] ?: 0);

   $this->subtaskStatusModel->toggleStatus($values['subtask_id']);

    return $this->response->redirect($this->helper->url->to('SubtaskStatusController', 'change', array(
      'refresh-table' => 1,
      'project_id' => $project['id'],
      'task_id' => $task['id'],
      'subtask_id' => $values['subtask_id']
    )), true);
  }
  /**
   * Show Form to create new entry
   * @access public
   * @param array $values
   * @param array $errors
    */
    public function create(array $values = array(), array $errors = array())
    {
      $project = $this->getProject();

      if (empty($values)) {
        $values = array('project_id' => $project['id'],
                        'task_id' => $this->request->getIntegerParam('task_id')
                      );
      }

      if ($this->request->getIntegerParam('subtask_id')) {
          $values['opposite_subtask_id'] = $this->request->getIntegerParam('subtask_id');
          $subtask = $this->subtaskModel->getById($values['opposite_subtask_id']);

          $values['subtask'] = $subtask['title'];
          $autofocus = "time_spent";
      } else {
          $autofocus = "subtask";
      }


      $this->response->html($this->template->render('Timetrackingeditor:create', array(
        'values' => $values,
        'errors' => $errors,
        'project' => $project,
        'autofocus' => $autofocus,
        'title' => t('Add new time tracking event')
      )));
    }

    /**
     * Edit an existing entry
     *
     * @access public
     * @param array $values
     * @param array $errors
     */
     public function edit(array $values = array(), array $errors = array())
     {
       $project = $this->getProject();

       if (empty($values)) {
         $values = array('project_id' => $project['id'],
                         'task_id' => $this->request->getIntegerParam('task_id'),
                         'subtask_id' => $this->request->getIntegerParam('subtask_id'),
                         'id' => $this->request->getIntegerParam('id')
                       );
       }

       $values = $this->subtaskTimeTrackingEditModel->getById($this->request->getIntegerParam('id'));

       $values = $this->dateParser->format($values, array('start'), $this->dateParser->getUserDateFormat());
       $values['subtask'] = $values['subtask_title'];
       $values['opposite_subtask_id']  = $values['subtask_id'];

       $this->response->html($this->template->render('Timetrackingeditor:edit', array(
         'values' => $values,
         'errors' => $errors,
         'project' => $project,
         'title' => t('Edit a time tracking event')
       )));
     }

    /**
    * Update a time tracking entry
    *
    * @access public
    * @param array $values
    * @param array $errors
    */
    public function update(array $values = array(), array $errors = array())
    {
      $project = $this->getProject();
      $values = $this->request->getValues();
      $oldtimetracking = $this->subtaskTimeTrackingModel->getById($values['id']);

      if (!isset($values['is_billable'])) {
        $values["is_billable"] = 0;
      }

      $validator = new SubtasktimetrackingValidator($this->container);
      list($valid, $errors) = $validator->validateModification($values);

      if ($valid && $this->subtaskTimeTrackingEditModel->update($values)) {
        $this->flash->success(t('Timetracking entry updated successfully.'));
        $this->updateTimespent($values['task_id'], $oldtimetracking['subtask_id'], $oldtimetracking['time_spent'] * -1);
        $this->updateTimespent($values['task_id'], $values['opposite_subtask_id'], $values['time_spent']);

        if ($oldtimetracking['is_billable'] == 1) {
            $this->updateTimebillable($values['task_id'], $oldtimetracking['opposite_subtask_id'], $oldtimetracking['time_spent'] * -1);
        }
        if ($values['is_billable'] == 1) {
            $this->updateTimebillable($values['task_id'], $values['opposite_subtask_id'], $values['time_spent']);
        }
        return $this->afterSave($project, $values);
      }

      $this->flash->failure(t('Unable to update your time tracking entry.'));
      return $this->edit($values, $errors);

    }


    /**
    * Save a newly created time tracking entry
    * @access public
    * @param array $values
    * @param array $errors
    */
    public function save(array $values = array(), array $errors = array())
    {
      $project = $this->getProject();
      $values = $this->request->getValues();

      list($valid, $errors) = $this->subtasktimetrackingValidator->validateCreation($values);

      $subtaskTimeTrackingCreationModel = new SubtasktimetrackingCreationModel($this->container);
      if ($valid && $subtaskTimeTrackingCreationModel->create($values)) {
         $this->updateTimespent($values['task_id'], $values['opposite_subtask_id'], $values['time_spent']);
          if (isset($values['is_billable']) && $values['is_billable'] == 1) {
            $this->updateTimebillable($values['task_id'], $values['opposite_subtask_id'], $values['time_spent']);
          }
          $this->flash->success(t('Timetracking entry added successfully.'));

          return $this->afterSave($project, $values);
       }

       $this->flash->failure(t('Unable to create your time tracking entry.'));
       return $this->create($values, $errors);

    }

    /**
     * Confirmation dialog before removing an entry
     *
     * @access public
     */
    public function confirm()
    {

       $id = $this->request->getIntegerParam('id');

        $timetracking = $this->subtaskTimeTrackingEditModel->getById($id);

        $this->response->html($this->template->render('timetrackingeditor:remove', array(
            'timetracking' => $timetracking,
        )));
    }

    /**
     * Remove an entry
     *
     * @access public
     */
    public function remove()
    {
        $this->checkCSRFParam();
        $id = $this->request->getIntegerParam('id');
        $timetracking = $this->subtaskTimeTrackingEditModel->getById($id);

        if ($this->subtaskTimeTrackingEditModel->remove($id)) {
            $this->updateTimespent($timetracking['task_id'], $timetracking['subtask_id'], $timetracking['time_spent'] * -1);
            if ($timetracking['is_billable'] == 1) {
                $this->updateTimebillable($timetracking['task_id'], $timetracking['subtask_id'], $timetracking['time_spent'] * -1);
            }
            $this->flash->success(t('Entry removed successfully.'));
        } else {
            $this->flash->failure(t('Unable to remove this entry.'));
        }

        $this->response->redirect($this->helper->url->to('TaskViewController', 'timetracking', array('project_id' => $timetracking['project_id'], 'task_id' => $timetracking['task_id'])), true);
    }

    /**
    * update time spent for the task
    *
    * @access private
    * @param int $task_id
    * @param int $subtask_id
    * @return bool
    */

    private function updateTimespent($task_id, $subtask_id, $time_spent)
    {
      $this->subtaskTimeTrackingModel->updateSubtaskTimeSpent($subtask_id, $time_spent);
      return $this->subtaskTimeTrackingModel->updateTaskTimeTracking($task_id);

    }

/**
 * update time billable for the task
 *
 * @access private
 * @param int $task_id
 * @param int $subtask_id
 * @return bool
 */
 private function updateTimebillable($task_id, $subtask_id, $time_billable)
 {
     $this->subtaskTimeTrackingModel->updateSubtaskTimeBillable($subtask_id, $time_billable);
     return $this->subtaskTimeTrackingModel->updateTaskTimeTracking($task_id);
 }


    /**
     * Present another, empty form if add_another is activated
     *
     * @access private
     * @param array $project
     * @param array $values
     */
     private function afterSave(array $project, array &$values)
     {
 	     if (isset($values['add_another']) && $values['add_another'] == 1) {
            return $this->create(array(
             'project_id' => $this->getProject()['id'],
                'subtask' => $values['subtask'],
		'opposite_subtask_id' => $values['opposite_subtask_id'],
                'task_id' => $values['task_id'],
                  'start' => $values['start'],
            'is_billable' => $values['is_billable'],
            'add_another' => 1,
            ));
        }

        return $this->response->redirect($this->helper->url->to('TaskViewController', 'timetracking', array('project_id' => $project['id'], 'task_id' => $values['task_id'])), true);
    }

}
