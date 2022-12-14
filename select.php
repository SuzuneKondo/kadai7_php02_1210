<?php

//XSS対策
//hにscriptで入力された時の悪さを防ぐ大事
function h($str){
    return htmlspecialchars($str, ENT_QUOTES);
}



//1.  DB接続します
//DBに接続するおまじない🤗
try {
  //ID:'root', Password: xamppは 空白 ''
  $pdo = new PDO('mysql:dbname=gs_kadai_db;charset=utf8;host=localhost','root','');
} catch (PDOException $e) {
  exit('DBConnectError:'.$e->getMessage());
}

//２．データ取得SQL作成//データを抜き出す部分
//抜き出すだけなのでバインド変数はいらない
$stmt = $pdo->prepare("SELECT*FROM gs_bm_table;");//用意
$status = $stmt->execute();//実行

//３．データ表示
$view="";
if ($status==false) {
    //execute（SQL実行時に失敗してエラーがある場合）
  $error = $stmt->errorInfo();
  exit("ErrorQuery:".$error[2]);

}else{
  //elseの中はSQL成功した場合
  //Selectデータの数だけ自動でループしてくれる
  //FETCH_ASSOC=http://php.net/manual/ja/pdostatement.fetch.php
  while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){//DBから一行取って来ている、resultには１行入ってる
    //=前の.が追加の意、これがないとどんどん上書きになってしまう（１個しかデータ出てこない）
    $view .= '<tr><td class="result-data">' . $result['id'] . '</td><td class="result-data">' . h($result['name']). '</td><td class="result-data">' . h($result['url']). '</td><td class="result-data">' .h($result['comment']) . '</td></tr>' ;
  }

}
?>


<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/reset.css">
<title>ブックマーク一覧</title>
</head>

<body id="main">
<!-- Head[Start] -->
<header>
  <nav class="navbar navbar-default">
    <div class="container-fluid">
      <div class="navbar-header">
      <a class="navbar-brand" href="index.php">データ登録</a>
      </div>
    </div>
  </nav>
</header>
<!-- Head[End] -->

<!-- Main[Start] -->
<main>
  <h1 class="form-title">登録一覧</h1>
  <div class="result-box-flame">
    <table border="1" class="result-flame">
      <tr>
          <th class="ttt">ID</th>
          <th class="ttt">名前</th>
          <th class="ttt">URL</th>
          <th class="ttt">コメント</th>
      </tr>
      <tr><?= $view ?></tr>
      <!-- <tr>
        <label>
        <input type="checkbox" name="level" value="ok">
        完全に理解した
        </label>
        <label>
        <input type="checkbox" name="level" value="bad">
        全然分からない
        </label>
      </tr> -->
  </div>
</main>
<!-- Main[End] -->

</body>
</html>
