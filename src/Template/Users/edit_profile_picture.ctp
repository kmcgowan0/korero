<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<?php if ($my_profile) : ?>
    <?php if ($user->upload) :
        $profile_img = $user->upload;
    else :
        $profile_img = 'placeholder.png';
    endif; ?>

    <?= $this->Form->create($user, ['id' => 'image-form']) ?>
    <fieldset>
        <?php echo $this->Form->control('add-image', ['type' => 'file', 'id' => 'upload']); ?>
        <div id="upload-demo"></div>
        <?php echo $this->Form->hidden('upload', ['type' => 'file', 'id' => 'profile-picture']); ?>
    </fieldset>

    <?= $this->Form->button(__('Update profile picture')) ?>
    <?= $this->Form->end() ?>

    <script type="text/javascript">
        $( document ).ready(function() {
            var $uploadCrop;

            function readFile(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $uploadCrop.croppie('bind', {
                            url: e.target.result
                        });
                        $('.upload-demo').addClass('ready');
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }

            $uploadCrop = $('#upload-demo').croppie({
                viewport: {
                    width: 200,
                    height: 200,
                    type: 'circle'
                },
                boundary: {
                    width: 300,
                    height: 300
                }
            });

            $('#upload').on('change', function () { readFile(this); });

        });
    </script>

<?php else : ?>

    <p>Not allowed bro</p>

<?php endif; ?>