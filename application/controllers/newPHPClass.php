<?php

class Criterion {

    var $id;
    var $name;
    var $weight;

    function __construct($id, $name, $weight) {
        $this->id = $id;
        $this->name = $name;
        $this->weight = $weight;
    }

    function create_criteria() {
        $conn = mysqli_connect("localhost", "root", "", "mydb");
        $sql = "insert into criteriona   values('" . $this->id . "', '" . $this->name . "', '" . $this->weight . "' )";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    function update_criteria() {
        $conn = mysqli_connect("localhost", "root", "", "mydb");
        $sql = "update criteriona  set   values('" . $this->id . "', '" . $this->name . "', '" . $this->weight . "' )";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    function delete_criteria() {
        $conn = mysqli_connect("localhost", "root", "", "mydb");
        $sql = "delete from criteriona where id='" . $this->id . "'";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

}

if (isset($_POST['submit'])) {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $weight = $_POST["weight"];
    $cr = new Criterion($id, $name, $weight);
    $status = $cr->create_criteria();
}
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $cr = new Criterion($id, "", "");
    $status = $cr->delete_criteria();
  //  header("");
}


