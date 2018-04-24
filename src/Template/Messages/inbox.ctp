<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Message[]|\Cake\Collection\CollectionInterface $messages
 * @var \App\Model\Entity\Message[]|\Cake\Collection\CollectionInterface $messaged
 * @var \App\Model\Entity\Message[]|\Cake\Collection\CollectionInterface $message_threads
 * @var \App\Model\Entity\Message[]|\Cake\Collection\CollectionInterface $user_array
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Message'), ['action' => 'add']) ?></li>
    </ul>
</nav>
<div class="messages index large-9 medium-8 columns content">
    <?php if ($message_threads) : ?>
    <h3><?= __('Messages') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
        <tr>
            <th scope="col"><?= $this->Paginator->sort('sender') ?></th>
            <th scope="col"><?= $this->Paginator->sort('body') ?></th>
            <th scope="col"><?= $this->Paginator->sort('sent') ?></th>
            <th scope="col" class="actions"><?= __('Actions') ?></th>
        </tr>
        </thead>
        <tbody>

        <?php foreach ($message_threads as $message_thread): ?>
<!--            --><?php //var_dump($message_thread->last());?>
            <tr>
                <td><?php if ($message_thread->last()->sender == $authUser['id']) : ?>
                        <?php $recipient_id = $message_thread->last()->recipient; ?>
                        Sent to <?php echo $user_array[$recipient_id]['firstname']; ?>
                    <?php elseif ($message_thread->last()->recipient == $authUser['id']) :
                        $sender_id = $message_thread->last()->sender;
                        ?>
                        Received from <?php echo $user_array[$sender_id]['firstname']; ?>
                    <?php endif; ?></td>

                <td><?= h($message_thread->last()->body) ?></td>
                <td><?= h($message_thread->last()->sent) ?></td>
                <td class="actions">
                    <?php if ($message_thread->last()->recipient != $authUser['id']) :
                    $thread_link = $message_thread->last()->recipient;
                    elseif ($message_thread->last()->recipient == $authUser['id']) :
                    $thread_link = $message_thread->last()->sender;
endif;
                    ?>
                    <?= $this->Html->link(__('View'), ['action' => 'view', $thread_link]) ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>

</div>
