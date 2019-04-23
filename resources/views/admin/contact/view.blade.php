@extends('layouts.backend.master')
@section('title', 'View Message')

@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Contact</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#"><i class="feather icon-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="#">Contact</a></li>
                    <li class="breadcrumb-item"><a href="#">View</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- [ breadcrumb ] end -->
<!-- [ Main Content ] start -->
<div class="row">
    <!-- [ task-detail-left ] start -->
    <div class="col-xl-4 col-lg-12 task-detail-right">
        <div class="card">
            <div class="card-header">
                <h5>Details</h5>
            </div>
            <div class="card-body task-details">
                <table class="table">
                    <tbody>
                        <tr>
                            <td><i class="fas fa-user-plus m-r-5"></i> Message from:</td>
                            <td class="text-right"><a class="text-secondary" href="#">{{ ucwords($contact->name) }}</a></td>
                        </tr>
                        <tr>
                            <td><i class="far fa-envelope m-r-5"></i> Email:</td>
                            <td class="text-right">{{ $contact->email }}</td>
                        </tr>
                        <tr>
                            <td><i class="far fa-calendar m-r-5"></i> Sent on:</td>
                            <td class="text-right">{{ date('F j, Y g:i a', strtotime($contact->created_at)) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- [ task-detail-left ] end -->
    <!-- [ task-detail-right ] start -->
    <div class="col-xl-8 col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-3"><i class="fas fa-info-circle m-r-5"></i> {{ ucwords($contact->subject) }}</h5>
            </div>
            <div class="card-body">
                <div class="m-b-20">
                    <h6>Content</h6>
                    <hr>
                    <p class="">{{ $contact->content }}</p>
                </div>
            </div>
        </div>
    </div>
    <!-- [ task-detail-right ] end -->
</div>
<!-- [ Main Content ] end -->
@endsection