<?php

namespace Tabata\Controller;

use Tabata\Entity\User;
use Tabata\System\Controller;
use Tabata\Service\WorkoutService;
use Tabata\Service\UserService;
use Tabata\Entity\Exercise;
use Tabata\Entity\Routine;
use Tabata\Entity\Favorite;
use Tabata\Entity\RoutineExercise;

class MainController extends Controller
{
    private $user;

    public function __construct()
    {
        // default user for now
        $this->user = UserService::getActiveUser();

        parent::__construct($this->user);
    }

    public function index($routineId = 0)
    {
        if (empty($this->user->id)) {
            return $this->register();
        }

        $limit = $this->user->sets;
        $workoutLength = $this->user->set_time;
        $workoutBreak = $this->user->break_time;

        if (!empty($_GET['limit'])) {
            $getLimit = (int) $_GET['limit'];
            if ($getLimit > 0) {
                $limit = $getLimit;
            }
        }

        if (!empty($_GET['length'])) {
            $getLength = (int) $_GET['length'];
            if ($getLength > 0) {
                $workoutLength = $getLength;
            }
        }

        if (!empty($_GET['break'])) {
            $getBreak = (int) $_GET['break'];
            if ($getBreak > 0) {
                $workoutBreak = $getBreak;
            }
        }

        $isFavorite = false;

        if (!empty($routineId)) {
            $workoutRoutine = Routine::find($routineId);

            // yes we have a routine
            if (!empty($workoutRoutine->id)) {
                // override variables
                $limit = $workoutRoutine->sets;
                $workoutLength = $workoutRoutine->sets_time;
                $workoutBreak = $workoutRoutine->break_time;
                $isDirty = false;

                // check for new values from post
                if (!empty($_GET['limit'])) {
                    $getLimit = (int) $_GET['limit'];
                    if ($getLimit > 0 && $getLimit !== $limit) {
                        $isDirty = true;
                        $limit = $workoutRoutine->sets = $getLimit;
                    }
                }

                if (!empty($_GET['length'])) {
                    $getLength = (int) $_GET['length'];
                    if ($getLength > 0 && $getLength !== $workoutLength) {
                        $isDirty = true;
                        $workoutLength = $workoutRoutine->sets_time = $getLength;
                    }
                }

                if (!empty($_GET['break'])) {
                    $getBreak = (int) $_GET['break'];
                    if ($getBreak > 0 && $getBreak !== $workoutBreak) {
                        $isDirty = true;
                        $workoutBreak = $workoutRoutine->break_time = $getBreak;
                    }
                }

                if ($isDirty) {
                    $workoutRoutine->save();
                }

                // get exercises
                $exercises = RoutineExercise::getExercises($workoutRoutine->id);
                $workoutItems = [];

                // convert to array
                foreach ($exercises as $exercise) {
                    $workoutItems[] = [
                        'id' => $exercise['exercise_id'],
                        'name' => $exercise['exercise_name'],
                    ];
                }

                // check if more exercises are requested than what was currently included
                $currentTotalWorkoutItems = count($workoutItems);
                if ($limit != $currentTotalWorkoutItems) {
                    if ($limit > $currentTotalWorkoutItems) {
                        // add some more
                        $newWorkoutItems = $this->getRandomExercises($limit);
                        for ($i = $currentTotalWorkoutItems; $i < $limit; ++$i) {
                            $key = $i - 1;
                            $workoutItems[] = $newWorkoutItems[$key];
                        }
                    } elseif ($limit < $currentTotalWorkoutItems) {
                        // remove last ones
                        for ($i = ($limit + 1); $i <= $currentTotalWorkoutItems; ++$i) {
                            $key = $i - 1;
                            unset($workoutItems[$key]);
                        }
                    }

                    // update saved workout
                    $workoutItems = $this->updateSavedRoutine($workoutRoutine->id, $workoutItems);
                }

                // check for favorite
                $favorite = Favorite::findBy(['routine' => $workoutRoutine->id, 'user' => $this->user->id]);
                $isFavorite = !empty($favorite);
            }
        } else {
            $workoutItems = $this->getRandomExercises($limit);
        }

        $timerInSeconds = 0;
        $timerInSeconds += $limit * $workoutLength;
        $timerInSeconds += $limit * $workoutBreak;

        $this->view("timer", [
            'isTimerPage' => true,
            'limit' => $limit,
            'workoutLength' => $workoutLength,
            'workoutBreak' => $workoutBreak,
            'isFavorite' => $isFavorite,
            'timerInSeconds' => $timerInSeconds,
            'workoutItems' => $workoutItems,
            'routineId' => $routineId,
        ]);
    }

    public function account()
    {
        $this->requiresAuth();

        $exercises = Exercise::findBy(['user' => $this->user->id]);
        $routines = Routine::findBy(['user' => $this->user->id]);

        $this->view("account", [
            'isAboutPage' => true,
            'exercises' => $exercises,
            'routines' => $routines,
            'user' => $this->user,
        ]);
    }

