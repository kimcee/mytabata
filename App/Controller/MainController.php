<?php

namespace App\Controller;

use OpenAI;
use App\Entity\User;
use App\Entity\Routine;
use App\Entity\Exercise;
use App\Entity\Favorite;
use App\System\Controller;
use App\Service\UserService;
use App\Entity\RoutineExercise;
use App\Service\WorkoutService;

class MainController extends Controller
{
    private $user;

    public function __construct()
    {
        // default user for now
        $this->user = UserService::getActiveUser();

        parent::__construct($this->user);
    }

    public function index(int $routineId = 0)
    {
        $limit = $this->user->sets ?? 8;
        $workoutLength = $this->user->set_time ?? 20;
        $workoutBreak = $this->user->break_time ?? 10;
        $workoutRounds = $this->user->rounds ?? 1;
        $isFavorite = false;

        if (!empty($routineId)) {
            $workoutRoutine = Routine::find($routineId);

            // yes we have a routine
            if (!empty($workoutRoutine->id)) {
                // override variables
                $limit = $workoutRoutine->sets;
                $workoutLength = $workoutRoutine->sets_time;
                $workoutBreak = $workoutRoutine->break_time;
                $workoutRounds = $workoutRoutine->rounds;

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

                // check for favorite
                $favorite = Favorite::findBy(['routine' => $workoutRoutine->id, 'user' => $this->user->id]);
                $isFavorite = !empty($favorite);
            }
        }

        if (empty($workoutItems)) {
            $workoutItems = $this->getRandomExercises($limit);
        }

        $timerInSeconds = $this->calcTimer($limit, $workoutLength, $workoutBreak);

        $this->view("timer", [
            'isTimerPage' => true,
            'limit' => $limit,
            'workoutLength' => $workoutLength,
            'workoutBreak' => $workoutBreak,
            'isFavorite' => $isFavorite,
            'timerInSeconds' => $timerInSeconds,
            'workoutItems' => $workoutItems,
            'workoutRounds' => $workoutRounds,
            'routineId' => $routineId,
            'showExercises' => false,
        ]);
    }

    private function calcTimer(int $limit = 0, int $workoutLength = 0, int $workoutBreak = 0): int
    {
        $timerInSeconds = 0;
        $timerInSeconds += $limit * $workoutLength;
        $timerInSeconds += ($limit * $workoutBreak) - $workoutBreak;

        return $timerInSeconds;
    }

