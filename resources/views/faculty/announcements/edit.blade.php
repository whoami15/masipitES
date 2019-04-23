@extends('layouts.backend.master')
@section('title', 'Edit Announcement')

@section('content')
<div ng-app="announcementEditApp" ng-controller="announcementEditCtrl as frm">
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
                        <li class="breadcrumb-item"><a href="#">Announcements</a></li>
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
                    <h5>Edit Announcement</h5>
                </div>
                <div class="card-body">
                    <form class="ng-pristine ng-valid-email ng-invalid ng-invalid-required" name="announcementFrm" ng-submit="frm.create(announcementFrm.$valid)" autocomplete="off">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" ng-model="frm.title" ng-init="frm.title='{{ $announcement->title }}'" id="title" class="form-control" placeholder="Title" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Content</label>
                                <textarea name="announcement" ng-model="frm.announcement" ng-init="frm.announcement='{{ $announcement->announcement }}'" class="form-control" rows="9" required></textarea>
                            </div>
                            <div class="form-group">
                                <button type="submit" ng-disabled="announcementFrm.$invalid" id="update_btn" class="btn btn-primary">Update</button>
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
        var announcementEditApp = angular.module('announcementEditApp', ['angular.filter']);
        announcementEditApp.controller('announcementEditCtrl', function ($scope, $http, $sce) {

            var vm = this;

            var announcement_id = '{{$announcement->id}}';

            vm.create = function () {
               
               $('#update_btn').prop('disabled', true);
               $('#update_btn').html('Please wait... <i class="fa fa-spinner fa-spin"></i>');

               $http({
                   method: 'POST',
                   url: '/faculty/announcements/'+announcement_id+'/edit',
                   data: JSON.stringify({
                        title: vm.title,
                        announcement: vm.announcement
                   })
               }).success(function (data) {
                   $('#update_btn').prop('disabled', false);
                   $('#update_btn').html('Save');
                   if (data.result == 1){

                        new PNotify({
                            title: 'Success',
                            text: data.message,
                            type: 'success'
                        });

                       setTimeout(window.location.href = '/faculty/announcements', 10000);

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