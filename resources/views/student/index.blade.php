@extends('layouts.backend.master')
@section('title', 'Dashboard')

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">MasipitES</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#"><i class="feather icon-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="#">Faculty Dashboard</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- [ breadcrumb ] end -->
<!-- [ Main Content ] start -->
<div class="row">
    <div class="col-xl-12">
        <div class="row">
            <div class="col-md-4">
                <div class="card statustic-card">
                    <div class="card-header borderless pb-0">
                        <h5>Learning Materials</h5>
                    </div>
                    <div class="card-body text-center">
                        <span class="d-block text-c-blue f-36">{{ $total_learning_materials }}</span>
                        <p class="m-b-0">Total</p>
                    </div>
                    <div class="card-footer bg-c-blue border-0">
                        <h6 class="text-white m-b-0">Today: {{ $total_learning_materials_today }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-12 col-md-12">
        <div class="card user-list table-card">
            <div class="card-header">
                <h5>Learning Materials</h5>
                <div class="card-header-right text-center">
                    <a href="{{ url('/student/elearning') }}">
                        <i class="feather icon-more-horizontal"></i>see all
                    </a>
                </div>
            </div>
            <div class="card-body pb-0">
                <div class="table-responsive">
                    <div class="user-scroll new-scroll" style="height:430px;position:relative;">
                        <table class="table table-hover m-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Uploaded by (Name)</th>
                                    <th>Title</th>
                                    <th>Subject</th>
                                    <th>Status</th>
                                    <th>Date Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($learning_materials)
                                @foreach($learning_materials as $learning_material)
                                <tr>
                                    <td>{{ $learning_material->id }}</td>
                                    <td>{{ ucwords($learning_material->user->full_name) }}</td>
                                    <td>{{ $learning_material->title }}</td>
                                    <td>{{ $learning_material->subject_user->description }}</td>
                                    <td>
                                    @if($learning_material->status == 1)
                                        <mark>Active</mark>
                                    @else
                                        <mark>Inactive</mark>
                                    @endif
                                    </td>
                                    <td>{{ date('F j, Y g:i a', strtotime($learning_material->created_at)) }}</td>
                                    <td>
                                        <a href="{{ url('/student/file/'.$learning_material->uuid.'/download') }}" class="btn shadow-1 btn-success btn-sm">download</a>
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- [ Main Content ] end -->
@endsection