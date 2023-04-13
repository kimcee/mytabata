

<div class="accordion" id="accordion-1">
    <div class="mb-2">
        <button class="btn accordion-btn no-effect color-theme bg-secondary fs-4 fw-normal" data-bs-toggle="collapse" data-bs-target="#collapse1">
            <i class="fa fa-heart color-red-dark me-2"></i>
            Favorite Routines
            <i class="fa fa-chevron-down font-10 accordion-icon"></i>
        </button>
        <div id="collapse1" class="collapse" data-bs-parent="#accordion-1">
            <div class="pt-3 pb-2 ps-3 pe-3">
                <?php if (count($routines) > 0) : ?>
                    <div class="list-group list-custom-small">
                        <?php foreach ($routines as $routine) : ?>
                            <div class="list-group-workout-item text-uppercase fs-4 my-routine-<?=$routine->id?>">
                                <div class="row">
                                    <div class="col-12 col-md-8 text-center text-md-start">
                                        <span class="view-routine routine-name routine-name-<?=$routine->id?>" data-id="<?=$routine->id?>"><?=$routine->name?></span>
                                        <input
                                                id="routine-name-<?=$routine->id?>"
                                                type="text"
                                                class="d-none form-control fs-2 edit-routine-name"
                                                value="<?=$routine->name?>"
                                                name="routine-name"
                                                data-id="<?=$routine->id?>"
                                        />
                                    </div>
                                    <div class="col-12 col-md-4 text-center text-md-end fs-6" style="letter-spacing: 15px;">
                                        <span><i class="fa-regular fa-eye view-routine" data-id="<?=$routine->id?>"></i></span>
                                        <span><i class="fa-regular fa-pen-to-square edit-routine edit-routine-<?=$routine->id?>" data-id="<?=$routine->id?>"></i></span>
                                        <i class="fa-solid fa-check color-green-light save-routine-<?=$routine->id?> d-none"></i>
                                        <span><i class="fa-regular fa-trash delete-routine" data-id="<?=$routine->id?>"></i></span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                <?php else : ?>
                    <div class="pt-3 pb-3">
                        <p class="fs-4" style="line-height: 32px;"><span>You have not favorited any routines yet. On the home page, click on the heart icon when you like a routine.</span></p>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>
    <div class="mb-2">
        <button class="btn accordion-btn no-effect color-theme bg-secondary fs-4 fw-normal exercises-btn" data-bs-toggle="collapse" data-bs-target="#collapse2">
            <i class="fa-solid fa-dumbbell color-yellow-dark me-2"></i>
            Your Exercises
            <i class="fa fa-chevron-down font-10 accordion-icon"></i>
        </button>
        <div id="collapse2" class="collapse" data-bs-parent="#accordion-1">
            <div class="pt-3 pb-2 ps-3 pe-3">
                <?php if (count($exercises) > 0) : ?>
                    <div class="list-group list-custom-small">
                        <?php foreach ($exercises as $key => $exercise) : ?>
                            <div class="list-group-workout-item my-exercise-<?=$exercise->id?> text-uppercase fs-4">
                                <div class="row">
                                    <div class="col-12 col-md-8 text-center text-md-start">
                                        <span class="item-name item-name-<?=$key?>"><?=$exercise->name?></span>
                                        <input
                                                id="item-name-<?=$key?>"
                                                type="text"
                                                class="d-none form-control fs-2 edit-workout-name"
                                                value="<?=$exercise->name?>"
                                                name="item-name"
                                                data-id="<?=$exercise->id?>"
                                                data-key="<?=$key?>"
                                        />
                                        <input type="hidden" class="item-id item-id-<?=$key?>" value="<?=$exercise->id?>" />
                                        <input type="hidden" class="item-key item-key-<?=$key?>" value="<?=$key?>" />
                                    </div>
                                    <div class="col-12 col-md-4 text-center text-md-end fs-6" style="letter-spacing: 15px;">
                                        <span><i class="fa-regular fa-pen-to-square edit-item edit-item-<?=$key?>" data-key="<?=$key?>"></i></span>
                                        <i class="fa-solid fa-check color-green-light save-item-<?=$key?> d-none"></i>
                                        <i class="fa-regular fa-trash delete-custom-exercise" data-id="<?=$exercise->id?>"></i>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach ?>
                    </div>
                <?php else : ?>
                    <div class="pt-3 pb-3">
                        <p class="fs-4" style="line-height: 32px;"><span>You have not created any exercises yet.</span></p>
                    </div>
                <?php endif ?>
                <p class="mb-3"><a href="/create_new_exercise" class="btn btn-primary fs-4">Create New Exercise</a></p>
            </div>
        </div>
    </div>
    <div class="mb-2">
        <button class="btn accordion-btn no-effect color-theme bg-secondary fs-4 fw-normal" data-bs-toggle="collapse" data-bs-target="#collapse3">
            <i class="fa fa-user-circle color-blue-dark me-2"></i>
            User Info
            <i class="fa fa-chevron-down font-10 accordion-icon"></i>
        </button>
        <div id="collapse3" class="collapse"  data-bs-parent="#accordion-1">
            <div class="pt-3 pb-2 ps-3 pe-3">

                <div class="alert alert-success account-success d-none">
                    Saved.
                </div>

                <div class="input-style has-borders has-icon mb-4">
                    <i class="fa fa-user"></i>
                    <input type="name" value="<?=$user->name?>" class="form-control user-name fs-6" id="form1" placeholder="Name">
                    <i class="fa fa-times disabled invalid color-red-dark"></i>
                    <i class="fa fa-check disabled valid color-green-dark"></i>
                </div>

                <div class="input-style has-borders has-icon mb-4">
                    <i class="fa-solid fa-envelope"></i>
                    <input type="email" value="<?=$user->email?>" class="form-control user-email fs-6" id="form2" placeholder="Email">
                    <i class="fa fa-times disabled invalid color-red-dark"></i>
                    <i class="fa fa-check disabled valid color-green-dark"></i>
                </div>

                <div class="input-style has-borders has-icon mb-4">
                    <i class="fa-solid fa-key"></i>
                    <input type="password" class="form-control user-password fs-6" id="form3" placeholder="Password">
                    <i class="fa fa-times disabled invalid color-red-dark"></i>
                    <i class="fa fa-check disabled valid color-green-dark"></i>
                </div>

                <div class="pb-3">
                    <input type="button" class="btn btn-primary save-account fs-4" value="Save" />
                </div>

            </div>
        </div>
    </div>
</div>

<div class="pt-3 pb-3 fs-6">
    <p class="text-center">
        <a href="/logout" class="btn btn-xs btn-secondary mr-5 rounded" style="margin-right: 15px !important;">Logout</a>
        <a href="/delete_account" class="btn btn-xs btn-danger rounded">Delete Account</a>
    </p>
</div>