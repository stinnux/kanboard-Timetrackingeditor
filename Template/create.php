<div class="page-header">
    <h2><?= t('Add a new Time Tracking Event') ?></h2>
</div>
<form class="popover-form" method="post" action="<?= $this->url->href('TimeTrackingEditorController', 'save', array('plugin' => 'timetrackingeditor', 'project_id' => $values['project_id'], 'task_id' => $values['task_id'])) ?>" autocomplete="off">

    <?= $this->form->csrf() ?>

    <?= $this->form->hidden('project_id', $values) ?>
    <?= $this->form->hidden('task_id', $values) ?>
    <?= $this->form->hidden('opposite_subtask_id', $values) ?>

    <?= $this->form->label(t('Subtask'), 'subtask') ?>
    <?= $this->form->text(
           'subtask',
           $values,
           $errors,
           array(
               'required',
               (!isset($autofocus) || $autofocus == 'subtask' ?  'autofocus' : ''),
               'placeholder="'.t('Start to type subtask title...').'"',
               'title="'.t('Start to type subtask title...').'"',
               'data-dst-field="opposite_subtask_id"',
               'data-search-url="'.$this->url->href('SubtaskAjaxController', 'autocomplete', array('plugin' => 'timetrackingeditor', 'task_id' => $values['task_id'])).'"',
           ),
           'autocomplete') ?>

     <?= $this->form->label(t('Time spent'), 'time_spent') ?>
     <?= $this->form->numeric('time_spent', $values, $errors, array('maxlength="10"', 'required', (isset($autofocus) && $autofocus == "time_spent" ? 'autofocus' : '')), 'form-numeric') ?> hours

    <?= $this->form->label(t('Start Date'), 'start') ?>
    <?= $this->form->text('start', $values, $errors, array('maxlength="10"', 'required'), 'form-date') ?>

    <?= $this->form->label(t('Comment'), 'comment') ?>
    <?= $this->form->textarea('comment', $values, $errors, array(), 'markdown-editor') ?>

    <?= $this->form->checkbox('is_billable', t('Billable?'), 1, isset($values['is_billable']) && $values['is_billable'] == 1) ?>
    <?= $this->form->checkbox('add_another', t('Add another event'), 1, isset($values['add_another']) && $values['add_another'] == 1) ?>

    <?= $this->modal->submitButtons(); ?>

</form>
