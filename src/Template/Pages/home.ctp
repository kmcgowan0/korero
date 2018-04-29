<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Datasource\ConnectionManager;
use Cake\Error\Debugger;
use Cake\Network\Exception\NotFoundException;

//get rid of this to use regular layout
$this->layout = false;

if (!Configure::read('debug')) :
    throw new NotFoundException(
        'Please replace src/Template/Pages/home.ctp with your own version or re-enable debug mode.'
    );
endif;

$cakeDescription = 'CakePHP: the rapid development PHP framework';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $cakeDescription ?>
    </title>

    <?= $this->Html->meta('icon') ?>
    <?= $this->Html->css('base.css') ?>
    <?= $this->Html->css('cake.css') ?>
    <?= $this->Html->css('home.css') ?>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="/js/scripts.js"></script>
    <!--    <script src="/js/geolocation.js"></script>-->
    <link href="https://fonts.googleapis.com/css?family=Raleway:500i|Roboto:300,400,700|Roboto+Mono" rel="stylesheet">

</head>
<body class="home">

<header class="row">
    <div class="header-image"><?= $this->Html->image('cake.logo.svg') ?></div>
    <div class="header-title">
        <h1>Hello</h1>
        <p>This will be a link to an about page</p>
    </div>
</header>
<div class="row">

    <div class="columns large-6 large-offset-5">
        <?php if (!$authUser) : ?>
            <?= $this->Html->link('Login', ['controller' => 'Users', 'action' => 'login']); ?>
            <p>The actual login form should be here</p>
            <?= $this->Html->link('Don\'t have an account? Sign up here', ['controller' => 'Users', 'action' => 'add']); ?>
            <p>OR</p>
            <?php echo $this->Html->link(
                'Login with Facebook',
                ['controller' => 'SocialProfiles', 'action' => 'login', '?' => ['provider' => 'Facebook']]
            ); ?>
        <?php else : ?>
            <?= $this->Html->link('Edit your account', ['controller' => 'Users', 'action' => 'edit', $authUser['id']]); ?>
            <br>
            <?= $this->Html->link('Logout', ['controller' => 'Users', 'action' => 'logout']); ?>
        <?php endif; ?>
        <br>


    </div>


    <hr/>

</div>

<div class="row">


</body>
</html>
