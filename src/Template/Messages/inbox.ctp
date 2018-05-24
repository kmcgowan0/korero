<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Message[]|\Cake\Collection\CollectionInterface $message_threads
 * @var \App\Model\Entity\Message[]|\Cake\Collection\CollectionInterface $user_array
 */
?>
<div class="container">
    <div class="messages index small-12 columns content">
        <div class="row">
            <h3><?= __('Messages') ?></h3>
            <?php if ($message_threads) : ?>

                <table cellpadding="0" cellspacing="0">
                    <thead>
                    <tr>
                        <th scope="col"><?= $this->Paginator->sort('sender') ?></th>
                        <th scope="col"><?= $this->Paginator->sort('body') ?></th>
                        <th scope="col" class="hide-for-small-only"><?= $this->Paginator->sort('sent') ?></th>
                        <th scope="col" class="actions hide-for-small-only"><?= __('Actions') ?></th>
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
                        <?php if ($message_thread->last()->recipient != $authUser['id']) :
                            $thread_link = $message_thread->last()->recipient;
                        elseif ($message_thread->last()->recipient == $authUser['id']) :
                            $thread_link = $message_thread->last()->sender;
                        endif;
                        ?>
                        <tr class="<?php echo $seen; ?>"
                            onclick="window.location='/messages/view/<?php echo $thread_link; ?>';">

                            <td><?php if ($message_thread->last()->sender == $authUser['id']) :
                                    $recipient_id = $message_thread->last()->recipient; ?>Sent to <span
                                        style="white-space: nowrap;"><?php echo $user_array[$recipient_id]['firstname']; ?></span> <?php
                                elseif ($message_thread->last()->recipient == $authUser['id']) :
                                    $sender_id = $message_thread->last()->sender;
                                    ?>Received from <span
                                        style="white-space: nowrap;"><?php echo $user_array[$sender_id]['firstname']; ?></span> <?php
                                endif; ?></td>

                            <td><?= h($message_thread->last()->body) ?></td>
                            <td class="hide-for-small-only"><?= h($message_thread->last()->sent) ?></td>
                            <td class="actions hide-for-small-only">

                                <?= $this->Html->link(__('View'), ['action' => 'view', $thread_link]) ?>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else : ?>
                <p>You don't have any messages, why not start a conversation with someone?</p>

            <?php endif; ?>
        </div>
    </div>
</div>