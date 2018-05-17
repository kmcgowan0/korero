<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var \App\Model\Entity\Interest $interest
 */
?>
<div class="users form large-9 medium-8 columns content">
    <?php if ($user->interests) { ?>
        <h4>Current Interests</h4>
<div id="interests-list"></div>
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
            foreach ($user->interests as $user_interest) {
                echo '<input type="hidden" name="interests[_ids][]" value="' . $user_interest->id . '">';
            }
            ?>


        </div>
    </fieldset>

    <div class="row">
        <h4>Search for interests</h4>
        <div id="selected" class=""></div>

    </div>
    <?= $this->Form->button(__('Submit'), ['name' => 'submit-form']) ?>
    <?= $this->Form->end() ?>

    <?= $this->Form->create($interest, ['url' => '/interests/add', 'id' => 'new-interest-form']) ?>
    <fieldset>
        <?php
        echo $this->Form->control('name', ['id' => 'search', 'autocomplete' => 'off']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Add Interest')) ?>
    <?= $this->Form->end() ?>
    <div id="results"></div>
</div>




