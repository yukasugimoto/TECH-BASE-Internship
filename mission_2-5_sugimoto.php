<?php
//最初に変数を定義
$filename = 'mission_2-5_sugimoto.txt' ;
//A?B:C AがtrueのときはBを実行、falseはCを実行
$name = ( isset( $_POST["name"] ) === true ) ?$_POST["name"]: "";
$comment  = ( isset( $_POST["comment"] )  === true ) ?  trim($_POST["comment"])  : "";
//trim — 文字列の先頭および末尾にあるホワイトスペースを取り除く
$date = date("Y/m/d H:i:s") ;
$txt = "<>".$name."<>".$comment."<>".$date;
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
//削除
$delete = ( isset($_POST["delete"]) === true ) ?$_POST["delete"]: "";
//編集番号
$edit = (isset($_POST["edit"]) === true ) ?$_POST["edit"]: "";
//編集用フォームへ表示する番号
$ednum = (isset($_POST["ednum"]) === true) ?$_POST["ednum"]: "";
//パスワード
$pass = ( isset($_POST["pass"]) === true ) ?$_POST["pass"]: "";
//削除パス
$delpas = ( isset($_POST["delpas"]) === true ) ?$_POST["delpas"]: "";
//編集パス
$edpas = ( isset($_POST["edpas"]) === true ) ?$_POST["edpas"]: "";


//投稿がある場合のみの処理
if( isset($_POST["send"]) && empty($_POST["ednum"]) === true ){
	if ($name === "") $err_msg1= "名前を入力してください";
	if( $comment === "") $err_msg2= "コメントを入力してください";
	if( $pass === "") $err_msg5= "パスワードを入力してください";
	//エラーメッセージが出ないとき、という処理
	if( $err_msg1 === "" && $err_msg2 === "" && $err_msg5 === "" ){
		$fp = fopen($filename, "a" );
		//ファイルの行数をカウントする
		$cnt = count(file($filename));
		$cnt++;
		fwrite($fp, $cnt.$txt."<>".$pass."<>"."\n");
		fclose($fp);
		$message =$name."さん ご入力ありがとうございます。"."<br>".date("Y年m月d日 H時i分s秒")."に「".$comment."」を受け付けました。";
	}
	
}

//削除番号が送信されたとき
if( isset($_POST["delsend"] )=== true) {
	if( $delete === "") $err_msg3 = "削除番号を入力してください";
	if( $delpas === "") $err_msg6 = "パスワードを入力してください";
	if( $err_msg3 === "" && $err_msg6 === "" ) {
		//かつ　$delpasがテキストファイルの中のパスと一致するとき
		//テキストファイル内のパスワードが一致したら
		$filearr = file($filename);
		$fo = fopen($filename, "r");
		foreach ($filearr as $value){
			$content = explode("<>", $value);
			$passname = $content[4];
			if( $passname !== $delpas ) $err_msg8 = "パスワードが正しくありません" ;
			elseif( $passname === $delpas ) {	

				//ファイルを読みこみ配列へ
				$fd = file($filename);
				//ファイルを開く、上書き
				$fp = fopen($filename, 'w');
				//行数を数える
				$cnt = count($fd);
				$num = 0;
				//ループ、
				for( $i=0;$i<$cnt;$i++ ) {
					//投稿番号を区切る
					$fe = explode("<>", $fd[$i]);	
					//0が番号、1名前、2コメント、3ひづけ、4パス
					$ff = $fe[1]."<>".$fe[2]."<>".$fe[3]."<>".$fe[4]."<>"."\n";
					//行数と削除番号が一致しないとき書き込む
					if($i+1 != $delete){		
						$num++;
						fwrite($fp, $num."<>".$ff);
						//それ以外のときはスキップ
					}else{
							continue;
					}
				} //for
				fclose($fp);
				$message1 = $delete."の投稿が削除されました。" ;

			} //passのif
			

		} //passのforeach

	} //errmsg
} //isset

