@extends('layouts.backend.master')
@section('title', 'Dashboard')

@section('header_scripts')
<link rel="stylesheet" href="{{ URL::asset('assets/plugins/angular/angular-material.min.css') }}">
<style> .ng-cloak { display: none !important; } 

.fixed-content {
    height: 450px!important;
    width: 100%!important;
    overflow-y:scroll;
	overflow-x:hidden;
}
</style>
@stop
@section('content')
<!-- [ breadcrumb ] start -->
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Message</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#"><i class="feather icon-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="#">Message</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- [ breadcrumb ] end -->
<!-- [ Main Content ] start -->
<div class="row" ng-app="publicMessageApp" ng-controller="publicMessageCtrl as frm">
    <!-- [ message ] start -->
    <div class="col-sm-12">
        <div class="card mb-0">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="ch-block">
                            <div class="h-list-body">
                                <div class="msg-user-chat scroll-div new-scroll user-scroll">
                                    <div class=" fixed-content style4">
                                        <div class="main-friend-chat">
                                            <div ng-repeat="message in data.messages" class="media chat-messages">
                                                <a ng-if="message.user_id == '{{ Auth::user()->id }}'" class="media-left photo-table ng-cloak" href="#"><img class="img-fluid media-object img-radius m-t-5" src="/assets/backend/images/user/avatar-2.jpg" alt="Generic placeholder image"></a>
                                                <div class="media-body chat-menu-content" ng-if="message.user_id == '{{ Auth::user()->id }}'">
                                                    <div class="">
                                                        <p class="chat-cont ng-cloak">@{{ message.content}} (You)</p>
                                                        <div class="clearfix"></div>
                                                    </div>
                                                    <p class="chat-time ng-cloak">@{{ message.created_at| date: 'h:mm a'}}</p>
                                                </div>
                                                <div class="media-body chat-menu-reply text-right" ng-if="message.user_id != '{{ Auth::user()->id }}'">
                                                    <div class="">
                                                        <p class="chat-cont ng-cloak">@{{ message.content}} (@{{ message.user.full_name}})</p>
                                                    </div>
                                                    <p class="chat-time ng-cloak">@{{ message.created_at| date: 'h:mm a'}}</p>
                                                </div>
                                                <a ng-if="message.user_id != '{{ Auth::user()->id }}'" class="media-right photo-table ng-cloak" href="#!"><img class="media-object img-radius img-radius m-t-5" src="/assets/backend/images/user/avatar-1.jpg" alt="Generic placeholder image"></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="msg-form">
                                <form class="ng-pristine ng-valid-email ng-invalid ng-invalid-required" name="publicMessageFrm" ng-submit="frm.sendMessasge(publicMessageFrm.$valid)" autocomplete="off">
                                    <div class="input-group mb-0">
                                        <input type="text" name="message" id="message" ng-model="frm.message" class="form-control msg-send-chat" placeholder="Text . . ." required>
                                        <div class="input-group-append">
                                            <button type="submit" ng-disabled="publicMessageFrm.$invalid" class="btn btn-theme btn-icon btn-msg-send" id="send_btn"><i class="feather icon-play"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ message ] end -->
</div>
<!-- [ Main Content ] end -->
@endsection
@section('footer_scripts')
<script type="text/javascript">
    (function () {
        var publicMessageApp = angular.module('publicMessageApp', ['angular.filter']);
        publicMessageApp.controller('publicMessageCtrl', function ($scope, $http, $sce) {
     
            var vm = this;

            getdata();
            function getdata() {

                $http.get('/admin/messages/public-data').success(function (data) {

                    $scope.data = data;

                }); 
            }

            vm.sendMessasge = function () {

                $('#send_btn').prop('disabled', true);
                $('#send_btn').html('<i class="fa fa-spinner fa-spin"></i>');


                var form_data = new FormData();
                form_data.append('_token', '{{csrf_token()}}');
                form_data.append('message', vm.message);

                $http({
                    method: 'POST',
                    url: '/admin/messages/public/send',
                    data: form_data,
                    headers: {
                        'Content-Type': undefined
                    }
                }).success(function (data) {
                    $('#send_btn').prop('disabled', false);
                    $('#send_btn').html('<i class="feather icon-play"></i>');
                    if (data.result == 1){

                        new PNotify({
                            title: 'Success',
                            text: data.message,
                            type: 'success'
                        });

                        getdata();
                        vm.message = '';

                    } else {
                        new PNotify({
                            title: 'Warning',
                            text: "Something went wrong. Please try again!",
                            type: 'default'
                        });
                    }
                }).error(function (data) {

                    $('#send_btn').prop('disabled', false);
                    $('#send_btn').html('<i class="feather icon-play"></i>');

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