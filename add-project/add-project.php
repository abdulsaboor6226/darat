<?php

    // [DATABASE CONNECTION STARTS]
    $host = "localhost";
    $user = "darat_user";
    $password = "JTg[0~y}]cS)";
    $db = "darat_darat";

    $con = mysqli_connect($host,$user,$password,$db);

    // Check connection
    if (mysqli_connect_errno()){
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }else{  //echo "Connect"; 

    }

    // [DATABASE CONNECTION ENDS]


    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        $name                       = $_POST["name"];
        $location                   = $_POST["location"];
        $city_id                    = $_POST["city_id"];
        $description                = $_POST["description"];
        $description                = $_POST["description"];
        $developer_contact          = $_POST["developer_contact"];
        $featured_phone_number      = $_POST["featured_phone_number"];
        $featured_whatsapp_number   = $_POST["featured_whatsapp_number"];
        $user_id                    = $_POST["user_id"];

        $logo_file_name             = $_FILES['logo']['name'];
        $logo_file_temp_name        = $_FILES['logo']['tmp_name'];

        $project_image1_name             = $_FILES['image1']['name'];
        $project_image1_temp_name        = $_FILES['image1']['tmp_name'];

        $project_image2_name             = $_FILES['image2']['name'];
        $project_image2_temp_name        = $_FILES['image2']['tmp_name'];

        $project_image3_name             = $_FILES['image3']['name'];
        $project_image3_temp_name        = $_FILES['image3']['tmp_name'];

        $project_image4_name             = $_FILES['image4']['name'];
        $project_image4_temp_name        = $_FILES['image4']['tmp_name'];

        $project_image5_name             = $_FILES['image5']['name'];
        $project_image5_temp_name        = $_FILES['image5']['tmp_name'];

        $folder_logo            = "";
        $folder_project_image   = "";


    }

    $data = array("text_data" => $_POST, "files" => $_FILES);
    $response = array("status" => true, "message"=>"php api response", "data" => $data);
    die(json_encode($response));

?>