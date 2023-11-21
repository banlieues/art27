<?php include_once("config.php"); ?>
<?php include_once("mysqli.php"); ?>

<?php 
/** 
 * post.php v0.1 by djphil (CC-BY-NC-SA 4.0) 
**/
if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    $name = $_POST['name'] ?? null;

    if ($name === "batchprocess")
    {
        $size = trim($_POST['size']) ?? 0;
        $step = trim($_POST['step']) ?? 0;

        $select = "id, name, url_file, contentByte, contentByte_Type";
        $sql = "SELECT $select FROM $tbname WHERE  (contentByte <>'' OR contentByte <>' ' OR contentByte IS NULL) LIMIT $size OFFSET ".($step * $size)."";
        $result = mysqli_query($db, $sql);
        $num_rows = mysqli_num_rows($result);
       
        if ($num_rows > 0)
        {
            $datas = [];

            while ($row = mysqli_fetch_assoc($result))
            {
                $id = trim($row['id']);
                
                $name = $id.'_'.slugify_name_file(trim($row['name']));
                if(!empty($row['url_file']))
                {
                    $name = trim($row['url_file']);
                }

                $byte = trim($row['contentByte']);
                $type = trim($row['contentByte_Type']);
               
                $conv = "no";


                if (!empty($byte)&&!file_exists($path_final.$name))
                {
                    $conv = "yes";
                    file_put_contents($path.$name, base64_decode($byte, $strict), LOCK_EX);
                    

                }
                
                if (empty($type)) $type = "unknow";

                $sql_update="UPDATE $tbname SET contentByte='',contentByte_Type='',url_file='$name' WHERE id=$id";
                mysqli_query($db, $sql_update);
               
                $datas[] = [
                    "id" => $id, 
                    "name" => $name, 
                    "type" => $type,
                    "byte" => $conv,
                 ];
            }

            unset($result, $row);
            echo json_encode($datas);
        }
    }
}
?>
