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
    <?= $this->Form->create($user, ['enctype'=>'multipart/form-data']) ?>
    <fieldset>
        <legend><?= __('Edit User') ?></legend>
        <?php
        echo $this->Form->control('email');
        echo $this->Form->control('firstname');
        echo $this->Form->control('lastname');
        echo $this->Form->control('dob', ['empty' => true, 'minYear' => 1950, 'maxYear' => date('Y')]);
        echo $this->Form->control('location');
        echo '<label for="upload">Profile picture</label>';
        echo $this->Form->input('upload', array('type' => 'file'));
        ?>
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
    </div>


    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>

</div>
