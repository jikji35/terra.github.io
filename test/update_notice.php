<?php
// 데이터베이스 연결 정보
$host = 'sql12.freemysqlhosting.net';
$dbname = 'sql12622736';
$username = 'sql12622736';
$password = 'fXgnDPnXKi';


// PDO 객체 생성 및 데이터베이스 연결
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("데이터베이스 연결에 실패했습니다: " . $e->getMessage());
}

// 이미지 ID와 요약 텍스트 가져오기
if (isset($_POST['imageId']) && isset($_POST['summary'])) {
  $imageId = $_POST['imageId'];
  $summary = $_POST['summary'];

    // 데이터베이스에 텍스트 업데이트
    $stmt = $pdo->prepare("UPDATE images SET notice = ? WHERE idx = ?");
    $stmt->bindParam(1, $summary);
    $stmt->bindParam(2, $imageId);
    if ($stmt->execute()) {
        echo "텍스트가 성공적으로 업데이트되었습니다.";
    } else {
        echo "이미지 ID와 요약 텍스트를 받아오지 못했습니다..";
    }

}

?>
