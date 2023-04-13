<div class="timer-area">

    <div class="row align-items-center mb-0">
        <div class="col-12 col-sm-6">

            <div>
                <div class="timer-text text-center fw-bold">Timer</div>
                <div class="rest text-center fw-bold d-none">Rest</div>
                <div class="workit text-center text-warning fw-bold d-none">Move</div>
            </div>

            <h1 id="segment-display" class="text-center counter" style="font-size: 100px; line-height: 80px;">00:00</h1>

            <p class="text-center fw-bold mb-0">
                Total Time: <span id="timer-display" class="text-center">00:00</span>
            </p>

            <div class="px-5 d-block d-sm-none text-center" style="letter-spacing: 15px;">
                <button class="rounded-circle bg-green-dark start-timer action-btn"><i class="fa-solid fa-play"></i></button>
                <button class="rounded-circle bg-orange-dark stop-timer action-btn"><i class="fa-solid fa-pause"></i></button>
                <button class="rounded-circle bg-pink-dark heart-workout action-btn"><i class="fa-<?=($isFavorite ? 'solid' : 'regular')?> fa-heart"></i></button>
                <button class="rounded-circle bg-dark-dark refresh-workout action-btn"><i class="fa-solid fa-arrows-rotate"></i></button>
                <button class="rounded-circle bg-gray-dark settings-workout action-btn"><i class="fa-solid fa-gear"></i></button>
            </div>
        </div>
        <div class="d-none d-sm-block col-sm-6">
            <div class="px-5 mb-3 text-center" style="letter-spacing: 15px;">
                <button class="rounded-circle bg-green-dark start-timer action-btn"><i class="fa-solid fa-play"></i></button>
                <button class="rounded-circle bg-orange-dark stop-timer action-btn"><i class="fa-solid fa-pause"></i></button>
                <button class="rounded-circle bg-pink-dark heart-workout action-btn"><i class="fa-<?=($isFavorite ? 'solid' : 'regular')?> fa-heart"></i></button>
                <button class="rounded-circle bg-dark-dark refresh-workout action-btn"><i class="fa-solid fa-arrows-rotate"></i></button>
                <button class="rounded-circle bg-gray-dark settings-workout action-btn"><i class="fa-solid fa-gear"></i></button>
            </div>
        </div>
    </div>

    <div class="change-settings" style="display: none;">
        <form method="get" class="text-center p-5">
            <input type="hidden" name="routine_id" class="routine-id" value="<?=@$routineId?>" />
            <div class="row">
                <div class="col">
                    <!-- <label class="fs-4">Sets<br /><span class="super-small">(number)</span></label> -->
                    <select class="form-control" name="limit" id="limit">
                        <?php for ($i = 2; $i < 100; ++$i) : ?>
                            <option value="<?=$i?>"<?=($i == $limit ? ' selected' : '')?>><?=$i?> Sets</option>
                        <?php endfor ?>
                    </select>
                </div>
                <div class="col">
                    <!-- <label class="fs-4">Time/Set<br /><span class="super-small">(seconds)</span></label> -->
                    <select class="form-control" name="length" id="length">
                        <?php for ($i = 10; $i < 241; ++$i) : ?>
                            <option value="<?=$i?>"<?=($i == $workoutLength ? ' selected' : '')?>><?=$i?> Seconds / Set</option>
                        <?php endfor ?>
                    </select>
                </div>
                <div class="col">
                    <!-- <label class="fs-4">Time/Break<br /><span class="super-small">(seconds)</span></label> -->
                    <select class="form-control" name="break" id="break">
                        <?php for ($i = 1; $i < 121; ++$i) : ?>
                            <option value="<?=$i?>"<?=($i == $workoutBreak ? ' selected' : '')?>><?=$i?> Seconds / Break</option>
                        <?php endfor ?>
                    </select>
                </div>
            </div>
            <div class="pt-0">
                <div class="text-begin">
                    <input type="submit" name="update" value="Update" class="btn bg-blue-dark text-uppercase fs-6 rounded w-100 update-workout" />
                </div>
            </div>
        </form>
    </div>
</div>

<div class="top-spacer">&nbsp;</div>

<div class="list-group list-custom-small" id="workoutItems">
    <?php
    foreach ($workoutItems as $key => $workout) {
        ++$key;
        ?>
        <div class="list-group-workout-item text-uppercase workout-<?=$key?>" id="workout-<?=$key?>" style="letter-spacing: 0px;">
            <div class="row px-3 mb-0 border-bottom">
                <div class="col-8 col-md-9 fs-6 text-start text-md-start">
                    <span class="set-key-text"><?=$key?></span>.
                    <span class="item-name item-name-<?=$key?>"><?=$workout['name']?></span>
                    <input
                        id="item-name-<?=$key?>"
                        type="text"
                        class="d-none form-control fs-2 edit-workout-name"
                        value="<?=$workout['name']?>"
                        name="item-name"
                        data-id="<?=$workout['id']?>"
                        data-key="<?=$key?>"
                    />
                    <input type="hidden" class="item-id item-id-<?=$key?>" value="<?=$workout['id']?>" />
                    <input type="hidden" class="item-key item-key-<?=$key?>" value="<?=$key?>" />
                </div>
                <div class="col-4 col-md-3 text-end text-md-end fs-6">
                    <!-- <span><i class="fa-solid fa-eye"></i></span> -->
                    <span><i class="w-action-btn fa-regular fa-arrows-rotate change-item" data-key="<?=$key?>"></i></span>
                    <span><i class="w-action-btn fa-regular fa-pen-to-square edit-item edit-item-<?=$key?>" data-key="<?=$key?>"></i></span>
                    <span><i class="w-action-btn fa-solid fa-check color-green-light save-item save-item-<?=$key?> d-none" data-key="<?=$key?>"></i></span>
                    <span><i class="w-action-btn fa-sharp fa-solid fa-up-down sortable"></i></span>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
</div>