<div class="page-header">
    <h2><?= t('Remove a time tracking entry') ?></h2>
</div>

<div class="confirm">
    <div class="alert alert-info">
        <?= t('Do you really want to remove this entry?') ?>
        <ul>
            <li>
                <strong><?= $this->text->e($timetracking['subtask_title']) ?></strong>
            </li>
        </ul>
    </div>

    <div class="form-actions">
        <?= $this->url->link(t('Yes'), 'TimeTrackingEditorController', 'remove', array('plugin' => 'timetrackingeditor', 'id' => $timetracking['id'], 'project_id' => $timetracking['project_id'], 'subtask_id' => $timetracking['subtask_id']), true, 'btn btn-red') ?>
        <?= t('or') ?>
        <?= $this->url->link(t('cancel'), 'TimeTrackingEditorController', 'show', array('plugin' => 'timetrackingeditor','id' => $timetracking['id'], 'project_id' => $timetracking['project_id']), false, 'close-popover') ?>
    </div>
</div>
