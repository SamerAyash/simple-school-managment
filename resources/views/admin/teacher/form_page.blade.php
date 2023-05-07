@extends('layout.app')
@push('style')
    <script>
        function previewFile(input){
            var file = $("input[type=file]").get(0).files[0];

            if(file){
                var reader = new FileReader();

                reader.onload = function(){
                    $("#previewImg").attr("src", reader.result);
                }

                reader.readAsDataURL(file);
            }
        }
    </script>
@endpush
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
                            Teachers
                        </h5>
                        <!--end::Page Title-->

                        <!--begin::Breadcrumb-->
                        <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                            <li class="breadcrumb-item">
                                <a href="{{route('teacher.index')}}" class="text-muted">
                                    Teachers
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="#" class="text-muted">
                                    {{!$teacher?'Create teacher' : 'Edit teacher'}}
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
                            <h3 class="card-label">{{!$teacher?'Create new teacher' : 'Edit teacher'}}</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <!--begin::Example-->
                        <div class="example mb-10">
                            <div class="example-preview">
                                <form method="post" action="{{!$teacher? route('teacher.store'): route('teacher.update',$teacher->id)}}" enctype="multipart/form-data">
                                    @csrf
                                    @if($teacher)
                                        @method('put')
                                    @endif

                                    <div class="image-input image-input-outline mb-3" >
                                        <img width="250" id="previewImg" src="{{asset($teacher? $teacher->image_url : 'assets/media/users/default.jpg')}}" alt="Placeholder">
                                        <p>
                                            <input type="file" name="image" accept=".jpeg,.jpg,.png,.gif" onchange="previewFile(this);">
                                        </p>
                                    </div>
                                    @error('image')
                                    <div class="text-danger" role="alert">
                                        {{$errors->first('image')}}
                                    </div>
                                    @enderror
                                    <div class="form-group row">
                                        <label  class="col-2 col-form-label">Name </label>
                                        <div class="col-10">
                                            <input class="form-control" type="text" value="{{$teacher? $teacher->name :old('name')}}" name="name"/>
                                            @error('name')
                                            <div class="text-danger" role="alert">
                                                {{$errors->first('name')}}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label  class="col-2 col-form-label">Email</label>
                                        <div class="col-10">
                                            <input class="form-control" type="email" value="{{$teacher? $teacher->email :old('email')}}" name="email"/>
                                            @error('email')
                                            <div class="text-danger" role="alert">
                                                {{$errors->first('email')}}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label  class="col-2 col-form-label">Phone</label>
                                        <div class="col-10">
                                            <input class="form-control" type="text" value="{{$teacher? $teacher->phone :old('phone')}}" name="phone"/>
                                            @error('phone')
                                            <div class="text-danger" role="alert">
                                                {{$errors->first('phone')}}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label  class="col-2 col-form-label">Password</label>
                                        <div class="col-10">
                                            <input class="form-control" type="text" value="" name="password"/>
                                            @error('password')
                                            <div class="text-danger" role="alert">
                                                {{$errors->first('password')}}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="card-footer align-items-end">
                                        @if($teacher)
                                            <button type="submit" class="btn btn-success mr-2">Update</button>
                                        @else
                                            <button type="submit" class="btn btn-success mr-2">Store</button>
                                        @endif
                                        <a href="{{route('teacher.index')}}"  class="btn btn-secondary">Cancel</a>
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
