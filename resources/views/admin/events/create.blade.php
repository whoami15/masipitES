@extends('layouts.backend.master')
@section('title', 'Events')

@section('content')
<div ng-app="addEventApp" ng-controller="addEventCtrl as frm">
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
                        <li class="breadcrumb-item"><a href="#">Add Event</a></li>
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
                    <h5>Add Event</h5>
                </div>
                <div class="card-body">
                    <form class="ng-pristine ng-valid-email ng-invalid ng-invalid-required" name="eventAddFrm" ng-submit="frm.create(eventAddFrm.$valid)" autocomplete="off">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" ng-model="frm.title" id="title" class="form-control" placeholder="Title" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">When</label>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <input type="date" name="event_date" ng-model="frm.event_date" max="2030-01-01" min="1930-01-01" format="yyyy-mm-dd" id="event_date" class="form-control" required>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <input type="time" name="event_time" ng-model="frm.event_time" id="event_time" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Where</label>
                                <input type="text" name="event_location" ng-model="frm.event_location" id="event_location" class="form-control" placeholder="Location" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Choose photo</label><br>
                                <input type="file" name="photo" id="photo" accept="image/*" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Content</label>
                                <textarea name="content" ng-model="frm.content" class="form-control" rows="3" required></textarea>
                            </div>
                            <div class="form-group">
                                <button type="submit" ng-disabled="eventAddFrm.$invalid" id="save_btn" class="btn btn-primary">Save</button>
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
        var addEventApp = angular.module('addEventApp', ['angular.filter']);
        addEventApp.controller('addEventCtrl', function ($scope, $http, $sce, $filter) {

            var vm = this;

            vm.create = function () {

                var date_final = $filter('date')(new Date(vm.event_date), 'M/d/yy h:mm a');
                var time_final = $filter('date')(new Date(vm.event_time), 'M/d/yy h:mm a');
               
                $('#save_btn').prop('disabled', true);
                $('#save_btn').html('Please wait... <i class="fa fa-spinner fa-spin"></i>');

                var form_data = new FormData();
                form_data.append('photo', $('#photo')[0].files[0]);
                form_data.append('_token', '{{csrf_token()}}');
                form_data.append('title', vm.title);
                form_data.append('event_date', date_final);
                form_data.append('event_time', time_final);
                form_data.append('event_location', vm.event_location);
                form_data.append('content', vm.content);

                $http({
                    method: 'POST',
                    url: '/admin/events/create',
                    data: form_data,
                    headers: {
                        'Content-Type': undefined
                    }
               }).success(function (data) {
                   $('#save_btn').prop('disabled', false);
                   $('#save_btn').html('Save');
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

                   $('#save_btn').prop('disabled', false);
                   $('#save_btn').html('Save');

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