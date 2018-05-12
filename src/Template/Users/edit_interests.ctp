<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<div class="users form large-9 medium-8 columns content">
    <?php if ($user->interests) { ?>
        <h4>Current Interests</h4>

        <?php foreach ($user->interests as $interests): ?>
            <p><?= h($interests->name) ?></p>
            <?= $this->Form->postLink(
                __('X'),
                ['action' => 'remove-interest', $user->id, $interests->id],
                ['confirm' => __('Are you sure you want to remove # {0}?', $user->id, $interests->id)]
            )
            ?>
        <?php endforeach; ?>
    <?php } else { ?>
        <h5>Add some interests and start connecting with people</h5>
    <?php } ?>
    <?= $this->Form->create($user) ?>
    <fieldset>
        <h4>Top Interests</h4>
        <?php echo $this->Form->control('interests._ids', ['options' => $top_interests, 'multiple' => 'checkbox', 'label' => false]);
        ?>
        <div id="selected-form">
            <?php
            foreach ($user->interests as $interest) {
                echo '<input type="hidden" name="interests[_ids][]" value="' . $interest->id . '">';
            }
            ?>


        </div>
    </fieldset>

    <div class="row">
        <h4>Search for interests</h4>
        <div id="selected" class=""></div>
        <div>

            <input id="search" type="text">
        </div>
        <div id="results"></div>
        <button id="add-interest" type="button">Add Interest</button>
        <?= $this->Form->submit(('Can\'t find your interest listed? add it here'), ['name' => 'new-interest']) ?>
    </div>
    <?= $this->Form->button(__('Submit'), ['name' => 'submit-form']) ?>
    <?= $this->Form->end() ?>

</div>
