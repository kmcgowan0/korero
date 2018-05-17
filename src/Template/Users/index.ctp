<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User[]|\Cake\Collection\CollectionInterface $users
 */
?>

<div class="users index large-9 medium-8 columns content">
    <h3><?= __('Users') ?></h3>

    <?= $this->Form->create('', ['type' => 'get']) ?>
    <?= $this->Form->control('term') ?>
    <button>Search</button>
    <?= $this->Form->end() ?>

    <table cellpadding="0" cellspacing="0" id="user-table">
        <thead>
        <tr>
            <th scope="col"></th>
            <th scope="col"><?= $this->Paginator->sort('firstname') ?></th>
            <th scope="col"><?= $this->Paginator->sort('location') ?></th>
            <th scope="col"><?= $this->Paginator->sort('You both like') ?></th>
            <th scope="col" class="actions"><?= __('Actions') ?></th>
        </tr>
        </thead>
        <tbody>

        <?php foreach ($distinct_users as $user):

        if ($user->id != $authUser['id']) {

        $related_interests = [];

        foreach ($user_matching_data as $matching_datum) {
            //if the matching data matches the id of the related user
            //add that interest to an array for this user
            if ($matching_datum['UsersInterests']->user_id == $user->id) {

                array_push($related_interests, $matching_datum['Interests']);
            }
        }

            $related_users_var[$user->id] = $related_interests;
            //add each related interest to a string
            $related_interest_str = array();
            foreach ($related_interests as $related_interest) {
                $related_interest_str[] = $related_interest->name;
            }
            $interest_count = count($related_interest_str);

            ?>

            <tr>
                <td><?php if ($user->upload) :
                        $profile_img = $user->upload;
                    else :
                        $profile_img = 'placeholder.png';
                    endif; ?>
                    <div class="profile-picture-small profile-picture"
                         style="background-image: url(/img/<?php echo $profile_img; ?>)">

                    </div>
                </td>
                <td><?= h($user->firstname) ?>, <?= h($user->age) ?></td>
                <td><?= h($user->coded_location) ?></td>
                <td><?php echo implode(", ", $related_interest_str); ?>
                </td>
                <td class="actions">
                    <?= $this->Html->link(__('View profile'), ['action' => 'view', $user->id]) ?>
                    <br>
                    <?= $this->Html->link(__('Send a message'), ['controller' => 'messages', 'action' => 'view', $user->id]) ?>
                </td>
            </tr>
        <?php } ?>

            <?php
        endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
