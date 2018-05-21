<div class="container login-container dark-theme">
    <div class="row">
        <div class="columns small-12 medium-4 medium-offset-4 text-center">
            <img src="/img/logo-home.png" class="logo-home">
            <p class="intro-text">
               Korero means conversation. Connect and converse with new people in your area based on similar interests.
            </p>
        </div>
    </div>
    <div class="row">
        <div class="users form columns small-12 medium-4 medium-offset-4">
            <?= $this->Flash->render() ?>
            <?= $this->Form->create() ?>
            <fieldset>
                <?= $this->Form->control('email', ['label' => false, 'placeholder' => 'Email Address']) ?>
                <?= $this->Form->control('password', ['label' => false, 'placeholder' => 'Password']) ?>
            </fieldset>
            <?= $this->Form->button(__('Login')); ?>
            <?= $this->Form->end() ?>
        </div>
        <div class="columns small-12 medium-4 medium-offset-4 text-center">
            <?= $this->Html->link('Don\'t have an account? Sign up here', ['controller' => 'Users', 'action' => 'add'], ['class' => 'underline-hover']); ?>
        </div>
    </div>
</div>