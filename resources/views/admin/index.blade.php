@extends('layouts.backend.master')
@section('title', 'Dashboard')

@section('content')
<div ng-app="dashboardApp" ng-controller="dashboardCtrl as frm">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10">MasipitES</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index-2.html"><i class="feather icon-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="#!">Admin Dashboard</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->
    <!-- [ Main Content ] start -->
    <div class="row">
        <div class="col-xl-12 col-md-12 col-sm-12">
            <div class="row">
                <div class="col-md-4 col-sm-4">
                    <div class="card statustic-card">
                        <div class="card-header borderless pb-0">
                            <h5>Pending</h5>
                        </div>
                        <div class="card-body text-center">
                            <span class="d-block text-c-blue f-36">{{ $total_pending_users }}</span>
                            <p class="m-b-0">Total</p>
                        </div>
                        <div class="card-footer bg-c-blue border-0">
                            <h6 class="text-white m-b-20"></h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4">
                    <div class="card statustic-card">
                        <div class="card-header borderless pb-0">
                            <h5>Faculty</h5>
                        </div>
                        <div class="card-body text-center">
                            <span class="d-block text-c-green f-36">{{ $total_teacher }}</span>
                            <p class="m-b-0">Total</p>
                        </div>
                        <div class="card-footer bg-c-green border-0">
                            <h6 class="text-white m-b-0">Today: {{ $total_teacher_today }}</h6>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4">
                    <div class="card statustic-card">
                        <div class="card-header borderless pb-0">
                            <h5>Students</h5>
                        </div>
                        <div class="card-body text-center">
                            <span class="d-block text-c-purple f-36">{{ $total_student }}</span>
                            <p class="m-b-0">Total</p>
                        </div>
                        <div class="card-footer bg-c-purple border-0">
                            <h6 class="text-white m-b-0">Today: {{ $total_student_today }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
	    	<div class="row">
	    		<div class="col-lg-6 col-md-6 col-sm-6">
	    			<div class="card bg-c-blue order-card">
	    				<div class="card-body">
	    					<h6 class="m-b-20 text-center">News</h6>
	    					<h2 class="text-center"><span>{{ $total_news }}</span></h2>
	    					<p class="m-b-0 text-right">Today<span class="float-left">{{ $total_news_today }}</span></p>
	    				</div>
	    			</div>
	    		</div>
	    		<div class="col-lg-6 col-md-6 col-sm-6">
	    			<div class="card bg-c-green order-card">
	    				<div class="card-body">
	    					<h6 class="m-b-20 text-center">Events</h6>
	    					<h2 class="text-center"><span>{{ $total_events }}</span></h2>
	    					<p class="m-b-0 text-right">Today<span class="float-left">{{ $total_events_today }}</span></p>
	    				</div>
	    			</div>
	    		</div>
	    	</div>
	    </div>

        <div class="col-xl-12 col-md-12">
            <div class="card user-list table-card">
                <div class="card-header">
                    <h5>Pending Users</h5>
                    <div class="card-header-right text-center">
                        <a href="{{ url('/admin/pending') }}">
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
                                        <th>Name</th>
                                        <th>Username</th>
                                        <th>Role</th>
                                        <th>Details</th>
                                        <th>Date Registered</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($pending_users)
                                    @foreach($pending_users as $user)
                                    <tr>
                                        <td>{{ ucwords($user->full_name) }}</td>
                                        <td>{{ $user->username }}</td>
                                        <td>
                                            @if($user->role == 1)
                                            <mark>Student</mark>
                                            @elseif($user->role == 2)
                                            <mark>Faculty</mark>
                                            @endif
                                        </td>
                                        <td class="text-c-green">
                                            @if($user->role == 1)
                                            <h6 class="mb-2">ID Number: <mark>{{ $user->id_no }}</mark></h6>
                                            <h6 class="mb-1">Grade Level: <mark>{{ $user->grade_level_user->description }}</mark></h6>
                                            @elseif($user->role == 2)
                                            <h6 class="mb-2">Position: <mark>{{ $user->position }}</mark></h6>
                                            <h6 class="mb-1">Department: <mark>{{ $user->department }}</mark></h6>
                                            @endif
                                        </td>
                                        <td>{{ date('F j, Y g:i a', strtotime($user->created_at)) }}</td>
                                        <td>
                                            <small id="processing{{ $user->id }}" style="display:none">Processing... <i class="fa fa-spinner fa-spin"></i></small>
                                            <button ng-click="frm.accept({{ $user->id }})" id="accept_btn{{ $user->id }}" class="btn shadow-1 btn-success btn-sm">approve</button>
                                            <button ng-click="frm.decline({{ $user->id }})" id="decline_btn{{ $user->id }}" class="btn shadow-1 btn-danger btn-sm">decline</button>
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
</div>
@endsection
@section('footer_scripts')
<script>
    (function () {
        var dashboardApp = angular.module('dashboardApp', ['angular.filter']);
        dashboardApp.controller('dashboardCtrl', function ($scope, $http, $sce) {

            var vm = this;

            vm.accept = function (id) {
               
                $('#accept_btn'+id).hide();
                $('#processing'+id).show();

                var status = 1;
                $http({
                    method: 'POST',
                    url: '/admin/accept/'+id+'/status',
                    data: JSON.stringify({
                       status: status
                    })
                }).success(function (data) {
                    $('#accept_btn'+id).show();
                    $('#processing'+id).hide();
                    if (data.result==1){

                        new PNotify({
                            title: 'Success',
                            text: data.message,
                            type: 'success'
                        });

                        setTimeout(window.location.href = '/admin', 5000);
                    }
                }).error(function (data) {
                    $('#accept_btn'+id).show();
                    $('#processing'+id).hide();
                  if(data.result == 0){

                        new PNotify({
                            title: 'Warning',
                            text: data.message,
                            type: 'default'
                        });

                    } else {

                        angular.forEach(data.errors, function(message, key){

                            new PNotify({
                                title: 'Warning',
                                text: data.message,
                                type: 'default'
                            });

                      });
                    }
                });
            };

            vm.decline = function (id) {
               
               $('#decline_btn'+id).hide();
               $('#processing'+id).show();

               var status = 1;
               $http({
                   method: 'POST',
                   url: '/admin/decline/'+id+'/status',
                   data: JSON.stringify({
                      status: status
                   })
               }).success(function (data) {
                   $('#decline_btn'+id).show();
                   $('#processing'+id).hide();
                   if (data.result==1){

                        new PNotify({
                            title: 'Success',
                            text: data.message,
                            type: 'success'
                        });

                       setTimeout(window.location.href = '/admin', 5000);
                   }
               }).error(function (data) {
                   $('#decline_btn'+id).show();
                   $('#processing'+id).hide();
                 if(data.result == 0){

                       new PNotify({
                           title: 'Warning',
                           text: data.message,
                           type: 'default'
                       });

                   } else {

                       angular.forEach(data.errors, function(message, key){

                           new PNotify({
                               title: 'Warning',
                               text: data.message,
                               type: 'default'
                           });

                     });
                   }
               });
           };

            
        });
    })();

</script>

@stop