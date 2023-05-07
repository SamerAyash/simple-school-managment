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
                            Dashboard
                        </h5>
                        <!--end::Page Title-->

                        <!--begin::Breadcrumb-->
                        <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                            <li class="breadcrumb-item">
                                <a href="{{route('student.home')}}" class="text-muted">
                                    Dashboard
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
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th scope="col">Course ID</th>
                        <th scope="col">Course Name</th>
                        <th scope="col">Grade</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($grades as $grade)
                        <tr>
                            <td>{{$grade->course_id}}</td>
                            <td>{{$grade->name}}</td>
                            <td>{{$grade->grade}}</td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
            <!--end::Container-->
        </div>
        <!--end::Entry-->
    </div>
@endsection

