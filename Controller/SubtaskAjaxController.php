<?php

namespace Kanboard\Plugin\Timetrackingeditor\Controller;

use Kanboard\Controller\BaseController;
use Kanboard\Core\Filter\QueryBuilder;
use Kanboard\Model\SubtaskModel;
use Kanboard\Plugin\Timetrackingeditor\Filter\SubtaskTasksFilter;
use Kanboard\Plugin\Timetrackingeditor\Filter\SubtaskIdFilter;
use Kanboard\Plugin\Timetrackingeditor\Filter\SubtaskTitleFilter;
use Kanboard\Plugin\Timetrackingeditor\Formatter\SubtaskAutoCompleteFormatter;

/**
 * Task Ajax Controller
 *
 * @package  Kanboard\Plugin\Timetrackingeditor\Controller
 * @author   Thomas Stinner
 */
class SubtaskAjaxController extends BaseController
{
    /**
     * Task auto-completion (Ajax)
     *
     * @access public
     */
    public function autocomplete()
    {
        $search = $this->request->getStringParam('term');
        $task_id = $this->request->getIntegerParam('task_id');

        $subtaskQuery = new QueryBuilder();
        $subtaskQuery->withQuery($this->db
              ->table(SubtaskModel::TABLE)
              ->eq('task_id', $task_id)
              ->columns(SubtaskModel::TABLE.'*'));

        if (ctype_digit($search)) {
            $subtaskQuery->withFilter(new SubtaskIdFilter($search));
        } else {
            $subtaskQuery->withFilter(new SubtaskTitleFilter($search));
        }

        $this->response->json($subtaskQuery->format(new SubtaskAutoCompleteFormatter($this->container)));
    }
}
