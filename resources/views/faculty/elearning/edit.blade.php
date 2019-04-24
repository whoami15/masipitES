@extends('layouts.backend.master')
@section('title', 'Edit Learning Materials')

@section('content')
<div ng-app="learningMaterialEditApp" ng-controller="learningEditMaterialCtrl as frm">
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
                        <li class="breadcrumb-item"><a href="#">Learning Materials</a></li>
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
                    <h5>Edit Learning Material</h5>
                </div>
                <div class="card-body">
                    <form class="ng-pristine ng-valid-email ng-invalid ng-invalid-required" name="learningMaterialEditFrm" ng-submit="frm.uploadMaterial(learningMaterialEditFrm.$valid)" autocomplete="off">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" ng-model="frm.title" id="title" ng-init="frm.title='{{ $learning_material->title }}'" class="form-control" placeholder="Title" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Choose file (if you want to change file)</label><br>
                                <input type="file" name="doc_file" id="doc_file" ng-init="frm.doc_file='{{ $learning_material->filename }}'"
                                accept=".csv, text/plain, application/vnd.openxmlformats-officedocument.wordprocessingml.document, application/msword, application/pdf,	application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel, application/vnd.ms-powerpoint, application/vnd.openxmlformats-officedocument.presentationml.presentation">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Subject</label>
                                <select name="subject" ng-model="frm.subject" id="subject" ng-init="frm.subject='{{ $learning_material->subject }}'" class="form-control" required>
                                    <option selected="">Select Subject</option>
                                    @if($subjects)
                                    @foreach($subjects as $subject)
                                    <option value="{{ $subject->subject_user->id }}">{{ $subject->subject_user->description }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Grade Level</label>
                                <select name="grade_level" ng-model="frm.grade_level" id="grade_level" ng-init="frm.grade_level='{{ $learning_material->grade_level }}'" class="form-control" required>
                                    <option selected="">Select Grade Level</option>
                                    @if($grade_level)
                                    @foreach($grade_level as $grade)
                                    <option value="{{ $grade->grade_level_user->id }}">{{ $grade->grade_level_user->description }}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Description</label>
                                <textarea name="description" ng-model="frm.description" ng-init="frm.description='{{ $learning_material->description }}'" class="form-control" rows="3" required></textarea>
                            </div>
                            <div class="form-group">
                                @if($teacher_class == 0)
                                <h6>Oops. It looks like you haven't added a class yet, add <a href="{{ url('/faculty/class/add') }}">here</a>.</h6>
                                @else
                                <button type="submit" ng-disabled="learningMaterialEditFrm.$invalid" id="upload_btn" class="btn btn-primary">Update</button>
                                @endif
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
        var learningMaterialEditApp = angular.module('learningMaterialEditApp', ['angular.filter']);
        learningMaterialEditApp.controller('learningEditMaterialCtrl', function ($scope, $http, $sce) {

            var vm = this;

            var learning_material_id = '{{$learning_material->id}}';

            vm.uploadMaterial = function () {

                $('#upload_btn').prop('disabled', true);
                $('#upload_btn').html('Please wait... <i class="fa fa-spinner fa-spin"></i>');


                var form_data = new FormData();
                form_data.append('doc_file', $('#doc_file')[0].files[0]);
                form_data.append('_token', '{{csrf_token()}}');
                form_data.append('title', vm.title);
                form_data.append('subject', vm.subject);
                form_data.append('grade_level', vm.grade_level);
                form_data.append('description', vm.description);

                $http({
                    method: 'POST',
                    url: '/faculty/elearning/'+learning_material_id+'/edit',
                    data: form_data,
                    headers: {
                        'Content-Type': undefined
                    }
                }).success(function (data) {
                    $('#upload_btn').prop('disabled', false);
                    $('#upload_btn').html('Save');
                    if (data.result == 1){

                        new PNotify({
                            title: 'Success',
                            text: data.message,
                            type: 'success'
                        });

                        setTimeout(window.location.href = '/faculty/elearning', 10000);

                    } else {
                        new PNotify({
                            title: 'Warning',
                            text: 'Something went wrong. Please try again!',
                            type: 'default'
                        });
                    }
                }).error(function (data) {

                    $('#upload_btn').prop('disabled', false);
                    $('#upload_btn').html('Save');

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