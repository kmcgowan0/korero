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
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.17/d3.js"></script>
    <script src="/js/connections.js"></script>


    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>
<body>
<nav class="top-bar expanded" data-topbar role="navigation">
    <ul class="title-area large-3 columns">
        <li class="name">
            <a href="/users/connections"><img src="/img/logo-header.png" class="logo"></a>
        </li>
    </ul>
    <div class="large-4 columns search-column">
        <?php if ($authUser) : ?>
        <?= $this->Form->create('', ['type' => 'get', 'url' => '/users/search']) ?>
        <?= $this->Form->control('term', ['label' => false, 'placeholder' => 'Search', 'class' => 'header-search']) ?>
        <?= $this->Form->end() ?>
        <?php endif; ?>
    </div>
    <div class="top-bar-section large-5 columns">
        <ul class="right">
            <?php if ($authUser) : ?>
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
<?= $this->Flash->render() ?>
<div class="container clearfix">
    <?= $this->fetch('content') ?>
</div>
<footer class="footer">
    <div class="row">
        <div class="small-12 medium-2 columns">
            <img class="logo" src="/img/logo-footer.png">
        </div>
        <div class="small-12 medium-4 columns">
           <p><strong>Lorem Ipsum</strong></p>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam efficitur elit lacus, a molestie lectus imperdiet sit amet. Vestibulum varius, nisi vel interdum tincidunt, metus turpis vehicula odio, sit amet placerat dui eros vel dui. </p>
        </div>
        <div class="small-12 medium-3 columns">
            <p><strong>Quick Links</strong></p>
            <ul class="footer-links">
                <li><a href="#">About</a></li>
                <li><a href="#">Help</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
        </div>
        <div class="small-12 medium-3 columns">
            <p><strong>Get in Touch</strong></p>
            <ul class="footer-links">
                <li>Call: 00000000000</li>
                <li>Email: info@koreroapp.com</li>
                <li>Facebook</li>
                <li>Twitter</li>
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

    <!--    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAs_1lMnpJElTDelPDgGnO6gZ_raZihRE8"></script>-->

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAuQ_s1xd3bkDunnX0IJQCE2FXJgEQFOkU"></script>
    <script src="/js/vendor/what-input.js"></script>
    <script src="/js/vendor/foundation.js"></script>
    <script src="/js/app.js"></script>

</footer>
</body>
</html>
