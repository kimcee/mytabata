<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <title>MyTABATA</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" type="text/css" href="/Assets/styles/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="/Assets/styles/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="/Assets/fonts/css/fontawesome-all.min.css">
    <link rel="manifest" href="/_manifest.json?v=4" data-pwa-version="set_in_manifest_and_pwa_js">
    <link rel="apple-touch-icon" sizes="180x180" href="/Assets/app/icons/icon-192x192.png">

    <style>
        body {
            background: #191b27;
        }
        .timer-area {
            position: fixed !important;
            width: 100%;
            top: 55px;
            left: 0;
            padding-top: 10px;
            min-height: 150px;
            border-bottom: 1px solid #555;
            background: #0f1117;
        }
        .theme-light .timer-area {
            background: #eee;
        }
        .top-spacer {
            width: 100%;
            height: 130px;
        }
        .btn {
            letter-spacing: 2px;
        }
        .super-small {
            font-size: 12px;
        }
        .header-title {
            text-transform: none !important;
        }
        .view-routine {
            cursor: pointer;
        }
        .account-heading {
            background: #333 !important;
        }
        .theme-light .account-heading {
            background: #ccc !important;
        }
        .action-btn {
            width: 65px; height: 65px;
            font-size: 30px;
            text-align; center;
            line-height: 68px;
            cursor: pointer;
        }
        .w-action-btn {
            cursor: pointer;
            background: #333;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            line-height: 35px !important;
            padding: 0;
            text-align: center;
            margin-left: 15px;
        }
        #workoutItems {
            margin-left: -15px !important;
            margin-right: -15px !important;
        }
        .theme-light .w-action-btn {
            background: #ccc;
        }
        .bg-green-dark.show-disabled {
            opacity: 0.5;
        }
        .is-sorting {
            background: #333;
        }
        .theme-light .is-sorting {
            background: #eee;
        }
        .register-bg {
            background: #181923;
            color: #fff;
        }
        .register-bg h1 {
            letter-spacing: 2px;
        }
        .register-bg p, .register-bg h1 {
            color: #000;
        }
        .register-form {
            margin-top: 50px;
            padding-bottom: 0;
        }
        .register-form-row {
            position: absolute;
            top: 0;
            left: 0;
            margin: 0;
            width: 100%;
        }
        .register-form .input-style.has-borders input {
            height: 35px;
            padding-top: 0;
            padding-bottom: 0;
            line-height: 35px;
            font-size: 12px;
        }
        body.register .content {
            margin: 0;
        }
        .list-group-workout-item, .list-group-workout-item input {
            font-size: 32px !important;
        }
        #segment-display {
            font-size: 100px;
            line-height: 80px;
        }
        .workout-item-active {
            background-color: #8CC152 !important;
        }
        .workout-item-active .exercise-name {
            color: #FFF !important;
        }
        .workout-item-pending {
            /*bg-orange-dark*/
            background-color: #E9573F !important;
        }
        .workout-item-pending .exercise-name {
            color: #FFF !important;
        }
        .workout-item-finished {
            /*bg-dark-dark*/
            background-color: #434A54 !important;
        }
        .workout-item-finished .exercise-name {
            color: #FFF !important;
        }
        .is-playing .w-action-btn {
            display: none;
        }
        .action-btns {
            letter-spacing: 15px;
        }
        .is-playing .exercise-name {
            width: 100% !important;
        }
        .is-playing .exercise-btns {
            display: none !important;
        }

        @media screen and (max-width: 600px) {
            .action-btns {
                letter-spacing: 8px;
            }
            #segment-display {
                font-size: 60px;
                line-height: 50px;
            }
            .list-group-workout-item, .list-group-workout-item input {
                font-size: 16px !important;
            }
            .w-action-btn {
                margin-left: 5px;
                width: 24px;
                height: 24px;
                line-height: 24px !important;
                font-size: 10px;
            }
            .register-form-row {
                position: relative;
                top: auto;
                left: auto;
                margin: inherit;
                width: 100%;
            }
            .register-bg p, .register-bg h1 {
                color: #fff;
            }
            .register-form {
                margin-top: -150px;
                padding-bottom: 50px;
            }
            .register-form .input-style.has-borders input {
                height: 35px;
                padding-top: 0;
                padding-bottom: 0;
                line-height: 35px;
                font-size: 12px;
            }
            .timer-area {
                min-height: 190px;
            }
            .top-spacer {
                height: 170px;
            }
            .action-btn {
                width: 40px;
                height: 40px;
                font-size: 18px;
                line-height: 42px;
            }
        }
    </style>

