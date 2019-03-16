<?php
    set_time_limit(0);
    require_once 'connection.php';
    require "simple_html_dom.php";

    $db = new DB_Connection();
    $connection = $db->get_connection();
    $url = "https://fptcapquang.info/internet-cap-quang-fpt-2019/";
    //microsoft
    
    $html = file_get_html($url);
	
	// echo $html;
    $category = $html->find("#wide-nav ul li");
    // $category = $html->find("#secondary aside");
    // echo $category[0];
    getCategoryRoot($category, $connection);
    $connection->close();

    function getCategoryRightChildren($category, $connection){
        for ($i = 0; $i < count($category); $i++){

            $categoryRoot = $category[$i]->find("span", 0)->text();

            $sql = "INSERT INTO category VALUES (NULL, '$categoryRoot', '#', 0, 0, 0, 1, 1, '')";
    
            echo $sql;
            insert($connection, $sql);
            $id_cate = $connection->insert_id;

            echo "ROOT: ".$categoryRoot;
            echo "<br/>";

            $categoryChild = $category[$i]->find("p");

            foreach($categoryChild as $item){
                $the_a = $item->find("a",0);
                // Xu ly the a
                $name = $the_a->text();
                $href = $the_a->href;
                $path = basename($href);
                echo "Name cap 1: ".$name." ----------  Href: ".$path;
                echo "<br/>";
    
                // //Cap 2
                $html2 = file_get_html($href);
                $namePost = $html2->find(".entry-title", 0)->text();
    
                $content = $html2->find(".entry-content", 0);
                $content->find(".yarpp-related", 0)->outertext = "";
                $content->find(".kk-star-ratings", 0)->outertext = "";
                $content->find(".blog-share", 0)->outertext = "";
                $content->find("script", 0)->outertext = "";
                
                echo $namePost;
                echo "<br>";
                echo $content;
    
                $chitiet_UTF8  = htmlentities($content, ENT_COMPAT, "UTF-8");
    
                $sql = "INSERT INTO post VALUES (NULL, '$namePost', '$chitiet_UTF8', 1)";
    
                echo $sql;
                insert($connection, $sql);
                $id_post = $connection->insert_id;
    
                $sql = "INSERT INTO category VALUES (NULL, '$name', '$path', '$id_cate', 0, 1, 0, 1, '$href')";
    
                echo $sql;
                insert($connection, $sql);

            }

           

            echo "<br/>-------------------------------------------<br/>";
        }
    }

    function getCategoryRoot($category, $connection){
        for ($i = 1; $i < count($category)-1; $i++){
            $the_a = $category[$i]->find("a",0);
            // Xu ly the a
            $name = $the_a->text();
            $href = $the_a->href;
            $path = basename($href);
            echo "Name cap 1: ".$name." ----------  Href: ".$path;
            echo "<br/>";

            // //Cap 2
            $html2 = file_get_html($href);
            $namePost = $html2->find(".entry-title", 0)->text();

            $content = $html2->find(".entry-content", 0);
            $content->find(".yarpp-related", 0)->outertext = "";
            $content->find(".kk-star-ratings", 0)->outertext = "";
            $content->find(".blog-share", 0)->outertext = "";
            $content->find("script", 0)->outertext = "";
            
            echo $namePost;
            echo "<br>";
            echo $content;

            $chitiet_UTF8  = htmlentities($content, ENT_COMPAT, "UTF-8");

            $sql = "INSERT INTO post VALUES (NULL, '$namePost', '$chitiet_UTF8', 1)";

            echo $sql;
            insert($connection, $sql);
            $id_post = $connection->insert_id;

            $sql = "INSERT INTO category VALUES (NULL, '$name', '$path', '$id_post', 0, 1, 0, 1, '$href')";

            echo $sql;
            insert($connection, $sql);

            echo "<br/>-------------------------------------------<br/>";
        }
    }
    function insert( $connection,$query){
        $result = mysqli_query($connection,$query);
        echo $result;
    }
?>