<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User[]|\Cake\Collection\CollectionInterface $users
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New User'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Interests'), ['controller' => 'Interests', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Interest'), ['controller' => 'Interests', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="users index large-9 medium-8 columns content">
    <h3><?= __('Users') ?></h3>

    <?= $this->Form->create('', ['type' => 'get']) ?>
    <?= $this->Form->control('term') ?>
    <button>Search</button>
    <?= $this->Form->end() ?>

    <table cellpadding="0" cellspacing="0">
        <thead>
        <tr>
            <th scope="col"></th>
            <th scope="col"><?= $this->Paginator->sort('firstname') ?></th>
            <th scope="col"><?= $this->Paginator->sort('lastname') ?></th>
            <th scope="col"><?= $this->Paginator->sort('dob') ?></th>
            <th scope="col"><?= $this->Paginator->sort('location') ?></th>
            <th scope="col"><?= $this->Paginator->sort('interests') ?></th>
            <th scope="col" class="actions"><?= __('Actions') ?></th>
        </tr>
        </thead>
        <tbody>

        <?php foreach ($users as $user):
            $interests = array();
            foreach ($user->interests as $interest) {
                $interests[] = $interest->name;
            } ?>

            <tr>
                <td><?php if ($user->upload) :
                        $profile_img = $user->upload;
                    else :
                        $profile_img = 'placeholder.png';
                    endif; ?>
                    <div class="profile-picture-small profile-picture"
                         style="background-image: url(/img/<?php echo $profile_img; ?>)">

                    </div>
                </td>
                <td><?= h($user->firstname) ?></td>
                <td><?= h($user->lastname) ?></td>
                <td><?= h($user->dob) ?></td>
                <td><?= h($user->location) ?></td>
                <td><?php echo implode(", ", $interests); ?>
                </td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $user->id]) ?>
                    <?php if ($authUser['id'] == $user->id) : ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $user->id]) ?>
                    <?php endif; ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $user->id], ['confirm' => __('Are you sure you want to delete # {0}?', $user->id)]) ?>
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
