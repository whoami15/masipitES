@extends('layouts.backend.master')
@section('title', 'Edit Event')

@section('content')
<div ng-app="eventEditApp" ng-controller="eventEditCtrl as frm">
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
                        <li class="breadcrumb-item"><a href="#">Events</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->
    <!-- [ Main Content ] start -->
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h5>Edit Event</h5>
                </div>
                <div class="card-body">
                    <form class="ng-pristine ng-valid-email ng-invalid ng-invalid-required" name="eventEditFrm" ng-submit="frm.editEvent(eventEditFrm.$valid)" autocomplete="off">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" ng-model="frm.title" ng-init="frm.title='{{ $events->title }}'" id="title" class="form-control" placeholder="Title" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">When</label>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <input type="date" name="event_date" ng-model="frm.event_date" max="2030-01-01" min="1930-01-01" format="yyyy-mm-dd" ng-init="frm.event_date=event_date" id="event_date" class="form-control" required>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <input type="text" name="event_time" ng-model="frm.event_time" ng-init="frm.event_time='{{ $events->event_time }}'" id="event_time" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Where</label>
                                <input type="text" name="event_location" ng-model="frm.event_location" ng-init="frm.event_location='{{ $events->event_location }}'" id="event_location" class="form-control" placeholder="Location" required>
                            </div>
                            <div class="form-group text-center">
                                <label>Event Photo</label> 
                                <br>
                                    <img src="{{ url('uploads/events/'.$events->photo) }}" class="img img-responsive center-block" id="photo" style="background-color: #fff; border: 1px solid #ddd; border-radius: 4px;height:200px; margin:0 auto;"  />
                                </span>
                                
                                <br>
                                <br>
                                <input type="file" name="photo" id="edit_photo" accept="image/*">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Content</label>
                                <textarea name="content" ng-model="frm.content" ng-init="frm.content='{{ $events->content }}'" class="form-control" rows="9" required></textarea>
                            </div>
                            <div class="form-group">
                                <button type="submit" ng-disabled="eventEditFrm.$invalid" id="update_btn" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- [ Form Validation ] end -->
    </div>
    <!-- [ Main Content ] end -->
</div>
@endsection
@section('footer_scripts')
<script>
    (function () {
        var eventEditApp = angular.module('eventEditApp', ['angular.filter']);
        eventEditApp.controller('eventEditCtrl', function ($scope, $http, $sce, $filter) {

            var vm = this;

            $scope.event_date = new Date("{{$events->event_date}}");

            var event_id = '{{$events->id}}';

            vm.editEvent = function () {

                console.log(date_final = $filter('date')(new Date(vm.event_date), 'M/d/yy h:mm a'));
               
                $('#update_btn').prop('disabled', true);
                $('#update_btn').html('Please wait... <i class="fa fa-spinner fa-spin"></i>');

                var form_data = new FormData();
                form_data.append('photo', $('#edit_photo')[0].files[0]);
                form_data.append('_token', '{{csrf_token()}}');
                form_data.append('title', vm.title);
                form_data.append('event_date', date_final);
                form_data.append('event_time', vm.event_time);
                form_data.append('event_location', vm.event_location);
                form_data.append('content', vm.content);

                $http({
                    method: 'POST',
                    url: '/admin/events/'+event_id+'/edit',
                    data: form_data,
                    headers: {
                        'Content-Type': undefined
                    }
                }).success(function (data) {
                   $('#update_btn').prop('disabled', false);
                   $('#update_btn').html('Save');
                   if (data.result == 1){

                        new PNotify({
                            title: 'Success',
                            text: data.message,
                            type: 'success'
                        });

                       setTimeout(window.location.href = '/admin/events', 10000);

                   } else {
                        new PNotify({
                            title: 'Warning',
                            text: 'Something went wrong. Please try again!',
                            type: 'default'
                        });
                   }
               }).error(function (data) {

                   $('#update_btn').prop('disabled', false);
                   $('#update_btn').html('Save');

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
                                text: message,
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