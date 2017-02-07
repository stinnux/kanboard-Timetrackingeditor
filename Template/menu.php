<div class="dropdown">
    <a href="#" class="dropdown-menu dropdown-menu-link-icon"><i class="fa fa-cog fa-fw"></i><i class="fa fa-caret-down"></i></a>
    <ul>
        <li>
            <?= $this->modal->medium("pencil-square-o", t('Edit'), 
            'TimeTrackingEditorController', 'edit', 
            array('plugin' => 'timetrackingeditor', 
            'task_id' => $task['id'], 
            'project_id' => $task['project_id'], 
            'subtask_id' => $subtask_id, 
            'id' => $id)) ?>
        </li>
        <li>
            <?= $this->modal->medium("trash-o", t('Remove'), 
            'TimeTrackingEditorController', 'confirm', 
            array('plugin' => 'timetrackingeditor', 
            'task_id' => $task['id'],
            'project_id' => $task['project_id'], 
            'subtask_id' => $subtask_id, 
            'id' => $id)) ?>
        </li>
    </ul>
</div>
