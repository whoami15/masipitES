@extends('layouts.backend.master')
@section('title', 'Edit Profile')

@section('content')
<div ng-app="editProfileApp" ng-controller="editProfileCtrl as frm">
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
                        <li class="breadcrumb-item"><a href="#">Profile</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->
    <!-- [ Main Content ] start -->
    <div class="row">
		<div class="col-sm-12">
			<ul class="nav nav-tabs" id="myTab" role="tablist">
				<li class="nav-item">
					<a class="nav-link active text-uppercase" id="user1-tab" data-toggle="tab" href="#user1" role="tab" aria-controls="user1" aria-selected="true">Profile</a>
				</li>
				<li class="nav-item">
					<a class="nav-link text-uppercase" id="user2-tab" data-toggle="tab" href="#user2" role="tab" aria-controls="user2" aria-selected="false">Update</a>
				</li>
                <li class="nav-item">
					<a class="nav-link text-uppercase" id="user3-tab" data-toggle="tab" href="#user3" role="tab" aria-controls="user2" aria-selected="false">Password</a>
				</li>
			</ul>
			<div class="tab-content" id="myTabContent">
				<!-- [ user card1 ] start -->
				<div class="tab-pane fade show active" id="user1" role="tabpanel" aria-labelledby="user1-tab">
					<div class="row mb-n4">
						<div class="col-xl-4 col-md-6">
							<div class="card user-card user-card-1">
								<div class="card-header border-0 p-2 pb-0">
									<div class="cover-img-block">
										<img src="{{ URL::asset('assets/backend/images/widget/slider5.jpg') }}" alt="" class="img-fluid">
									</div>
								</div>
								<div class="card-body pt-0">
									<div class="user-about-block text-center">
										<div class="row align-items-end">
											<div class="col"></div>
											<div class="col"><img class="img-radius img-fluid wid-80" src="{{ URL::asset('assets/backend/images/user/avatar.jpg') }}" alt="User image"></div>
											<div class="col"></div>
										</div>
									</div>
									<div class="text-center">
										<h6 class="mb-0 mt-3">{{ $user->full_name }}</h6>
										<p class="mb-3 text-muted">
                                            @if($user->role == 2)
                                            Faculty
                                            @endif
                                        </p>
                                        <p class="mb-0">{{ $user->username }}</p>
                                        <p class="mb-0">{{ $user->email }}</p>
                                        <p class="mb-0">{{ $user->gender }}</p>
                                        <p class="mb-0">{{ $user->birth_date !== null ? date('F j, Y', strtotime($user->birth_date)) : null }}</p>
                                    </div>
                                    <hr class="wid-80 b-wid-3 my-4">
									<div class="row text-center">
										<div class="col">
											<h6 class="mb-1">{{ $user->position }}</h6>
										</div>
										<div class="col">
											<h6 class="mb-1">{{ $user->department }}</h6>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- [ user card1 ] end -->
                <!-- varient [ 2 ][ cover shape ] card Start -->
				<div class="tab-pane fade" id="user2" role="tabpanel" aria-labelledby="user2-tab">
					<div class="row mb-n4">
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <form class="ng-pristine ng-valid-email ng-invalid ng-invalid-required" name="profileEditFrm" ng-submit="frm.updateProfile(profileEditFrm.$valid)" autocomplete="off">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">First name</label>
                                        <input type="text" name="first_name" ng-model="frm.first_name" ng-init="frm.first_name='{{ $user->first_name }}'" id="first_name" class="form-control" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Middle name</label>
                                        <input type="text" name="middle_name" ng-model="frm.middle_name" ng-init="frm.middle_name='{{ $user->middle_name }}'" id="middle_name" class="form-control" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Last name</label>
                                        <input type="text" name="last_name" ng-model="frm.last_name" ng-init="frm.last_name='{{ $user->last_name }}'" id="last_name" class="form-control" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Gender</label>
                                        <select name="gender" ng-model="frm.gender" ng-init="frm.gender='{{ $user->gender }}'" id="gender" class="form-control" required>
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
                                        <button type="submit" ng-disabled="profileEditFrm.$invalid" id="update_profile_btn" class="btn btn-primary">Update</button>
                                    </div>
                                </div>
                            </form>
                        </div>
					</div>
				</div>
				<!-- varient [ 2 ][ cover shape ] card end -->
				<!-- varient [ 3 ][ cover shape ] card Start -->
				<div class="tab-pane fade" id="user3" role="tabpanel" aria-labelledby="user3-tab">
					<div class="row mb-n4">
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <form class="ng-pristine ng-valid-email ng-invalid ng-invalid-required" name="profileFrm" ng-submit="frm.updatePassword(profileFrm.$valid)" autocomplete="off">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Current Password</label>
                                        <input type="password" name="current_password" ng-model="frm.current_password" id="current_password" class="form-control" placeholder="Current Password" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Password</label>
                                        <input type="password" name="password" ng-model="frm.password" id="password" class="form-control" placeholder="Password" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Confirm new password</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation" ng-model="frm.password_confirmation" class="form-control" placeholder="Confirm new password" required>
                                    </div>
                                    <div class="form-group">
                                        <button type="submit" ng-disabled="profileFrm.$invalid" id="update_btn" class="btn btn-primary">Update</button>
                                    </div>
                                </div>
                            </form>
                        </div>
					</div>
				</div>
				<!-- varient [ 3 ][ cover shape ] card end -->
			</div>
		</div>
	</div>
	<!-- [ Main Content ] end -->
</div>
@endsection
@section('footer_scripts')
<script>
    (function () {
        var editProfileApp = angular.module('editProfileApp', ['angular.filter']);
        editProfileApp.controller('editProfileCtrl', function ($scope, $http, $sce, $filter) {

            var vm = this;

            $scope.birth_date = new Date("{{$user->birth_date}}");

            vm.updateProfile = function () {

                date_final = $filter('date')(new Date(vm.birth_date), 'M/d/yy h:mm a');

                $('#update_profile_btn').prop('disabled', true);
                $('#update_profile_btn').html('Please wait... <i class="fa fa-spinner fa-spin"></i>');

                $http({
                    method: 'POST',
                    url: '/faculty/profile',
                    data: JSON.stringify({
                        gender: vm.gender,
                        birth_date: date_final,
                    })
                }).success(function (data) {
                    $('#update_profile_btn').prop('disabled', false);
                    $('#update_profile_btn').html('Update');
                    if (data.result == 1){
                    
                        new PNotify({
                            title: 'Success',
                            text: data.message,
                            type: 'success'
                        });
                        
                        setTimeout(window.location.href = '/faculty/profile', 10000);
                        
                    } else {
                        new PNotify({
                            title: 'Warning',
                            text: 'Something went wrong. Please try again!',
                            type: 'default'
                        });
                }
                }).error(function (data) {

                    $('#update_profile_btn').prop('disabled', false);
                    $('#update_profile_btn').html('Update');

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

            vm.updatePassword = function () {
               
               $('#update_btn').prop('disabled', true);
               $('#update_btn').html('Please wait... <i class="fa fa-spinner fa-spin"></i>');

                $http({
                    method: 'POST',
                    url: '/faculty/profile/password',
                    data: JSON.stringify({
                        current_password: vm.current_password,
                        password: vm.password,
                        password_confirmation: vm.password_confirmation
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

                       setTimeout(window.location.href = '/faculty/profile', 10000);

                   } else {

                        $('#update_btn').prop('disabled', false);
                        $('#update_btn').html('Save');

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