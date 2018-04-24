<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\UsersInterest[]|\Cake\Collection\CollectionInterface $usersInterests
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Users Interest'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Interests'), ['controller' => 'Interests', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Interest'), ['controller' => 'Interests', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="usersInterests index large-9 medium-8 columns content">
    <h3><?= __('Users Interests') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('user_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('interest_id') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usersInterests as $usersInterest): ?>
            <tr>
                <td><?= $this->Number->format($usersInterest->id) ?></td>
                <td><?= $usersInterest->has('user') ? $this->Html->link($usersInterest->user->id, ['controller' => 'Users', 'action' => 'view', $usersInterest->user->id]) : '' ?></td>
                <td><?= $usersInterest->has('interest') ? $this->Html->link($usersInterest->interest->name, ['controller' => 'Interests', 'action' => 'view', $usersInterest->interest->id]) : '' ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $usersInterest->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $usersInterest->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $usersInterest->id], ['confirm' => __('Are you sure you want to delete # {0}?', $usersInterest->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