    public function account()
    {
        $this->requiresAuth();

        $exercises = Exercise::findBy(['user' => $this->user->id]);
        $routines = Routine::findBy(['user' => $this->user->id]);

        $this->view("account", [
            'hasExercises' => (count($exercises) > 0),
            'exercises' => $exercises,
            'hasRoutines' => (count($routines) > 0),
            'routines' => $routines,
            'showExercises' => !empty($_GET['exercises']),
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
            'hasError' => $error,
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
            'pageClass' => 'register',
            'hasError' => !empty($error),
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

    public function ajaxListExercises()
    {
        $this->requiresAuth();

        $customExercises = Exercise::getAllBy(['user' => $this->user->id]);
        $defaultExercises = Exercise::getAllBy(['user' => 1]);
        $exercises = array_merge($defaultExercises, $customExercises);

        $exercisesResponse = [];

        if (empty($exercises)) {
            $this->ajax($exercisesResponse);
            return;
        }

        foreach ($exercises as $e) {
            // if $e->name is similar to $_REQUEST['q'] then include it
            if (!empty($_REQUEST['q']) && strpos(strtolower($e->name), strtolower($_REQUEST['q'])) === false) {
                continue;
            }

            $exercisesResponse[] = [
                'id' => $e->id,
                'text' => $e->name,
            ];
        }

        $this->ajax($exercisesResponse);
    }

    public function ajaxUpdateRoutineSettings(
        int $sets = 0,
        int $setsTime = 0,
        int $breakTime = 0,
        int $rounds = 0,
        array $currentWorkoutItems = [],
        int $routineId = 0
    ) {
        $workoutRoutine = null;
        $newWorkoutItems = [];

        if (!empty($routineId)) {
            $workoutRoutine = Routine::find($routineId);

            if ($workoutRoutine) {
                if ($workoutRoutine->user === $this->user->id) {
                    if ($workoutRoutine->sets !== $sets) {
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

                        if ($sets > $workoutRoutine->sets) {
                            // add some more
                            $diff = $sets - $workoutRoutine->sets;
                            $newWorkoutItems = $this->getRandomExercises($diff);
                            $newCounter = 0;
                            for ($i = $workoutRoutine->sets; $i < $sets; ++$i) {
                                $workoutItems[] = $newWorkoutItems[$newCounter];
                                ++$newCounter;
                            }
                        } elseif ($sets < $workoutRoutine->sets) {
                            // remove last ones
                            for ($i = ($sets + 1); $i <= $workoutRoutine->sets; ++$i) {
                                $key = $i - 1;
                                unset($workoutItems[$key]);
                            }
                        }

                        // update saved workout
                        $workoutItems = $this->updateSavedRoutine($workoutRoutine->id, $workoutItems);
                    }

                    $workoutRoutine->sets = $sets;
                    $workoutRoutine->sets_time = $setsTime;
                    $workoutRoutine->break_time = $breakTime;
                    $workoutRoutine->rounds = $rounds;
                    $workoutRoutine->save();
                }
            }
        } else {
            $totalCurrentWorkoutItems = count($currentWorkoutItems);
            if ($sets > $totalCurrentWorkoutItems) {
                // add some more
                $diff = $sets - $totalCurrentWorkoutItems;
                $newWorkoutItems = $this->getRandomExercises($diff);
            }
        }

        $timerInSeconds = $this->calcTimer(
            $sets,
            $setsTime,
            $breakTime
        );

        $this->ajax([
            'sets' => $sets,
            'sets_time' => $setsTime,
            'break_time' => $breakTime,
            'rounds' => $rounds,
            'timer_in_seconds' => $timerInSeconds,
            'new_items' => $newWorkoutItems,
        ]);
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
        $exercise = Exercise::getRandomItem($this->user->id ?? 0);

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

    public function ajaxEditExercise($id = 0, $name = '', int $routineId = 0, bool $save = false)
    {
        $this->requiresAuthAjax();

        if (empty($id) && empty($name)) {
            $this->ajax(['status' => 'error because of no ID or name', 'id' => $id, 'name' => $name]);
        }

        $exercise = Exercise::findBy(['id' => $id, 'user' => $this->user->id], 1);
        $name = $this->formatName($name);
        
        if (empty($exercise->id)) {
            // create new execercise
            $exercise = new Exercise();
            $exercise->name = $name;
            $exercise->user = $this->user->id;
            $exercise->insert();
        } else {
            if ($save) {
                if ($exercise->user == $this->user->id) {
                    $exercise->name = $this->formatName($name);
                    $exercise->save();
                }
            }
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
        int $set_break = 10,
        int $rounds = 1
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
            $workoutRoutine->rounds = $rounds;
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
        $routineExercise = RoutineExercise::findBy(['user' => $this->user->id, 'exercise' => $exerciseId]);

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

        if (isset($this->user->id) && $this->user->id > 1) {
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

    public function ajaxAiGenerateWorkout()
    {
        $this->requiresAuthAjax();

        // if (empty($_POST)) {
        //     $this->ajax(['status' => 'error']);
        // }

        $goals = @$_POST['goals'] ?? 'Gain Muscle';
        $weightCurrent = @$_POST['weight_current'] ?? '150';
        $weightIdeal = @$_POST['weight_ideal'] ?? '165';
        $timeAvailable = @$_POST['time'] ?? '20';
        $daysPerWeek = @$_POST['days'] ?? '5';

        $equipment = '';
        $equipment .= @$_POST['yogamat'] ? 'Yogamat, ' : '';
        $equipment .= @$_POST['jumprope'] ? 'Jumprope, ' : '';
        $equipment .= @$_POST['kettle'] ? 'Kettlebells, ' : '';
        $equipment .= @$_POST['dumbbell'] ? 'Dumbbells, ' : '';
        $equipment .= @$_POST['bench'] ? 'Bench, ' : '';
        $equipment .= @$_POST['box'] ? 'Box, ' : '';
        $equipment .= @$_POST['stepper'] ? 'Stepper, ' : '';
        $equipment = trim($equipment, ', ');

        $userSurveyPrompt = "You are an incredible fitness and tabata workout creator. You've just completed a user survey to generate a Tabata workout. Here are the responses:\n\n";
        $userSurveyPrompt .= "Goals: {$goals}\n";
        $userSurveyPrompt .= "Weight: {$weightCurrent} lbs, Ideal Weight: {$weightIdeal} lbs\n";
        $userSurveyPrompt .= "Equipment available: {$equipment}\n";
        $userSurveyPrompt .= "Time available per day: {$timeAvailable} minutes\n";
        $userSurveyPrompt .= "Number of days per week for workouts: {$daysPerWeek}\n\n";
        $userSurveyPrompt .= "Based on this information, design a Tabata workout routine that aligns with the user's goals, utilizes the available equipment, and fits within their time constraints. Determine how many rounds there will be, how long each exercise will be, and how long each break in between each exercise will be. Make sure the entire routine, include all rounds, is within their time constraints. Ensure the workout is balanced, effective, and suitable for achieving the stated goals.\n\n";
        $userSurveyPrompt .= "This is a sample JSON response:\n\n";
        $userSurveyPrompt .= "{
          \"information\": \"With this routine, you will be able to achieve your goal of losing 15 pounds in 3 months.\",
          \"days\": [
            {
                \"workoutName\": \"Monday Burner\",
                \"itemsNeeded\": \"Weights and yoga mat\",
                \"totalRounds\": 2,
                \"totalExercises\": 5,
                \"secondsPerExercise\": 20,
                \"secondsPerBreak\": 10,
                \"exercises\": [
                    {
                        \"name\": \"Jumping Jacks\",
                        \"description\": \"Jump up and down, clapping your hands\"
                    },
                    {
                        \"name\": \"Arm Curls\",
                        \"description\": \"Use 10lb dumbbells to curl weights to your shoulders\"
                    },
                    {
                        \"name\": \"Box Jumps\",
                        \"description\": \"Jump on top of a box\"
                    },
                    {
                        \"name\": \"Pushups\",
                        \"description\": \"Do some push-ups, be sure to keep your back straight\"
                    },
                    {
                        \"name\": \"Butterfly Stretch\",
                        \"description\": \"Sit in butterfly position with your knees bent\"
                    },
                ]
            },
            {
                \"workoutName\": \"Tough on Tuesday\",
                \"itemsNeeded\": \"Jump Box and yoga mat\",
                \"totalRounds\": 3,
                \"totalExercises\": 2,
                \"secondsPerExercise\": 50,
                \"secondsPerBreak\": 15,
                \"exercises\": [
                    {
                        \"name\": \"Jumping Jacks\",
                        \"description\": \"Jump up and down, clapping your hands\"
                    },
                    {
                        \"name\": \"Arm Curls\",
                        \"description\": \"Use 10lb dumbbells to curl weights to your shoulders\"
                    },
                ]
            },
          ]
        }\n";
        $userSurveyPrompt .= "Please format your response in a similar manner but with data specific to survey responses, so the total number of `exercises` will be based on the tabata routine you come up and the names and descriptions should all be something you come up with as well. The exercises should be based on the equipment specified in the survey or use cardio movements and body weight. The only thing you need to keep consistent is the structure and keys of the json, all of the values should come from your response based on the survey. The total number of entries in `days` should be equal to `Number of days per week for workouts` from the survey.";

        $apiKey = OPENAI_API_KEY;

        // Set the endpoint URL
        $endpoint = 'https://api.openai.com/v1/chat/completions';
        
        // Set the request headers
        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $apiKey
        ];

        $data = [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => $userSurveyPrompt,
                ],
                [
                    'role' => 'user',
                    'content' => 'Please only respond based on the data provided, and only respond in the format requested. There should be no other dialogue or extra explanation of anything, just the json format is all that is needed.'
                ],
            ],
            // 'prompt' => $userSurveyPrompt . "\n\n" . 'Please only respond based on the data provided, and only respond in the format requested. There should be no other dialogue or extra explanation of anything, just the json format is all that is needed.',
            // 'max_tokens' => 20000, // Adjust as needed
            // 'temperature' => 0.7 // Adjust as needed
        ];

        // Initialize cURL session
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute the request
        $response = curl_exec($ch);

        // Check for errors
        if (curl_errno($ch)) {
            // echo 'Error: ' . curl_error($ch);
            $this->ajax([
                'status' => 'error',
                'response' => 'Error: ' . curl_error($ch)
            ]);
        }

        // Close cURL session
        curl_close($ch);

        // Decode the response JSON
        $result = json_decode($response, true);

        $workouts = [];
        $message = '';

        if (!empty($result['choices'][0]['message']['content'])) {
            $data = json_decode($result['choices'][0]['message']['content'], true);
            $message = @$data['information'];
            // echo '<pre>';
            // print_r(json_decode($result['choices'][0]['message']['content'], true));
            // echo '</pre>';
            // exit;

            foreach ($data['days'] as $day) {
                
                $workoutRoutine = new Routine();
                $workoutRoutine->user = $this->user->id;
                $workoutRoutine->name = @$day['workoutName'];
                $workoutRoutine->items_needed = @$day['itemsNeeded'];
                $workoutRoutine->sets = (int) @$day['totalExercises'] > 0 ? $day['totalExercises'] : count($day['exercises']);
                $workoutRoutine->sets_time = (int) @$day['secondsPerExercise'] ? $day['secondsPerExercise'] : 20;
                $workoutRoutine->break_time = (int) @$day['secondsPerBreak'] ? $day['secondsPerBreak'] : 10;
                $workoutRoutine->rounds = (int) @$day['totalRounds'] ? $day['totalRounds'] : 3;
                $workoutRoutine->create();

                $workouts[] = [
                    'id' => $workoutRoutine->id,
                    'name' => $workoutRoutine->name,
                ];
        
                foreach ($day['exercises'] as $exercise) {

                    $exerciseItem = new Exercise();
                    $exerciseItem->name = @$exercise['name'];
                    $exerciseItem->description = $this->sanitizeDescription(@$exercise['description']);
                    $exerciseItem->user = $this->user->id;
                    $exerciseItem->create();

                    $workoutRoutineExercise = new RoutineExercise();
                    $workoutRoutineExercise->user = $this->user->id;
                    $workoutRoutineExercise->routine = $workoutRoutine->id;
                    $workoutRoutineExercise->exercise = $exerciseItem->id;
                    $workoutRoutineExercise->create();
                }
        
                // now heart the routine
                $favorite = new Favorite();
                $favorite->user = $this->user->id;
                $favorite->routine = $workoutRoutine->id;
                $favorite->create();
            }
        }

        // dd($result['choices'], $result['choices'][0]['message']['content']);

        // Output the generated text
        // echo $result['choices'][0]['text'];
        $this->ajax([
            'status' => 'success',
            'workouts' => $workouts,
            'message' => $message,
        ]);
    }

