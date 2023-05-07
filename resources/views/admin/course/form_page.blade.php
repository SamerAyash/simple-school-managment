@extends('layout.app')
@section('content')
    <!--begin::Content-->
    <div class="content  d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Subheader-->
        <div class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
            <div
                class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                <!--begin::Info-->
                <div class="d-flex align-items-center flex-wrap mr-1">

                    <!--begin::Page Heading-->
                    <div class="d-flex align-items-baseline flex-wrap mr-5">
                        <!--begin::Page Title-->
                        <h5 class="text-dark font-weight-bold my-1 mr-5">
                            Courses
                        </h5>
                        <!--end::Page Title-->

                        <!--begin::Breadcrumb-->
                        <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                            <li class="breadcrumb-item">
                                <a href="{{route('course.index')}}" class="text-muted">
                                    Courses
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="#" class="text-muted">
                                    {{!$course?'Create course' : 'Edit course'}}
                                </a>
                            </li>
                        </ul>
                        <!--end::Breadcrumb-->
                    </div>
                    <!--end::Page Heading-->
                </div>
                <!--end::Info-->

                <!--begin::Toolbar-->
                <div class="d-flex align-items-center"></div>
                <!--end::Toolbar-->
            </div>
        </div>
        <!--end::Subheader-->
        <div class="d-flex flex-column-fluid">
            <!--begin::Container-->
            <div class=" container ">
                <!--begin::Card-->
                <div class="card card-custom gutter-b">
                    <div class="card-header">
                        <div class="card-title">
                            <h3 class="card-label">{{!$course?'Create new course' : 'Edit course'}}</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <!--begin::Example-->
                        <div class="example mb-10">
                            <div class="example-preview">
                                <form method="post" action="{{!$course? route('course.store'): route('course.update',$course->id)}}" enctype="multipart/form-data">
                                    @csrf
                                    @if($course)
                                        @method('put')
                                    @endif
                                    <div class="form-group row">
                                        <label  class="col-2 col-form-label">Name </label>
                                        <div class="col-10">
                                            <input class="form-control" type="text" value="{{$course? $course->name :old('name')}}" name="name"/>
                                            @error('name')
                                            <div class="text-danger" role="alert">
                                                {{$errors->first('name')}}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label  class="col-2 col-form-label">Number</label>
                                        <div class="col-10">
                                            <input class="form-control" type="text" value="{{$course? $course->course_number :old('course_number')}}" name="course_number"/>
                                            @error('course_number')
                                            <div class="text-danger" role="alert">
                                                {{$errors->first('course_number')}}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="input-group row">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" for="inputGroupSelect01">Teacher</label>
                                        </div>
                                        <select class="custom-select" id="inputGroupSelect01" name="teacher_id">
                                            <option selected disabled>Choose...</option>
                                            @foreach(\App\Models\Teacher::all() as $teacher)
                                                <option value="{{$teacher->id}}" {{$course && $course->teacher_id == $teacher->id? 'selected' : ''}}>{{$teacher->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="card-footer align-items-end">
                                        @if($course)
                                            <button type="submit" class="btn btn-success mr-2">Update</button>
                                        @else
                                            <button type="submit" class="btn btn-success mr-2">Store</button>
                                        @endif
                                        <a href="{{route('course.index')}}"  class="btn btn-secondary">Cancel</a>
                                    </div>

                                </form>
                            </div>
                        </div>
                        <!--end::Example-->
                    </div>
                </div>
                <!--end::Card-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Entry-->
    </div>
@endsection
@push('script')

@endpush
