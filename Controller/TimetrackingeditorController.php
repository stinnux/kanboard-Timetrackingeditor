<?php

namespace Kanboard\Plugin\Timetrackingeditor\Controller;

use Kanboard\Controller\BaseController;
use Kanboard\Plugin\Timetrackingeditor\Validator\SubtasktimetrackingValidator;
use Kanboard\Plugin\Timetrackingeditor\Model\SubtasktimetrackingCreationModel;

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
                'user_id' => $values['user_id'],
                'subtask' => $values['subtask'],
		'opposite_subtask_id' => $values['opposite_subtask_id'],
                'start' => $values['start'],
                'add_another' => 1,
            ));
        }

        return $this->response->redirect($this->helper->url->to('TaskViewController', 'timetracking', array('project_id' => $project['id'], 'task_id' => $values['task_id'])), true);
    }

}