    public function deleteAccount()
    {
        $this->requiresAuth();

        if (!empty($_POST['confirm'])) {
            $this->user->delete();
            header("Location: /register");
            exit;
        }

        $this->view("confirm_delete");
    }

    public function logout()
    {
        if (!empty($this->user->id)) {
            $user = User::find($this->user->id);
            UserService::logoutUser($user);
        }

        header("Location: /register");
        exit;
    }

    public function login()
    {
        $error = false;

        if (!empty($_POST['email']) && !empty($_POST['password'])) {

            $isUserLoggedIn = User::login($_POST['email'], $_POST['password']);

            if ($isUserLoggedIn) {
                header("Location: /");
                exit;
            }

            $error = true;
        }

        $this->view('login', [
            'error' => $error,
        ]);
    }

    public function privacy()
    {
        $this->view('privacy');
    }

    public function terms()
    {
        $this->view('terms');
    }

    public function contact()
    {
        $this->view('contact');
    }

    public function register()
    {
        $error = '';

        if (!empty($_POST['register'])) {
            if (empty($_POST['email']) || empty($_POST['name']) || empty($_POST['password'])) {
                $error = 'All fields required';
            } else {
                if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                    $error = 'Email is invalid';
                } else {
                    $email = $_POST['email'];
                    $name = $this->formatName($_POST['name']);
                    $password = $_POST['password'];

                    // see if email already exists
                    $userExists = User::findBy(['email' => $email], 1);
                    if (!empty($userExists->id)) {
                        $error = 'Email already exists in our system';
                    } else {
                        $user = new User();
                        $user->name = $name;
                        $user->email = $email;
                        $user->setPassword($password);
                        $user->insert();

                        UserService::loginUser($user);

                        header("Location: /");
                        exit;
                    }
                }
            }
        }

