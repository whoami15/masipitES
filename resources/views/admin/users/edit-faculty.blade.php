@extends('layouts.backend.master')
@section('title', 'Edit Faculty Profile')

@section('content')
<div ng-app="facultyEditApp" ng-controller="facultyEditCtrl as frm">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Faculty Profile</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#"><i class="feather icon-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="#">Edit</a></li>
                        <li class="breadcrumb-item"><a href="#">Faculty Profile</a></li>
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
                    <h5>Faculty Profile</h5>
                </div>
                <div class="card-body task-details">
                    <table class="table">
                        <tbody>
                            <br><br>
                            <tr>
                                <td>Full Name:</td>
                                <td class="text-right">{{ ucwords($faculty->full_name) }}</td>
                            </tr>
                            <tr>
                                <td>Position:</td>
                                <td class="text-right">{{ $faculty->position }}</td>
                            </tr>
                            <tr>
                                <td>Department:</td>
                                <td class="text-right">{{ $faculty->department }}</td>
                            </tr>
                            <tr>
                                <td>Birthdate:</td>
                                <td class="text-right">{{ $faculty->birth_date !== null ? date('F j, Y', strtotime($faculty->birth_date)) : null }}</td>
                            </tr>
                            <tr>
                                <td>Registered on:</td>
                                <td class="text-right">{{ date('F j, Y g:i a', strtotime($faculty->created_at)) }}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
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
                    <h5 class="mb-3">Edit Profile</h5>
                </div>
                <div class="card-body">
                    <div class="m-b-20">
                        <form class="ng-pristine ng-valid-email ng-invalid ng-invalid-required" name="facultyEditFrm" ng-submit="frm.editFaculty(facultyEditFrm.$valid)" autocomplete="off">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">First Name</label>
                                    <input type="text" name="first_name" ng-model="frm.first_name" ng-init="frm.first_name='{{ $faculty->first_name }}'" id="first_name" class="form-control" placeholder="First name" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Middle Name</label>
                                    <input type="text" name="middle_name" ng-model="frm.middle_name" ng-init="frm.middle_name='{{ $faculty->middle_name }}'" id="middle_name" class="form-control" placeholder="Middle name" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" name="last_name" ng-model="frm.last_name" ng-init="frm.last_name='{{ $faculty->last_name }}'" id="last_name" class="form-control" placeholder="Last name" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Gender</label>
                                    <select name="gender" ng-model="frm.gender" id="gender" ng-init="frm.gender='{{ $faculty->gender }}'" class="form-control" required>
                                        <option selected="">Select Gender</option>
                                        <option value="MALE">MALE</option>
                                        <option value="FEMALE">FEMALE</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Birthdate</label>
                                    <input type="date" name="birth_date" ng-model="frm.birth_date" max="2030-01-01" min="1930-01-01" format="yyyy-mm-dd" ng-init="frm.birth_date=birth_date" id="birth_date" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Position</label>
                                    <select name="position" ng-model="frm.position" id="position" ng-init="frm.position='{{ $faculty->position }}'" class="form-control" required>
                                        <option selected="">Select Position</option>
                                        @if($positions)
                                        @foreach($positions as $position)
                                        <option value="{{ $position->description }}">{{ $position->description }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Department</label>
                                    <select name="department" ng-model="frm.department" id="department" ng-init="frm.department='{{ $faculty->department }}'" class="form-control" required>
                                        <option selected="">Select Department</option>
                                        @if($departments)
                                        @foreach($departments as $department)
                                        <option value="{{ $department->description }}">{{ $department->description }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group">
                                    <button type="submit" ng-disabled="facultyEditFrm.$invalid" id="update_btn" class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ task-detail-right ] end -->
    </div>
    <!-- [ Main Content ] end -->
</div>
@endsection
@section('footer_scripts')
<script>
    (function () {
        var facultyEditApp = angular.module('facultyEditApp', ['angular.filter']);
        facultyEditApp.controller('facultyEditCtrl', function ($scope, $http, $sce, $filter) {

            var vm = this;

            $scope.birth_date = new Date("{{$faculty->birth_date}}");

            var faculty_id = '{{$faculty->id}}';

            vm.editFaculty = function () {

                date_final = $filter('date')(new Date(vm.birth_date), 'M/d/yy h:mm a');
               
                $('#update_btn').prop('disabled', true);
                $('#update_btn').html('Please wait... <i class="fa fa-spinner fa-spin"></i>');

                $http({
                    method: 'POST',
                    url: '/admin/user/faculty/'+faculty_id+'/edit',
                    data: JSON.stringify({
                        first_name: vm.first_name,
                        middle_name: vm.middle_name,
                        last_name: vm.last_name,
                        gender: vm.gender,
                        birth_date: date_final,
                        position: vm.position,
                        department: vm.department
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
                        
                        setTimeout(window.location.href = '/admin/list', 10000);
                        
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