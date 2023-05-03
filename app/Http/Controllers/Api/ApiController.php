<?php

namespace App\Http\Controllers\Api;

use App\Http\Traits\GeneralResponse\GeneralResponse;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Document;
use App\Models\FavouriteCourse;
use App\Models\FavouriteRoom;
use App\Models\FeedbackCourse;
use App\Models\Message;
use App\Models\Room;
use App\Models\User;
use App\Models\VideosCourse;
use http\Env\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use PHPOpenSourceSaver\JWTAuth\Contracts\Providers\Auth;
use function PHPUnit\Framework\isEmpty;
use Illuminate\Support\Facades;
class ApiController extends Controller
{
    use ApiTrait;

    public function __construct()
    {
        $this->middleware('JWTMiddleware', ['except' => ['login', 'reset', 'register']]);
    }


    //RESET PASSWORD

    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => [
                'required',
                'email',
                Rule::in(['uaahmed89@gmail.com'])
            ]], [
            'email.in' => 'This Api is for only this email => uaahmed89@gmail.com ',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $emailErrors = $errors->first('email');
            // Do something with the error messages
            if ($emailErrors) {
                return $this->ApiResponse($emailErrors, 404, '');
            }
        }

        $status = Password::sendResetLink(
            $request->only('email')
        );
        return $status == Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);
    }

    /*@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@*/


    //CREATE COURSE
    public function create_courses(Request $request)
    {
        $data = $request->only('name');
        $validator = Validator::make($data, [
            'name' => 'required|string|unique',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $nameErrors = $errors->first('name');
            if ($nameErrors) {
                return $this->ApiResponse($nameErrors, 404, '');
            }
        }

        if (Facades\Auth::user()->type == 'instructor') {
            $course = Course::create([
                'name' => $request->name,
                'instructor_name' => Facades\Auth::user()->name,
                'instructor_id' => \Illuminate\Support\Facades\Auth::user()->id,
            ]);
            return $this->ApiResponse('Created Successfully', 200, $course);
        } else
            return $this->ApiResponse('Field : You Are Not An Instructor', 404, "");
    }


    /*@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@*/


    //SHOW ALL COURSES
    public function show_all_course()
    {


        $courses = Course::first();
        if ($courses) {
            $courses = Course::with(['video_course' => function ($q) {
                $q->select('name', 'videos', 'course_id');
            }, 'feedback' => function ($q) {
                $q->select('body', 'course_id');
            }])->get();
            return $this->ApiResponse('Success', 200, $courses);
        } else
        {
            $courses = Course::get();
            return $this->ApiResponse('There are no courses', 404,$courses) ;

        }
    }


    /*@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@*/


    //SEARCHING FOR COURSES
    public function search_course(Request $request)
    {
        $name = $request->name;

        if (isset($name)) {

            $course = Course::with(['video_course', 'feedback'])->where('name', 'like', '%' . $name . '%')->get();
            if (!$course)
                return $this->ApiResponse('Not Found', 404, "");
            else {
                $course = Course::with(['video_course' => function ($q) {
                    $q->select('name', 'videos', 'course_id');
                }, 'feedback' => function ($q) {
                    $q->select('body', 'course_id');
                }])->where('name', 'like', '%' . $name . '%')->get();
                return $this->ApiResponse('Success', 200, $course);
            }
        } else {

            $name = Course::with(['video_course' => function ($q) {
                $q->select('videos', 'course_id');
            }, 'feedback' => function ($q) {
                $q->select('body', 'course_id');
            }])->get();
            return $this->ApiResponse('Success', 200, $name);
        }

    }

    /*@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@*/


    //WRITE FEEDBACK
    public function create_feedback(Request $request)
    {
        $data = $request->only('body', 'course_id');
        $validator = Validator::make($data, [
            'body' => 'required||integer|between:1,5',
            'course_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $bodyErrors = $errors->first('body');
            $course_idErrors = $errors->first('course_id');
            if ($bodyErrors) {
                return $this->ApiResponse($bodyErrors, 404, '');
            }
            if ($course_idErrors) {
                return $this->ApiResponse($course_idErrors, 404, '');
            }
        }

        $body = $request->body;
        $course_id = $request->course_id;
        $test = DB::table('courses')->select('id')->where('id', $course_id)->first();
        if ($test) {
            $feedback = FeedbackCourse::create([
                'body' => $body,
                'course_id' => $course_id,
            ]);
            return $this->ApiResponse('Success', 200, $feedback);
        } else
            return $this->ApiResponse('Course_id Not Found', 404, null);
    }


    /*@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@*/


    //SHOW FEEDBACK BY COURSE ID
    public function show_feedbacks_Course_id(Request $request)
    {
        $data = $request->only('course_id');
        $validator = Validator::make($data, [
            'course_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $id = $request->course_id;
        $course_id = Course::find($id);
        $feedback = FeedbackCourse::where('course_id', $id)->first();
        if ($feedback && $course_id) {
            return $this->ApiResponse('Success', 200, $course_id->Feedback);
        } else {
            $feedback = FeedbackCourse::get();
            return $this->ApiResponse('Not Found id or no feedback for this course', 404, $feedback);
        }
    }

    /*@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@*/


    //UPLOAD VIDEO ON A COURSE
    public function upload_videos_course_id(Request $request)
    {

        $data = $request->only('course_id', 'videos', 'name');
        $validator = Validator::make($data, [
            'name' => 'required',
            'course_id' => 'required|integer',
            'videos' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $nameErrors = $errors->first('name');
            $course_idErrors = $errors->first('course_id');
            $videoErrors = $errors->first('videos');
            if ($nameErrors) {
                return $this->ApiResponse($nameErrors, 404, '');
            } elseif ($course_idErrors) {
                return $this->ApiResponse($course_idErrors, 404, '');
            } elseif ($videoErrors) {
                return $this->ApiResponse($videoErrors, 404, '');
            }

        }

        $id = \Illuminate\Support\Facades\Auth::user()->id;
        $insteuctor_id = DB::table('courses')->select('instructor_id')->where('instructor_id', '=', $id)->first();

        if (\Illuminate\Support\Facades\Auth::user()->type == 'instructor' && $insteuctor_id) {
            $data = $request->only('videos', 'course_id', 'name');
            $validator = Validator::make($data, [
                'videos' => 'required|unique:videos_courses',
                'course_id' => 'required|string',
                'name' => 'required|string',
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->messages()], 200);
            }

            $videos = $request->file('videos');
            $videos_name = $videos->getClientOriginalName();
            $name = $request->name;
            $course_id = $request->course_id;
            $id = DB::table('courses')->select('id')->where('id', $course_id)->first();
            if ($id) {
                $VideoCourse = VideosCourse::create([
                    'videos' => $videos_name,
                    'course_id' => $course_id,
                    'name' => $name,

                ]);
                $videos->move(public_path('files'), $videos_name);
                return $this->ApiResponse('Uploaded Successfully', 200, '');
            } else
                return $this->ApiResponse('Course_id not found', 404, "");
        } else
            return $this->ApiResponse('Sorry You Are Not An Instructor OR This Course Not Belongs To You', 404, "");
    }


    /*@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@*/


    //SHOW VIDEO BY COURSE ID
    public function show_videos_course_id(Request $request)
    {
        $data = $request->only('course_id');
        $validator = Validator::make($data, [
            'course_id' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            $errors = $validator->errors();
            $course_idErrors = $errors->first('course_id');
            if ($course_idErrors) {
                return $this->ApiResponse($course_idErrors, 404, '');
            }
        }

        $id = $request->course_id;
        //$feedback = FeedbackCourse::where('course_id', $id)->first();
        $course_id = Course::with(['video_course' => function ($q) {
            $q->select('name', 'videos', 'course_id');
        }, 'feedback' => function ($q) {
            $q->select('body', 'course_id');
        }])->find($id);
        if ($course_id) {
            //$course_id->feedback = $feedback;
            return $this->ApiResponse('Success', 200, $course_id);
        } else {
            $course_id = Course::get();
                return $this->ApiResponse('Not Found', 404, $course_id);
        }
    }

    /*@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@*/


    //UPDATE USER
    public function update_user(Request $request)
    {


        $id = \Illuminate\Support\Facades\Auth::user()->id;
        $user = User::find($id);

        if ($user) {
            $data = $request->only('email');
            $validator = Validator::make($data, [
                'email' => 'email|unique:users,email,' . $id,
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors();
                $emailErrors = $errors->first('email');
                if ($emailErrors) {
                    return $this->ApiResponse($emailErrors, 404, '');
                }
            }
            if ($request->has('name')) {
                $user->name = $request->name;
            }

            if ($request->has('email')) {
                $user->email = $request->email;
            }

            if ($request->has('password')) {
                $user->password = bcrypt($request->password);
            }

            $user->save();
            return $this->ApiResponse('Updated Successfully', 200, $user);
        }

    }



    /*@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@*/


    //REMOVE USER
    public function remove_user(Request $request)
    {


        $id = Facades\Auth::user()->id;
        $user = User::find($id);
        if (!$user) {
            return $this->ApiResponse('There Are No User By This Token', 404, "");
        } else {
            $user->delete();
            return $this->ApiResponse('Your Account Deleted successfully', 200, "");
        }


//        $email = $request->email;
//        if (!$email)
//        {
//            return $this->ApiResponse('Enter Your Email', 404, "");
//        }
//        if ($email == Facades\Auth::user()->email)
//        {
//            Facades\Auth::user()->delete();
//            return $this->ApiResponse('Your Account Deleted successfully', 200, "");
//        }
//        else
//        {
//            return $this->ApiResponse('Sorry the email is incorrect', 200, "");
//        }
//        DB::table('users')->where('id', $id)->delete();


    }

    /*@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@*/


    // CREATE ROOM
    public function createRoom(Request $request)
    {
        $data = $request->only('name');
        $validator = Validator::make($data, [
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $nameErrors = $errors->first('name');
            // Do something with the error messages
            if ($nameErrors) {
                return $this->ApiResponse($nameErrors, 404, '');
            }
        }

        $type = Facades\Auth::user()->type;
        if ($type == 'instructor') {
            $room = Room::create([
                'name' => $request->name,
                'description' => $request->description,
                'user_id' => Facades\Auth::user()->id,

            ]);

            return $this->ApiResponse('Created Successfully', 200, $room);
        } else {
            return $this->ApiResponse('Sorry You Are Not An Instructor', 404, "");
        }

    }


    /*@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@*/


    //Go TO ROOM
    public function joinRoom(Request $request)
    {
        $data = $request->only('room_id');
        $validator = Validator::make($data, [
            'room_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $room_idErrors = $errors->first('room_id');
            if ($room_idErrors) {
                return $this->ApiResponse($room_idErrors, 404, '');
            }
        }

        $name = $request->name;
        $room_id = $request->room_id;
        $room = DB::table('rooms')->where('id', '=', $room_id)->first();
        if ($room) {
            if (!$request->file('file')) {
                if ($request->message) {
                    $message = Message::create([
                        'message_text' => $request->message,
                        'room_id' => $request->room_id,
                        'file' => "",
                        'user_id' => Facades\Auth::user()->id,
                        'sender' => \Illuminate\Support\Facades\Auth::user()->name,
                    ]);
                } else {
                    $message = Message::create([
                        'message_text' => "Joined Successfully",
                        'room_id' => $request->room_id,
                        'file' => "",
                        'user_id' => Facades\Auth::user()->id,
                        'sender' => \Illuminate\Support\Facades\Auth::user()->name,
                    ]);
                }
                $message = DB::table('messages')->select('sender', 'message_text', 'file', 'created_at')->where('room_id', '=', $room_id)->get();
                return response()->json(['Room Chat' => $message]);
            } else {

                $file = $request->file('file');
                $file_name = $file->getClientOriginalName();
                if ($request->message) {
                    Message::create([
                        'message_text' => $request->message,
                        'room_id' => $request->room_id,
                        'file' => $file_name,
                        'user_id' => Facades\Auth::user()->id,
                        'sender' => \Illuminate\Support\Facades\Auth::user()->name,
                    ]);
                    $file->move(public_path('files'), $file_name);
                    $message = DB::table('messages')->select('sender', 'message_text', 'file', 'created_at')->where('room_id', '=', $room_id)->get();
                    return response()->json(['Room Chat' => $message]);
                } else {
                    $message = Message::create([
                        'message_text' => "Joined Successfully",
                        'room_id' => $request->room_id,
                        'file' => "",
                        'user_id' => Facades\Auth::user()->id,
                        'sender' => \Illuminate\Support\Facades\Auth::user()->name,
                    ]);
                }
            }
        } else {
            return $this->ApiResponse('Not Found', 404, "");
        }


    }


    /*@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@*/


    public function uploadFile(Request $request)
    {

        $data = $request->only('file');
        $validator = Validator::make($data, [
            'file' => 'required|file',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $file = $request->file('file');
        $name = $file->getClientOriginalName();
        $fileContent = file_get_contents($file);
        $fileSuccess = Document::create([
            'name' => $name,
            'content' => $fileContent,
        ]);
        return $this->ApiResponse('Created Successfully', 200, $name);

    }


    /*@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@*/


    public function AddRoomFav(Request $request)
    {

//        $data = $request->only('room_id');
//        $validator = Validator::make($data, [
//            'room_id' => 'required|integer',
//        ]);
//
//        if ($validator->fails()) {
//            $errors = $validator->errors();
//            $room_idErrors = $errors->get('room_id');
//            if ($room_idErrors) {
//                return $this->ApiResponse($room_idErrors, 404, '');
//            }
//        }
        $data = $request->only('room_id');
        $validator = Validator::make($data, [
            'room_id' => ['required',
                Rule::unique('favourite_rooms')->where(function ($query) {
                    return $query->where('user_id', Facades\Auth::user()->id);
                }), 'integer'],
        ], ['room_id.unique' => 'The Room Already Has Been Added To Your Favourite']);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $room_idErrors = $errors->first('room_id');
            if ($room_idErrors) {
                return $this->ApiResponse($room_idErrors, 404, '');
            }
        }

        $room = Room::find($request->room_id);
        if ($room) {
            $addFav = FavouriteRoom::create([
                'user_id' => Facades\Auth::user()->id,
                'room_id' => $request->room_id,
            ]);

            return $this->ApiResponse('Added Successfully', 200, '');
        } else
            return $this->ApiResponse('Not Found', 404, "");

    }


    /*@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@*/


    public function AddCourseFav(Request $request)
    {
        $data = $request->only('course_id');
        $validator = Validator::make($data, [
            'course_id' => ['required',
                Rule::unique('favourite_courses')->where(function ($query) {
                    return $query->where('user_id', Facades\Auth::user()->id);
                })],
        ], ['course_id.unique' => 'The Course Already Has Been Added To Your Favourite']);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $course_idErrors = $errors->first('course_id');
            if ($course_idErrors) {
                return $this->ApiResponse($course_idErrors, 404, '');
            }
        }

        $course = Course::find($request->course_id);
        if ($course) {
            $addFav = FavouriteCourse::create([
                'user_id' => Facades\Auth::user()->id,
                'course_id' => $request->course_id,
            ]);

            return $this->ApiResponse('Added Successfully', 200, '');
        } else
            return $this->ApiResponse('Not Found', 404, "");

    }


    /*@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@*/


    public function showFavRoom(Request $request)
    {
        $roomo = FavouriteRoom::with('room')->where('user_id', '=', Facades\Auth::user()->id)->first();
        if ($roomo) {
            $room = FavouriteRoom::with('room')->where('user_id', '=', Facades\Auth::user()->id)->get();
            return $this->ApiResponse('success', 200, $room);
        } else {
            {            $room = FavouriteRoom::get();
                return $this->ApiResponse('Nothing Added Yet', 404, $room);
            }
        }
    }


    /*@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@*/


    public function showFavCourse(Request $request)
    {
        $course = FavouriteCourse::with('course')->where('user_id', '=', Facades\Auth::user()->id)->first();
        if ($course) {
            $course = FavouriteCourse::with('course')->where('user_id', '=', Facades\Auth::user()->id)->get();
            return $this->ApiResponse('success', 200, $course);
        } else {
            {
                $course = FavouriteCourse::get();
                return $this->ApiResponse('Nothing Added Yet', 404, $course);
            }
        }
    }

    /*@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@*/


    public function getProfile()
    {
        $profile = User::find(Facades\Auth::user()->id);
        return $this->ApiResponse('success', 200, $profile);
    }


    /*@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@*/


    public function removeFavCourse(Request $request)
    {
        $data = $request->only('course_id');
        $validator = Validator::make($data, [
            'course_id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $course_idErrors = $errors->first('course_id');
            if ($course_idErrors) {
                return $this->ApiResponse($course_idErrors, 404, '');
            }
        }
        $course_id = $request->course_id;
        $course = FavouriteCourse::where('course_id', $course_id)->where('user_id', Facades\Auth::user()->id)->first();
        if ($course) {
            $course->delete();
            return $this->ApiResponse('Removed From Favourite Courses Successfully', 200, "");
        } else {
            return $this->ApiResponse('Not Found Or This Course Is Not In Your Fav', 404, "");
        }
    }

    /*@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@*/


    public function removeFavRoom(Request $request)
    {
        $data = $request->only('room_id');
        $validator = Validator::make($data, [
            'room_id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $room_idErrors = $errors->first('room_id');
            if ($room_idErrors) {
                return $this->ApiResponse($room_idErrors, 404, '');
            }
        }
        $room_id = $request->room_id;
        $room = FavouriteRoom::where('room_id', $room_id)->where('user_id', Facades\Auth::user()->id)->first();
        if ($room) {
            $room->delete();
            return $this->ApiResponse('Removed From Favourite Rooms Successfully', 200, "");
        } else {
            return $this->ApiResponse('Not Found Or This Room Is Not In Your Fav', 404, "");
        }
    }


    /*@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@*/


    public function getMyRooms()
    {
        $user_id = Facades\Auth::user()->id;
        $myRooms = DB::table('rooms')->where('user_id', $user_id)->first();
        if ($myRooms) {
            $myRooms = DB::table('rooms')->where('user_id', $user_id)->get();
            return $this->ApiResponse('success', 200, $myRooms);
        } else
            {            $myRooms = DB::table('rooms')->where('user_id', $user_id)->get();
                return $this->ApiResponse('Nothing Added Yet', 404, $myRooms);
            }
    }

    /*@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@*/


    public function getMyCourses()
    {
        $user_id = Facades\Auth::user()->id;
        $myCourses = DB::table('courses')->where('instructor_id', $user_id)->first();
        if ($myCourses) {
            $myCourses = DB::table('courses')->where('instructor_id', '=', $user_id)->get();
            return $this->ApiResponse('success', 200, $myCourses);
        }else
        {            $myCourses = DB::table('rooms')->where('user_id', $user_id)->get();
            return $this->ApiResponse('Nothing Added Yet', 404, $myCourses);
        }
    }


    /*@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@*/



    public function show_all_room()
    {
        $rooms = Room::first();
        if(!$rooms)
        {
            $rooms = Room::get();
            return $this->ApiResponse('Not Found', 404, $rooms);
        }
        else
        {
            $rooms = Room::get();
            return $this->ApiResponse('Success', 200, $rooms);

        }
    }
}


