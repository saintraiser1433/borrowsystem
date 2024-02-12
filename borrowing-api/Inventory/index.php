<?php
// Allow requests from any origin
header("Access-Control-Allow-Origin: *");

// Allow these methods for the preflight request (OPTIONS)
header("Access-Control-Allow-Methods: GET, POST, OPTIONS,DELETE,PUT");

// Allow these headers for the preflight request (OPTIONS)
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
include '../conn.php';
// Sample data (you can replace this with a database connection or any other data source)
$action = $_GET['action'];
$json_data = file_get_contents("php://input");
$data = json_decode($json_data, true);
switch ($action) {
    case 'GET':
        $sql = "SELECT asset_tag,item_name,category_id,item_model,item_condition,status,description,img_path FROM tbl_inventory ";
        $rs = $conn->query($sql);
        $data = [];

        if ($rs) {
            while ($row = $rs->fetch_assoc()) {
                // Assuming your resident table has 'id', 'name', and 'age' columns
                $data[] = [
                    'asset_tag' => $row['asset_tag'],
                    'item_name' => $row['item_name'],
                    'category_id' => $row['category_id'],
                    'item_model' => $row['item_model'],
                    'item_condition' => $row['item_condition'],
                    'status' => $row['status'],
                    'description' => $row['description'],
                    'img_path' => $row['img_path'],
                ];
            }
        }
        echo json_encode($data);
        break;
    case 'POST':
        $fetch = json_decode($_POST['data'], true);
        $asset_tag = $fetch['asset_tag'];
        $item_name = $fetch['item_name'];
        $category_id = $fetch['category_id'];
        $item_model = $fetch['item_model'];
        $item_condition = $fetch['item_condition'];
        $description = $fetch['description'];
        $status = $fetch['status'];
        if (empty($_FILES) || !isset($_FILES['files'])) {
            $dir = "NULL"; // Set to NULL keyword
        } else {
            $pth = $fetch['img_path'] . ".png";
            $dir = "'" . $fetch['img_path'] . ".png'";
            move_uploaded_file($_FILES['files']['tmp_name'], $pth);
        }
        $query = "INSERT INTO tbl_inventory (asset_tag,item_name,category_id,item_model,item_condition,description,img_path,status) 
        values ($asset_tag,'$item_name',$category_id,'$item_model','$item_condition','$description',$dir,$status)";
        $stmt = $conn->query($query);
        if ($stmt) {
            echo json_encode(['message' => 'Asset added successfully']);
        } else {
            echo json_encode(['error' => 'Failed to add asset']);
        }
        break;
    case 'PUT':
        $pth = $fetch['img_path'] . ".png";
        $dir = "'" . $fetch['img_path'] . ".png'";
        $fetch = json_decode($_POST['data'], true);
        $asset_tag = $fetch['asset_tag'];
        $item_name = $fetch['item_name'];
        $category_id = $fetch['category_id'];
        $item_model = $fetch['item_model'];
        $item_condition = $fetch['item_condition'];
        $description = $fetch['description'];
        $status = $fetch['status'];
        if (empty($_FILES) || !isset($_FILES['files'])) {
            $dir = "NULL"; // Set to NULL keyword
        } else {
            $pth = $fetch['img_path'] . ".png";
            $dir = "'" . $fetch['img_path'] . ".png'";
            move_uploaded_file($_FILES['files']['tmp_name'], $pth);
        }
        $query = "UPDATE tbl_inventory SET 
        item_name='$item_name',
        category_id=$category_id,
        item_model='$item_model',
        item_condition='$item_condition',
        description='$description',
        img_path=$dir,
        status=$status
        where asset_tag=$asset_tag";
        $stmt = $conn->query($query);
        if ($stmt) {
            echo json_encode(['message' => 'Asset updated successfully']);
        } else {
            echo json_encode(['error' => 'Failed to update asset']);
        }
        break;
    case 'DELETE':
        $assettag = $_GET['asset_tag'];
        $sqlDelete = "DELETE FROM tbl_inventory WHERE asset_tag = '$assettag'";
        $result = $conn->query($sqlDelete);

        if ($result) {
            echo json_encode(['message' => 'Item deleted successfully']);
        } else {
            echo json_encode(['error' => 'Failed to delete Item']);
        }
        break;
}
