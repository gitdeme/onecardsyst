/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
 var url = "http://localhst:81/acs/";
    var config = {headers: {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'}
    }
    var app = angular.module("myApp", ['ngTable']);
    app.controller("procter", ['$scope', '$http', 'NgTableParams', function ($scope, $http, NgTableParams) {
            $scope.newName = "students";
            $scope.students = [];
            $scope.message = "";
            $scope.absentSstudents = [];
            $scope.validID = false;
             $scope.validform = false;
            $scope.permit = {};
            $scope.studentsOnVacation = [];
            $scope.getPermitedStudents = function () {
                var data = $.param({vacationPermision: $scope.newName});
                $http.post('getStudentsOnBreak', data, config).then(function (response) {// on success
                    console.log(response.data);
                    $scope.studentsOnVacation = response.data;
                    $scope.permitedtableParams = new NgTableParams({}, {dataset: $scope.studentsOnVacation});
                }, function (response) {
                    console.log(response.data, response.status);
                });
            };
            $scope.getStudentByID = function () {
                if ($scope.permit.bcID != undefined) {
                    var value = $scope.permit.bcID;
                    var data = $.param({get_student_by_id: 'get_student_by_id', ID: value});
                    $http.post('get_student_info_by_id_or_barcode', data, config).then(function (response) {// on success
                        console.log(response.data);
                        var data_ = response.data;
                        if (data_.length > 0) {
                            $scope.permit.name = data_[0].FullName;
                            $scope.validID = false;
                        }else {
                            $scope.validID = true;
                            $scope.permit.name = "";
                        }
                    }, function (response) {
                        console.log(response.data, response.status);

                    });
                }
            };

            $scope.savePermision = function () {
                $scope.permit.savepermision = "savepermision";
                console.log($scope.permit);
                var data = $.param($scope.permit);
                $http.post('save_break_student', data, config).then(function (response) {// on success
                    console.log(response.data);
                    $scope.message = response.data.Message;
                    if (response.data.ok === true) {
                        $scope.showsuccess = true;
                        $scope.permit = {};
                    } else {
                        $scope.showError = true;
                    }
                    // $scope.tableParams = new NgTableParams({}, {dataset: $scope.students});
                }, function (response) {
                    console.log(response.data, response.status);
                    $scope.showError = false;
                });
            }

            $scope.deletePermision = function (row) {
                row.deletePermision = "deletePermision";
                console.log(row);
                var data = $.param(row);
                $http.post('delete_record', data, config).then(function (response) {// on success
                    console.log(response.data);
                    $scope.message = response.data.Message;
                    if (response.data.ok === true) {
                        $scope.showsuccess = true;
                        $scope.students = $scope.students.splice($scope.students.indexOf(row), 1);
                        // $scope.tableParams = new NgTableParams({}, {dataset: remain});

                    } else {
                        $scope.showError = true;
                    }
                    // $scope.tableParams = new NgTableParams({}, {dataset: $scope.students});
                }, function (response) {
                    console.log(response.data, response.status);
                    $scope.showError = false;
                });
            }
            $scope.getAbsentStudents = function () {
                var data = $.param({getAbsentstudents: "getAbsentstudents"});
                $http.post('get_absent_students', data, config).then(function (response) {// on success
                    console.log(response.data);
                    $scope.absentstudents = response.data;
                    $scope.tableParams_absent = new NgTableParams({}, {dataset: $scope.absentstudents});

                }, function (response) {
                    console.log(response.data, response.status);

                });
            };
            //  $scope.getStudents();
            $scope.getPermitedStudents();
          //  $scope.getAbsentStudents();

        }]);

