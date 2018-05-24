<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 * @var \App\Model\Entity\Interest $interest
 */
$this->assign('title', 'Edit Interests');
?>
<div class="row">
    <div class="users form small-12 columns content">
        <?php if ($user->interests) { ?>
            <h4>Current Interests</h4>
            <div id="interests-list"></div>
        <?php } else { ?>
            <h5>Add some interests and start connecting with people</h5>
        <?php } ?>
        <div class="row">
            <h4>Search for interests</h4>
            <div id="selected" class=""></div>

        </div>
        <div class="row">
        <?= $this->Form->create($interest, ['url' => '/interests/add', 'id' => 'new-interest-form']) ?>
        <fieldset>
            <?php
            echo $this->Form->control('name', ['id' => 'search', 'autocomplete' => 'off', 'label' => 'Interest Name']);
            ?>
        </fieldset>
        <?= $this->Form->button(__('Add Interest')) ?>
        <?= $this->Form->end() ?>
        <div id="results"></div>
        </div>
        <div class="row">
            <?= $this->Form->create($user) ?>
            <fieldset>
                <h4>Top Interests</h4>
                <?php echo $this->Form->control('interests._ids', ['options' => $top_interests, 'multiple' => 'checkbox', 'label' => false, 'class' => 'half-width']);
                ?>
                <div id="selected-form">
                    <?php
                    foreach ($user->interests as $user_interest) {
                        echo '<input type="hidden" name="interests[_ids][]" value="' . $user_interest->id . '">';
                    }
                    ?>


                </div>
            </fieldset>


            <?= $this->Form->button(__('Save'), ['name' => 'submit-form']) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>




