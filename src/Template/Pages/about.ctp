<div class="container dark-theme">
    <div class="row">
        <div class="columns small-12 medium-10 medium-offset-1 text-center">
            <h1>What is Korero?</h1>
            <p>Korero means conversation. Connect and converse with new people in your area based on similar
                interests.Korero means conversation. Connect and converse with new people in your area based on similar
                interests.Korero means conversation. Connect and converse with new people in your area based on similar
                interests.Korero means conversation. Connect and converse with new people in your area based on similar
                interests.</p>
        </div>
    </div>
    <div class="row">
        <div class="columns small-12 medium-10 medium-offset-1 text-center">
            <div class="orbit" role="region" aria-label="Favorite Space Pictures" data-orbit>
                <ul class="orbit-container">
                    <li class="orbit-slide is-active">
                        <div class="row">
                            <div class="columns small-12 medium-6">
                                <img src="/img/logo.png">
                            </div>
                            <div class="columns small-12 medium-6">
                                <p class="text-center">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Unde
                                    harum rem, beatae ipsa consectetur quisquam. Rerum ratione, delectus atque tempore
                                    sed, suscipit ullam, beatae distinctio cupiditate ipsam eligendi tempora
                                    expedita.</p>
                            </div>
                        </div>
                    </li>
                    <li class="orbit-slide">
                        <div class="row">
                            <div class="columns small-12 medium-6">
                                <p class="text-center">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Unde
                                    harum rem,
                                    beatae ipsa consectetur quisquam. Rerum ratione, delectus atque tempore sed,
                                    suscipit ullam,
                                    beatae distinctio cupiditate ipsam eligendi tempora expedita.</p>
                            </div>
                            <div class="columns small-12 medium-6">
                                <img src="/img/logo.png">
                            </div>
                        </div>
                    </li>
                    <li class="orbit-slide">
                        <div class="row">
                            <div class="columns small-12 medium-6">
                                <img src="/img/logo.png">
                            </div>
                            <div class="columns small-12 medium-6">
                                <p class="text-center">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Unde
                                    harum rem,
                                    beatae ipsa consectetur quisquam. Rerum ratione, delectus atque tempore sed,
                                    suscipit ullam,
                                    beatae distinctio cupiditate ipsam eligendi tempora expedita.</p>
                            </div>
                        </div>
                    </li>
                    <!-- More slides... -->
                </ul>
                <nav class="orbit-bullets">
                    <button class="is-active" data-slide="0"><span class="show-for-sr">First slide details.</span><span
                                class="show-for-sr">Current Slide</span></button>
                    <button data-slide="1"><span class="show-for-sr">Second slide details.</span></button>
                    <button data-slide="2"><span class="show-for-sr">Third slide details.</span></button>
                </nav>
            </div>

        </div>
    </div>
    <div class="row">
        <div class="columns small-12 medium-10 medium-offset-1 text-center">
            <h4>Join today!</h4>
            <?= $this->Html->link('Sign up', ['controller' => 'Users', 'action' => 'add'], ['class' => 'button']); ?>
        </div>
    </div>
</div>
