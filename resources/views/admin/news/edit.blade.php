@extends('layouts.backend.master')
@section('title', 'Edit News')

@section('content')
<div ng-app="newsEditApp" ng-controller="newsEditCtrl as frm">
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
                        <li class="breadcrumb-item"><a href="#">News</a></li>
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
                    <h5>Edit News</h5>
                </div>
                <div class="card-body">
                    <form class="ng-pristine ng-valid-email ng-invalid ng-invalid-required" name="newsEditFrm" ng-submit="frm.editNews(newsEditFrm.$valid)" autocomplete="off">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" ng-model="frm.title" ng-init="frm.title='{{ $news->title }}'" id="title" class="form-control" placeholder="Title" required>
                            </div>
                            <div class="form-group text-center">
                                <label>News Photo</label> 
                                <br>
                                    <img src="{{ url('uploads/news/'.$news->photo) }}" class="img img-responsive center-block" id="photo" style="background-color: #fff; border: 1px solid #ddd; border-radius: 4px;height:200px; margin:0 auto;"  />
                                </span>
                                
                                <br>
                                <br>
                                <input type="file" name="photo" id="edit_photo" accept="image/*">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Content</label>
                                <textarea name="content" ng-model="frm.content" ng-init="frm.content='{{ $news->content }}'" class="form-control" rows="9" required></textarea>
                            </div>
                            <div class="form-group">
                                <button type="submit" ng-disabled="newsEditFrm.$invalid" id="update_btn" class="btn btn-primary">Update</button>
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
        var newsEditApp = angular.module('newsEditApp', ['angular.filter']);
        newsEditApp.controller('newsEditCtrl', function ($scope, $http, $sce) {

            var vm = this;

            var news_id = '{{$news->id}}';

            vm.editNews = function () {
               
                $('#update_btn').prop('disabled', true);
                $('#update_btn').html('Please wait... <i class="fa fa-spinner fa-spin"></i>');

                var form_data = new FormData();
                form_data.append('photo', $('#edit_photo')[0].files[0]);
                form_data.append('_token', '{{csrf_token()}}');
                form_data.append('title', vm.title);
                form_data.append('content', vm.content);

                $http({
                    method: 'POST',
                    url: '/admin/news/'+news_id+'/edit',
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

                       setTimeout(window.location.href = '/admin/news', 10000);

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