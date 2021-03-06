<?php 
    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD']=='POST') {
        $mysqli = new mysqli('localhost', 'root', '', 'testing');

        if ($mysqli->connect_errno) {
            die (json_encode([
                "status" => "error",
                "message" => $mysqli->connect_error
            ]));
        }

        $name = $_FILES['image']['name'];
        $path = $_FILES['image']['tmp_name'];
        $size = $_FILES['image']['size'];
        $format = $_FILES['image']['type'];
        $error = $_FILES['image']['error'];

        if ($error == 0) {
            if ($size <= 5000000) {
                if (($format == 'image/png') || ($format == 'image/jpeg')) {
                    $fileName = time() . strstr($name, '.');
                    move_uploaded_file($path, 'upload/' . $fileName);

                    $sql = "INSERT INTO images (image) VALUES ('$fileName')";
                    if ($mysqli->query($sql)) {
                        echo json_encode([
                            "status" => "success",
                            "message" => "upload image berhasil"
                        ]);
                    } else {
                        echo json_encode([
                            "status" => "error",
                            "message" => $mysqli->error
                        ]);
                    } 
                } else {
                    echo json_encode([
                        "status" => "error",
                        "message" => "format image harus png atau jpeg"
                    ]);
                }
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "size image max 5MB"
                ]);
            }
        }

        $mysqli->close();
    }
?>