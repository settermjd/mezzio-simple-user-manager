<div>
    <form method="post" enctype="application/x-www-form-urlencoded" name="reset-password-form">
        <label for="password">
            <input type="password" id="password" name="password" placeholder="enter your password">
        </label>
        <label for="confirm_password">
            <input type="password" id="confirm_password" name="confirm_password" placeholder="enter your password">
        </label>
        <input type="submit" name="submit" value="Update Password">
        <input type="hidden" name="identity">
        <input type="hidden" name="__csrf">
    </form>
</div>