        $this->view('register', [
            'page_class' => 'register',
            'error' => $error,
        ]);
    }

    public function createNewExercise()
    {
        $this->requiresAuth();

        $exercise = new Exercise();
        $exercise->name = "EDIT TO CHANGE NAME";
        $exercise->user = $this->user->id;
        $exercise->insert();

        header("Location: /account?exercises=true");
        exit;
    }

    public function ajaxSetTheme(string $theme = '')
    {
        $this->requiresAuthAjax();
        $this->user->dark_mode = (int) ($theme == 'dark');
        $this->user->save();
        $this->ajax(['status' => 'success']);
    }

    public function ajaxSaveAccount(string $name = '', string $email = '', string $password = '')
    {
        $this->requiresAuthAjax();

        if (!empty($name)) {
            $this->user->name = $name;
        }

        if (!empty($email)) {
            $this->user->email = $email;
        }

        if (!empty($password)) {
            $this->user->setPassword($password);
        }

        $this->user->save();

        $this->ajax(['status' => 'success']);
    }

    public function ajaxChangeExercise(int $exerciseId = 0, int $routineId = 0)
    {
        $this->requiresAuthAjax();

        $exercise = Exercise::getRandomItem($this->user->id);

        if (!empty($routineId)) {
            $routineExercise = RoutineExercise::findBy([
                'user' => $this->user->id,
                'routine' => $routineId,
                'exercise' => $exerciseId,
            ], 1);

            $routineExercise->exercise = $exercise['id'];
            $routineExercise->save();
        }

        $this->ajax($exercise);
    }

    public function ajaxEditExercise($id = 0, $name = '', int $routineId = 0)
    {
        $this->requiresAuthAjax();

        if (empty($id) || empty($name)) {
            $this->ajax(['status' => 'error']);
        }

        $exercise = Exercise::find($id);
        $name = $this->formatName($name);

        if ($exercise->user == $this->user->id) {
            $exercise->name = $name;
            $exercise->save();
        } else {
            $exercise = new Exercise(Exercise::class);
            $exercise->user = $this->user->id;
            $exercise->name = $name;
            $exercise->insert();
        }

        if (!empty($routineId)) {
            $routineExercise = RoutineExercise::findBy([
                'user' => $this->user->id,
                'routine' => $routineId,
                'exercise' => $id,
            ], 1);

            $routineExercise->exercise = $exercise->id;
            $routineExercise->save();
        }

        $this->ajax($exercise);
    }

    public function ajaxHeartRoutine(
        int $routineId = 0,
        array $workoutItems = [],
        int $limit = 8,
        int $set_length = 20,
        int $set_break = 10
    ) {
        $this->requiresAuthAjax();

        if (!empty($routineId)) {
            $workoutRoutine = Routine::find($routineId);
            $routineId = $workoutRoutine->id;
        }

        if (empty($routineId)) {
            $workoutRoutine = new Routine();
            $workoutRoutine->user = $this->user->id;
            $workoutRoutine->name = date("F jS, Y");
            $workoutRoutine->sets = $limit;
            $workoutRoutine->sets_time = $set_length;
            $workoutRoutine->break_time = $set_break;
            $workoutRoutine->create();
        }

        // add exercises
        $workoutItems = $this->updateSavedRoutine($workoutRoutine->id, $workoutItems);

        // now heart the routine
        $favorite = new Favorite();
        $favorite->user = $this->user->id;
        $favorite->routine = $workoutRoutine->id;
        $favorite->create();

        $this->ajax([
            'routine_id' => $workoutRoutine->id,
            'items' => $workoutItems
        ]);
    }

    public function ajaxUnHeartRoutine($routineId = 0)
    {
        $this->requiresAuthAjax();

        $this->ajaxDeleteRoutine($routineId);
    }

    public function ajaxDeleteRoutine(int $routineId = 0)
    {
        $this->requiresAuthAjax();

        // delete favorite first
        $favorite = Favorite::findBy([
            'user' => $this->user->id,
            'routine' => $routineId
        ], 1);

        if (!empty($favorite)) {
            $favorite->delete();
        }

        // delete routine
        $routine = Routine::find($routineId);

        if ($routine->user !== $this->user->id) {
            $this->ajax(['status' => 'error', 'message' => 'Access denied']);
        }

        $routine->delete();

        $this->ajax(['status' => 'success']);
    }

    public function ajaxEditRoutine(int $routineId = 0, string $name = '')
    {
        $this->requiresAuthAjax();

        if (empty($routineId) || empty($name)) {
            $this->ajax(['status' => 'error', 'message' => 'Name is required']);
        }

        $routine = Routine::find($routineId);

        if ($routine->user !== $this->user->id) {
            $this->ajax(['status' => 'error', 'message' => 'Access denied']);
        }

        $routine->name = $this->formatName($name);
        $routine->save();

        $this->ajax(['status' => 'success', 'name' => $routine->name]);
    }

    public function ajaxDeleteExercise(int $exerciseId = 0)
    {
        $this->requiresAuthAjax();

        $exercise = Exercise::find($exerciseId);

        if ($exercise->user !== $this->user->id) {
            $this->ajax(['status' => 'error', 'message' => 'Access denied']);
        }

        // check if exercise is referenced in a routine
        $routineExercise = RoutineExercise::findBy(['exercise' => $exerciseId]);

        if (count($routineExercise) > 0) {
            $this->ajax([
                'status' => 'error',
                'message' => 'This exercise is referenced by one of your routines. Delete the routine first and then you can delete this exercise'
            ]);
        }

        $exercise->delete();

        $this->ajax(['status' => 'success']);
    }

    private function getRandomExercises(int $limit = 0): array
    {
        $workouts = new WorkoutService();
        $systemExercises = Exercise::getAllBy(['user' => 1]);
        $myExercises = [];

        if ($this->user->id > 1) {
            $myExercises = Exercise::getAllBy(['user' => $this->user->id]);
        }

        if (!empty($myExercises)) {
            $exercises = array_merge($systemExercises, $myExercises);
        } else {
            $exercises = $systemExercises;
        }

        foreach ($exercises as $exercise) {
            $workouts->add($exercise->id, $exercise->name);
        }

        return $workouts->randomize($limit);
    }

    public function ajaxSaveRoutineOrder(int $routineId = 0, array $workoutItems = [])
    {
        $this->requiresAuthAjax();

        if (empty($routineId) || empty($workoutItems)) {
            $this->ajax(['status' => 'error']);
        }

        $this->updateSavedRoutine($routineId, $workoutItems);
        $this->ajax(['status' => 'success']);
    }

    private function updateSavedRoutine(int $routineId = 0, array $workoutItems = []): array
    {
        RoutineExercise::deleteBy(['routine' => $routineId]);
        $workoutRoutineExercise = new RoutineExercise();
        foreach ($workoutItems as $key => $item) {
            $workoutRoutineExercise->user = $this->user->id;
            $workoutRoutineExercise->routine = $routineId;
            $workoutRoutineExercise->exercise = $item['id'];
            $workoutRoutineExercise->create();

            // update ID
            $workoutItems[$key]['id'] = $workoutRoutineExercise->id;

            // clear so we can create a new entry
            $workoutRoutineExercise->clear();
        }

        return $workoutItems;
    }

    private function formatName(string $name = ''): string
    {
        $name = preg_replace(
            "/[^a-zA-Z \/'\d]/i",
            "",
            $name
        );

        return strtoupper($name);
    }

    private function requiresAuth()
    {
        if (empty($this->user->id)) {
            header("Location: /register");
            exit;
        }
    }

    private function requiresAuthAjax()
    {
        if (empty($this->user->id)) {
            $this->ajax(['status' => 'access denied']);
        }
    }

}