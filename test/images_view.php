<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>영수증 이미지열람</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      overflow-x: auto;
    }
    table {
        border-collapse: collapse;
        margin-top: 40px;
        width: 100%;
    }

    th, td {
        border: 1px solid black;
        padding: 10px;
        text-align: center; /* 셀 안의 내용을 가로로 중앙 정렬 */
    }
    .text-month{
        margin-top: 10px;
    }
    .notice {
      margin-top: 5px;
      color: red;
    }

    img { /* 이미지 사진 */
        width: 350px;
        height: 250px;
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

    .big_image {
      cursor: pointer;
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

// 오늘의 날짜 출력
$today = date('Y/m/d H:i');
echo "<div style='margin-top: 20px'>오늘의 날짜: $today</div>";


// 선택형 버튼 생성
$months = range(1, 12);
$currentMonth = isset($_GET['month']) ? $_GET['month'] : date('n'); // 현재 월
echo "<div class='text-month'>";
foreach ($months as $month) {
    $activeClass = ($month == $currentMonth) ? 'active' : '';
    echo "<a class='btn $activeClass' href='?month=$month'>$month 월</a>";
}
echo "</div>";
echo "<div class='notice'> [알림] 위의 해당되는 X월 버튼을 클릭하면 이미지를 볼수있습니다.</div>";



// 선택된 월에 해당하는 이미지 가져오기
$stmt = $pdo->prepare("SELECT * FROM images WHERE MONTH(date) = ?");
$stmt->bindParam(1, $currentMonth);
$stmt->execute();
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);




//  항목 출력
echo "<table>";
echo "<tr>";
echo "<th>no</th>";
echo "<th>날짜</th>";
echo "<th>이미지</th>";
echo "<th style='width: 30%'>요약</th>";
echo "<th>내려받기</th>";
echo "</tr>";

// 이미지 출력
foreach ($images as $image) {
  $imageId = $image['idx'];
  $photo = $image['photo'];
  $date = $image['date'];
  $imagePath = "./data/profile/" . basename($photo);
  $downloadLink = "./data/profile/" . basename($photo); // 이미지 다운로드 링크

  echo "<tr>";
  echo "<td>$imageId</td>";
  echo "<td>$date</td>";
  echo "<td><img class='thumbnail' src='$imagePath' alt='Image'></td>";

  // 요약 항목 가져오기
  $stmt = $pdo->prepare("SELECT notice FROM images WHERE idx = ?");
  $stmt->bindParam(1, $imageId);
  $stmt->execute();
  $summary = $stmt->fetchColumn();

  echo "<td>$summary</td>"; // 요약 항목 출력

  // 이미지 다운로드 링크(다운로드 제한이 걸려서 안됨!!!)
  // echo "<td><a href='http://jikji35.ivyro.net/.$downloadLink' download>다운로드</a></td>";

  // 이미지 확대 보기 링크
  echo "<td class='big_image'><a onclick=\"openFullScreen('$imagePath')\">이미지 확대</a></td>";

  echo "</tr>";
}


?>





<script>
  // 모바일에서 이미지 풀스크린으로 확대시켜서 보기
  // JavaScript function to open image in full-screen mode
function openFullScreen(imageUrl) {
  if (typeof window.orientation !== 'undefined') {
    // For mobile devices
    var elem = document.createElement("div");
    elem.style.backgroundImage = "url('" + imageUrl + "')";
    elem.style.backgroundSize = "contain";
    elem.style.backgroundRepeat = "no-repeat";
    elem.style.backgroundPosition = "center";
    elem.style.width = "100%";
    elem.style.height = "100%";
    elem.style.position = "fixed";
    elem.style.top = "0";
    elem.style.left = "0";
    elem.style.zIndex = "9999";
    elem.style.cursor = "pointer";

    elem.addEventListener("click", exitFullScreen);

    document.documentElement.appendChild(elem);

    function exitFullScreen() {
      document.documentElement.removeChild(elem);
    }
  } else {
    // For desktop devices
    window.open(imageUrl);
  }
}

</script>

</body>
</html>


