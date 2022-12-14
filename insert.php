<?php

/**
 * 1. index.phpのフォームの部分がおかしいので、ここを書き換えて、
 * insert.phpにPOSTでデータが飛ぶようにしてください。
 * 2. insert.phpで値を受け取ってください。
 * 3. 受け取ったデータをバインド変数に与えてください。
 * 4. index.phpフォームに書き込み、送信を行ってみて、実際にPhpMyAdminを確認してみてください！
 */

//これはほぼテンプレです🤗

//1. POSTデータ取得

$name = $_POST['name'];
$url = $_POST['url'];
$comment = $_POST['comment'];

//2. DB接続します
//DBに接続するおまじない🤗
try {
  //ID:'root', Password: xamppは 空白 ''
  $pdo = new PDO('mysql:dbname=gs_kadai_db;charset=utf8;host=localhost','root','');
} catch (PDOException $e) {
  exit('DBConnectError:'.$e->getMessage());
}

//３．データ登録SQL作成

// 1. SQL文を用意
$stmt = $pdo->prepare("INSERT INTO gs_bm_table(id, name, url, comment, date)VALUES(NULL, :name, :url, :comment, sysdate()) ");

//  2. バインド変数を用意
// Integer 数値の場合 PDO::PARAM_INT
// String文字列の場合 PDO::PARAM_STR
//SQLインジェクションの記述🤗 直でinsertで＄--を読み込む形にしておくと、悪用上書きや全削除をされてしまう
//インサートの場合は書き込み作業なので危ない
//:をつけた箱に一度格納して、それを防ぐ部分。stmtはstatementの略

$stmt->bindValue(':name', $name, PDO::PARAM_STR);
$stmt->bindValue(':url', $url, PDO::PARAM_STR);
$stmt->bindValue(':comment', $comment, PDO::PARAM_STR);

//  3. 実行
$status = $stmt->execute();

//４．データ登録処理後
if($status === false){
  //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
  $error = $stmt->errorInfo();
  exit('ErrorMessage:'.$error[2]);//失敗したらここ
}else{
  //５．index.phpへリダイレクト//成功したらここ
  header('Location:index.php');
}
?>
