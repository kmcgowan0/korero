<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $user->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $user->id)]
            )
            ?></li>
        <li><?= $this->Html->link(__('List Users'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Interests'), ['controller' => 'Interests', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Interest'), ['controller' => 'Interests', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="users form large-9 medium-8 columns content">
    <h4>Current Interests</h4>

    <?php foreach ($user->interests as $interests): ?>
            <p><?= h($interests->name) ?></p>
        <?= $this->Form->postLink(
            __('Delete'),
            ['action' => 'remove-interest', $user->id, $interests->id],
            ['confirm' => __('Are you sure you want to remove # {0}?', $user->id, $interests->id)]
        )
        ?>
    <?php endforeach; ?>
    <?= $this->Form->create($user) ?>
    <fieldset>
        <legend><?= __('Edit User') ?></legend>
        <h4>Top Interests</h4>
        <?php echo $this->Form->control('interests._ids', ['options' => $top_interests, 'multiple' => 'checkbox']);
        ?>
        <div id="selected-form">
            <?php
            foreach($user->interests as $interest) {
                echo '<input type="hidden" name="interests[_ids][]" value="' . $interest->id . '">';
            }
            ?>


        </div>
    </fieldset>

    <div class="row">
        <label>Search for interests</label>
        <div id="selected" class=""></div>
        <div>

            <input id="search" type="text">
        </div>
        <div id="results"></div>
        <?= $this->Form->submit(('Can\'t find your interest listed? add it here'), ['name' => 'new-interest']) ?>
    </div>
    <?= $this->Form->button(__('Submit'), ['name' => 'submit-form']) ?>
    <?= $this->Form->end() ?>

</div>
