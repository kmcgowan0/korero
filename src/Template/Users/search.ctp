<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User[]|\Cake\Collection\CollectionInterface $users
 */
?>
<?php if ($auth_user->upload) :
    $profile_img = $auth_user->upload;
else :
    $profile_img = 'placeholder.png';
endif; ?>
<div class="main-user profile-picture" id="user-<?php echo $auth_user->id; ?>"
     style="background-image: url(/img/<?php echo $profile_img; ?>)"></div>

<div class="users index large-9 medium-8 columns content">
    <?php if ($users) :
        //related users var to pass to js
        $related_users_var = array();
        $position = 0; ?>
        <div class="related-container">
            <h5>People who like <?php echo $term; ?></h5>
            <?php
            foreach ($users as $user) {
                if ($user['id'] != $auth_user['id']) {
                    $related_users_var[$user['id']] = $user->interests;

                    $related_interest_str = array();
                    foreach ($user->interests as $interest) {
                        $related_interest_str[] = $interest->name;
                    }
                    if ($user['upload']) {
                        $related_profile_img = $user['upload'];
                    } else {
                        $related_profile_img = 'placeholder.png';
                    } ?>
                    <a href="#" data-open="modal-<?php echo $user['id']; ?>"
                       data-id="<?php echo $user['id']; ?>" id="user-<?php echo $user['id']; ?>"
                       class="reveal-link">
                        <div class="related-user profile-picture" id="related-user-<?php echo $user['id']; ?>"
                             style="border: solid #000 2px; background-image: url(/img/<?php echo $related_profile_img; ?>); transform: rotate(<?php echo $position; ?>deg) translate(15em) rotate(-<?php echo $position; ?>deg);">
                            <p><?= h($user['firstname']) ?></p>
                        </div>
                    </a>
                    <!--                modal what which is revealed-->
                    <div class="reveal" id="modal-<?php echo $user['id']; ?>" data-reveal>
                        <div class="profile-info row">
                            <div class="profile-picture-small profile-picture small-2 columns"
                                 style="background-image: url(/img/<?php echo $related_profile_img; ?>)"></div>
                            <div class="small-10 medium-8 columns">
                                <h4><?= h($user['firstname']) ?></h4>
                                <p>You both like <?php echo implode(", ", $related_interest_str); ?></p>
                            </div>
                            <div class="small-10 medium-2 columns">
                                <?= $this->Html->link(__('Full Conversation'), ['controller' => 'messages', 'action' => 'view', $user['id']]) ?>
                            </div>
                        </div>

                        <div id="messages<?php echo $user['id']; ?>"></div>
                        <div class="messages-in-view">
                            <?= $this->Form->create($message, ['data-id' => $user['id'], 'class' => 'message-form' . $user['id']]) ?>
                            <fieldset>
                                <?php
                                echo $this->Form->input('body', ['type' => 'text', 'label' => false, 'id' => 'message-body' . $user['id']]);
                                echo $this->Form->hidden('recipient', ['value' => $user['id'], 'id' => 'message-recipient']);
                                ?>
                            </fieldset>
                            <?= $this->Form->button(__('Send', ['type' => 'button'])) ?>
                            <?= $this->Form->end() ?>
                        </div>
                        <button class="close-button" data-close aria-label="Close modal" type="button">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php }
                $position = $position + $space_allocated;
            }

            ?>
        </div>
        <div id="canvas"></div>
        <script>
            var relatedUsers = <?php echo json_encode($related_users_var); ?>;
        </script>
    <?php else : ?>
        <div class="container">
            <p>You have no mutual interests with anyone around you, consider adding some. bitch.</p>
        </div>
    <?php endif; ?>
</div>
