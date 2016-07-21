<?php

namespace Kanboard\Plugin\Timetrackingeditor\Controller;
use Kanboard\Controller\BaseController;
use Kanboard\Plugin\Timetrackingeditor\Model\SubtasktimetrackingEditModel;
use Kanboard\Plugin\Timetrackingeditor\Model\SubtasktimetrackingCreationModel;
use Kanboard\Plugin\Timetrackingeditor\Validator\SubtasktimetrackingValidator;

/**
 * Column Controller
 *
 * @package  Kanboard\Plugin\Timetrackingeditor\Controller
 * @author   Frederic Guillot
 */
class TimetrackingeditorController extends BaseController
{


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

      $this->response->html($this->template->render('Timetrackingeditor:create', array(
        'values' => $values,
        'errors' => $errors,
        'project' => $project,
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

       $model = new SubtasktimetrackingEditModel($this->container);
       $timetracking = $model->getById($values['id']);

       $values['start'] = $timetracking['start'];
       $values['time_spent'] = $timetracking['time_spent'];
       $values = $this->dateParser->format($values, array('start'), $this->dateParser->getUserDateFormat());
       $values['subtask'] = $timetracking['subtask_title'];
       $values['opposite_subtask_id']  = $timetracking['subtask_id'];

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

      $validator = new SubtasktimetrackingValidator($this->container);
      list($valid, $errors) = $validator->validateModification($values);

      $subtasktimetrackingEditModel = new SubtasktimetrackingEditModel($this->container);
      if ($valid && $subtasktimetrackingEditModel->update($values)) {
        $this->flash->success(t('Timetracking entry updated successfully.'));
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

      $this->logger->debug("In Save");

      $validator = new SubtasktimetrackingValidator($this->container);
      list($valid, $errors) = $validator->validateCreation($values);

      $this->logger->debug("Valid? " . ($valid ? "True" : "False"));

      $subtasktimetrackingCreationModel = new SubtasktimetrackingCreationModel($this->container);
      if ($valid && $subtasktimetrackingCreationModel->create($values)) {
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

        $model = new SubtasktimetrackingEditModel($this->container);
        $timetracking = $model->getById($id);

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

        $model = new SubtasktimetrackingEditModel($this->container);

        $timetracking = $model->getById($id);


        if ($model->remove($id)) {
            $this->flash->success(t('Entry removed successfully.'));
        } else {
            $this->flash->failure(t('Unable to remove this entry.'));
        }

        $this->response->redirect($this->helper->url->to('TaskViewController', 'timetracking', array('project_id' => $timetracking['project_id'], 'task_id' => $timetracking['task_id'])), true);
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
            'add_another' => 1,
            ));
        }

        return $this->response->redirect($this->helper->url->to('TaskViewController', 'timetracking', array('project_id' => $project['id'], 'task_id' => $values['task_id'])), true);
    }

}
