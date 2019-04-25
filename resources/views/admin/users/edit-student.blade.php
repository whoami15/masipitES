@extends('layouts.backend.master')
@section('title', 'Edit Student Profile')

@section('content')
<div ng-app="studentEditApp" ng-controller="studentEditCtrl as frm">
    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Student Profile</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#"><i class="feather icon-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="#">Edit</a></li>
                        <li class="breadcrumb-item"><a href="#">Student Profile</a></li>
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
                    <h5>Student Profile</h5>
                </div>
                <div class="card-body task-details">
                    <table class="table">
                        <tbody>
                            <br><br>
                            <tr>
                                <td>LRN:</td>
                                <td class="text-right"><h6>{{ $student->lrn }}</h6></td>
                            </tr>
                            <tr>
                                <td>ID Number:</td>
                                <td class="text-right">{{ strToUpper($student->id_no) }}</td>
                            </tr>
                            <tr>
                                <td>Full Name:</td>
                                <td class="text-right">{{ ucwords($student->full_name) }}</td>
                            </tr>
                            <tr>
                                <td>Grade Level:</td>
                                <td class="text-right">{{ $student->grade_level_user->description }}</td>
                            </tr>
                            <tr>
                                <td>Gender:</td>
                                <td class="text-right">{{ $student->gender }}</td>
                            </tr>
                            <tr>
                                <td>Birthdate:</td>
                                <td class="text-right">{{ $student->birth_date !== null ? date('F j, Y', strtotime($student->birth_date)) : null }}</td>
                            </tr>
                            <tr>
                                <td>Registered on:</td>
                                <td class="text-right">{{ date('F j, Y g:i a', strtotime($student->created_at)) }}</td>
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
                        <form class="ng-pristine ng-valid-email ng-invalid ng-invalid-required" name="studentEditFrm" ng-submit="frm.editStudent(studentEditFrm.$valid)" autocomplete="off">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-label">First Name</label>
                                    <input type="text" name="first_name" ng-model="frm.first_name" ng-init="frm.first_name='{{ $student->first_name }}'" id="first_name" class="form-control" placeholder="First name" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Middle Name</label>
                                    <input type="text" name="middle_name" ng-model="frm.middle_name" ng-init="frm.middle_name='{{ $student->middle_name }}'" id="middle_name" class="form-control" placeholder="Middle name" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" name="last_name" ng-model="frm.last_name" ng-init="frm.last_name='{{ $student->last_name }}'" id="last_name" class="form-control" placeholder="Last name" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Gender</label>
                                    <select name="gender" ng-model="frm.gender" id="gender" ng-init="frm.gender='{{ $student->gender }}'" class="form-control" required>
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
                                    <label class="form-label">Grade Level</label>
                                    <select name="grade_level" ng-model="frm.grade_level" id="grade_level" ng-init="frm.grade_level='{{ $student->grade_level }}'" class="form-control" required>
                                        <option selected="">Select Grade Level</option>
                                        @if($grade_level)
                                        @foreach($grade_level as $grade)
                                        <option value="{{ $grade->id }}">{{ $grade->description }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group">
                                    <button type="submit" ng-disabled="studentEditFrm.$invalid" id="update_btn" class="btn btn-primary">Update</button>
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
        var studentEditApp = angular.module('studentEditApp', ['angular.filter']);
        studentEditApp.controller('studentEditCtrl', function ($scope, $http, $sce, $filter) {

            var vm = this;

            $scope.birth_date = new Date("{{$student->birth_date}}");

            var student_id = '{{$student->id}}';

            vm.editStudent = function () {

                date_final = $filter('date')(new Date(vm.birth_date), 'M/d/yy h:mm a');
               
                $('#update_btn').prop('disabled', true);
                $('#update_btn').html('Please wait... <i class="fa fa-spinner fa-spin"></i>');

                $http({
                    method: 'POST',
                    url: '/admin/user/student/'+student_id+'/edit',
                    data: JSON.stringify({
                        first_name: vm.first_name,
                        middle_name: vm.middle_name,
                        last_name: vm.last_name,
                        gender: vm.gender,
                        birth_date: date_final,
                        grade_level: vm.grade_level
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