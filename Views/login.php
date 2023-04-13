<h1 class="text-start ps-3">LOGIN</h1>
<form method="post">
    <div class="pt-3 pb-2 ps-3 pe-3">

        <?php if ($error) : ?>
        <div class="alert alert-danger">
            Invalid login credentials.
        </div>
        <?php endif ?>

        <div class="input-style has-borders has-icon mb-4">
            <i class="fa-solid fa-envelope"></i>
            <input type="email" name="email" value="" class="form-control user-email fs-6" id="form2" placeholder="Email">
            <i class="fa fa-times disabled invalid color-red-dark"></i>
            <i class="fa fa-check disabled valid color-green-dark"></i>
        </div>

        <div class="input-style has-borders has-icon mb-4">
            <i class="fa-solid fa-key"></i>
            <input type="password" name="password" class="form-control user-password fs-6" id="form3" placeholder="Password">
            <i class="fa fa-times disabled invalid color-red-dark"></i>
            <i class="fa fa-check disabled valid color-green-dark"></i>
        </div>

        <div class="pb-3">
            <input type="submit" class="btn btn-primary fs-6 px-3 rounded" value="LOGIN" />
        </div>

    </div>
</form>