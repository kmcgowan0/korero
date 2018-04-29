<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit User'), ['action' => 'edit', $user->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete User'), ['action' => 'delete', $user->id], ['confirm' => __('Are you sure you want to delete # {0}?', $user->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Interests'), ['controller' => 'Interests', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Interest'), ['controller' => 'Interests', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="users view large-9 medium-8 columns content">
    <h3><?= h($user->firstname) ?></h3>
    <?php if ($user->upload) :
        $profile_img = $user->upload;
    else :
        $profile_img = 'placeholder.png';
    endif; ?>
    <div class="profile-picture-large profile-picture"
         style="background-image: url(/img/<?php echo $profile_img; ?>)"></div>
    <?php if ($allowed_user && $my_profile == false) { ?>
        <h6><?= $this->Html->link(__('Send a message'), ['controller' => 'Messages', 'action' => 'view', $user->id]) ?></h6>
    <?php } ?>
    <?php if ($my_profile == true) { ?>
        <h6><?= $this->Html->link(__('Reset Password'), ['action' => 'password-reset', $user->id]) ?></h6>
        <h6><?= $this->Html->link(__('Edit Account'), ['action' => 'edit', $user->id]) ?></h6>
        <h6><?= $this->Html->link(__('Edit Interests'), ['action' => 'edit-interests', $user->id]) ?></h6>
    <?php } ?>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Email') ?></th>
            <td><?= h($user->email) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('First Name') ?></th>
            <td><?= h($user->firstname) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Last Name') ?></th>
            <td><?= h($user->lastname) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($user->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Dob') ?></th>
            <td><?= h($user->dob) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Location') ?></th>
            <td><?= h($user->location) ?></td>
        </tr>
    </table>
    <?php if (!empty($user->interests)): ?>
        <div class="related">
            <h4><?= __('Interests') ?></h4>
<ul class="interests">
    <?php foreach ($user->interests as $interests): ?>
    <li><?= h($interests->name) ?></li>
    <?php endforeach; ?>
</ul>

        </div>
    <?php else : ?>
        <?php if ($my_profile == true) { ?>
            <div class="related">
                <p>It doesn't look like you have any interests currently listed.</p>
                <h6><?= $this->Html->link(__('Add some'), ['action' => 'edit-interests', $user->id]) ?></h6>

            </div>
        <?php } ?>
    <?php endif; ?>
</div>
