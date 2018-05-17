<div class="task-show-title color-<?= $task['color_id'] ?>">
    <h2><?= $this->text->e($task['title']) ?></h2>
</div>

<?= $this->render('task/time_tracking_summary', array('task' => $task)) ?>

<h3><?= t('Subtask timesheet') ?></h3>

<?= $this->modal->medium("plus",t('Add a new timetracking entry'), 'TimeTrackingEditorController',
        'create', array(
            'plugin' => 'timetrackingeditor',
            'task_id' => $task['id'],
            'project_id' => $task['project_id'])) ?> 
          
<?php if ($subtask_paginator->isEmpty()): ?>
    <p class="alert"><?= t('There is nothing to show.') ?></p>
<?php else: ?>
    <table class="table-fixed">
        <tr>
            <th class="column-15"><?= $subtask_paginator->order(t('User'), 'username') ?></th>
            <th><?= $subtask_paginator->order(t('Subtask'), 'subtask_title') ?></th>
            <th class="column-20"><?= $subtask_paginator->order(t('Start'), 'start') ?></th>
            <th class="column-20"><?= $subtask_paginator->order(t('End'), 'end') ?></th>
            <th class="column-10 right"><?= $subtask_paginator->order(t('Time spent'), \Kanboard\Model\SubtaskTimeTrackingModel::TABLE.'.time_spent') ?></th>
	    <th class="column-10"></th>
        </tr>
        <?php foreach ($subtask_paginator->getCollection() as $record): ?>
        <tr>
            <td><?= $this->url->link($this->text->e($record['user_fullname'] ?: $record['username']), 'UserViewController', 'show', array('user_id' => $record['user_id'])) ?>
            <?php if ($record['is_billable']): ?>
              <i class='fa fa-cart-plus'></i>
            <?php endif ?>
            <?= $this->app->tooltipMarkdown($record['comment']) ?>
            </td>
            <td><?= t($record['subtask_title']) ?></td>
            <td><?= $this->dt->datetime($record['start']) ?></td>
            <td><?= $this->dt->datetime($record['end']) ?></td>
            <td class="right"><?= n($record['time_spent']).' '.t('hours') ?></td>
            <td>
		<?php if ($this->user->isCurrentUser($record['user_id'])) { ?>
                <?= $this->render('timetrackingeditor:menu', array(
                    'task' => $task,
                    'subtask_id' => $record['subtask_id'],
                    'id' => $record['id']
                )) ?>
                <?php } ?>
            </td>
        </tr>
        <?php endforeach ?>
    </table>

    <?= $subtask_paginator ?>
<?php endif ?>
