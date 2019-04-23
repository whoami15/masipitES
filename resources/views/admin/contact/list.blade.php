@extends('layouts.backend.master')
@section('title', 'Contact')

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
<div ng-app="listContactApp" ng-controller="listContactCtrl as frm">
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
                        <li class="breadcrumb-item"><a href="#">Contact</a></li>
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
                    <h5>Messages</h5>
                </div>
                <div class="card-body pb-0">
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
                                        <th>Subject</th>
                                        <th>Content</th>
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
        var listContactApp = angular.module('listContactApp', ['angular.filter']);
        listContactApp.controller('listContactCtrl', function ($scope, $http, $sce) {

            var vm = this;

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
                                url: '/admin/contact-data',
                                data: function (data) {

                                    for (var i = 0, len = data.columns.length; i < len; i++) {
                                        if (! data.columns[i].search.value) delete data.columns[i].search;
                                        if (data.columns[i].searchable === true) delete data.columns[i].searchable;
                                        if (data.columns[i].orderable === true) delete data.columns[i].orderable;
                                        if (data.columns[i].data === data.columns[i].name) delete data.columns[i].name;
                                    }
                                    delete data.search.regex;
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
                                {data: 'name', name: 'name', orderable: false, searchable: false},
                                {data: 'subject', name: 'subject', orderable: false, searchable: true},
                                {data: 'content', name: 'content', orderable: false, searchable: false},
                                {data: 'date', name: 'date'},
                                {data: 'action', name: 'action', orderable: false, searchable: false}
                            ],
                            order: [[4, 'desc']],
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