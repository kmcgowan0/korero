<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\UsersInterest $usersInterest
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Users Interests'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Interests'), ['controller' => 'Interests', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Interest'), ['controller' => 'Interests', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="usersInterests form large-9 medium-8 columns content">
    <?= $this->Form->create($usersInterest) ?>
    <fieldset>
        <legend><?= __('Add Users Interest') ?></legend>
        <?php
        echo $this->Form->input('interest_id', ['type'=>'select', 'multiple'=>'checkbox', 'options'=>$interests]);
        ?>
    </fieldset>
    <p>Can't find your interest above?</p>
    <?= $this->Html->link(__('Add it here'), ['controller' => 'Interests', 'action' => 'add']) ?>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
