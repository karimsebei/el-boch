<?php

include("db.php");
$errors=array();
$table='topics';
$topics = selectAll($table);
$id="";
$name="";
$description="";


if (isset($_POST["add-topic"])) {

    if (empty($_POST['name'])) {
       array_push($errors, 'Name is required');
    }

    // Assuming selectOne() returns null if no topic is found
    $existingTopic = selectOne('topics', ['name' => $_POST['name']]);
    if (!empty($existingTopic)) {
        array_push($errors, 'Topic already exists');
    }

    if (count($errors) == 0) {
        unset($_POST['add-topic']);
        // Assuming create() creates the topic in the database
        $topic_id = create('topics', $_POST);
        $_SESSION['message'] = 'Topic created successfully';
        $_SESSION['type'] = 'success';
        header('Location: ../pages/index_topics.php');
        exit();
    } else {
        // Initialize variables
        $name = isset($_POST['name']) ? $_POST['name'] : '';
        $description = isset($_POST['description']) ? $_POST['description'] : '';
    }
}


if (isset($_GET['id'])) {
    $id =$_GET['id'];
    $topic = selectOne($table , ['id' => $id]);
    $id = $topic['id'];
    $name = $topic['name'];
    $description = $topic['description'];
}

if (isset($_GET['del_id'])) {
    $id =$_GET['del_id'];
    $topic = delete($table , $id);
    $_SESSION['message']= 'topic deleted successfully';
    $_SESSION['type']= 'sucess';
    header('Location: ../pages/index_topics.php');
    exit();

}

if (isset($_POST['update-topic'])) {
    $id = $_POST['id'];
    unset($_POST['update-topic'] ,$_POST['id']);
    $topic_id = update($table,$id,$_POST);
    $_SESSION['message']= 'topic updated successfully';
    $_SESSION['type']= 'sucess';
    header('Location: ../pages/index_topics.php');
    exit();



}
?>