
<!-- 1. 이페이지는 영수증 사진보기를 업로드(관리자),편집(관리자),열람페이지(회원) http://localhost/account_book/images_view.php 를 시작한다. ===> images_upload.php(사진입력) ==> images_view_edit.php(사진편집) ==> images_view.php(사진공개 열람)
2. 데이타베이스에서 사용내역서는 수입관련 테이블(income_table)/지출관련 테이블(expense_table)을 사용하고있고, 영수증사진 관련테이블은 images 이다. 
3. 이 데이타는 account_book.php, account_book_1.php, account_view.php파일과 서로 연결되어있다.  -->

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

// 파일 저장 경로
$uploadDir = 'data/profile/';

// 이미지 업로드 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // 이미지 파일이 있는지 확인
  if (!empty($_FILES['photo']['name'])) {
    $filename = $_FILES['photo']['name'];
    $tmpFilePath = $_FILES['photo']['tmp_name'];
    $newFilePath = $uploadDir . $filename;

    // 이미지 파일의 해시 값을 생성
    $imageHash = md5_file($tmpFilePath);

    // 세션에 이미지 해시 값이 존재하는지 확인
    session_start();
    if (isset($_SESSION['uploaded_images']) && in_array($imageHash, $_SESSION['uploaded_images'])) {
      echo "이미지가 중복으로 업로드되었습니다.";
      exit;
    }

    // 이미지 파일을 지정된 폴더로 이동
    if (move_uploaded_file($tmpFilePath, $newFilePath)) {
      // 데이터베이스에 이미지 파일 경로와 날짜 저장
      $currentDate = date('Y-m-d H:i:s'); // 현재 날짜와 시간
      $stmt = $pdo->prepare("INSERT INTO images (photo, date) VALUES (?, ?)");
      $stmt->bindParam(1, $newFilePath);
      $stmt->bindParam(2, $currentDate);
      if ($stmt->execute()) {
        // 이미지가 성공적으로 업로드되었을 때 세션에 이미지 해시 값을 추가
        $_SESSION['uploaded_images'][] = $imageHash;
        // 이미지가 성공적으로 업로드되었을 때 리다이렉션 처리
        header("Location: images_view_edit.php");
        exit;
      } else {
        echo "이미지 업로드 중 오류가 발생했습니다.";
      }
    } else {
      echo "이미지 업로드 중 오류가 발생했습니다.";
    }
  } else {
    echo "이미지를 선택해주세요.";
  }
}

?>


<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>이미지 업로드</title>
  <style>
    body {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background-color: #f5f5f5;
    }

    .upload-container {
      width: 500px;
      height: 600px;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .upload-form {
      width: 80%;
      padding: 50px;
      border: 1px solid #ccc;
      border-radius: 10px;
      background-color: #fff;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    body > div.upload-container > form > input[type=submit]:nth-child(2) {
      margin-top: 20px;
    }

    .upload-form input[type="file"] {
      margin-bottom: 10px;
    }

    .upload-form input[type="submit"] {
      padding: 10px 20px;
      margin-top: 40px;
      background-color: #4CAF50;
      color: white;
      border: none;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <div class="upload-container">
    
    <form class="upload-form" action="" method="POST" enctype="multipart/form-data">
    <h2>이미지 입력</h2>
      <input type="file" name="photo" accept="image/*">
      <input type="submit" value="업로드">
    </form>
  </div>
</body>
</html>
