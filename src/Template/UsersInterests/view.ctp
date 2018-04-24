<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\UsersInterest $usersInterest
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Users Interest'), ['action' => 'edit', $usersInterest->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Users Interest'), ['action' => 'delete', $usersInterest->id], ['confirm' => __('Are you sure you want to delete # {0}?', $usersInterest->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Users Interests'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Users Interest'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Interests'), ['controller' => 'Interests', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Interest'), ['controller' => 'Interests', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="usersInterests view large-9 medium-8 columns content">
    <h3><?= h($usersInterest->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('User') ?></th>
            <td><?= $usersInterest->has('user') ? $this->Html->link($usersInterest->user->id, ['controller' => 'Users', 'action' => 'view', $usersInterest->user->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Interest') ?></th>
            <td><?= $usersInterest->has('interest') ? $this->Html->link($usersInterest->interest->name, ['controller' => 'Interests', 'action' => 'view', $usersInterest->interest->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($usersInterest->id) ?></td>
        </tr>
    </table>
</div>
