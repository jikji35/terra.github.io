<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <style>
    table {
        border-collapse: collapse;
        margin-top: 10px;
        width: 100%;
    }

    th, td {
        border: 1px solid black;
        padding: 10px;
        text-align: center;
    }

    img {
        width: 200px;
        height: 150px;
        object-fit: cover;
        display: block;
        margin: 0 auto;
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

    @media (max-width: 640px) {
        img {
            max-width: 100%;
            height: auto;
        }
    }
  </style>


  <script>
    document.addEventListener("DOMContentLoaded", function() {
      var deleteButtons = document.querySelectorAll(".btn_mem_delete");
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
                var response = xhr.responseText;
                if (response === 'success') {
                  // 삭제가 성공적으로 처리되면 해당 행을 테이블에서 제거
                  var tableRow = button.parentNode.parentNode;
                  tableRow.parentNode.removeChild(tableRow);
                } else {
                  // 삭제 실패 시 알림
                  alert("이미지 삭제 중 오류가 발생했습니다.");
                }
              }
            };
            xhr.send("imageId=" + imageId);
          }
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



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 이미지 ID 가져오기
    $imageId = isset($_POST['imageId']) ? $_POST['imageId'] : null;

    if ($imageId) {
        try {
            // PDO 객체 생성 및 데이터베이스 연결
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // 이미지 정보 조회
            $stmt = $pdo->prepare("SELECT * FROM images WHERE idx = ?");
            $stmt->bindParam(1, $imageId);
            $stmt->execute();
            $image = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($image) {
                // 이미지 파일 삭제
                $filePath = $image['photo'];
                if (file_exists($filePath)) {
                    unlink($filePath);
                }

                // 이미지 데이터베이스에서 삭제
                $stmt = $pdo->prepare("DELETE FROM images WHERE idx = ?");
                $stmt->bindParam(1, $imageId);
                if ($stmt->execute()) {
                    echo '이미지가 성공적으로 삭제되었습니다.';
                } else {
                    echo '이미지 삭제 중 오류가 발생했습니다.';
                }
            } else {
                echo '해당 이미지를 찾을 수 없습니다.';
            }
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
}



?>
</body>
</html>
