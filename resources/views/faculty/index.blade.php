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
                        <h5>Announcements</h5>
                    </div>
                    <div class="card-body text-center">
                        <span class="d-block text-c-blue f-36">{{ $total_announcements }}</span>
                        <p class="m-b-0">Total</p>
                    </div>
                    <div class="card-footer bg-c-blue border-0">
                        <h6 class="text-white m-b-0">Today: {{ $total_announcements_today }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card statustic-card">
                    <div class="card-header borderless pb-0">
                        <h5>Learning Materials</h5>
                    </div>
                    <div class="card-body text-center">
                        <span class="d-block text-c-green f-36">{{ $total_learning_materials }}</span>
                        <p class="m-b-0">Total</p>
                    </div>
                    <div class="card-footer bg-c-green border-0">
                        <h6 class="text-white m-b-0">Today: {{ $total_learning_materials_today }}</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card statustic-card">
                    <div class="card-header borderless pb-0">
                        <h5>Messages</h5>
                    </div>
                    <div class="card-body text-center">
                        <span class="d-block text-c-purple f-36">{{ $total_message }}</span>
                        <p class="m-b-0">Total</p>
                    </div>
                    <div class="card-footer bg-c-purple border-0">
                        <h6 class="text-white m-b-0">Today: {{ $total_message_today }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-12 col-md-12">
        <div class="card user-list table-card">
            <div class="card-header">
                <h5>Announcements</h5>
                <div class="card-header-right text-center">
                    <a href="{{ url('/faculty/announcements') }}">
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
                                    <th>Title</th>
                                    <th>By (Name)</th>
                                    <th>Content</th>
                                    <th>Date Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($announcements)
                                @foreach($announcements as $announcement)
                                <tr>
                                    <td>{{ $announcement->title }}</td>
                                    <td>
                                        @if($announcement->user_id == $user->id)
                                        You
                                        @else
                                        {{ ucwords($announcement->user->full_name) }}
                                        @endif
                                    </td>
                                    <td>{{ str_limit($announcement->announcement, 15) }}</td>
                                    <td>{{ date('F j, Y g:i a', strtotime($announcement->created_at)) }}</td>
                                    <td>
                                        @if($announcement->user_id == $user->id)
                                        <a href="{{ url('/faculty/announcements/view/'.$announcement->id) }}" class="btn shadow-1 btn-success btn-sm">view</a>
                                        <a href="{{ url('/faculty/announcements/edit/'.$announcement->id) }}" class="btn shadow-1 btn-danger btn-sm">edit</a>
                                        @else
                                        <a href="{{ url('/faculty/announcements/view/'.$announcement->id) }}" class="btn shadow-1 btn-success btn-sm">view</a>
                                        @endif
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