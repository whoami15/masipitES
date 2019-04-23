@extends('layouts.backend.master')
@section('title', 'Transfer Grade Level')

@section('header_scripts')
<link href="{{ URL::asset('assets/backend/plugins/datatables/plugins/bootstrap/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/backend/plugins/datatables/plugins/responsive/responsive.dataTables.min.css') }}" rel="stylesheet">
<style type="text/css">
    .text-wrap{
        white-space:normal;
    }
    .width-120{
        width:120px;
    }
    div.dataTables_wrapper div.dataTables_processing {
        margin-top: 0px;
    }
</style>
@stop

@section('content')
<div ng-app="gradeLevelUserApp" ng-controller="gradeLevelCtrl as frm">
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
                        <li class="breadcrumb-item"><a href="#">Transfer Grade Level</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->
    <!-- [ Main Content ] start -->
    <div class="row">
        <!-- [ user Project list ] start -->
        <div class="col-xl-12 col-md-12">
            <div class="card user-list table-card">
                <div class="card-header">
                    <h5>Transfer Grade Level</h5>
                </div>
                <div class="card-body pb-0">
                    <div class="col-md-12 text-center p-2 mt-2">
                        <button class="btn btn-primary btn-sm" ng-click="frm.sortby(1)">Grade I</button>
                        <button class="btn btn-primary btn-sm" ng-click="frm.sortby(2)">Grade II</button>
                        <button class="btn btn-primary btn-sm" ng-click="frm.sortby(3)">Grade III</button>
                        <button class="btn btn-primary btn-sm" ng-click="frm.sortby(4)">Grade IV</button>
                        <button class="btn btn-primary btn-sm" ng-click="frm.sortby(5)">Grade V</button>
                        <button class="btn btn-primary btn-sm" ng-click="frm.sortby(6)">Grade VI</button>
                        <button class="btn btn-danger btn-sm" ng-click="frm.sortby('RECENT')">All</button>
                    </div>
                    <br>
                    <div id="loading">
                        <h3 class="text-center"><i class="fa fa-spinner fa-spin"></i> Please wait...</h3>
                    </div>
                    <div class="table-responsive">
                        <div class="user-scroll new-scroll" style="height:430px;position:relative;">
                            <table class="table table-striped table-bordered text-center" id="content-table" width="100%">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Username</th>
                                        <th>ID Number</th>
                                        <th>Grade Level</th>
                                        <th>Date Registered</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                            <br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ user Project list ] end -->
    </div>
    <!-- [ Main Content ] end -->
</div>
@endsection
@section('footer_scripts')
<script src="{{ URL::asset('assets/backend/plugins/datatables/jquery.dataTables.min.js') }}" ></script>
<script src="{{ URL::asset('assets/backend/plugins/datatables/plugins/bootstrap/dataTables.bootstrap4.min.js') }}" ></script>
<script src="{{ URL::asset('assets/backend/plugins/datatables/plugins/responsive/dataTables.responsive.min.js') }}" ></script>
<script>
    (function () {
        var gradeLevelUserApp = angular.module('gradeLevelUserApp', ['angular.filter']);
        gradeLevelUserApp.controller('gradeLevelCtrl', function ($scope, $http, $sce) {

            var vm = this;

            vm.sort_by = 'RECENT';

            vm.sortby = function (sort) {
                if (sort) vm.sort_by = sort;
                getdata();
            }

            getdata();
            function getdata() {

                $("#content-table").dataTable().fnDestroy();
                $(".table-responsive").hide();  
                $('#loading').show(); 
                

                    angular.element(document).ready( function () {


                        var tbl = $('#content-table').DataTable({
                            processing: true,
                            serverSide: true,
                            ajax: {
                                url: '/admin/user/grade-level-data',
                                data: function (data) {

                                    for (var i = 0, len = data.columns.length; i < len; i++) {
                                        if (! data.columns[i].search.value) delete data.columns[i].search;
                                        if (data.columns[i].searchable === true) delete data.columns[i].searchable;
                                        if (data.columns[i].orderable === true) delete data.columns[i].orderable;
                                        if (data.columns[i].data === data.columns[i].name) delete data.columns[i].name;
                                    }
                                    delete data.search.regex;

                                    if(vm.sort_by != 'RECENT') {
                                        data.sort_by = vm.sort_by;
                                    }
                                }
                            },
                            lengthChange: false,
                            info: false,
                            autoWidth: false,
                            columnDefs: [
                                {
                                    render: function (data, type, full, meta) {
                                        return "<div class='text-wrap width-120'>" + data + "</div>";
                                    },
                                    targets: [0,6]
                                }
                             ],
                            columns: [
                                {data: 'id', orderable: false, searchable: false},
                                {data: 'name', name: 'first_name', orderable: true, searchable: true},
                                {data: 'username', name: 'username', orderable: false, searchable: true},
                                {data: 'id_no', name: 'id_no', orderable: false, searchable: true},
                                {data: 'grade_level', name: 'grade_level', orderable: false, searchable: false},
                                {data: 'date', name: 'date'},
                                {data: 'action', name: 'action', orderable: false, searchable: false}
                            ],
                            order: [[6, 'desc']],
                            "initComplete": function(settings, json) { 
                                   $('#loading').delay( 300 ).hide(); 
                                   $(".table-responsive").delay( 300 ).show(); 
                            } 
                        });

                     });

            }

            vm.changeGradeLevel = function (id) {
               
                $('#grade_level'+id).hide();
                $('#processing'+id).show();

                var grade_level = $('#grade_level'+id).val();
                $http({
                    method: 'POST',
                    url: '/admin/user/grade-level/'+id+'/transfer',
                    data: JSON.stringify({
                        grade_level: grade_level
                    })
                }).success(function (data) {
                    $('#grade_level'+id).show();
                    $('#processing'+id).hide();
                    if (data.result==1){

                        new PNotify({
                            title: 'Success',
                            text: data.message,
                            type: 'success'
                        });

                        getdata();
                    }
                }).error(function (data) {
                    $('#grade_level'+id).show();
                    $('#processing'+id).hide();
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