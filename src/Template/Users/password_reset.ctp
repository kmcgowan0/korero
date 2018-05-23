<div class="users form large-9 medium-9 columns">
    <?= $this->Form->create() ?>
    <fieldset>
        <?= $this->Form->input('old_password',['type' => 'password' , 'placeholder' => 'Old password', 'label' => false])?>
        <?= $this->Form->input('password1',['type'=>'password' ,'placeholder'=>'New password', 'label' => false]) ?>
        <?= $this->Form->input('password2',['type' => 'password' , 'placeholder'=>'Repeat password', 'label' => false])?>
    </fieldset>
    <?= $this->Form->button(__('Save')) ?>
<?= $this->Form->end() ?>