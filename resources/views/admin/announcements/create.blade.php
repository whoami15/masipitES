@extends('layouts.backend.master')
@section('title', 'Generate Security Keys')

@section('content')
<div ng-app="securityKeysApp" ng-controller="securityKeysCtrl as frm">
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
                        <li class="breadcrumb-item"><a href="#">Generate Security Keys</a></li>
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
                    <h5>Generate Security Keys</h5>
                </div>
                <div class="card-body">
                    <form class="ng-pristine ng-valid-email ng-invalid ng-invalid-required" name="generateCodesFrm" ng-submit="frm.generateCodes(generateCodesFrm.$valid)" autocomplete="off">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">How many Security Keys?</label>
                                    <select name="quantity" ng-model="frm.quantity" class="form-control ng-pristine ng-untouched ng-empty ng-invalid ng-invalid-require" required>
                                        <option value="100" selected>100</option>
                                        <option value="300">300</option>
                                        <option value="500">500</option>
                                        <option value="1000">1000</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="submit" ng-disabled="generateCodesFrm.$invalid" id="generate_codes_btn" class="btn btn-primary">Generate Keys</button>
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
        var securityKeysApp = angular.module('securityKeysApp', ['angular.filter']);
        securityKeysApp.controller('securityKeysCtrl', function ($scope, $http, $sce) {

            var vm = this;

            vm.generateCodes = function () {
               
               $('#generate_codes_btn').prop('disabled', true);
               $('#generate_codes_btn').html('Generating <i class="fa fa-spinner fa-spin"></i>');

               $http({
                   method: 'POST',
                   url: '/admin/security-keys/generate',
                   data: JSON.stringify({
                       quantity: vm.quantity
                   })
               }).success(function (data) {
                   $('#generate_codes_btn').prop('disabled', false);
                   $('#generate_codes_btn').html('Generate Codes');
                   if (data.result == 1){

                        new PNotify({
                            title: 'Success',
                            text: data.message,
                            type: 'success'
                        });

                   } else {
                        new PNotify({
                            title: 'Warning',
                            text: 'Something went wrong. Please try again!',
                            type: 'default'
                        });
                   }
               }).error(function (data) {

                   $('#generate_codes_btn').prop('disabled', false);
                   $('#generate_codes_btn').html('Generate Codes');

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