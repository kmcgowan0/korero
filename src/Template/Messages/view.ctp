<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Message $message
 * @var \App\Model\Entity\Message $messages_in_thread
 */
?>

<?php if ($allowed_user == true) { ?>
<div class="messages view large-9 medium-8 columns content">
    <h4>Conversation with <?php echo $user_array[$sent_to_id]['firstname']; ?></h4>

    <p>You both like</p>
    <div id="messages"></div>
    <div class="large-12">
        <?= $this->Form->create($message, ['id' => 'message-form']) ?>
        <fieldset>
            <?php
            echo $this->Form->input('body', ['type' => 'text', 'label' => false, 'id' => 'body']);
            ?>
        </fieldset>
        <?= $this->Form->button(__('Send')) ?>
        <?= $this->Form->end() ?>
    </div>
</div>

<script>
    var messageId = <?php echo json_encode($sent_to_id); ?>;
</script>

<?php } else { ?>
    <p>Sorry, you aren't allowed there. Back tf off.</p>
    <?= $this->Html->link(__('Go Back'), ['controller' => 'users', 'action' => 'connections']) ?>
<?php } ?>