<div class="row">
<?php foreach ($user->interests as $interests): ?>

        <div class="columns small-6 large-4 current-interests-list" data-id="<?= h($interests->id) ?>">
            <?= $this->Form->postLink(
                __('x'),
                ['action' => 'remove-interest', $user->id, $interests->id],
                ['confirm' => __('Are you sure you want to remove this interest?', $user->id, $interests->id)]
            )
            ?>
            <p><?= h($interests->name) ?></p>

        </div>


<?php endforeach; ?>
</div>
