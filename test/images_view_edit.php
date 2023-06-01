<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="친목회 지출내역" />
  <meta name="format-detection" content="telephone=no">
  <title>영수증 이미지편집</title>
  <link rel="icon" href="./favicon/favicon.ico" />
	
  <!-- (아래) manifest.json파일 ~ 앱스토어에 등록하지 않아도 사용 가능: 사용자가 웹 페이지를 방문하면, 바로 사용할 수 있습니다. 앱스토어에 등록하지 않아도 되므로, 앱 설치 및 업데이트에 대한 제약이 없습니다.-->
  <!--    홈 화면에 설치 가능: 사용자가 앱 아이콘을 홈 화면에 추가할 수 있습니다. 이를 통해, 마치 네이티브 앱처럼 사용이 가능합니다.-->
  <link rel="apple-touch-icon" sizes="32x32" href="./favicon/apple-icon-32x32.png">
  <link rel="apple-touch-icon" sizes="57x57" href="./favicon/apple-icon-57x57.png">
  <link rel="icon" type="image/png" sizes="36x36"  href="./favicon/android-icon-36x36.png">
  <link rel="icon" type="image/png" sizes="48x48"  href="./favicon/android-icon-48x48.png">
  <link rel="icon" type="image/png" sizes="16x16" href="./favicon/favicon-16x16.png">
  <link rel="manifest" href="./favicon/manifest.json">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="theme-color" content="#ffffff">
  
    
  <style>
    body {
      margin: 0;
      padding: 0;
      overflow-x: auto;
    }
    
    table {
        border-collapse: collapse;
        margin-top: 10px;
        width: 100%;
    }

    th, td {
        border: 1px solid black;
        padding: 10px;
        text-align: center; /* 셀 안의 내용을 가로로 중앙 정렬 */
    }

    img {
        width: 200px;
        height: 150px;
        object-fit: cover; /* 이미지 비율 유지 */
        display: block; /* 이미지를 블록 요소로 설정하여 수직 정렬 적용 */
        margin: 0 auto; /* 이미지를 가로로 중앙 정렬 */
    }

    button.btn_mem_delete {
      cursor: pointer;
    }

    .btn {
        display: inline-block;
        padding: 5px 10px;
        margin-right: 5px;
        border: 1px solid #ccc;
        text-decoration: none;
        color: #333;
    }
    .btn.active {
        background-color: #ccc;
    }


    .summary-input {
        width: 100%;
        height: calc(100% - 20px); /* 셀의 높이에서 상하 여백을 제외한 값으로 조정 */
        box-sizing: border-box;
    }


    @media (max-width: 580px) {
        img {
            max-width: 100%;
            height: auto;
        }
        * {
            font-size: 14px;
        }
    }


  </style>

  
  <script>
    document.addEventListener("DOMContentLoaded", function() {
  var deleteButtons = document.querySelectorAll(".btn_mem_delete");
  var summaryInputs = document.querySelectorAll(".summary-input");

  summaryInputs.forEach(function(input) {
    input.addEventListener("change", function() {
      var imageId = this.getAttribute("data-idx");
      var summary = this.value;

      // AJAX 요청으로 텍스트를 DB의 notice 필드에 보내기
      var xhr = new XMLHttpRequest();
      xhr.open("POST", "update_notice.php", true);
      xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
          console.log("텍스트가 성공적으로 업데이트되었습니다.");
        }
      };
      xhr.send("imageId=" + imageId + "&summary=" + summary);
    });
  });

  deleteButtons.forEach(function(button) {
    button.addEventListener("click", function() {
      var imageId = this.getAttribute("data-idx");
      var confirmation = confirm("이미지를 삭제하시겠습니까?");
      if (confirmation) {
        // AJAX 요청으로 이미지 삭제 처리
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "delete_image.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
          if (xhr.readyState === 4 && xhr.status === 200) {
            // 삭제가 성공적으로 처리되면 해당 행을 테이블에서 제거
            var tableRow = button.parentNode.parentNode;
            tableRow.parentNode.removeChild(tableRow);
          }
        };
        xhr.send("imageId=" + imageId);

        header("Location: images_view.php");
        exit;
      }
    });
  });



    summaryInputs.forEach(function(input) {
      input.addEventListener("change", function() {
      var imageId = this.getAttribute("data-idx");
      var summary = this.value;
      

    });
  });


});

  </script>
</head>
<body>


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

// 선택형 버튼 생성
$months = range(1, 12);
$currentMonth = isset($_GET['month']) ? $_GET['month'] : date('n'); // 현재 월
echo "<div>";
foreach ($months as $month) {
    $activeClass = ($month == $currentMonth) ? 'active' : '';
    echo "<a class='btn $activeClass' href='?month=$month'>$month 월</a>";
}
echo "</div>";

// 선택된 월에 해당하는 이미지 가져오기
$stmt = $pdo->prepare("SELECT * FROM images WHERE MONTH(date) = ?");
$stmt->bindParam(1, $currentMonth);
$stmt->execute();
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);


//  항목 출력
echo "<table>";
echo "<tr>";
echo "<th>번호</th>";
echo "<th>날짜</th>";
echo "<th>이미지</th>";
echo "<th style='width: 30%'>요약</th>";
echo "<th>전송</th>";
echo "<th>삭제</th>";
echo "</tr>";

// 이미지 출력
foreach ($images as $image) {
  $imageId = $image['idx']; // idx 필드를 사용하여 이미지의 고유 번호 가져오기
  $photo = $image['photo'];
  $date = $image['date'];
  $imagePath = "./data/profile/" . basename($photo);
  echo "<tr>";
  echo "<td>$imageId</td>"; // 이미지의 고유 번호를 출력
  echo "<td>$date</td>";
  echo "<td><img class='thumbnail' src='$imagePath' alt='Image'></td>";
  echo "<td><textarea style='height: 140px' class='summary-input' data-idx='$imageId'></textarea></td>";
  echo "<td><button class='btn_summary_submit' data-idx='$imageId'>전송</button></td>";
  echo "<td><button class='btn_mem_delete' data-idx='$imageId'>삭제</button></td>";
  echo "</tr>";
}

echo "</table>";




// echo "<button class='btn_summary_submit' data-idx='$imageId'>전송</button>";



// 이미지 업로드 및 수정 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // 이미지 중복 업로드 방지를 위한 세션 시작
  session_start();

  // 이미지 파일이 있는지 확인
  if (!empty($_FILES['photo']['name'])) {
      $uploadDir = './data/profile/'; // 이미지가 저장될 폴더
      $filename = $_FILES['photo']['name'];
      $tmpFilePath = $_FILES['photo']['tmp_name'];
      $newFilePath = $uploadDir . $filename;

      // 이미지 파일의 해시 값을 생성
      $imageHash = md5_file($tmpFilePath);

      // 세션에 이미지 해시 값이 존재하는지 확인
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
              echo "이미지가 성공적으로 업로드되었습니다.";
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


</body>
</html>


