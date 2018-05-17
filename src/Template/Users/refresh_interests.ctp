<?php foreach ($user->interests as $interests): ?>
    <p><?= h($interests->name) ?></p>
    <?= $this->Form->postLink(
        __('X'),
        ['action' => 'remove-interest', $user->id, $interests->id],
        ['confirm' => __('Are you sure you want to remove # {0}?', $user->id, $interests->id)]
    )
    ?>
<?php endforeach; ?>