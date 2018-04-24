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

    <?= $this->Html->css('foundation.min.css') ?>
    <?= $this->Html->css('app.css') ?>
    <?= $this->Html->css('base.css') ?>
    <?= $this->Html->css('cake.css') ?>
    <?= $this->Html->css('style.css') ?>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
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
            <h1><a href=""><?= $this->fetch('title') ?></a></h1>
        </li>
    </ul>

    <div class="top-bar-section large-5 columns">
        <ul class="right">
            <?php if ($authUser) : ?>
                <li><?= $this->Html->link(__('View Profile'), ['controller' => 'Users', 'action' => 'view', $authUser['id']]) ?></li>
                <!-- number of notifications -->
                <li><?= $this->Html->link(__('Messages'), ['controller' => 'Messages', 'action' => 'inbox']) ?><span id="notifications"></span></li>
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
<footer>

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

    <script src="/js/vendor/what-input.js"></script>
    <script src="/js/vendor/foundation.js"></script>
    <script src="/js/app.js"></script>

</footer>
</body>
</html>
