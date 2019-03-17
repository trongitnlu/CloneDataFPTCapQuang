<?php
    set_time_limit(0);
    require_once '../connection.php';
    require "../simple_html_dom.php";

    $db = new DB_Connection();
    $connection = $db->get_connection();
    $sql = "SELECT * FROM category WHERE top=1 AND id_root=0";


    select($connection, $sql);
    $connection->close();

    function select( $connection,$query){
        $res = array();
        // Thực hiện câu truy vấn, hàm này truyền hai tham số vào là biến kết nối và câu truy vấn
        $result = mysqli_query($connection, $query);
 
        // Nếu thực thi không được thì thông báo truy vấn bị sai
        if (!$result){
            die ('Câu truy vấn bị sai');
        }
        
        // Lặp qua kết quả và in ra ngoài màn hình
        // Vì các field trong database là id, name, phone, address nên
        // khi vardum mang sẽ có cấu trúc tương tự
        while ($row = mysqli_fetch_assoc($result)){
            array_push($res,$row);
        }
        
        // Xóa kết quả khỏi bộ nhớ
        mysqli_free_result($result);
        echo json_encode($res, JSON_PRETTY_PRINT);
        // Sau khi thực thi xong thì ngắt kết nối database
    }
?>