<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class TeacherController extends Controller
{

    public function login() {

        return view('teacher.login');
    }
    public function index_page()
    {
        $courses= Course::where('teacher_id', auth()->guard('teacher')->id())->get();
        return view('teacher.dashboard',compact('courses'));
    }
    public function dologin() {
        $this->validate(request(),[
            'password'=>'required',
            'email' => 'required',
        ]);
        $rememberme = request('rememberme') == 1?true:false;
        if (auth()->guard('teacher')->attempt(['email' => request('email'), 'password' => request('password')], $rememberme)) {
            return redirect(route('teacher.home'));
        } else {
            session()->flash('error', 'Make sure the email or password is correct');
            return redirect(route('teacher.login'));
        }
    }
    public function logout() {
        auth()->logout();
        return redirect(route('teacher.login'));
    }
    public function grade($course_number){

        $student_grades= Grade::where('course_id', $course_number)->pluck('user_id')->toArray();
        $students= Student::whereNotIn('id',$student_grades)->get();
        return view('teacher.add_grade',compact('course_number','students'));
    }
    public function add_grade(Request $request, $course_id){
        $request->validate([
            'student_id'=> 'required',
            'grade'=> 'required',
        ]);

        Grade::create([
            'user_id'=> $request->student_id,
            'course_id'=> $course_id,
            'grade'=> $request->grade,
        ]);
        return redirect()->back()->with(['success'=> 'The grade was added successfully']);
    }

    protected $path= 'admin.teacher';
    public function index()
    {
        $teachers= Teacher::orderByDesc('created_at')->get();
        return view($this->path.'.index',compact('teachers'));
    }
    public function create()
    {
        $teacher= null;
        return view($this->path.'.form_page',compact('teacher'));
    }
    public function store(Request $request)
    {
        $request->flashOnly(['name','phone','email']);
        $validation= Validator::make(
            $request->all(),[
            'name'=>'required|string|max:255',
            'email'=>'required|string|max:255',
            'phone'=>'required|string|max:255',
            'password'=>'required|string|max:255',
            'image'=>'mimes:jpeg,jpg,png,gif|required'
        ],[],[]
        );
        if ($validation->fails()){
            return redirect()->back()->withErrors($validation->errors());
        }

        $filename=null;
        if ($request->image){

            $file = $request->image;
            $extension = $file->getClientOriginalExtension();
            $directory = '/images/';
            $filename = sha1(time().rand()).".{$extension}";
            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }
            Image::make($file)->save(Storage::disk('public')->path($directory).$filename);

        }
        try {
            Teacher::create([
                'name'=> $request->name,
                'email'=> $request->email,
                'phone'=> $request->phone,
                'password'=> Hash::make($request->password),
                'image'=> $filename,
            ]);
            // all good
            return redirect()->route('teacher.index')->with(['success'=>'The teacher added successfully']);
        } catch (\Exception $e) {
            // something went wrong
            return redirect()->back()->with(['error'=>'Error']);
        }

    }
    public function edit($id)
    {
        $teacher= Teacher::whereId($id)->first();
        return view($this->path.'.form_page',compact('teacher'));
    }
    public function update(Request $request, $id)
    {
        $request->flashOnly((['name','email','phone']));
        $validation= Validator::make(
            $request->all(),[
            'name'=>'required|string|max:255',
            'email'=>'required|string|max:255',
            'phone'=>'required|string|max:255',
            'image'=>'mimes:jpeg,jpg,png,gif'
        ],[],[]
        );

        if ($validation->fails()){
            return redirect()->back()->withErrors($validation->errors());
        }
        try {

            $teacher= Teacher::whereId($id)->first();
            $filename= $teacher->image;
            if ($request->image){

                if (Storage::disk('public')->exists($teacher->image_path)){
                    Storage::disk('public')->delete($teacher->image_path);
                }
                $file = $request->image;
                $extension = $file->getClientOriginalExtension();
                $directory = '/images/';
                $filename = sha1(time().rand()).".{$extension}";
                if (!Storage::disk('public')->exists($directory)) {
                    Storage::disk('public')->makeDirectory($directory);
                }
                Image::make($file)->save(Storage::disk('public')->path($directory).$filename);

            }

            $teacher->update([
                'name'=> $request->name,
                'email'=> $request->email,
                'phone'=> $request->phone,
                'password'=> $request->password? Hash::make($request->password): $teacher->password,
                'image'=> $filename,
            ]);
            // all good
            return redirect()->route('teacher.index')->with(['success'=>'Updated Successfully']);
        } catch (\Exception $e) {
            // something went wrong
            return redirect()->back()->with(['error'=>'something went wrong']);

        }
    }
    public function destroy($id)
    {
        try {

            $teacher= Teacher::whereId($id)->first();
            if (Storage::disk('public')->exists($teacher->image_path)){
                Storage::disk('public')->delete($teacher->image_path);
            }
            $teacher->delete();
            // all good
            return redirect()->back()->with(['success'=>'Deleted Successfully']);

        } catch (\Exception $e) {
            // something went wrong
            return redirect()->back()->with(['error'=>'Error']);
        }

    }
}
