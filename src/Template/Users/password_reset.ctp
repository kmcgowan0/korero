<?php $this->assign('title', 'Reset Password'); ?>
<div class="row view-profile">
    <div class="users form small-12 medium-6 large-4 medium-offset-3 large-offset-4 columns">
        <?= $this->Form->create() ?>
        <fieldset>
            <?= $this->Form->input('old_password', ['type' => 'password', 'placeholder' => 'Old password', 'label' => false]) ?>
            <?= $this->Form->input('password1', ['type' => 'password', 'placeholder' => 'New password', 'label' => false]) ?>
            <?= $this->Form->input('password2', ['type' => 'password', 'placeholder' => 'Repeat password', 'label' => false]) ?>
        </fieldset>
        <?= $this->Form->button(__('Save')) ?>
        <?= $this->Form->end() ?>
    </div>
</div>