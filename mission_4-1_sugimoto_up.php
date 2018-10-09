<?php
//定義
$name = ( isset( $_POST['name'] ) === true ) ?$_POST['name']: "";
$comment = ( isset( $_POST['comment'] ) === true ) ? trim($_POST['comment']) : "";
$pass = ( isset($_POST['pass']) === true ) ?$_POST['pass']: "";
$date = date("Y/m/d H:i:s");
$delete = ( isset($_POST['delete']) === true ) ?$_POST['delete']: "";
$edit = ( isset($_POST['edit']) === true ) ?$_POST['edit']: "";
//編集用フォームへ表示する番号
$ednum = (isset($_POST["ednum"]) === true) ?$_POST["ednum"]: "";
//削除パス
$delpas = ( isset($_POST["delpas"]) === true ) ?$_POST["delpas"]: "";
//編集パス
$edpas = ( isset($_POST["edpas"]) === true ) ?$_POST["edpas"]: "";
$err_msg1 = "";
$err_msg2 = "";
$err_msg3 = "";
$err_msg4 = "";
$err_msg5 = "";
$err_msg6 = "";
$err_msg7 = "";
$err_msg8 = "";
$err_msg9 = "";
$message = "";
$message1 = "";
$message2 = "";
$message3 = "";

//データベース接続
$dsn  =  'データベース名';
$user  =  'ユーザー名';
$password  =  'パスワード';
try{
	$pdo  =  new  PDO($dsn,$user,$password);
	array(PDO::ATTR_EMULATE_PREPARES => false);
} catch (PDOException $e) {
 exit('データベース接続失敗。'.$e->getMessage());
}
//テーブルつくる
//$sql = "CREATE TABLE IF NOT EXISTS `DATA`"
//."("
//. "`num` INT auto_increment primary key,"
//. "`name` TEXT,"
//. "`comment` TEXT,"
//. "`pass` TEXT,"
//. "`date` DATETIME"
//.");";
//$stmt = $pdo->query($sql);
//$stmt -> execute();

//register to database
//if pushed 'send' button
if( isset($_POST['send']) && empty($_POST["ednum"]) === true ){
	if ($name === "") $err_msg1= "名前を入力してください";
	if( $comment === "") $err_msg2= "コメントを入力してください";
	if( $pass === "") $err_msg3= "パスワードを入力してください";
	//エラーメッセージが出ないとき、という処理
	if( $err_msg1 === "" && $err_msg2 === "" && $err_msg3 === "" ){	
		$sql = $pdo -> prepare( "INSERT INTO DATA (num, name, comment, pass, date) VALUES (:num, :name, :comment, :pass, :date)");
		$sql  ->  bindParam(':num',  $num,  PDO::PARAM_STR);
		$sql  ->  bindParam(':name',  $name,  PDO::PARAM_STR);
		$sql  ->  bindParam(':comment',  $comment,  PDO::PARAM_STR);
		$sql  ->  bindParam(':pass',  $pass,  PDO::PARAM_STR);
		$sql  ->  bindParam(':date',  $date,  PDO::PARAM_STR);
		$sql -> execute();
		$message =$name."さん ご入力ありがとうございます。"."<br>".date("Y年m月d日 H時i分s秒")."に「".$comment."」を受け付けました。";
	}
}

//delete
if( isset($_POST['delsend'])=== true ){
	if( $delete === "") $err_msg4 = "削除番号を入力してください";
	if( $delpas === "") $err_msg5 = "パスワードを入力してください";
	if( $err_msg4 === "" && $err_msg5 === "" ) {
		//データベース→番号とパスワードを調べて一致したら削除する
		$sql = "SELECT * FROM DATA ";
		$result = $pdo->query($sql);
		foreach($result as $row) {
			if($row["num"] === $delete && $row["pass"] === $delpas ){						
				$sql = "delete from DATA where num = $delete "; 
				$result = $pdo->query($sql);
				$message1 = $delete."の投稿が削除されました。" ;
			}
			else{
				$err_msg8 = "パスワードが正しくありません" ;
			}
		}
	}
}

