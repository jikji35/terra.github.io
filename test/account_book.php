<!-- 1. 이페이지는 계모임에서 총무담당 사용지출내역을 관리하고, http://localhost/account_book/account_book.php 에서 관리자페이지로 사용내역서를 입력할수있다.
2. account_book_1.php 에서는 관리자페이지로 편집(수정/삭제)을 한다.
3. account_view.php 에서는 회원들에게 공개적으로 보여주는 페이지이다.
4. 영수증 사진보기를 클릭하면 http://localhost/account_book/images_view.php 페이지를 회원들에게 보여준다. ===> images_upload.php(사진입력) ==> images_view_edit.php(사진편집) ==> images_view.php(사진공개 열람)
5. 데이타베이스의 사용내역서는 수입관련 테이블(income_table)/지출관련 테이블(expense_table)을 사용하고있고, 영수증사진 관련테이블은 images 이다. -->


<!DOCTYPE html>
<html>
<head>
  <title>Account Book</title>
  <!-- 부트스트랩 CDN 링크 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
 
  <style>
   
   body {
      font-size: 22px;
    }

    input[type='date'] { /* 일자 입력란 */
      height: 60px;
      font-weight: bold;
      font-size: 30px;
    }

    #type{    /* Type(수입/지출) 입력란 */
      height: 60px;
      font-size: 30px;
      height: 60px;
      font-size: 30px;
    }

    input[type='text'] {  /* 항목,비고 입력란 */
      height: 60px;
      background-color: blue;
      color: white;
      font-weight: bold;
      font-size: 30px;
    }

    input[type='number'] {  /* 금액 입력란 */
      height: 60px;
      background-color: blue;
      color: white;
      font-weight: bold;
      font-size: 30px;
    }
 
    label {
      margin-top: 20px;
      margin-bottom: 10px;
    }

    .container {
      width: 800px;
      height: 1000px;
      border-radius: 5px;
      margin: 150px auto; 
      padding: 50px 10px;
    }

    h1 {
      color: coral;
      text-align: center;
      font-weight: bold;
    }
  
    form {
      margin-top: 120px;
      padding: 5px 40px;
    }

    div.form-group {
      margin-top: 14px;
    }

    button.btn-success {
      position: relative;
      left: 50%;
      width: 120px;
      margin-top: 120px;
      transform: translateX(-50%);
      font-size: 30px;


    }
    



    @media (max-width: 580px) {
        img {
            max-width: 100%;
            height: auto;
        }
    }

  </style>

</head>

<body>
  <?php

    // 데이터베이스 연결 정보
    $host = 'sql12.freemysqlhosting.net';
    $dbname = 'sql12622736';
    $username = 'sql12622736';
    $password = 'fXgnDPnXKi';

    // PDO 객체 생성
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);

    // 폼 제출 처리
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $date = $_POST['date'];
      $type = $_POST['type'];
      $category = $_POST['category'];
      $description = $_POST['description'];
      $amount = $_POST['amount'];

    // 유효성 검사
    if (empty($date) || empty($type) || empty($category) || empty($amount)) {
        echo '<p>일자, Type, 항목, 금액은 필수 입력 사항입니다.</p>';
    } else {
        // 데이터베이스에 데이터 저장
        if ($type === '수입') {
            $table = 'income_table';
        } else if ($type === '지출') {
            $table = 'expense_table';
        }

        $stmt = $pdo->prepare("INSERT INTO $table (date, category, description, amount) VALUES (?, ?, ?, ?)");
        $stmt->execute([$date, $category, $description, $amount]);

        // 저장 후 페이지 리로드
        header("Location: account_book.php");
        exit;
        }
      }

?>



<div class="container">
  <h1>사용내역서 입력</h1>
  
  <!-- 입력 폼 -->
  <form method="POST" action="">
      <div class="form-group">
        <label for="date">일자:</label>
        <input type="date" class="form-control" id="date" name="date" required>
      </div>
      
      <div class="form-group">
        <label for="type">Type:</label>
        <select class="form-control" id="type" name="type" required>
          <option value="수입">수입</option>
          <option value="지출">지출</option>
        </select>
      </div>

      <div class="form-group">
        <label for="category">항목:</label>
        <input type="text" class="form-control" id="category" name="category" required>
      </div>

      <div class="form-group">
        <label for="description">비고:</label>
        <input type="text" class="form-control" id="description" name="description">
      </div>

      <div class="form-group">
        <label for="amount">금액:</label>
        <input type="number" class="form-control" id="amount" name="amount" required>
      </div>
      <button type="submit" class="btn btn-lg btn-success">저장</button>
    </form>
</div>

  <!-- 부트스트랩 JS CDN 링크 -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
