<?php $this->assign('title', 'Login'); ?>
<div class="container login-container dark-theme">
    <div class="row">
        <div class="columns small-12 medium-6 medium-offset-3 text-center large-4 large-offset-4">
            <img src="/img/logo-home.png" class="logo-home">
            <p class="intro-text">
                Korero means conversation. Connect and talk to new people in your area based on similar
                interests. </p>
        </div>
    </div>
    <div class="row">
        <div class="users form columns small-12 medium-6 medium-offset-3 large-4 large-offset-4">
            <?= $this->Flash->render() ?>
            <?= $this->Form->create() ?>
            <fieldset>
                <?= $this->Form->control('email', ['label' => false, 'placeholder' => 'Email Address']) ?>
                <?= $this->Form->control('password', ['label' => false, 'placeholder' => 'Password']) ?>
                <?= $this->Form->hidden('screen_size', ['id' => 'screen-size']) ?>

            </fieldset>
            <?= $this->Form->button(__('Login')); ?>
            <?= $this->Form->end() ?>
        </div>
        <div class="columns small-12 medium-4 medium-offset-4 text-center mb-2">
            <?= $this->Html->link('Don\'t have an account? Sign up here', ['controller' => 'Users', 'action' => 'add'], ['class' => 'underline-hover']); ?>
        </div>
    </div>
</div>
<script>
    var width = $(window).width();
    $('#screen-size').val(width);
</script>