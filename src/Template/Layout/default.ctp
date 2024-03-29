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

$cakeDescription = 'CakePHP: the rapid development php framework';
?>
<!DOCTYPE html>
<html>
<head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-119882173-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-119882173-1');
    </script>

    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $this->fetch('title') ?> | Korero
    </title>
    <?= $this->Html->meta('icon') ?>

    <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">
    <?= $this->Html->css('foundation.min.css') ?>
    <?= $this->Html->css('app.css') ?>
    <?= $this->Html->css('base.css') ?>
    <?= $this->Html->css('cake.css') ?>
    <?= $this->Html->css('style.css') ?>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="/js/scripts.js"></script>
    <script src="/js/connections.js"></script>


    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>

<nav class="top-bar expanded" data-topbar role="navigation">
        <ul class="title-area small-8 large-3 medium-4 columns">
            <li class="name">
                <?php if ($authUser) {
                    $home_link = '/users/connections';
                } else {
                    $home_link = '/';
                } ?>
                <a href="<?php echo $home_link; ?>"><img src="/img/logo-header.png" class="logo"></a>
            </li>
        </ul>

        <div class="large-4 medium-4 columns search-column hide-for-small-only">
            <?php if ($authUser) : ?>
                <?= $this->Form->create('', ['type' => 'get', 'url' => '/users/search']) ?>
                <?= $this->Form->control('term', ['label' => false, 'placeholder' => 'Search for interests', 'class' => 'header-search', 'autocomplete' => 'off']) ?>
                <?= $this->Form->end() ?>
            <?php endif; ?>
        </div>
        <div class="small-4 columns search-column show-for-medium-down">
            <a data-toggle="offCanvas" class="menu-link"><img class="burger-menu" src="/img/menu.png"></a>

        </div>

        <div class="top-bar-section large-5 medium-4 columns hide-for-medium-down">
            <ul class="right">
                <?php if ($authUser) : ?>
                    <li><?= $this->Html->link(__('Connections'), ['controller' => 'Users', 'action' => 'connections']) ?></li>
                    <li><?= $this->Html->link(__('View Profile'), ['controller' => 'Users', 'action' => 'view', $authUser['id']]) ?></li>
                    <!-- number of notifications -->
                    <li><a href="/messages/inbox">Messages <span id="notifications"></span></a></li>
                    <li><?= $this->Html->link(__('Logout'), ['controller' => 'Users', 'action' => 'logout']) ?></li>
                <?php else : ?>
                    <li><?= $this->Html->link(__('Login'), ['controller' => 'Users', 'action' => 'login']) ?></li>
                    <li><?= $this->Html->link(__('Create an account'), ['controller' => 'Users', 'action' => 'add']) ?></li>
                <?php endif; ?>
            </ul>

        </div>
</nav>

<div class="off-canvas position-right" id="offCanvas" data-off-canvas>
    <?php if ($authUser) : ?>

        <li><?= $this->Html->link(__('Connections'), ['controller' => 'Users', 'action' => 'connections']) ?></li>
        <li><?= $this->Html->link(__('View Profile'), ['controller' => 'Users', 'action' => 'view', $authUser['id']]) ?></li>
        <!-- number of notifications -->
        <li><a href="/messages/inbox">Messages <span id="notifications"></span></a></li>
        <li><?= $this->Html->link(__('Logout'), ['controller' => 'Users', 'action' => 'logout']) ?></li>
        <div class="container">
            <?= $this->Form->create('', ['type' => 'get', 'url' => '/users/search']) ?>
            <?= $this->Form->control('term', ['label' => false, 'placeholder' => 'Search for interests', 'class' => 'header-search']) ?>
            <?= $this->Form->end() ?>
        </div>
    <?php else : ?>
        <li><?= $this->Html->link(__('Login'), ['controller' => 'Users', 'action' => 'login']) ?></li>
        <li><?= $this->Html->link(__('Create an account'), ['controller' => 'Users', 'action' => 'add']) ?></li>
    <?php endif; ?>
</div>
<div class="off-canvas-content" data-off-canvas-content>
    <!-- Your page content lives here -->

    <ul class="vertical medium-horizontal menu">

    </ul>
    <?= $this->Flash->render() ?>
    <div class=" clearfix">
        <?= $this->fetch('content') ?>
    </div>
    <footer class="footer">
        <div class="row">
            <div class="small-12 medium-2 columns text-center">
                <img class="footer-logo" src="/img/logo-footer.png">
            </div>
            <div class="small-12 medium-4 columns">
                <p><strong>About Korero</strong></p>
                <p>Korero means conversation. Connect and talk to new people in your area based on similar
                    interests.
                </p>
            </div>
            <div class="small-12 medium-3 columns">
                <p><strong>Quick Links</strong></p>
                <ul class="footer-links">
                    <li><?= $this->Html->link(__('About'), ['controller' => 'Pages', 'action' => 'about']) ?></li>
                    <li><?= $this->Html->link(__('Contact'), ['controller' => 'Pages', 'action' => 'contact']) ?></li>

                </ul>
            </div>
            <div class="small-12 medium-3 columns">
                <ul class="footer-links">
                    <li><?= $this->Html->link(__('Terms & Conditions'), ['controller' => 'Pages', 'action' => 'terms-and-conditions']) ?></li>
                    <li><?= $this->Html->link(__('Privacy Policy'), ['controller' => 'Pages', 'action' => 'privacy-policy']) ?></li>
                </ul>
            </div>
        </div>

        <script>
            $("#btn").click(function () {
                var geocoder = new google.maps.Geocoder();
                geocoder.geocode({'address': 'miami, us'}, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        alert("location : " + results[0].geometry.location.lat() + " " + results[0].geometry.location.lng());
                    } else {
                        alert("Something got wrong " + status);
                    }
                });
            });
        </script>

        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAuQ_s1xd3bkDunnX0IJQCE2FXJgEQFOkU"></script>
        <script src="/js/vendor/what-input.js"></script>
        <script src="/js/vendor/foundation.js"></script>
        <script src="/js/app.js"></script>


    </footer>
</div>

</body>
</html>
