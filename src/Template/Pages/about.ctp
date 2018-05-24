<?php $this->assign('title', 'About'); ?>
<div class="container dark-theme">
    <div class="row">
        <div class="columns small-12 medium-10 medium-offset-1 text-center intro-para">
            <h1>What is Korero?</h1>
            <p>Korero is a word that means ‘conversation’, ‘discussion’, and ‘meeting’, and this service is one which
                allows users to initiate conversation, have discussions, and meet new people. Korero is a social service
                which connects users with others, based upon common interests and physical
                location, and allows them to message each other.</p>
        </div>
    </div>
    <div class="row">
        <div class="columns small-12 medium-10 medium-offset-1 text-center intro-para about-page">

            <div class="row">
                <div class="columns small-12 medium-6 hide-for-small-only">
                    <img src="/img/interests.png">
                </div>
                <div class="columns small-12 medium-6 about-text">
                    <h5 class="text-center">Step 1</h5>
                    <p class="text-center">Create an account and add some things you're interested in. Choose from the
                        most popular interests on the site, search all available interests, or add your own!</p>
                </div>
            </div>

            <div class="row">
                <div class="columns small-12 medium-6 about-text">
                    <h5 class="text-center">Step 2</h5>

                    <p class="text-center">See people who have the same interests, either nearby or on the other side of
                        the world, by adjusting your radius accordingly.</p>
                </div>
                <div class="columns small-12 medium-6 hide-for-small-only">
                    <img src="/img/connections.png">
                </div>
            </div>

            <div class="row">
                <div class="columns small-12 medium-6 hide-for-small-only">
                    <img src="/img/conversation.png">
                </div>
                <div class="columns small-12 medium-6 about-text">
                    <h5 class="text-center">Step 3</h5>

                    <p class="text-center">Choose someone, and send them a message. Talk about something you both like,
                        and make a new friend.</p>
                </div>
            </div>

        </div>
    </div>
    <div class="row">
        <div class="columns small-12 medium-10 medium-offset-1 text-center pb-3">
            <h4>Join today!</h4>
            <?= $this->Html->link('Sign up', ['controller' => 'Users', 'action' => 'add'], ['class' => 'red-button']); ?>
        </div>
    </div>
</div>