//編集番号が送信されたとき「編集選択」
if( isset($_POST["edsend"]) === true) {
	if( $edit === "") $err_msg4 = "編集番号を入力してください";
	if( $edpas === "") $err_msg7 = "正しいパスワードを入力してください";
	if( $err_msg4 === "" && $err_msg7 === "" ) {
		//かつ　$edpasがテキストファイルの中のパスと一致するとき
		//テキストファイル内のパスワードが一致したら
		$filearr = file($filename);
		$fo = fopen($filename, "r");
		foreach ($filearr as $value){
			$content = explode("<>", $value);
			$passname = $content[4];	
			if( $passname === $edpas ) {
				$editrue = $edit;
				//配列化して読み込む
				$are = file($filename);
				//配列の数を取得
				$cnt = count($are);
				//配列の数だけループさせる
				for( $i=0;$i<$cnt;$i++ ) {
					$data = explode("<>", $are[$i]);
					//投稿番号と削除番号がイコールの時のname&comment値は
					if($i+1 == $edit){
						//フォーム欄に表示させる
						$enam = $data[1];
						$ecom = $data[2];
						$epas = $data[4];
					}//編集番号送信のif
				}//for
				$message2 = "編集してください";
			} //passif
			else{
				$err_msg9 = "パスワードが正しくありません";
			}

		} //passforeachif
	} //errmsg if
}
	//編集フォームに表示されたとき「書き込み」
	if( isset($_POST["send"]) && !empty($_POST["ednum"]) === true ) {
		//ファイルの中身を取り出す
		$arf = file($filename);
		//配列の数を取得
		$cnt = count($arf);
		$num = $ednum-1;
		//ファイルを開く
		$fp = fopen($filename, 'w');
		//配列の数だけループさせる
		for($i=0; $i<$cnt; $i++) {
			//投稿番号を取り出す
			$adata = explode("<>", $arf[$i]);
			$arg = $adata[0]."<>".$adata[1]."<>".$adata[2]."<>".$adata[3]."<>".$adata[4]."<>"."\n"; 
			$arh = $adata[0]."<>".$name."<>".$comment."<>".$date."<>".$pass."<>"."\n";
			//テキストの値と投稿番号を比較
			//同じとき書き換える
			if($num == $i){
				fwrite($fp, $arh);
			//違うときそのまま上書き
			}else{
				fwrite($fp, $arg);
			}
		}//for if	
				fclose($fp);
		$message3 = "投稿が編集されました。";
	}//最初if
//ちゃんとバックアップとっておこうね。。

?>

<!DOCTYPE HTML>
<html lang="ja">
	<head>
		<meta charset="utf-8">
		<title>misson_2_5</title>
	</head>
	<body>
		<h1>みっしょん２の５</h1><br>
		※（完璧ではないけど）できました＼(^o^)／<br>
		※パスワードは他サイトで使っているものにはしないでください<br>
		<?php echo $message2; ?>
		<!-- 新規投稿名前とコメントとパスワード -->
		<form action="" method="post">
			<input type= "text" name="name" size="30" value="<?php echo $enam; ?>" placeholder="名前">
			<?php echo $err_msg1; ?> <br>
			<input type= "text" name="comment" size="30" value="<?php echo $ecom; ?>" placeholder="コメント">
			<?php echo $err_msg2; ?> <br>
			<input type= "text" name="pass" size="30" value="<?php echo $epas; ?>" placeholder="パスワード">
			<?php echo $err_msg5; ?> <br>
			<input type="submit" name="send" value="送信"><br>
		<!-- 編集用フォーム -->
			<input type= "hidden" name="ednum" size="30" value="<?php echo $editrue; ?>" placeholder="編集対象番号"> <br>
		</form>		
		<!-- 削除用 -->
		<form action="" method="post">
			<input type= "text" name="delete" size="15" value="<?php echo $delete; ?>" placeholder="削除対象番号">
			<?php echo $err_msg3; ?> <br>
			<input type= "text" name="delpas" size="30" value="<?php echo $delpas; ?>" placeholder="パスワード">
			<?php echo $err_msg6; ?> <br>
			<input type="submit" name="delsend" value="削除">
			<br><br>
		</form>
		<!-- 編集用 -->
		<form action="" method="post">
			<input type= "text" name="edit" size="15" value="<?php echo $edit; ?>" placeholder="編集対象番号"> 
			<?php echo $err_msg4; ?> <br>
			<input type= "text" name="edpas" size="30" value="<?php echo $edpas; ?>" placeholder="パスワード">
			<?php echo $err_msg7; ?>
			<br>
			<input type="submit" name="edsend"value="編集">
			 <br>
		</form>
	<?php echo $message; ?>
	<?php echo $message1; ?>
	<?php echo $message3; ?>
	<br><br>

		<?php
		//ブラウザに表示
		//file関数で読み込む
		$array = file($filename);
		//配列をループで読み込む
		for($i=0; $i < count($array); $i++){
			$exp = explode("<>", $array[$i]);
			$arr = array(
				"number"=>$exp[0],
				"name"=>$exp[1],
				"comment"=>$exp[2],
				"date"=>$exp[3]
				);
				echo $arr["number"]." ".$arr["name"]." ".$arr["comment"]." ".$arr["date"]."<br>";
		}



		?>

	</body>
</html>