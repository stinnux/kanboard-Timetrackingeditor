<div class="page-header">
    <h2><?= t('Edit a Time Tracking Event') ?></h2>
</div>
<form class="popover-form" method="post" action="<?= $this->url->href('TimetrackingeditorController', 'update', array('plugin' => 'timetrackingeditor', 'project_id' => $values['project_id'], 'task_id' => $values['task_id'])) ?>" autocomplete="off">

    <?= $this->form->csrf() ?>

    <?= $this->form->hidden('project_id', $values) ?>
    <?= $this->form->hidden('task_id', $values) ?>
    <?= $this->form->hidden('opposite_subtask_id', $values) ?>
    <?= $this->form->hidden('id', $values) ?>
    <?= $this->form->hidden('old_time_spent', $values) ?>
    <?= $this->form->hidden('old_opposite_subtask_id', $values) ?>

    <?= $this->form->label(t('Subtask'), 'subtask') ?>
    <?= $this->form->text(
           'subtask',
           $values,
           $errors,
           array(
               'required',
               'autofocus',
               'placeholder="'.t('Start to type subtask title...').'"',
               'title="'.t('Start to type subtask title...').'"',
               'data-dst-field="opposite_subtask_id"',
               'data-search-url="'.$this->url->href('SubtaskAjaxController', 'autocomplete', array('plugin' => 'timetrackingeditor', 'task_id' => $values['task_id'])).'"',
           ),
           'autocomplete') ?>

    <?= $this->form->label(t('Start Date'), 'start') ?>
    <?= $this->form->text('start', $values, $errors, array('maxlength="10"', 'required'), 'form-date') ?>

    <?= $this->form->label(t('Time spent'), 'time_spent') ?>
    <?= $this->form->numeric('time_spent', $values, $errors, array('maxlength="10"', 'required'), 'form-numeric') ?> hours

    <div class="form-actions">
        <button type="submit" class="btn btn-blue"><?= t('Save') ?></button>
        <?= t('or') ?>
        <?= $this->url->link(t('cancel'), 'column', 'index', array('project_id' => $project['id']), false, 'close-popover') ?>
    </div>
</form>