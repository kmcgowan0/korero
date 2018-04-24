<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\UsersInterest $usersInterest
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $usersInterest->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $usersInterest->id)]
            )
        ?></li>
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
        <legend><?= __('Edit Users Interest') ?></legend>
        <?php
            echo $this->Form->control('user_id', ['options' => $users]);
            echo $this->Form->control('interest_id', ['options' => $interests]);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
