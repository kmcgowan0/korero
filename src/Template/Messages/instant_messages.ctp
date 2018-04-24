<?php foreach ($messages_in_thread as $message_in_thread) : ?>
    <?php if ($message_in_thread->sender == $authUser['id']) {
        $send_class = 'sent';
    } else {
        $send_class = 'received';

    } ?>
    <div class="<?php echo $send_class; ?> user-message">
        <?= h($message_in_thread->body) ?>
        <p class="timestamp"><?= h($message_in_thread->sent) ?></p>
    </div>
<?php endforeach; ?>