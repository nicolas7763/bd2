<?php
$pdo = new PDO("mysql:host=localhost;dbname=zhukov", "zhukov", "neto0626");
$pdo->query("SET NAMES UTF8");
if (isset($_GET['del_id'])) {//проверяем, есть ли переменная,усли есть удаляем
    $a=$_GET['del_id'];
    $pdo->exec("DELETE FROM tasks  WHERE id = $a LIMIT 1");
}

if (isset($_GET['red_id'])) { //Проверяем, передана ли переменная на редактирования
        if (isset($_POST['description'])) { //Если новое имя предано, то обновляем и имя и цену
            $stmt = $pdo->prepare("UPDATE tasks set description = :description WHERE id=:id LIMIT 1 ");
$stmt->bindParam(':description', $description);
$stmt->bindParam(':id', $id);
$description = $_POST['description'];
$id = $_GET['red_id'];
$stmt->execute();
header("Location: bd2.php");
        }
    }
    
//добавляем
if (isset($_POST["save"]) and ($_POST['description'])!=''){

   $stmt = $pdo->prepare("INSERT INTO tasks (description, date_added) VALUES (:description, :date_added)");
$stmt->bindParam(':description', $description);
$stmt->bindParam(':date_added', $date_added);
$description = $_POST["description"];
$date_added= date("Y-m-d H:i:s");
$stmt->execute();
header("Location: bd2.php");
}

//достаем данные
$sql = "SELECT id, description, is_done, date_added FROM tasks";
echo '<table cellpadding="5" cellspacing="0" border="1">';
echo "<tr>";
foreach ($pdo->query($sql) as $row) {
   
    echo "<td>".$row['id']."</td>";
    echo "<td>".$row['description']."</td>";
    echo "<td>".$row['is_done']."</td>";
    echo "<td>".$row['date_added']."</td>";
    echo '<td><a href="?del_id='.$row['id'].'">Удалить</a> <a href="?red_id='.$row['id'].'">Изменить</a></td>';
     echo "</tr>";  
}
echo "</table>";

if (isset($_GET['red_id'])) { //Если передана переменная на редактирование
        //Достаем запсись из БД
    $c = $_GET['red_id'];
    //var_dump($c);
        $sql = "SELECT id, description, is_done, date_added FROM tasks WHERE id =$c"; //запрос к БД
        foreach ($pdo->query($sql) as $row) {
           
        } //получение самой записи
?>
<form method="POST">
        
        <input type="text" name="description" placeholder="Описание задачи" value="<?php echo ($row['description']); ?>" />
        <input type="submit" name="sand" value="Изменить" />
    </form>
<?php
}
?>

<div style="float: left">
    <form method="POST">
        <input type="text" name="description" placeholder="Описание задачи" value="" />
        <input type="submit" name="save" value="Добавить" />
    </form>
</div>

