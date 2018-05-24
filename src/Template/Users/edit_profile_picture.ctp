<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
$this->assign('title', 'Edit Profile Picture');
?>
<?php if ($my_profile) : ?>
    <?php if ($user->upload) :
        $profile_img = $user->upload;
    else :
        $profile_img = 'placeholder.png';
    endif; ?>
<div class="row view-profile">
    <div class="small-12 medium-6 medium-offset-3 large-4 large-offset-4 columns text-center">

    <?= $this->Form->create($user, ['enctype' => 'multipart/form-data']) ?>
    <fieldset>
        <div class="profile-preview profile-picture profile-picture-large" style="background-image: url('/img/<?php echo $profile_img; ?>');"></div>
            <?php echo $this->Form->control('upload', ['type' => 'file', 'id' => 'profile-picture', 'label' => false]); ?>
    </fieldset>

    <?= $this->Form->button(__('Update profile picture')) ?>
    <?= $this->Form->end() ?>
    </div>
</div>

<?php else : ?>

    <p>Not allowed bro</p>

<?php endif; ?>