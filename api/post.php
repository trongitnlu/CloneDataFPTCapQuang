<?php
    require_once '../connection.php';
    $db = new DB_Connection();
    $connection = $db->get_connection();

    function select($connection,$path){
        $response = new stdClass();
        $response->error = false;
        $response->data = null;
        $response->message = 'Success';

        $sql = "SELECT * FROM category WHERE `path`='$path'";
        $result = mysqli_query($connection, $sql);
        // Nếu thực thi không được thì thông báo truy vấn bị sai
        if (!$result){
            // die ('Câu truy vấn bị sai');
            $response->error = true;
            $response->message = 'Failed';
            return $response;
        }
        
        // Lặp qua kết quả và in ra ngoài màn hình
        // khi vardum mang sẽ có cấu trúc tương tự
        $row = mysqli_fetch_assoc($result);
        if($row){
            $id_content = $row['id_content'];
            $sql = "SELECT * FROM post WHERE `id`=$id_content";
            $result = mysqli_query($connection, $sql);
            $row = mysqli_fetch_assoc($result);
            $row['content'] = htmlentities($row['content'], ENT_COMPAT, "UTF-8");
            if($row){
                $response->data = $row;
            }else{
                $response->error = true;
                $response->message = 'Failed';
            }
        }else{
            $response->error = true;
            $response->message = 'Failed';
        }
        mysqli_free_result($result);
        return $response;
        // Xóa kết quả khỏi bộ nhớ
}
    if($_GET["path"]) {
        echo json_encode(select($connection, $_GET['path']));
        exit();
    }else{
        $response = new stdClass();
        $response->error = true;
        $response->data = null;
        $response->message = 'failed';

        echo json_encode($response);
    }
?>