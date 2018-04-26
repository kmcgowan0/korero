<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<div class="users form large-9 medium-8 columns content">
    <?= $this->Form->create($user, ['enctype'=>'multipart/form-data']) ?>
    <fieldset>
        <legend><?= __('Edit User') ?></legend>
        <?php
        echo $this->Form->control('email');
        echo $this->Form->control('firstname');
        echo $this->Form->control('lastname');
        echo $this->Form->control('dob', ['empty' => true, 'minYear' => 1950, 'maxYear' => date('Y')]);
        echo $this->Form->control('location');
        echo '<span id="location"></span>';
        echo '<label for="upload">Profile picture</label>';
        echo $this->Form->input('upload', array('type' => 'file'));
        ?>
        <h4>Top Interests</h4>
        <?php echo $this->Form->control('interests._ids', ['options' => $top_interests, 'multiple' => 'checkbox']);
        ?>
        <div id="selected-form">
            <?php
                foreach($user->interests as $interest) {
                    echo '<input type="hidden" name="interests[_ids][]" value="' . $interest->id . '">';
                }
            ?>


        </div>
    </fieldset>

    <div class="row">
        <label>Search for interests</label>
        <div id="selected" class=""></div>
        <div>

            <input id="search" type="text">
        </div>
        <div id="results"></div>
    </div>


    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>

</div>
<button onclick="getLocation()">Try It</button>
<script>
    var locationInput = $("#location");
    console.log('location '+locationInput);


    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else {
            locationInput.html("Geolocation is not supported by this browser.");
        }
    }

    function showPosition(position) {
        locationInput.html(position.coords.latitude + ', ' + position.coords.longitude);
        console.log('position '+position);

    }
</script>
