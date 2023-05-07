<?php

namespace App\Http\Controllers;

use App\Models\Grade;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class StudentController extends Controller
{
    public function login() {

        return view('student.login');
    }
    public function index_page()
    {
        $grades= Grade::where('user_id', auth()->guard('student')->id())
            ->join('courses', 'courses.id', 'grades.course_id')
            ->get();
        return view('student.dashboard', compact('grades'));
    }

    public function dologin() {
        $this->validate(request(),[
            'password'=>'required',
            'email' => 'required',
        ]);
        $rememberme = request('rememberme') == 1?true:false;
        if (auth()->guard('student')->attempt(['email' => request('email'), 'password' => request('password')], $rememberme)) {
            return redirect(route('student.home'));
        } else {
            session()->flash('error', 'Make sure the email or password is correct');
            return redirect(route('student.login'));
        }
    }

    public function logout() {
        auth()->guard('student')->logout();
        return redirect(route('student.login'));
    }


    protected $path= 'admin.student';
    public function index()
    {
        $students= Student::orderByDesc('created_at')->get();
        return view($this->path.'.index',compact('students'));
    }
    public function create()
    {
        $student= null;
        return view($this->path.'.form_page',compact('student'));
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
            Student::create([
                'name'=> $request->name,
                'email'=> $request->email,
                'phone'=> $request->phone,
                'password'=> Hash::make($request->password),
                'image'=> $filename,
            ]);
            // all good
            return redirect()->route('student.index')->with(['success'=>'The student added successfully']);
        } catch (\Exception $e) {
            // something went wrong
            return redirect()->back()->with(['error'=>'Error']);
        }

    }
    public function edit($id)
    {
        $student= Student::whereId($id)->first();
        return view($this->path.'.form_page',compact('student'));
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
        ],[],[]);

        if ($validation->fails()){
            return redirect()->back()->withErrors($validation->errors());
        }
        try {

            $student= Student::whereId($id)->first();
            $filename= $student->image;
            if ($request->image){

                if (Storage::disk('public')->exists($student->image_path)){
                    Storage::disk('public')->delete($student->image_path);
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

            $student->update([
                'name'=> $request->name,
                'email'=> $request->email,
                'phone'=> $request->phone,
                'password'=> $request->password? Hash::make($request->password): $student->password,
                'image'=> $filename,
            ]);
            // all good
            return redirect()->route('student.index')->with(['success'=>'Updated Successfully']);
        } catch (\Exception $e) {
            // something went wrong
            return redirect()->back()->with(['error'=>'something went wrong']);

        }
    }
    public function destroy($id)
    {
        try {

            $student= Student::whereId($id)->first();
            if (Storage::disk('public')->exists($student->image_path)){
                Storage::disk('public')->delete($student->image_path);
            }
            $student->delete();
            // all good
            return redirect()->back()->with(['success'=>'Deleted Successfully']);

        }
        catch (\Exception $e) {
            // something went wrong
            return redirect()->back()->with(['error'=>'Error']);
        }

    }
}
