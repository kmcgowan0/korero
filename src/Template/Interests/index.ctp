<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Interest[]|\Cake\Collection\CollectionInterface $interests
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Interest'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="interests index large-9 medium-8 columns content">
    <h3><?= __('Interests') ?></h3>


    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($interests as $interest): ?>
                <?php $name = ucwords($interest->name); ?>
                <tr>
                <td><?= $this->Number->format($interest->id) ?></td>
                <td><?= $interest->name; ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $interest->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $interest->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $interest->id], ['confirm' => __('Are you sure you want to delete # {0}?', $interest->id)]) ?>
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