    public function ajaxGetItem()
    {
        $exercise = Exercise::findBy([
            'id' => (int) @$_POST['item_id'], 
            'user' => $this->user->id
        ], 1);

        if (empty($exercise) || empty($exercise->id)) {
            $exercise = Exercise::findBy([
                'id' => (int) @$_POST['item_id'], 
                'user' => 1
            ], 1);
        }

        $this->ajax([
            'status' => 'success',
            'can_edit' => ($exercise->user == $this->user->id),
            'name' => $exercise->name,
            'description' => $exercise->description,
        ]);
    }

    public function ajaxSaveItemDetails()
    {
        $this->requiresAuth();

        $exercise = Exercise::findBy([
            'id' => (int) @$_POST['item_id'], 
            'user' => $this->user->id
        ], 1);

        if (empty($exercise) || empty($exercise->id)) {
            $this->ajax([
                'status' => 'error',
                'message' => 'Exercise not found',
            ]);
        }

        $exercise->name = $this->formatName($_POST['name']);
        $exercise->description = $this->sanitizeDescription($_POST['description']);
        $exercise->save();

        $this->ajax([
            'status' => 'success',
            'name' => $exercise->name,
            'description' => $exercise->description,
        ]);
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

        return $name;
    }

    private function sanitizeDescription(string $description = ''): string
    {
        return htmlentities(strip_tags($description));
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