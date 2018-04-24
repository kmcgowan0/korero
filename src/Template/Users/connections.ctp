<?php if ($user->upload) :
    $profile_img = $user->upload;
else :
    $profile_img = 'placeholder.png';
endif; ?>
<div class="main-user profile-picture" id="user-<?php echo $user->id; ?>" style="background-image: url(/img/<?php echo $profile_img; ?>)"></div>


<?php if ($distinct_users->count()) : ?>
    <div class="related view large-9 medium-8 columns content">
        <h4><?= __('Related Users') ?></h4>
        <?php foreach ($distinct_users as $related_user):
            if ($related_user->id != $user->id) : ?>

                <?php
                $related_interests = [];

                foreach ($user_matching_data as $matching_datum) {
                    if ($matching_datum['UsersInterests']->user_id == $related_user->id) {
                        array_push($related_interests, $matching_datum['Interests']->name);
                    }
                }
                $interest_count = count($related_interests);
                $related_interest_str = array();
                foreach ($related_interests as $related_interest) {
                    $related_interest_str[] = $related_interest;
                }

                if ($related_user->upload) :
                    $related_profile_img = $related_user->upload;
                else :
                    $related_profile_img = 'placeholder.png';
                endif; ?>
                <a href="#" data-open="modal-<?php echo $related_user->id; ?>"
                   data-id="<?php echo $related_user->id; ?>" id="user-<?php echo $related_user->id; ?>" class="reveal-link">
                    <div class="related-user main-user profile-picture"
                         style="border: solid #000 <?php echo $interest_count; ?>px; background-image: url(/img/<?php echo $related_profile_img; ?>)">
                        <p><?= h($related_user->firstname) ?></p>
                    </div>
                </a>

                <div class="reveal" id="modal-<?php echo $related_user->id; ?>" data-reveal>
                    <div class="profile-info row">
                        <div class="profile-picture-small profile-picture small-2 columns"
                             style="background-image: url(/img/<?php echo $related_profile_img; ?>)"></div>
                        <div class="small-10 medium-8 columns">
                            <h4><?= h($related_user->firstname) ?></h4>
                            <p>You both like <?php echo implode(", ", $related_interest_str); ?></p>
                        </div>
                        <div class="small-10 medium-2 columns">
                            <?= $this->Html->link(__('Full Conversation'), ['controller' => 'messages', 'action' => 'view', $related_user->id]) ?>
                        </div>
                    </div>

                    <div id="messages<?php echo $related_user->id; ?>"></div>
                    <div class="messages-in-view">
                        <?= $this->Form->create($message, ['data-id' => $related_user->id, 'class' => 'message-form' . $related_user->id]) ?>
                        <fieldset>
                            <?php
                            echo $this->Form->input('body', ['type' => 'text', 'label' => false, 'id' => 'message-body' . $related_user->id]);
                            echo $this->Form->hidden('recipient', ['value' => $related_user->id, 'id' => 'message-recipient']);
                            ?>
                        </fieldset>
                        <?= $this->Form->button(__('Submit', ['type' => 'button'])) ?>
                        <?= $this->Form->end() ?>
                    </div>
                    <button class="close-button" data-close aria-label="Close modal" type="button">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>


            <?php endif;
        endforeach; ?>

    </div>

<?php endif; ?>

