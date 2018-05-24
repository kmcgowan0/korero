<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Message $message
 * @var \App\Model\Entity\Message $user_array
 * @var \App\Model\Entity\Message $sent_to_id
 * @var \App\Model\Entity\Message $mutual_interest_array
 * @var \App\Controller\Component\AllowedComponent $allowed_user
 */
?>
<div class="container">
    <?php if ($allowed_user == true || $user->accept_messages == 1 || !empty($messages_in_thread)) {
    $related_interest_str = array();
    foreach ($mutual_interest_array as $interest) {
        $related_interest_str[] = $interest;
    } ?>
    <div class="row">
        <div class="messages view small-12 columns content">

            <h4>Conversation with <a
                        href="/users/view/<?php echo $sent_to_id; ?>"><?php echo $user_array[$sent_to_id]['firstname']; ?></a>
            </h4>
            <!--        If the user has been blocked by the logged in user-->
            <?php if ($blocked_user) { ?>
                <p>You have blocked <?= h($user->firstname) ?>. This means you can't' see each other's profiles, and you
                    can no longer message each other.</p>
                <h6> <?= $this->Form->postLink(__('Unblock this user'), ['controller' => 'Users', 'action' => 'unblock-user', $user->id]) ?></h6>
                <div id="messages"></div>
                <?php
                //if the user has blocked the logged in user
            } else if ($blocked_by) { ?>
                <p>You have blocked by <?= h($user->firstname) ?>. This means you can't' see each other's profiles, and
                    you can no longer message each other.</p>
                <div id="messages"></div>
            <?php } else { ?>
                <?php if ($user->accept_messages == 1 && $authorised_user->accept_messages == 1 && count($related_interest_str) == 0 && empty($messages_in_thread)) { ?>
                    <p>You and <?php echo $user_array[$sent_to_id]['firstname']; ?> have nothing in common. Still want
                        to chat? Go ahead, find out something new.</p>
                    <?php
                } else if ($user->accept_messages == 1 && $authorised_user->accept_messages == 0 && empty($messages_in_thread)) { ?>
                    <p>You can message <?php echo $user_array[$sent_to_id]['firstname']; ?>, but doing so will allow
                        them to message you.</p>
                <?php } else if (count($related_interest_str) > 0) { ?>
                    <p>You both like: <?php echo implode(', ', $related_interest_str); ?></p>
                <?php } ?>
                <div id="messages" class="messages-list"></div>
                <div class="large-12">
                    <?= $this->Form->create($message, ['id' => 'message-form']) ?>
                    <fieldset>
                        <?php
                        echo $this->Form->input('body', ['type' => 'text', 'label' => false, 'id' => 'body', 'autocomplete' => 'off']);
                        ?>
                    </fieldset>
                    <?= $this->Form->button(__('Send')) ?>
                    <?= $this->Form->end() ?>
                </div>
            <?php } ?>
        </div>

        <script>
            var messageId = <?php echo json_encode($sent_to_id); ?>;
            refreshMessages(messageId);
            scrollBottom();
        </script>

        <?php } else { ?>
            <p>Sorry, you aren't allowed there. Back tf off.</p>
            <?= $this->Html->link(__('Go Back'), ['controller' => 'users', 'action' => 'connections']) ?>
        <?php } ?>
    </div>
</div>