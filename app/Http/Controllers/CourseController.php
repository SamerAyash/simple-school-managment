<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{

    protected $path= 'admin.course';
    public function index()
    {
        $courses= Course::orderByDesc('created_at')->get();
        return view($this->path.'.index',compact('courses'));
    }
    public function create()
    {
        $course= null;
        return view($this->path.'.form_page',compact('course'));
    }
    public function store(Request $request)
    {
        $request->flashOnly(['name','teacher_id','course_number']);
        $validation= Validator::make(
            $request->all(),[
            'name'=>'required|string|max:255',
            'course_number'=>'required|string|max:255',
            'teacher_id'=>'required|string|max:255',
        ],[],[]
        );
        if ($validation->fails()){
            return redirect()->back()->withErrors($validation->errors());
        }

        try {
            Course::create([
                'name'=> $request->name,
                'course_number'=> $request->course_number,
                'teacher_id'=> $request->teacher_id,
            ]);
            // all good
            return redirect()->route('course.index')->with(['success'=>'The course added successfully']);
        } catch (\Exception $e) {
            // something went wrong
            return redirect()->back()->with(['error'=>'Error']);
        }

    }
    public function edit($id)
    {
        $course= Course::whereId($id)->first();
        return view($this->path.'.form_page',compact('course'));
    }
    public function update(Request $request, $id)
    {
        $request->flashOnly((['name','course_number','teacher_id']));
        $validation= Validator::make(
            $request->all(),[
            'name'=>'required|string|max:255',
            'course_number'=>'required|string|max:255',
            'teacher_id'=>'required|string|max:255',
        ],[],[]);

        if ($validation->fails()){
            return redirect()->back()->withErrors($validation->errors());
        }
        try {

            $course= Course::whereId($id)->first();

            $course->update([
                'name'=> $request->name,
                'course_number'=> $request->course_number,
                'teacher_id'=> $request->teacher_id,
            ]);
            // all good
            return redirect()->route('course.index')->with(['success'=>'Updated Successfully']);
        } catch (\Exception $e) {
            // something went wrong
            return redirect()->back()->with(['error'=>'something went wrong']);

        }
    }
    public function destroy($id)
    {
        try {
            $course->delete();
            // all good
            return redirect()->back()->with(['success'=>'Deleted Successfully']);

        }
        catch (\Exception $e) {
            // something went wrong
            return redirect()->back()->with(['error'=>'Error']);
        }

    }
}