</head>

<body class="theme-<?=(!empty($user->dark_mode) ? 'dark' : 'light')?> <?=@$page_class?>"> <!-- detect-theme -->

<!--<div id="preloader"><div class="spinner-border color-highlight" role="status"></div></div>-->

<div id="page">

    <div class="header header-fixed header-logo-app">
        <a href="/" class="header-title go-home">MyTABATA</a>
<!--        <a href="#" data-menu="menu-main" class="header-icon header-icon-1"><i class="fas fa-bars"></i></a>-->
        <a href="/" class="header-icon header-icon-1 go-home"><i class="fa-solid fa-dumbbell"></i></a>
        <?php if (!empty($user)) : ?>
        <a href="/account" class="header-icon header-icon-2"><i class="fa fa-user-circle font-14"></i></a>
        <a href="#" class="header-icon header-icon-3 show-on-theme-dark toggle-theme"><i class="fas fa-lightbulb color-yellow-dark"></i></a>
        <a href="#" class="header-icon header-icon-3 show-on-theme-light toggle-theme"><i class="fas fa-moon"></i></a>
        <?php endif ?>
    </div>

    <div id="menu-main" data-menu-load="/Assets/menu-main.html" class="menu menu-box-left" data-menu-width="280" data-menu-effect="menu-over"></div>
    <div id="menu-account" data-menu-load="/Assets/menu-account.html" class="menu menu-box-bottom" data-menu-height="265" data-menu-effect="menu-parallax"></div>

    <div class="page-content header-clear">

        <div class="content"> <!-- style="margin-bottom: 250px !important;"> -->
            <?=$CONTENT?>
        </div>

        <div class="footer">

            <a href="/">
                <h1 class="text-center font-800 color-white font-40">MyTABATA</h1>
                <p class="mt-0 font-12 color-white opacity-30 text-center">Built with <i class="fa fa-heart color-red-light"></i> by Kimcee</p>
            </a>
            <!-- <p class="text-center">
                <a href="#" class="icon icon-xxs rounded-s mx-1 bg-facebook"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="icon icon-xxs rounded-s mx-1 bg-twitter"><i class="fab fa-twitter"></i></a>
                <a href="#" class="icon icon-xxs rounded-s mx-1 bg-instagram"><i class="fab fa-instagram"></i></a>
            </p> -->
            <div class="divider divider-margins bg-gray-light opacity-10 mb-2"></div>
            <div class="px-3">
                <div class="d-flex pb-2">
                    <div class="m-auto"><a href="/privacy" class="font-10 color-white">Privacy Policy</a></div>
                    <div class="m-auto"><a href="/terms" class="font-10 color-white">Terms and Conditions</a></div>
                    <div class="m-auto"><a href="/contact" class="font-10 color-white">Contact Us</a></div>
                </div>
            </div>
            <div class="divider divider-margins bg-gray-light opacity-10"></div>
            <p class="font-11 text-center mb-0">Copyright &copy; Enabled <?=date("Y")?>. All rights reserved.</p>

        </div>

    </div>

    <!-- Be sure this is on your main visiting page, for example, the index.html page-->
    <!-- Install Prompt for Android -->
    <div id="menu-install-pwa-android" class="menu menu-box-modal"
         data-menu-height="375"
         data-menu-width="320"
         data-menu-effect="menu-parallax">
        <img class="mx-auto mt-4 rounded-m" src="/Assets/app/icons/icon-128x128.png" alt="img" width="90">
        <h4 class="text-center mt-4 mb-2">MyTABATA on your Home Screen</h4>
        <p class="text-center boxed-text-xl">
            Install MyTABATA on your home screen, and access it just like a regular app. It really is that simple!
        </p>
        <div class="boxed-text-l">
            <a href="#" class="pwa-install mx-auto btn btn-m font-700 bg-highlight text-uppercase">Add to Home Screen</a>
            <a href="#" class="pwa-dismiss close-menu btn-full mt-3 pt-2 text-center text-uppercase font-600 color-red-light font-12">Maybe later</a>
        </div>
    </div>

    <!-- Install instructions for iOS -->
    <div id="menu-install-pwa-ios"
         class="menu menu-box-modal"
         data-menu-height="330"
         data-menu-width="320"
         data-menu-effect="menu-parallax">
        <div class="boxed-text-xl top-25">
            <img class="mx-auto mt-4 rounded-m" src="/Assets/app/icons/icon-128x128.png" alt="img" width="90">
            <h4 class="text-center mt-4 mb-2">MyTABATA on your Home Screen</h4>
            <p class="text-center ml-3 mr-3 mb-2 pb-2">
                Install MyTABATA on your home screen, and access it just like a regular app. Open your Safari menu and tap "Add to Home Screen".
            </p>
            <a href="#" class="pwa-dismiss close-menu btn-full mt-3 text-center text-uppercase font-900 color-red-light opacity-90 font-110">Maybe later</a>
        </div>
    </div>

