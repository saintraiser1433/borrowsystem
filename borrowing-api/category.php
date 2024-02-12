<?php
// Allow requests from any origin
header("Access-Control-Allow-Origin: *");

// Allow these methods for the preflight request (OPTIONS)
header("Access-Control-Allow-Methods: GET, POST, OPTIONS,DELETE,PUT");

// Allow these headers for the preflight request (OPTIONS)
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
include 'conn.php';
// Sample data (you can replace this with a database connection or any other data source)
$action = $_GET['action'];
$json_data = file_get_contents("php://input");
$data = json_decode($json_data, true);
switch ($action) {
    case 'GET':
        $sql = "SELECT category_id,description,status FROM tbl_categories order by category_id asc";
        $rs = $conn->query($sql);
        $data = [];

        if ($rs) {
            while ($row = $rs->fetch_assoc()) {
                // Assuming your resident table has 'id', 'name', and 'age' columns
                $data[] = [
                    'categoryId' => $row['category_id'],
                    'description' => $row['description'],
                    'status' => $row['status']
                ];
            }
        }
        echo json_encode($data);
        break;
    case 'POST':
        $description = $data['description'];
        $status = $data['status'];
        $query = "INSERT INTO tbl_categories (description,status) 
        values ('$description',$status)";
        $stmt = $conn->query($query);
        if ($stmt) {
            echo json_encode(['message' => 'Category added successfully']);
        } else {
            echo json_encode(['error' => 'Failed to add category']);
        }
        break;
    case 'PUT':
        $description = $data['description'];
        $status = $data['status'];
        $categoryId = $data['categoryId'];

        $query = "UPDATE tbl_categories SET 
        description='$description',
        status=$status
        where category_id=$categoryId";
        $stmt = $conn->query($query);
        if ($stmt) {
            echo json_encode(['message' => 'Category updated successfully']);
        } else {
            echo json_encode(['error' => 'Failed to update category']);
        }
        break;
    case 'DELETE':
        $categoryId = $_GET['categoryId'];
        $sqlDelete = "DELETE FROM tbl_categories WHERE category_id = $categoryId";
        $result = $conn->query($sqlDelete);

        if ($result) {
            echo json_encode(['message' => 'Category deleted successfully']);
        } else {
            echo json_encode(['error' => 'Failed to delete Category']);
        }
        break;
}
