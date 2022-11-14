
var csa = angular.module("csa", ['ngTable']).
        controller("macc", ["$scope", "$http", "NgTableParams", function ($scope, $http, NgTableParams) {
                var config = {headers: {'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'}}
                var ctr = "";
                $scope.showButtons = false;
                $scope.faculities = [];
                $scope.departments = [];
                $scope.showSuccess = false;
                $scope.showError = false;
                $scope.studentModel = {};
                $scope.deptStudent = [];
                $scope.streams = [];
                $scope.facModel = {};
                $scope.student = {};
                $scope.studentID = "";
//                    $scope.successMessage = "";
//                    $scope.errorMessage = "";
                $scope.bands = [{band_id: 1, band_name: "1st Year"}, {band_id: 2, band_name: "2nd Year"}, {band_id: 3, band_name: "3rd Year"}, {band_id: 4, band_name: "4th Year"}, {band_id: 5, band_name: "5th Year"}, {band_id: 6, band_name: "6th Year"}]
                $scope.programs = ["Regular", "Summer", "Extension"];
                //  $scope.programs_for_cafe = ["Regular", "Summer"];
                $scope.acYear = ["2017/2018", "2018/2019", "2019/2020", "2020/2021"];
                $scope.acyears = [];
                $scope.card = {};

                var date = new Date();
                var fullYear = date.getFullYear();
                $scope.years = [];

                var range = [];

                for (var i = 1; i < 70; i++) {
                    range.push(i);
                }
                $scope.range=range;

                for (var i = 2011; i < (fullYear - 6); i++) {
                    $scope.years.push(i);
                }
                $scope.getusers = function () {
                    var data = $.param({getall_user_acounts: "getall_user_acounts"})
                    $http.post('services/account_user.php', data, config).then(function (response) {// on success
                        console.log(response.data);
                        $scope.users = response.data;
                        $scope.userstable = new NgTableParams({}, {dataset: $scope.users});

                    }, function (response) {
                        console.log(response.data, response.status);

                    });
                }
                $scope.getStudents = function () {
                    var data = $.param({getStudents: "getusers"});
                    $http.post('services/angularservice.php', data, config).then(function (response) {// on success
                        console.log(response.data);
                        $scope.users = response.data;
                        $scope.userstable = new NgTableParams({}, {dataset: $scope.users});
                    }, function (response) {
                        console.log(response.data, response.status);
                    });
                }
                $scope.getFaculities = function () {
                    var data = $.param({getfac: "getfac"})
                    $http.post('print_id_card', data, config).then(function (response) {// on success
                        console.log(response.data);
                        $scope.faculities = response.data;
                        // $scope.userstable = new NgTableParams({}, {dataset: $scope.users});

                    }, function (response) {
                        console.log(response.data, response.status);

                    });
                }
                    $scope.getFaculities_cafe_services = function () {
                    var data = $.param({get_fac_for_gate_services: "get_fac_for_gate_services"})
                    $http.post('manage_students_to_gate', data, config).then(function (response) {// on success
                        console.log(response.data);
                        $scope.faculities = response.data;
                        // $scope.userstable = new NgTableParams({}, {dataset: $scope.users});

                    }, function (response) {
                        console.log(response.data, response.status);

                    });
                }
                $scope.getFaculities();
                $scope.getFaculities_cafe_services();

                $scope.getDepartments = function () {
                    var selectedFaculity = 0;
                    if ($scope.facModel.faculty_code != undefined) {
                        selectedFaculity = $scope.facModel.faculty_code;
                    } else if ($scope.card.faculty_code != undefined) {
                        selectedFaculity = $scope.card.faculty_code;
                    } else if ($scope.student.faculty_code != undefined) {
                        selectedFaculity = $scope.student.facuity_code;
                    }
                    console.log(selectedFaculity);
                    for (var i = 0; i < $scope.faculities.length; i++) {
                        var row = $scope.faculities[i];
                        if (row.faculity_code === selectedFaculity) {
                            $scope.departments = row.depts;
                            console.log($scope.departments);
                            break;
                        }
                    }
                    // console.log(selectedFac);
                }
                $scope.getStreams = function () {
                    var selecteddept = $scope.facModel.dept_code;
                    console.log(selecteddept);
                    for (var i = 0; i < $scope.departments.length; i++) {
                        var row = $scope.departments[i];
                        if (row.dept_code === selecteddept) {
                            $scope.streams = row.streams;
                            console.log($scope.streams);
                            break;
                        }
                    }
                    // console.log(selectedFac);
                }
                $scope.getStudentsByCrieteria = function ($option) {
                    var criteria = $scope.card;
                    console.log(criteria);
                    if ($option == 1)
                        criteria.forIDDesign = 1;
                    else {
                        criteria.IDDownload = 2;
                    }

                    if (criteria.dept_code !== undefined && criteria.program !== undefined && criteria.band !== undefined) {
                        criteria.preview_students_to_print_id_card = true;
                        var data = $.param(criteria);
                        $http.post('print_id_card', data, config).then(function (response) {// on success
                            console.log(response.data);
                            if ($option === 1) {
                                $scope.students = response.data;
                                $scope.studentsTable = new NgTableParams({}, {dataset: $scope.students});

                            }

                        }, function (response) {
                            console.log(response.data, response.status);

                        });


                    }

//                        for (var i = 0; i < $scope.faculities.length; i++) {
//                            var row = $scope.faculities[i];
//                            if (row.faculity_code === selectedFac) {
//                                $scope.departments = row.depts;
//                                break;
//                            }
//                        }
                    // console.log(selectedFac);
                }
                $scope.getCafeStudents = function () {

                    if ($scope.facModel.acyear !== null && $scope.facModel.program !== null) {
                        $scope.facModel.getcafestudents = "getcafestudents";
                        console.log($scope.facModel)
                        var data = $.param($scope.facModel)
                        $http.post('get_cafteria_client', data, config).then(function (response) {// on success
                            console.log(response.data);
                            $scope.nocafeUsers = response.data;
                            $scope.nocafeUserstable = new NgTableParams({}, {dataset: $scope.nocafeUsers});
                            // $scope.showButtons = true;
                            $("#showButtons").show()
                        }, function (response) {
                            console.log(response.data, response.status);

                        });
                    } else {

                    }

                }
                $scope.getActiveStudents = function () {
                    $scope.card.mangestudents = "mangestudents";
                    $scope.activeStudents = [];
                    $scope.activeStudentstable = [];

                    var data = $.param($scope.card);
                    console.log(data);

                    $http.post('manage_students_to_gate', data, config).then(function (response) {// on success
                        console.log(response);
                        //  if (response.data.status === "ok") {
                        console.log(response.data);
                        $scope.student.mangestudents = null;
                        $scope.activeStudents = response.data;
                        $scope.activeStudentstable = new NgTableParams({}, {dataset: $scope.activeStudents});


                    }, function (response) {
                        console.log(response.data, response.status);
                    });
                }
                $scope.activateStudents = function (row) {
                    var selectedRow = row;
                    selectedRow.activatestudent = "activatestudent";
                    var data = $.param(selectedRow);
                    $http.post('activate_student', data, config).then(function (response) {// on success
                        console.log(response);
                        selectedRow.activatestudent = null;
                        row.is_active_gate = "1"
                    }, function (response) {
                        console.log(response.data, response.status);
                    });
                }
                $scope.deactivateStudents = function (row) {
                    var selectedRow = row;
                    selectedRow.deactivatestudent = "deactivatestudent";
                    var data = $.param(selectedRow);
                    $http.post('deactivate_student', data, config).then(function (response) {// on success
                        console.log(response);
                        row.is_active_gate = "0"
                        selectedRow.deactivatestudent = null;
                    }, function (response) {
                        console.log(response.data, response.status);
                    });
                }

                $scope.delete_student = function (row) {
                    var selectedRow = row;
                    console.log(selectedRow);

                    selectedRow.delete_student = "delete student";
                    var data = $.param(selectedRow);
                    var confirm = confirmDelete();
                    if (confirm)
                    {
                        $http.post('delete_student', data, config).then(function (response) {// on success
                            console.log(response);
                            $scope.getStudentsByCrieteria(1);
                            // row.is_active_gate = "0"
                            // selectedRow.delete_student = null;
                        }, function (response) {
                            console.log(response.data, response.status);
                        });
                    }

                }
                $scope.delete_imported_student = function () {

                    var data = $.param($scope.card);
                    var confirm = confirmDelete();
                    if (confirm)
                    {
                        $http.post('delete_imported_student', data, config).then(function (response) {// on success
                            alert(response.data.message);

                        }, function (response) {
                            console.log(response.data, response.status);
                        });
                    }
                    return false;
                }


                $scope.deactivateAllStudents = function () {
                    $scope.card.alldeactivate = "alldeactivate";
                    var data = $.param($scope.card);

                    $http.post('deactivate_all', data, config).then(function (response) {// on success
                        var result = response.data;
                        $scope.student.alldeactivate = null;
                        if (result.status === 'ok') {
                            $scope.successMessage = result.message;
                            $scope.showSuccess = true;
                            $scope.showError = false;
                            $scope.getActiveStudents();
                        } else {
                            $scope.errrorMessage = result.message;
                            $scope.showError = true;
                            $scope.showSuccess = false;
                        }

                    }, function (response) {
                        console.log(response.data, response.status);
                    });
                }
                $scope.activateAllStudents = function () {
                    $scope.card.allactivate = "allactivate";
                    var data = $.param($scope.card);
                    $http.post('activate_all', data, config).then(function (response) {// on success
                        var result = response.data;
                        console.log(result)
                        $scope.card.allactivate = null;
                        if (result.status === 'ok') {
                            $scope.successMessage = result.message;
                            $scope.showSuccess = true;
                            $scope.getActiveStudents();
                        } else {
                            $scope.errrorMessage = result.message;
                            $scope.showError = true;
                        }

                    }, function (response) {
                        console.log(response.data, response.status);
                    });
                }


                $scope.activateUserAccount = function (row) {
                    row.activate_account = 'activate_account';
                    var data = $.param(row);
                    console.log(row);
                    $http.post('services/account_user.php', data, config).then(function (response) {// on success
                        var result = response.data;
                        console.log(result)
                        if (result.status === 'ok') {
                            row.activate_account = '';
                            $scope.getusers();
                        } else {
                        }
                    }, function (response) {
                        console.log(response.data, response.status);
                    });
                }
                $scope.deactivateUserAccount = function (row) {
                    row.deactivate_account = 'deactivate_account';
                    var data = $.param(row);
                    console.log(row);
                    $http.post('services/account_user.php', data, config).then(function (response) {// on success
                        var result = response.data;
                        console.log(result)

                        if (result.status === 'ok') {
                            $scope.getusers();
                            row.deactivate_account = '';

                        } else {

                        }

                    }, function (response) {
                        console.log(response.data, response.status);
                    });
                }


                $scope.updateAccount = function (row) {
                    row.delete_account = 'update_account';
                    var data = $.param(row);
                    console.log(row);
                    $http.post('services/account_user.php', data, config).then(function (response) {// on success
                        var result = response.data;
                        console.log(result)
                        if (result.status === 'ok') {
                        } else {

                        }

                    }, function (response) {
                        console.log(response.data, response.status);
                    });
                }

                $scope.changeToNoneCafeUser = function (row) {
                    $scope.studentModel = row;
                    $scope.studentModel.changetononecafeuser = "change_to_none_cafe_user";
                    var data = $.param($scope.studentModel);
                    console.log($scope.studentModel);

                    $http.post('changetononecafeuser', data, config).then(function (response) {// on success
                        var result = response.data;
                        console.log(result)

                        if (result.status === 'ok') {
                            $scope.studentModel.changetononecafeuser = "";
                            //  $scope.successMessage = result.message;
                            //   $scope.showSuccess = true;
                            $scope.studentModel.Status = "None-Cafe"
                        } else {
                            //  $scope.errrorMessage = result.message;
                            //  $scope.showError = true;
                        }

                    }, function (response) {
                        console.log(response.data, response.status);
                    });
                }
                $scope.changeToCafeUser = function (row) {
                    $scope.studentModel = row;
                    $scope.studentModel.changetocafeuser = "change_to_cafe_user";
                    var data = $.param($scope.studentModel);
                    console.log($scope.studentModel);

                    $http.post('changetocafeuser', data, config).then(function (response) {// on success
                        var result = response.data;
                        console.log(result)
                        if (result.status === 'ok') {
                            $scope.studentModel.changetocafeuser = "";
                            //   $scope.successMessage = result.message;
                            //  $scope.showSuccess = true;
                            $scope.studentModel.Status = "Cafe"
                        } else {
                            //  $scope.errrorMessage = result.message;
                            // $scope.showError = true;
                        }

                    }, function (response) {
                        console.log(response.data, response.status);
                    });
                }
                $scope.getStudentsGroupedbyDepartment = function () {
                    if ($scope.assign.acyear !== undefined && $scope.assign.program !== undefined) {
                        $scope.assign.getStudentsGroupedbyDepartment = "getStudentsGroupedbyDepartment";
                        var data = $.param($scope.assign);
                        console.log($scope.assign);
                        $http.post('assign_students_to_cafeteria', data, config).then(function (response) {// on success
                            var result = response.data;
//                            console.log(result);                           
//                            console.log(result);
//                            for (var i = 0; i < result.length; i++) {
//                                //    result[i].cafeNumber = 0;
//                                result[i].acyear = $scope.assign.acyear;
//                                result[i].program = $scope.assign.program;
//                            }

                            $scope.deptStudents = result;
                            console.log($scope.deptStudents);
                            $scope.deptStudntstable = new NgTableParams({}, {dataset: $scope.deptStudents});
                        }, function (response) {
                            console.log(response.data, response.status);
                        });
                    }
                }
                $scope.assignDeptsStudents = function (row) {

                    $scope.deptStudents.cafteriaAssignment = true;
                    //  console.log($scope.deptStudents);                              
                    // for (var i = 0; i < $scope.deptStudents.length; i++) {
                    //   var row = $scope.deptStudents[i];
                    if (row.CafeNumber !== null) {
                        var newObject = {
                            CafeNumber: row.CafeNumber,
                            Year: row.Year,
                            acyear: row.acyear,
                            admission: row.admission,
                            dept_code: row.dept_code,
                            program: row.Program,
                            semester: row.semester,
                            assignstudents_to_cafe: "assignstudents_to_cafe"
                        }


                        var data = $.param(newObject);


                        $http.post('assign_students_to_cafeteria', data, config).then(function (response) {// on success
                            $scope.success = true;
                            alert("Successfully Done");
                            console.log(response)

                        }, function (response) {
                            $scope.error = true;
                            $scope.success = false;
                            console.log(response.data, response.status);
                        });


                        //  newData.push(newObject);

                    } else {
                        alert("Please select cafe  ");
                    }
                    //  }

                }


                // $scope.getusers();
                $scope.save_id_card_request = function () {
                    $id = {'studentID': $scope.studentID}
                    var data = $.param($id);
                    $http.post('save_id_card_request', data, config).then(function (response) {// on success
                        console.log(response);

                    }, function (response) {
                        console.log(response.data, response.status);
                    });
                }


            }]);
