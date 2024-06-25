<?php
header("Content-Type: application/json");
include 'conn.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $sql = "SELECT * FROM perpustakaan";
        $result = $conn->query($sql);

        $books = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $books[] = $row;
            }
        }
        echo json_encode($books);
        break;

    case 'POST':
        $input = json_decode(file_get_contents('php://input'), true);
        $namabuku = $conn->real_escape_string($input['namabuku']);
        $penerbit = $conn->real_escape_string($input['penerbit']);
        $tahun = (int)$input['tahun'];

        $sql = "INSERT INTO perpustakaan (namabuku, penerbit, tahun) VALUES ('$namabuku', '$penerbit', $tahun)";

        if ($conn->query($sql) === TRUE) {
            $response = ["message" => "Buku berhasil ditambahkan"];
        } else {
            $response = ["message" => "Error: " . $sql . "<br>" . $conn->error];
        }
        echo json_encode($response);
        break;

    default:
        echo json_encode(["message" => "Method not supported"]);
        break;
}

$conn->close();
?>
