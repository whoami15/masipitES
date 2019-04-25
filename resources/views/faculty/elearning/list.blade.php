@extends('layouts.backend.master')
@section('title', 'Learning Materials')

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
<div ng-app="pendingUserApp" ng-controller="pendingUserCtrl as frm">
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
        <!-- [ user Project list ] start -->
        <div class="col-xl-12 col-md-12">
            <div class="card user-list table-card">
                <div class="card-header">
                    <h5>Learning Materials</h5>
                </div>
                <div class="card-body pb-0">
                    <div class="col-md-12 text-center p-2 mt-2">
                        <div class="col-md-12 text-center mt-2">
                            <select class="form-control form-control" name="grade_level" id="grade_level" ng-model="filetype" ng-change="frm.orderby()" >
                                <option selected="selected">Select File Type</option>
                                <option value="PDF">PDF</option> 
                                <option value="DOCX">DOCX</option>
                                <option value="DOC">DOC</option>
                                <option value="XLSX">XLSX</option>
                                <option value="XLS">XLS</option>
                                <option value="CSV">CSV</option>
                                <option value="PPTX">PPTX</option>
                                <option value="PPT">PPT</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12 text-center p-2 mt-2">
                        <button class="btn btn-success btn-sm" ng-click="frm.sortby('asc')">File Size (ASC)</button>
                        <button class="btn btn-primary btn-sm" ng-click="frm.sortby('desc')">File Size (DESC)</button>
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
                                        <th>Title</th>
                                        <th>Subject</th>
                                        <th>Grade Level</th>
                                        <th>Downloads</th>
                                        <th>File Size</th>
                                        <th>File Type</th>
                                        <th>Status</th>
                                        <th>Date Created</th>
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
        var pendingUserApp = angular.module('pendingUserApp', ['angular.filter']);
        pendingUserApp.controller('pendingUserCtrl', function ($scope, $http, $sce) {

            var vm = this;

            vm.sort_by = 'RECENT';
            vm.order = 'RECENT';

            vm.sortby = function (sort) {
                if (sort) vm.sort_by = sort;
                getdata();
            }

            vm.orderby = function () {
                if($scope.filetype) vm.order = $scope.filetype;
                vm.sort_by = 'RECENT';
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
                                url: '/faculty/elearning-data',
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

                                    if(vm.order != 'RECENT') {
                                        data.order_by =  vm.order;
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
                                    targets: [0]
                                }
                             ],
                            columns: [
                                {data: 'id', orderable: false, searchable: false},
                                {data: 'title', name: 'title', orderable: true, searchable: true},
                                {data: 'subject', name: 'subject', orderable: false, searchable: false},
                                {data: 'grade_level', name: 'grade_level', orderable: false, searchable: false},
                                {data: 'downloads', name: 'downloads', orderable: false, searchable: false},
                                {data: 'file_size', name: 'file_size', orderable: false, searchable: false},
                                {data: 'file_type', name: 'file_type', orderable: false, searchable: false},
                                {data: 'status', name: 'status', orderable: false, searchable: false},
                                {data: 'date', name: 'date'},
                                {data: 'action', name: 'action', orderable: false, searchable: false}
                            ],
                            order: [[7, 'desc']],
                            "initComplete": function(settings, json) { 
                                   $('#loading').delay( 300 ).hide(); 
                                   $(".table-responsive").delay( 300 ).show(); 
                            } 
                        });

                     });

            }

        });
    })();

</script>

@stop