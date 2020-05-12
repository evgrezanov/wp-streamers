<?php
?>
<style>
.text-center {
    text-align: center;
}
</style>
<div class="row">
    <div class="col-12">
        <form action="" method="post" name="user_registeration">
            <label><?php __('Username', 'wp-streamers') ?></label>
            <input type="text" name="username" placeholder="Username" class="text" required /><br />
            <label><?php __('Email address', 'wp-streamers') ?> </label>
            <input type="text" name="useremail" class="text" placeholder="Email" required /> <br />
            <label><?php __('Password', 'wp-streamers') ?></label>
            <input type="password" name="password" class="text" placeholder="Password" required /> <br />
            <input type="submit" name="user_registeration" value="SignUp" />
        </form>
        <?php if(isset($signUpError)){echo '<div>'.$signUpError.'</div>';}?>
    </div>
</div>
</div>