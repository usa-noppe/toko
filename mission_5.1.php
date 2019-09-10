<html>

<head>

<meta charset="utf-8">

<title>mission5</title>

</head>

<body>


<?php

ini_set('display_errors', 0);



//データベースに接続
$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));


//投稿内容を管理するテーブルを作成


$sql="CREATE TABLE IF NOT EXISTS toukou"
."("
	."id INT AUTO_INCREMENT PRIMARY KEY,"
	."name TEXT,"
	."comment TEXT,"
	."time TEXT,"
	."pass TEXT"
.");";


$stmt=$pdo->query($sql);//コマンド送信

$name=$_POST["name"];//名前欄
$comment=$_POST["comment"];//コメント欄
$time=date("Y年m月d日 H:i:s");//日付
$pass="summer";


//↓↓投稿フォームデータベースバージョン↓↓

//名前欄とコメント欄空じゃないときおよびhiddenが空の時およびパスワード欄から空じゃなくて正しい時
if(!empty($_POST["name"]) && !empty($_POST["comment"]) && empty($_POST["editnum"]) && !empty($_POST["pass"]) && $_POST["pass"] == $pass){

//データベースに格納
	$sql2 = $pdo -> prepare("INSERT INTO toukou (name, comment, time, pass) VALUES(:name, :comment, :time, :pass)");
	$sql2 -> bindParam(':name', $name, PDO::PARAM_STR);//name列に$nameを格納
	$sql2 -> bindParam(':comment', $comment, PDO::PARAM_STR);//comment列に$commentを格納
	$sql2 -> bindParam(':time', $time, PDO::PARAM_STR);//time列に日付格納
	$sql2 -> bindParam(':pass', $pass, PDO::PARAM_STR);//pass列にパスワード格納

	$sql2 -> execute();//コメント格納
	echo"投稿に成功しました";

}elseif(empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["pass"])){

	echo"入力に不備があります。すべての欄に入力してください。";

}elseif(!empty($_POST["name"]) && empty($_POST["comment"]) && !empty($_POST["pass"])){

	echo"入力に不備があります。すべての欄に入力してください。";

}elseif(!empty($_POST["name"]) && !empty($_POST["comment"]) && empty($_POST["pass"])){

	echo"入力に不備があります。すべての欄に入力してください。";

}elseif(!empty($_POST["name"]) && empty($_POST["comment"]) && empty($_POST["pass"])){

	echo"入力に不備があります。すべての欄に入力してください。";

}elseif(empty($_POST["name"]) && !empty($_POST["comment"]) && empty($_POST["pass"])){

	echo"入力に不備があります。すべての欄に入力してください。";

}elseif(empty($_POST["name"]) && empty($_POST["comment"]) && !empty($_POST["pass"])){

	echo"入力に不備があります。すべての欄に入力してください。";

}elseif(!empty($_POST["name"]) && !empty($_POST["comment"]) && empty($_POST["editnum"]) && !empty($_POST["pass"]) && $_POST["pass"] != $pass){

	echo"パスワードが違います。もう一度入力してください。";

}

//↓↓削除機能データベースバージョン↓↓

//削除欄とパスワード欄が空じゃないとき
if(!empty($_POST["delete"]) && !empty($_POST["pass_delete"])){

	$sql3="SELECT * FROM toukou";//編集したい番号の名前とコメントを取得
	$stmt2=$pdo->query($sql3);
	$results = $stmt2->fetchAll();//取得内容をすべて配列に格納fetchAll
	foreach($results as $row){
//データベース内ループ中投稿番号と削除対象番号が等しい時かつパスワードも等しい時
		if($_POST["delete"] == $row["id"] && $_POST["pass_delete"] == $row["pass"]){

			$id=$_POST["delete"];//削除したい番号
			$sql4='delete from toukou where id=:id';
			$stmt3=$pdo->prepare($sql4);
			$stmt3 -> bindParam(':id', $id, PDO::PARAM_INT);
			$stmt3 -> execute();
		}elseif($_POST["delete"] == $row["id"] && $_POST["pass_delete"] != $row["pass"]){//パスワードが一致しないとき

			echo"パスワードが違います";

		}
	}
}elseif(!empty($_POST["delete"]) && empty($_POST["pass_delete"])){//パスワード入力されていないとき

	echo"パスワードを入力してください";

}
//編集選択機能

//編集番号欄とパスワード欄が空ではないとき
if(!empty($_POST["edit"]) == !empty($_POST["pass_edit"])){

	$sql5="SELECT * FROM toukou";//データベース上の内容を取得
	$stmt4=$pdo->query($sql5);
	$results = $stmt4->fetchAll();//取得内容をすべて配列に格納fetchAll
	foreach($results as $row){
//データベース内ループ中、投稿番号と編集番号が等しい時 かつ パスワードも等しい時
		if($_POST["edit"] == $row["id"] && $_POST["pass_edit"] == $row["pass"]){
//名前とコメント取得
			$edit_num=$row["id"];
			$edit_name=$row["name"];
			$edit_comment=$row["comment"];
		}elseif($_POST["edit"] == $row["id"] && $_POST["pass_edit"] != $row["pass"]){

			echo"パスワードが違います";
		
		}
	}
}elseif(!empty($_POST["edit"]) && empty($_POST["pass_edit"])){//パスワード入力されていないとき

	echo"パスワードを入力してください";

}

//↓↓編集機能データベースバージョン↓↓

//名前欄、コメント欄およびhiddenの欄すべてが空ではないときに実行
if(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["editnum"]) && !empty($_POST["pass"]) && $_POST["pass"] == $pass){

	$id=$_POST["editnum"];//編集する番号
	$name_edit=$_POST["name"];//編集したい名前
	$comment_edit=$_POST["comment"];//編集したいコメント
	$sql6='update toukou set name=:name,comment=:comment where id=:id';
	$stmt5=$pdo->prepare($sql6);
	$stmt5->bindParam(':name', $name_edit, PDO::PARAM_STR);
	$stmt5->bindPARAM(':comment', $comment_edit, PDO::PARAM_STR);
	$stmt5->bindPARAM(':id', $id, PDO::PARAM_INT);
	$stmt5->execute();

}

?>
<br>
【入力フォーム】<br>
<form action="mission_5.php" method="post">
<input type="text" name="name" placeholder="名前" value="<?php echo $edit_name; ?>"><br>
<input type="text" name="comment" placeholder="コメント" value="<?php echo $edit_comment; ?>"><br>
<input type="hidden" name="editnum"  value="<?php echo $edit_num; ?>">
<input type="password" name="pass" placeholder="パスワード">
<input type="submit" value="送信"><br>
【削除フォーム】<br>
<form action="mission_5.php" method="post">
<input type="text" name="delete" placeholder="削除対象番号"><br>
<input type="password" name="pass_delete" placeholder="パスワード">
<input type="submit" value="削除"><br>
【編集フォーム】<br>
<form action="mission_5.php" method="post">
<input type="text" name="edit" placeholder="編集対象番号"><br>
<input type="password" name="pass_edit" placeholder="パスワード">
<input type="submit" value="編集"><br>

</form>


<?php

//データベースに接続
$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));



//最終的なテーブルに格納されたものをブラウザに表示
$sql6 = 'SELECT * FROM toukou';
$stmt5 = $pdo -> query($sql6);
$results = $stmt5 -> fetchAll();
foreach($results as $row2){
	echo $row2['id'].' '.$row2['name'].' '.$row2['comment'].' '.$row2['time'].'<br>';
}

?>

</body>

</html>
