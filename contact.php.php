<?php
// フォームからデータが送られてきたかチェック
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // データの取得（XSS対策でhtmlspecialcharsを使用）
    $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8');
    $message = htmlspecialchars($_POST['message'], ENT_QUOTES, 'UTF-8');
    $date = date("Y-m-d H:i:s");

    // 保存するデータの配列
    $data = [$date, $name, $email, $message];

    // CSVファイルを追記モードで開く（ファイルがなければ作成される）
    $file = fopen('contacts.csv', 'a');

    // Windows環境でも文字化けしないよう、必要に応じて変換（AzureはLinux環境が多いのでUTF-8のままでも可）
    // fputcsv($file, $data); 
    
    // 日本語対応を確実にするためBOM付きUTF-8やSJISに変換することもありますが、
    // 基本はそのまま書き込みます
    fputcsv($file, $data);

    fclose($file);

    // 送信完了後に元のページに戻る（クエリパラメータで成功を伝える）
    header("Location: index.html?status=success#contact");
    exit;
} else {
    // 直接アクセスされた場合はトップへ
    header("Location: index.html");
    exit;
}
?>