//edit
if( isset($_POST['edsend'])=== true ){
	if( $edit === "") $err_msg6 = "編集番号を入力してください";
	if( $edpas === "") $err_msg7 = "パスワードを入力してください";
	if( $err_msg6 === "" && $err_msg7 === "" ) {
		//番号とパスワードとデータベースの番号が一致したらフォーム欄に表示させる
		$sql = "SELECT * FROM DATA ";
		$result = $pdo->query($sql);
		foreach($result as $row) {
			 if($row["num"] === $edit && $row["pass"] === $edpas ){
		 		$editrue = $edit;
		 		$enam = $row["name"];
				$ecom = $row["comment"];
				$epas = $row["pass"];
				$message2 = "編集してください";	
		 	}
		 	else{
		 		$err_msg9 = "パスワードが正しくありません";
		 	}
		}
	}
}
//そしたらその編集対象を編集する
if( isset($_POST['send']) && !empty($_POST['ednum']) === true ) {
	$sql = "update  DATA  set  name= '$name'  ,  comment= '$comment'  ,  pass= '$pass'  WHERE  num  =  $ednum";
	$result  =  $pdo->query($sql);
	$message3 = "投稿が編集されました。";
}
?>

<!DOCTYPE HTML>
<html lang="ja">
	<head>
		<meta charset="utf-8">
		<title>mission_4_1</title>
	</head>
	<body>
		<h1>Mission4</h1><br>
		※パスワードは他サイトで使っているものにはしないでください<br>
		<?php echo $message2; ?>
		<!-- 新規投稿 -->
		<form action="" method="post">
			<input type= "text" name="name" size="30"  value="<?php echo $enam; ?>" placeholder="名前">
			<?php echo $err_msg1; ?> <br>
			<input type= "text" name="comment" size="30"  value="<?php echo $ecom; ?>" placeholder="コメント">
			<?php echo $err_msg2; ?> <br>
			<input type= "text" name="pass" size="30"  value="<?php echo $epas; ?>" placeholder="パスワード">
			<?php echo $err_msg3; ?> <br>
			<input type="submit" name="send" value="送信">
			<br>
		<!-- 編集用フォーム -->
			<input type= "hidden" name="ednum" size="30"  value="<?php echo $editrue; ?>" placeholder="編集対象番号">
			<br>
		<!-- 削除用 -->
		<form action="" method="post">
			<input type= "text" name="delete" size="15" placeholder="削除対象番号">
			<?php echo $err_msg4; ?> <br>
			<input type= "text" name="delpas" size="30" placeholder="パスワード">
			<?php echo $err_msg5; ?> <br>
			<input type="submit" name="delsend" value="削除">
			<br><br>
		</form>
		<!-- 編集用 -->
		<form action="" method="post">
			<input type= "text" name="edit" size="15" placeholder="編集対象番号">
			<?php echo $err_msg6; ?> <br>
			<input type= "text" name="edpas" size="30" placeholder="パスワード">
			<?php echo $err_msg7; ?> <br>
			<input type="submit" name="edsend"value="編集">
			 <br>
		</form>
	<?php echo $message; ?>
	<?php echo $message1; ?>
	<?php echo $message3; ?>
	<br><br>
		<?php
			//データベース接続
			$dsn  =  'mysql:dbname=tt_463_99sv_coco_com;host=localhost;charset=utf8';
			$user  =  'tt-463.99sv-coco';
			$password  =  'Rk8FEYJA';
			try{
				$pdo  =  new  PDO($dsn,$user,$password);
				array(PDO::ATTR_EMULATE_PREPARES => false);
			} catch (PDOException $e) {
			 exit('データベース接続失敗。'.$e->getMessage());
			}

			//show the table
			$sql = "SELECT * FROM DATA ORDER BY num ASC";
			$results = $pdo -> query($sql);
			foreach ($results as $row){
				//$rowの中にはテーブルのカラム名が入る
				echo  $row['num'].' ';
				echo  $row['name'].' '; 
				echo  $row['comment'].' ';
			//	echo  $row['pass'].' ';
				echo  $row['date'].'<br>'; 

			}

		?>

	</body>
</html>