</div>


<!-- Sounds -->
<audio id="beep3" autoload="auto"><source src="/Sounds/beep_1.mp3" type="audio/mpeg"></audio>
<audio id="beep2" autoload="auto"><source src="/Sounds/beep_1.mp3" type="audio/mpeg"></audio>
<audio id="beep1" autoload="auto"><source src="/Sounds/beep_1.mp3" type="audio/mpeg"></audio>
<audio id="begin" autoload="auto"><source src="/Sounds/beep_2.mp3" type="audio/mpeg"></audio>
<audio id="complete" autoload="auto"><source src="/Sounds/success.mp3" type="audio/mpeg"></audio>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
<!--<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>-->
<!--<script type="text/javascript" src="/Assets/scripts/jquery-ui-touch-punch.js"></script>-->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<script type="text/javascript" src="/Assets/scripts/bootstrap.min.js"></script>
<script type="text/javascript" src="/Assets/scripts/custom.js?v=<?=rand(1111,9999)?>"></script>

<script>

    let activeItemClass  = "workout-item-active";
    let pendingItemClass = "workout-item-pending";
    let finishedItemClass = "workout-item-finished";

    let isPaused = false;
    let isTimerRunning = false;
    let onBreak = false;
    let workoutNumber = 0;
    let justStarted = false;

    let duration = <?=intval(@$timerInSeconds)?>;
    let timer = duration, minutes, seconds;
    let display = $("#timer-display");

    let workoutDuration = <?=intval(@$workoutLength)?>;
    let breakDuration = <?=intval(@$workoutBreak)?>;
    let sTimer = breakDuration, sMinutes, sSeconds;
    let sDisplay = $('#segment-display');

    let sounds = {};

    function stopTimer() {
        isTimerRunning = false;
    }

    function startTimer() {
        onBreak = true;
        isTimerRunning = true;
        justStarted = true;

        var timerID = setInterval(function () {
            if (!isPaused) {
                if (isTimerRunning) {
                    updateTotalTimer();
                    updateWorkoutTimer();
                } else {
                    clearInterval(timerID);
                }
            }
        }, 1000);
    }

    function setTimersUI() {
        beginMin = calcSecondsToMinutes(timer);
        beginSec = calcRemainingSecondsInMinute(timer);
        display.text(beginMin + ":" + beginSec);
    }

    function calcSecondsToMinutes(timeInSeconds) {
        var nowMinutes = parseInt(timeInSeconds / 60, 10);
        return nowMinutes < 10 ? "0" + nowMinutes : nowMinutes;
    }

    function calcRemainingSecondsInMinute(timeInSeconds) {
        var nowSeconds = parseInt(timeInSeconds % 60, 10);
        return nowSeconds < 10 ? "0" + nowSeconds : nowSeconds;
    }

    function updateTotalTimer() {
        minutes = calcSecondsToMinutes(timer);
        seconds = calcRemainingSecondsInMinute(timer);
        display.text(minutes + ":" + seconds);

        if (--timer < 0) {
            timer = 0;

            // timer has expired
            isTimerRunning = false;
        }
    }

    function updateWorkoutTimer() {
        // update the UI
        if (onBreak) {
            sDisplay.addClass("text-secondary");

            if (sTimer == breakDuration) {
                if (!justStarted) {
                    // should say "rest"
                    playSound('complete');
                }

                if (!$(".timer-text").hasClass("d-none")) {
                    $(".timer-text").addClass("d-none");
                }

                $(".rest").removeClass("d-none");
                $(".workit").addClass("d-none");

                ++workoutNumber;
                workoutPrevNumber = workoutNumber - 1;

                $(".workout-" + workoutNumber)
                    .addClass(pendingItemClass)
                    .removeClass(activeItemClass);

                $(".list-group-workout-item." + activeItemClass)
                    .removeClass(activeItemClass)
                    .addClass(finishedItemClass);
            }
        } else {
            sDisplay.removeClass("text-secondary");

            if (sTimer == workoutDuration) {
                if (!justStarted) {
                    // should say "workout"
                    playSound('begin');

                    var offset = $(".top-spacer").height() + 75;

                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#workout-" + workoutNumber).offset().top - offset
                    }, 500);
                }

                $(".rest").addClass("d-none");
                $(".workit").removeClass("d-none");

                $(".workout-" + workoutNumber)
                    .addClass(activeItemClass)
                    .removeClass(pendingItemClass);
            }
        }

        // time is now up and running
        justStarted = false;

        // countdown tones
        if (sTimer < 4) {
            if (sTimer == 3) {
                playSound('beep3');
            } else if (sTimer == 2) {
                playSound('beep2');
            } else if (sTimer == 1) {
                playSound('beep1');
            }
        }

        sMinutes = calcSecondsToMinutes(sTimer);
        sSeconds = calcRemainingSecondsInMinute(sTimer);
        sDisplay.text(sMinutes + ":" + sSeconds);

        if (--sTimer < 1) {
            if (onBreak) {
                sTimer = workoutDuration;
                onBreak = false;
            } else {
                if (timer > 0) {
                    sTimer = breakDuration;
                    onBreak = true;
                }
            }
        }
    }

    function playSound(soundID, muted) {
        if (typeof muted == 'undefined') {
            muted = false;
        }

        if (sounds[soundID]) {
            var audio = sounds[soundID];
        } else {
            var audio = document.getElementById(soundID);
            sounds[soundID] = audio;
        }

        if (muted) {
            audio.muted = true;
        } else {
            if (audio.muted) {
                audio.muted = false;
            }
        }

        audio.play();
    }

    function playAllSounds() {
        // this is to give us access to
        // auto play the remaining sounds
        playSound('beep1', true);
        playSound('beep2', true);
        playSound('beep3', true);
        playSound('complete', true);
    }

    if ($("#workoutItems").hasClass("list-group")) {
        Sortable.create(workoutItems, {
            handle: '.sortable',
            animation: 250,
            dragClass: 'is-sorting',
            dragoverBubble: true,
            onUpdate: function () {
                let routine_id = $(".routine-id").val();

                let workout_items = [];
                let new_key = 0;

                $(".list-group-workout-item").each(function () {
                    let item_id = $(this).find(".item-id").val();
                    let item_name = $(this).find("span.item-name").text();
                    let current_key = $(this).find(".item-key").val();

                    // update key
                    ++new_key;
                    $(this).find(".item-key").val(new_key);
                    $(this).attr("id", "workout-" + new_key).removeClass("workout-" + current_key).addClass("workout-" + new_key);
                    $(this).find(".set-key-text").text(new_key);
                    $(this).find(".item-name").removeClass("item-name-" + current_key).addClass("item-name-" + new_key);
                    $(this).find('input').attr("id", "item-name-" + new_key).attr("data-key", new_key);
                    $(this).find('.change-item').attr("data-key", new_key);
                    $(this).find('.edit-item').removeClass("edit-item-" + current_key).addClass("edit-item-" + new_key).attr("data-key", new_key);
                    $(this).find('.save-item').removeClass("save-item-" + current_key).addClass("save-item-" + new_key).attr("data-key", new_key);

                    workout_items.push({
                        id: item_id,
                        name: item_name
                    });
                });

                if (!routine_id || routine_id < 1) {
                    return false;
                }

                $.post({
                    type: "POST",
                    url: "/ajax/save_routine_order",
                    data: {
                        routine_id: routine_id,
                        items: workout_items
                    },
                    success: function (result) {

                    }
                });
            }
        });
    }

    $(".toggle-theme").click(function() {
        $("body").toggleClass("theme-dark").toggleClass("theme-light");

        let current_theme = 'light';

        if ($("body").hasClass("theme-dark")) {
            current_theme = 'dark';
        }

        $.post({
            type: "POST",
            url: "/ajax/set_theme",
            data: {
                theme: current_theme
            }
        });
        return false;
    });

    $(".start-timer").click(function() {
        // stop user from initiating multiple times
        $(this).attr("disabled", true).addClass("show-disabled");
        $('body').addClass("is-playing");

        // check if timer is paused
        if (isPaused) {
            isPaused = false;
            return false;
        }

        // hack to give permission to play sounds
        playAllSounds();

        // sound to notify user timer has started
        // should say "get ready..."
        playSound('begin');

        // begin timer
        startTimer();
    });

    $(".stop-timer").click(function() {
        $('body').removeClass("is-playing");
        isPaused = true;
        $(".start-timer").attr("disabled", false).removeClass("show-disabled");
    });

    $(".change-item").click(function() {
        let routine_id = $(".routine-id").val();
        let key = $(this).parent("span").parent("div").parent("div").find(".item-key").val();
        let id = $(".item-id-" + key).val();

        $(this).addClass("changing").prop("disabled", true);

        $.post({
            url: "/ajax/change_exercise",
            data: {
                exercise_id: id,
                routine_id: routine_id
            },
            success: function (result) {
                $(".item-name-" + key).text(result.name);
                $(".item-id-" + key).val(result.id);
                $(".changing").prop("disabled", false).removeClass("changing");
            }
        });
    });

    $(".refresh-workout").click(function() {
        window.open("/","_self");
    });

    $(".heart-workout").click(function() {
        let routine_id = $(".routine-id").val();
        let workout_items = [];

        if (routine_id && $(this).find("i").hasClass("fa-solid")) {
            $(".heart-workout").find("i").addClass("fa-regular").removeClass("fa-solid");
            let limit = $("#limit").val();
            let set_length = $("#length").val();
            let set_break = $("#break").val();

            // remove heart
            $.post({
                type: "POST",
                url: "/ajax/unheart_routine",
                data: {
                    routine_id: routine_id,
                    limit: limit,
                    set_length: set_length,
                    set_break: set_break
                },
                success: function (result) {
                    // update the ID
                    $(".routine-id").val("");
                }
            });
        } else {

            $(".heart-workout").find("i").removeClass("fa-regular").addClass("fa-solid");

            $(".list-group-workout-item").each(function () {
                let item_id = $(this).find(".edit-workout-name").attr("data-id");
                let item_name = $(this).find("span.item-name").text();

                workout_items.push({
                    id: item_id,
                    name: item_name
                });
            });

            $.post({
                type: "POST",
                url: "/ajax/heart_routine",
                data: {
                    routine_id: routine_id,
                    items: workout_items
                },
                success: function (result) {
                    // update the ID
                    $(".routine-id").val(result.routine_id);
                }
            });
        }
    });

    $(".settings-workout").click(function() {
        $(".change-settings").slideToggle(250);
    });

    $(".edit-item").click(function() {
        let key = $(this).parent("span").parent("div").parent("div").find(".item-key").val();
        $(".item-name-" + key).addClass("d-none");
        $(".item-key-" + key).addClass("d-none");
        $("#item-name-" + key).removeClass("d-none");
        $(this).addClass("d-none");
        $(".save-item-" + key).removeClass("d-none");
        $("#item-name-" + key).focus();
    });

    $(".edit-workout-name").blur(function() {
        let name = $(this).val();
        let key = $(this).parent("div").find(".item-key").val();
        let id = $(this).parent("div").find(".item-id").val();
        let routine_id = $(".routine-id").val();

        $(".item-name-" + key).removeClass("d-none");
        $(".item-key-" + key).removeClass("d-none");
        $("#item-name-" + key).addClass("d-none");

        $(".edit-item-" + key).removeClass("d-none");
        $(".save-item-" + key).addClass("d-none");

        $.post({
            type: "POST",
            url: "/ajax/edit_exercise",
            data: {
                id: id,
                name: name,
                routine_id: routine_id
            },
            success: function (result) {
                $(".item-name-" + key).text(result.name);
                $("#item-name-" + key).val(result.name).attr("data-id", result.id);
                $(".item-id-" + key).val(result.id);
            }
        });
    });

    $(".edit-routine").click(function() {
        let routine_id = $(this).attr("data-id");
        $(".routine-name-" + routine_id).addClass("d-none");
        $("#routine-name-" + routine_id).removeClass("d-none");
        $(this).addClass("d-none");
        $(".save-routine-" + routine_id).removeClass("d-none");
        $("#routine-name-" + routine_id).focus();
    });

    $(".edit-routine-name").blur(function() {
        let name = $(this).val();
        let routine_id = $(this).attr("data-id");

        $(".routine-name-" + routine_id).removeClass("d-none");
        $("#routine-name-" + routine_id).addClass("d-none");

        $(".edit-routine-" + routine_id).removeClass("d-none");
        $(".save-routine-" + routine_id).addClass("d-none");

        $.post({
            type: "POST",
            url: "/ajax/edit_routine",
            data: {
                name: name,
                routine_id: routine_id
            },
            success: function (result) {
                $(".routine-name-" + routine_id).text(result.name);
                $("#routine-name-" + routine_id).val(result.name);
            }
        });
    });

    <?php if (!empty($_GET['exercises'])) : ?>
        setTimeout(function() {
            $(".exercises-btn").click();
        }, 250);
    <?php endif ?>

    $(".save-account").click(function() {
        let name = $(".user-name").val();
        let email = $(".user-email").val();
        let password = $(".user-password").val();

        $.post({
            type: "POST",
            url: "/ajax/save_account",
            data: {
                name: name,
                email: email,
                password: password
            },
            success: function (result) {
                // update the ID
                $(".account-success").removeClass("d-none");

                setTimeout(function() {
                    $(".alert-success").addClass("d-none");
                }, 2000);
            }
        });
    });

    $(".view-routine").click(function() {
        let routine_id = $(this).attr("data-id");
        window.open("/?routine_id=" + routine_id, "_self");
    });

    $(".delete-custom-exercise").click(function() {
        let exercise_id = $(this).attr('data-id');

        if (confirm("Are you sure you want to delete this?")) {
            $.post({
                type: "POST",
                url: "/ajax/delete_exercise",
                data: {
                    exercise_id: exercise_id
                },
                success: function (result) {
                    if (result.status === 'success') {
                        $(".my-exercise-" + exercise_id).slideUp(250);
                        setTimeout(function() {
                            $(".my-exercise-" + exercise_id).remove();
                        }, 250);
                    } else {
                        alert(result.message);
                    }
                }
            });
        }
    });

    $(".delete-routine").click(function() {
        let routine_id = $(this).attr('data-id');

        if (confirm("Are you sure you want to delete this?")) {
            $.post({
                type: "POST",
                url: "/ajax/delete_routine",
                data: {
                    routine_id: routine_id
                },
                success: function (result) {
                    if (result.status === 'success') {
                        $(".my-routine-" + routine_id).slideUp(250);
                        setTimeout(function() {
                            $(".my-routine-" + routine_id).remove();
                        }, 250);
                    } else {
                        alert(result.message);
                    }
                }
            });
        }
    });

    setTimersUI();

    //$("#limit").val('<?//=@$_GET['limit'] ?? $limit?>//');
    //$("#length").val('<?//=@$_GET['length'] ?? $workoutLength?>//');
    //$("#break").val('<?//=@$_GET['break'] ?? $workoutBreak?>//');

</script>

</body>
</html>
