<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
$this->assign('title', 'Edit account');
?>
<?php if ($my_profile) : ?>
    <?php if ($user->upload) :
        $profile_img = $user->upload;
    else :
        $profile_img = 'placeholder.png';
    endif; ?>
<div class="row">
    <div class="users form small-12 medium-6 medium-offset-3 columns content edit-form">
        <?= $this->Form->create($user, ['enctype' => 'multipart/form-data']) ?>
        <fieldset>
            <legend><?= __('Edit User') ?></legend>
            <?php
            echo $this->Form->control('email');
            echo $this->Form->control('firstname');
            echo $this->Form->control('lastname');
            echo $this->Form->control('dob', ['empty' => true, 'minYear' => 1950, 'maxYear' => date('Y')]);
            echo $this->Form->hidden('location', ['type' => 'text', 'id' => 'location-coords']); ?>
            <button class="location-button" type="button">Get my location</button>
            <h6>Current location: <span class="my-coded-location"><?= $user->coded_location; ?></span></h6>
            <?php
            echo $this->Form->hidden('coded_location', ['type' => 'text', 'class' => 'my-coded-location']);
            ?>
       <?php
            echo $this->Form->control('accept_messages', ['type' => 'checkbox', 'label' => 'Check this box if you\'re happy to receive messages from people with no mutual interests with you']);
			?>


        </fieldset>

        <?= $this->Form->button(__('Submit')) ?>
        <?= $this->Form->end() ?>
    </div>
<?php else : ?>

    <p>Not allowed bro</p>

<?php endif; ?>
<p id="my-location"></p>
</div>