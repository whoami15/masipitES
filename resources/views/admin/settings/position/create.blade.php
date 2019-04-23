@extends('layouts.backend.master')
@section('title', 'Add Position')

@section('content')
<div ng-app="addPositionApp" ng-controller="addPositionCtrl as frm">
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
                        <li class="breadcrumb-item"><a href="#">Position</a></li>
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
                    <h5>Add Position</h5>
                </div>
                <div class="card-body">
                    <form class="ng-pristine ng-valid-email ng-invalid ng-invalid-required" name="positionFrm" ng-submit="frm.create(positionFrm.$valid)" autocomplete="off">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Position</label>
                                <input type="text" name="position" ng-model="frm.position" id="position" class="form-control" placeholder="Position" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Description</label>
                                <textarea name="description" ng-model="frm.description" class="form-control" rows="3" required></textarea>
                            </div>
                            <div class="form-group">
                                <button type="submit" ng-disabled="positionFrm.$invalid" id="save_btn" class="btn btn-primary">Save</button>
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
        var addPositionApp = angular.module('addPositionApp', ['angular.filter']);
        addPositionApp.controller('addPositionCtrl', function ($scope, $http, $sce) {

            var vm = this;

            vm.create = function () {
               
               $('#save_btn').prop('disabled', true);
               $('#save_btn').html('Please wait... <i class="fa fa-spinner fa-spin"></i>');

               $http({
                    method: 'POST',
                    url: '/admin/settings/position/add',
                    data: JSON.stringify({
                        position: vm.position,
                        description: vm.description
                    })
               }).success(function (data) {
                   $('#save_btn').prop('disabled', false);
                   $('#save_btn').html('Save');
                   if (data.result == 1){

                        new PNotify({
                            title: 'Success',
                            text: data.message,
                            type: 'success'
                        });

                       setTimeout(window.location.href = '/admin/settings/position/add', 10000);

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