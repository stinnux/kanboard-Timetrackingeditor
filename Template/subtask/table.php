<?php if (! empty($subtasks)): ?>
    <table
        class="subtasks-table table-stripped"
        data-save-position-url="<?= $this->url->href('SubtaskController', 'movePosition', array('project_id' => $task['project_id'], 'task_id' => $task['id'])) ?>"
    >
    <thead>
        <tr>
            <th class="column-40"><?= t('Title') ?></th>
            <th><?= t('Assignee') ?></th>
            <th><?= t('Time tracking') ?></th>
            <?php if ($editable): ?>
                <th class="column-5"></th>
                <th class="column-5"></th>
            <?php endif ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($subtasks as $subtask): ?>
        <tr data-subtask-id="<?= $subtask['id'] ?>">
            <td>
                <?php if ($editable): ?>
                    <i class="fa fa-arrows-alt draggable-row-handle" title="<?= t('Change subtask position') ?>"></i>
                    <?= $this->subtask->renderToggleStatus($task, $subtask, "table") ?>
                <?php else: ?>
                    <?= $this->subtask->getTitle($subtask) ?>
                <?php endif ?>
            </td>
            <td>
                <?php if (! empty($subtask['username'])): ?>
                    <?= $this->text->e($subtask['name'] ?: $subtask['username']) ?>
                <?php endif ?>
            </td>
            <td>
                <ul class="no-bullet">
                    <li>
                        <?php if (! empty($subtask['time_spent'])): ?>
                            <strong><?= $this->text->e($subtask['time_spent']).'h' ?></strong> <?= t('spent') ?>
                        <?php endif ?>

                        <?php if (! empty($subtask['time_estimated'])): ?>
                            <strong><?= $this->text->e($subtask['time_estimated']).'h' ?></strong> <?= t('estimated') ?>
                        <?php endif ?>
                        <?php if (! empty($subtask['time_billable'])): ?>
                            <strong><?= $this->text->e($subtask['time_billable']).'h' ?></strong> <?= t('billable') ?>
                        <?php endif ?>

                    </li>
                    <?php if ($editable && $subtask['user_id'] == $this->user->getId()): ?>
                    <li>
                        <?php if ($subtask['is_timer_started']): ?>
                            <?= $this->modal->medium("pause",t('Stop timer'), 'TimeTrackingEditorController', 'stop', 
                            array('plugin' => 'Timetrackingeditor', 
                            'project_id' => $task['project_id'], 
                            'task_id' => $subtask['task_id'], 
                            'subtask_id' => $subtask['id'])) ?>
                            (<?= $this->dt->age($subtask['timer_start_date']) ?>)
                        <?php else: ?>
                            <?= $this->modal->medium("play-circle-o",t('Start timer'), 'TimeTrackingEditorController', 'start', 
                            array('plugin' => 'Timetrackingeditor', 
                            'project_id' => $task['project_id'], 
                            'task_id' => $subtask['task_id'], 
                            'subtask_id' => $subtask['id'])) ?>
                        <?php endif ?>
                    </li>
                    <?php endif ?>
                </ul>
            </td>
            <?php if ($editable): ?>
                <td>
                    <?= $this->render('subtask/menu', array(
                        'task' => $task,
                        'subtask' => $subtask,
                    )) ?>
                </td>
                <td>
                    <?= $this->modal->medium("clock-o", t('New'), 'TimeTrackingEditorController', 
                    'create', array(
                        'plugin' => 'Timetrackingeditor', 
                        'task_id' => $task['id'], 
                        'project_id' => $task['project_id'], 
                        'subtask_id' => $subtask['id'])) ?>
                </td>

            <?php endif ?>
        </tr>
        <?php endforeach ?>
    </tbody>
    </table>
<?php endif ?>
