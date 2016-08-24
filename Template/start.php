<div class="page-header">
    <h2><?= t('Start a new Timer') ?></h2>
</div>
<form class="popover-form" method="post" action="<?= $this->url->href('TimetrackingeditorController', 'startsave', array('plugin' => 'timetrackingeditor', 'project_id' => $values['project_id'], 'task_id' => $values['task_id'])) ?>" autocomplete="off">

    <?= $this->form->csrf() ?>

    <?= $this->form->hidden('project_id', $values) ?>
    <?= $this->form->hidden('task_id', $values) ?>
    <?= $this->form->hidden('subtask_id', $values) ?>

    <?= t('Subtask') ?>
    <?= $values['subtask']['title'] ?>

    <?= $this->form->label(t('Comment'), 'comment') ?>
    <?= $this->form->textarea('comment', $values, $errors, array(), 'markdown-editor') ?>

    <?= $this->form->checkbox('is_billable', t('Billable?'), 1, isset($values['is_billable']) && $values['is_billable'] == 1) ?>

    <div class="form-actions">
        <button type="submit" class="btn btn-blue"><?= t('Start Timer') ?></button>
        <?= t('or') ?>
        <?= $this->url->link(t('cancel'), 'column', 'index', array('project_id' => $project['id']), false, 'close-popover') ?>
    </div>
</form>
