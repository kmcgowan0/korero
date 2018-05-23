<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Message[]|\Cake\Collection\CollectionInterface $message_threads
 * @var \App\Model\Entity\Message[]|\Cake\Collection\CollectionInterface $user_array
 */
?>
<div class="container">
    <div class="messages index large-9 medium-8 columns content">
        <h3><?= __('Messages') ?></h3>
        <?php if ($message_threads) : ?>

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
                    <?php if ($message_thread->last()->seen == true || $message_thread->last()->sender == $authUser['id']) {
                        $seen = 'read';
                    } else {
                        $seen = 'unread';
                    } ?>
                    <tr class="<?php echo $seen; ?>">
                        <td><?php if ($message_thread->last()->sender == $authUser['id']) :
                                $recipient_id = $message_thread->last()->recipient; ?>
                                Sent to <?php echo $user_array[$recipient_id]['firstname'];
                            elseif ($message_thread->last()->recipient == $authUser['id']) :
                                $sender_id = $message_thread->last()->sender;
                                ?>
                                Received from <?php echo $user_array[$sender_id]['firstname'];
                            endif; ?></td>

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
        <p>You don't have any messages, why not start a conversation with someone?</p>
    </div>
</div>