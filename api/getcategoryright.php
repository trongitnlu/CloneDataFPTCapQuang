<?php
    require_once '../connection.php';

    $db = new DB_Connection();
    $connection = $db->get_connection();

    $result = select($connection, 0);
    $connection->close();
    echo json_encode($result, JSON_PRETTY_PRINT);

    function select($connection,$id){
            $sql = "SELECT * FROM category WHERE `right`=1 AND `status`=1 AND id_root=$id";
            $result = mysqli_query($connection, $sql);
            $res = array();
            // Nếu thực thi không được thì thông báo truy vấn bị sai
            if (!$result){
                // die ('Câu truy vấn bị sai');
                return $res;
            }
            
            // Lặp qua kết quả và in ra ngoài màn hình
            // khi vardum mang sẽ có cấu trúc tương tự
            while ($row = mysqli_fetch_assoc($result)){
                $row["item"] =  select($connection, $row["id"]);
                array_push($res,$row);
            }
            mysqli_free_result($result);
            return $res;
            // Xóa kết quả khỏi bộ nhớ
    }

?>