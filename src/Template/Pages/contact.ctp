<?php
$this->assign('title', 'Contact');
?>
<div class="container dark-theme">
    <div class="row">
        <div class="columns small-12 medium-10 medium-offset-1 text-center">
            <h1>Get in touch</h1>
            <p>If you've got a question get in touch using the form below.</p>
        </div>
    </div>
    <div class="row">
        <div class="columns small-12 medium-8 medium-offset-2 text-center">
            <form action="https://formspree.io/katiemcgoo@hotmail.co.uk" method="post">
                <input type="text" name="email" placeholder="Email address">
                <textarea rows="5" type="text" name="body" placeholder="Message body"></textarea>
                <input type="submit" name="submit" value="Send Message" class="red-button send-button">
                <input type="hidden" name="_next" value="/pages/confirmation" />
            </form>

        </div>
    </div>
</div>
