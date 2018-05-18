<div class="row">
<?php foreach ($user->interests as $interests): ?>

        <div class="columns small-12 medium-6 large-4 current-interests-list">
            <?= $this->Form->postLink(
                __('x'),
                ['action' => 'remove-interest', $user->id, $interests->id],
                ['confirm' => __('Are you sure you want to remove # {0}?', $user->id, $interests->id)]
            )
            ?>
            <p><?= h($interests->name) ?></p>

        </div>


<?php endforeach; ?>
</div>
