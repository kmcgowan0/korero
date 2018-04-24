<?php foreach ($interests as $interest): ?>
    <p class="selectable" data-id="<?= h($interest->id) ?>"><?= h($interest->name) ?></p>
<?php endforeach; ?>
