<div class="dropdown">
    <a href="#" class="dropdown-menu dropdown-menu-link-icon"><i class="fa fa-cog fa-fw"></i><i class="fa fa-caret-down"></i></a>
    <ul>
        <li>
            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
            <?= $this->url->link(t('Edit'), 'TimetrackingeditorController', 'edit', array('plugin' => 'timetrackingeditor', 'task_id' => $task['id'], 'project_id' => $task['project_id'], 'subtask_id' => $subtask_id, 'id' => $id), false, 'popover') ?>
        </li>
        <li>
            <i class="fa fa-trash-o" aria-hidden="true"></i>
            <?= $this->url->link(t('Remove'), 'TimetrackingeditorController', 'confirm', array('plugin' => 'timetrackingeditor', 'task_id' => $task['id'], 'project_id' => $task['project_id'], 'subtask_id' => $subtask_id, 'id' => $id), false, 'popover') ?>
        </li>
    </ul>
</div>
