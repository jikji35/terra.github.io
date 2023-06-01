<!-- 파일그래로의 주소를 입력하면 에러가 나므로 반드시 ?month=숫자(현재의 달)을 같이 입력해야한다. (예) http://localhost/account_book/account_book_1.php?month=5  현재5월달이라면 ===>  ?month=5 추가입력!   상단 월단위의 버튼을 눌러줘도 복구된다. -->

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>수입/지출 편집</title>
  
  <!-- 부트스트랩 CDN 링크 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
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

    /* th, td {
        border: 1px solid black;
        padding: 10px;
        text-align: center;
    } */
  
    /* 금액 항목 우측 정렬 */
    .amount-column {
      text-align: right;
    }

    .notice {
      margin-top: 5px;
      color: red;
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
    .btn_edit_delete{   /* 수정,삭제 버튼 */
      text-align: center;
    }
    .total_balance {  /* 총잔액 텍스트*/
      margin-top: 50px;
      color: blue;
    }
    .total_balance > span {  /* 총잔액 금액 텍스트*/
      color: red;
    }
    
    .jikji_footer {  /* 버튼: 영수증 입력하기/ 회원열람용 사이트복사 */
      position: relative;
    }
    
    .account_image { /* 영수증 사진보기 */
      position: absolute;
      top: 57px;
      left: 38px;
      font-size: 21px;
      text-decoration: none;
      
    }
    button.btn-success {/* 영수증 사진보기 */
      color: white;
    }
    
    #copyButton {
      position: absolute;
      top: 130px;
      left: 37px;
      font-size: 18px;
      text-decoration: none;
      border-radius: 10px;
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


    // 오늘의 날짜 출력
    $today = date('Y/m/d H:i');
    echo "<div style='margin-top: 30px'>오늘의 날짜: $today</div>";

    // 선택형 버튼 생성
    $months = range(1, 12);
    $currentMonth = isset($_GET['month']) ? $_GET['month'] : date('n'); // 현재 월
    echo "<div style='margin-top: 20px'>";
    foreach ($months as $month) {
        $activeClass = ($month == $currentMonth) ? 'active' : '';
        echo "<a class='btn $activeClass' href='?month=$month'>$month 월</a>";
    }
    echo "</div>";
    echo "<div class='notice'> [알림] 현재 수정처리는 해결되지않은상태이다. 수정=>저장하면 업로드는 되지만 기존 데이타가 삭제가 안되고 그대로 남아 있으므로 추가로 삭제해줘야한다! </div>";



    // 데이터 조회(지출) 

    $stmt = $pdo->prepare("SELECT * FROM expense_table ORDER BY date DESC");
    $stmt->execute();
    $expenseTransactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 년 지출 합계 계산
    $expenseTotal = 0;
    foreach ($expenseTransactions as $transaction) {
        $expenseTotal += $transaction['amount']; 
    }



    // 월별 지출 합계 계산
    $expenseMonthlyTotals = array();
    foreach ($expenseTransactions as $transaction) {
    $date = $transaction['date'];
    $amount = $transaction['amount'];
    $month = date('Y-m', strtotime($date)); // 년-월 형식으로 변환

    if (!isset($expenseMonthlyTotals[$month])) {
        $expenseMonthlyTotals[$month] = 0;
    }
    $expenseMonthlyTotals[$month] += $amount;
}





    // 데이터 조회(수입)
    $stmt = $pdo->prepare("SELECT * FROM income_table ORDER BY date DESC");
    $stmt->execute();
    $incomeTransactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 년 수입 합계 계산
    $incomeTotal = 0;
    foreach ($incomeTransactions as $transaction) {
        $incomeTotal += $transaction['amount'];
    }







// 월별 수입,지출 합계 계산(시작부분)
// 선택적으로 원하는 달을 클릭하면 주소값을 추출해서 그 해당 월의 수입합계와 지출합계를 구하기위해서이다.
// 현재 페이지의 URL 가져오기
$currentURL = $_SERVER['REQUEST_URI'];

// URL 분석
$urlParts = parse_url($currentURL);

// 쿼리 문자열 가져오기
$queryString = $urlParts['query'];

// 쿼리 문자열 분석
parse_str($queryString, $params);

// month 값 추출
$selectedMonth = isset($params['month']) ? intval($params['month']) : null;

// 추출된 month 값을 사용하여 원하는 동작 수행
if ($selectedMonth !== null) {
    // 선택한 월에 대한 동작 수행
    // 예: 월별 수입 합계와 지출 합계 출력
    $stmt = $pdo->prepare("SELECT * FROM expense_table WHERE MONTH(date) = ? ORDER BY date DESC");
    $stmt->execute([$selectedMonth]);
    $expenseTransactions = $stmt->fetchAll();

    $stmt = $pdo->prepare("SELECT * FROM income_table WHERE MONTH(date) = ? ORDER BY date DESC");
    $stmt->execute([$selectedMonth]);
    $incomeTransactions = $stmt->fetchAll();

    // 지출 합계 계산
    $selectedExpenseTotal = 0;
    foreach ($expenseTransactions as $transaction) {
      $selectedExpenseTotal += $transaction['amount']; 
    }
  
    // 수입 합계 계산
    $selectedIncomeTotal = 0;
    foreach ($incomeTransactions as $transaction) {
      $selectedIncomeTotal += $transaction['amount'];
    }

    // 결과 출력 ===> 주석처리시키고, 합계값을 년수입합계 옆 공간에 출력시키기위해 실제의 코드는 아래에서 사용한다!
    // echo "선택한 월: " . $selectedMonth . "월<br>";
    // echo "지출 합계: " . $expenseTotal . "<br>";
    // echo "수입 합계: " . $incomeTotal . "<br>";
} else {
    // month 값이 없는 경우의 동작 수행
    // 예: 전체 수입 합계와 지출 합계 출력
    // 기존의 코드를 사용하여 필요한 코드 작성


    // 월별 수입,지출 합계 계산(끝부분)
}





    // 선택한 달에 해당하는 수입목록과 지출목록의 데이터를 화면에 출력
    $currentMonth = isset($_GET['month']) ? $_GET['month'] : date('n'); // 현재 월

    // 데이터 조회(지출) 
    $stmt = $pdo->prepare("SELECT * FROM expense_table WHERE MONTH(date) = ? ORDER BY date DESC");
    $stmt->execute([$currentMonth]);
    $expenseTransactions = $stmt->fetchAll(PDO::FETCH_ASSOC);



    // 데이터 조회(수입)
    $stmt = $pdo->prepare("SELECT * FROM income_table WHERE MONTH(date) = ? ORDER BY date DESC");
    $stmt->execute([$currentMonth]);
    $incomeTransactions = $stmt->fetchAll(PDO::FETCH_ASSOC);



    // 수정-저장버튼 클릭 처리

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit'])) {
    $editID = $_POST['edit'];
    $id = $_POST['id'];
    $date = $_POST['date'];
    $type = $_POST['type'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $amount = $_POST['amount'];

    if ($type === '수입') {
        $table = 'income_table';
    } elseif ($type === '지출') {
        $table = 'expense_table';
    }

    // 수정 입력창으로 이동
    if ($editID === $id) {
      // 수정 입력창 출력
      ?>
      <form method="POST" action="account_book.php">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <input type="hidden" name="type" value="<?php echo $type; ?>">
        <div class="form-group">
          <label for="edit-date">일자:</label>
          <input type="date" class="form-control" id="edit-date" name="date" value="<?php echo $date; ?>" required>
        </div>
        <div class="form-group">
          <label for="edit-category">항목:</label>
          <input type="text" class="form-control" id="edit-category" name="category" value="<?php echo $category; ?>" required>
        </div>
        <div class="form-group">
          <label for="edit-description">비고:</label>
          <input type="text" class="form-control" id="edit-description" name="description" value="<?php echo $description; ?>" required>
        </div>
        <div class="form-group">
          <label for="edit-amount">금액:</label>
          <input type="number" class="form-control" id="edit-amount" name="amount" value="<?php echo $amount; ?>" required>
        </div>
        <div class="form-group">
          <button type="submit" class="btn btn-primary" name="save">저장</button>
          <button type="button" class="btn btn-secondary" onclick="location.href='account_book_1.php'">취소</button>
        </div>
      </form>
      <?php
      exit; // 수정 입력창을 출력한 후 스크립트 종료
    }

    // 기존 데이터 삭제
    $stmt = $pdo->prepare("DELETE FROM $table WHERE id = ?");
    $stmt->execute([$id]);

    // 수정된 데이터 저장
    $stmt = $pdo->prepare("INSERT INTO $table (date, category, description, amount) VALUES (?, ?, ?, ?)");
    $stmt->execute([$date, $category, $description, $amount]);

    // 페이지 리로드
    // header("Location: account_book_1.php");
    // exit;



?>





    <?php
    
    // $stmt = $pdo->prepare("UPDATE $table SET date = ?, category = ?, description = ?, amount = ? WHERE id = ?");
    // $stmt->execute([$date, $category, $description, $amount, $id]);



    if ($stmt->rowCount() > 0) {
        // 데이터 수정 성공
        echo "데이터 수정이 완료되었습니다.";

        // 수정된 데이터를 다시 조회하여 화면에 표시
        $stmt = $pdo->prepare("SELECT * FROM $table WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // 수정된 데이터 화면에 출력
        echo "ID: " . $row['id'] . "<br>";
        echo "날짜: " . $row['date'] . "<br>";
        echo "카테고리: " . $row['category'] . "<br>";
        echo "설명: " . $row['description'] . "<br>";
        echo "금액: " . $row['amount'] . "<br>";
    } else {
        // 데이터 수정 실패
        $errorInfo = $stmt->errorInfo();
        echo "데이터 수정에 실패하였습니다. 오류 메시지: " . $errorInfo[2];
    }


}



// 삭제 버튼 클릭 처리
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
  $deleteID = $_POST['delete'];
  $id = $_POST['id'];
  $type = $_POST['type'];

  if ($type === '수입') {
      $table = 'income_table';
  } else if ($type === '지출') {
      $table = 'expense_table';
  }

  $stmt = $pdo->prepare("DELETE FROM $table WHERE id = ?");
  $stmt->execute([$id]);

  // header("Location: account_book_1.php");
  // exit;
}



?>




<!-- 수입 목록 -->
<h3 style="width: 100%; text-align: center; color:darkgrey; margin-top: 30px">[수입 목록]</h3>
<table class="table">
  <thead>
    <tr>
      <th style="width: 5%; text-align: center;">no</th>
      <th style="width: 10%; text-align: center;">일자</th>
      <th style="width: 40%; text-align: center;">항목</th>
      <th style="width: 15%; text-align: center;">비고</th>
      <th style="width: 15%; text-align: center;">금액</th>
      <th style="width: 15%; text-align: center;">관리</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($incomeTransactions as $transaction): ?>
      <tr>
        <td style="border-right: 1px solid #dee2e6; text-align: center;"><?php echo $transaction['id']; ?></td>
        <td style="border-right: 1px solid #dee2e6; text-align: center;"><?php echo $transaction['date']; ?></td>
        <td style="border-right: 1px solid #dee2e6;"><?php echo $transaction['category']; ?></td>
        <td style="border-right: 1px solid #dee2e6;"><?php echo $transaction['description']; ?></td>
        <td class="amount-column" style="border-right: 1px solid #dee2e6;"><?php echo number_format($transaction['amount']); ?>원</td>
        <td style="border-right: 1px solid #dee2e6;">
          <form method="POST">
            <input type="hidden" name="id" value="<?php echo $transaction['id']; ?>">
            <input type="hidden" name="date" value="<?php echo $transaction['date']; ?>">
            <input type="hidden" name="type" value="수입">
            <input type="hidden" name="category" value="<?php echo $transaction['category']; ?>">
            <input type="hidden" name="description" value="<?php echo $transaction['description']; ?>">
            <input type="hidden" name="amount" value="<?php echo $transaction['amount']; ?>">
            <div class="btn_edit_delete">
              <button type="submit" name="edit" value="<?php echo $transaction['id']; ?>" class="btn btn-primary">수정</button>
              <button type="submit" name="delete" value="<?php echo $transaction['id']; ?>" class="btn btn-danger">삭제</button>
            </div>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
    <!-- 합계 -->
    <tr>
      <td colspan="2" class="text-right">월수입 합계:</td>
      <td><?php echo number_format(intval($selectedIncomeTotal)); ?>원</td>
      <td colspan="2" class="text-right">년수입 합계:</td>
      <td><?php echo number_format(intval($incomeTotal)); ?>원</td>
    </tr>
          
  </tbody>
</table>

<!-- 지출 목록 -->
<h3 style="width: 100%; text-align: center; color:darkgrey; margin-top: 30px">[지출 목록]</h3>
<table class="table">
  <thead>
    <tr>
      <th style="width: 5%; text-align: center;">no</th>
      <th style="width: 10%; text-align: center;">일자</th>
      <th style="width: 40%; text-align: center;">항목</th>
      <th style="width: 15%; text-align: center;">비고</th>
      <th style="width: 15%; text-align: center;">금액</th>
      <th style="width: 15%; text-align: center;">관리</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($expenseTransactions as $transaction): ?>
      <tr>
        <td style="border-right: 1px solid #dee2e6; text-align: center;"><?php echo $transaction['id']; ?></td>
        <td style="border-right: 1px solid #dee2e6; text-align: center;"><?php echo $transaction['date']; ?></td>
        <td style="border-right: 1px solid #dee2e6;"><?php echo $transaction['category']; ?></td>
        <td style="border-right: 1px solid #dee2e6;"><?php echo $transaction['description']; ?></td>
        <td class="amount-column" style="border-right: 1px solid #dee2e6;"><?php echo number_format($transaction['amount']); ?>원</td>
        <td style="border-right: 1px solid #dee2e6;">
          <form method="POST">
            <input type="hidden" name="id" value="<?php echo $transaction['id']; ?>">
            <input type="hidden" name="date" value="<?php echo $transaction['date']; ?>">
            <input type="hidden" name="type" value="지출">
            <input type="hidden" name="category" value="<?php echo $transaction['category']; ?>">
            <input type="hidden" name="description" value="<?php echo $transaction['description']; ?>">
            <input type="hidden" name="amount" value="<?php echo $transaction['amount']; ?>">
            <div class="btn_edit_delete">
              <button type="submit" name="edit" value="<?php echo $transaction['id']; ?>" class="btn btn-primary">수정</button>
              <button type="submit" name="delete" value="<?php echo $transaction['id']; ?>" class="btn btn-danger">삭제</button>
            </div>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>





      <!-- 합계 -->
      <tr>
        <td colspan="2" class="text-right">월지출 합계:</td>
        <td><?php echo number_format(intval($selectedExpenseTotal)); ?>원</td>
        <td colspan="2" class="text-right">년지출 합계:</td>
        <td><?php echo number_format(intval($expenseTotal)); ?>원</td>
      </tr>
    </tbody>
  </table>





    <!-- 총잔액 -->
    <div class="jikji_footer">
      <?php $balance = $incomeTotal - $expenseTotal; ?>
      <h3 class="total_balance" style="margin-left: 40px;">총잔액:<span> <?php echo number_format(intval($balance)); ?>원</span></h3>

      <!-- <a class="account_image " href="http://localhost/account_book/photos.php?month=' . $selectedMonth . '">사진보기</a> -->
    
      <a class="account_image " href="./images_upload.php"><button type="button" class="btn btn-success">영수증 입력하기</button></a>

      <button id="copyButton">회원열람용 사이트복사</button>
    </div>



    <script>
      // 버튼 클릭 시 주소 복사 함수
      function copyAddress() {
        var address = "http://jikji35.ivyro.net/account_book/account_view.php?month=5";  // 주소를 원하는 URL로 변경해주세요.
      
        // 임시 textarea 엘리먼트 생성
        var tempTextArea = document.createElement("textarea");
        tempTextArea.value = address;
        document.body.appendChild(tempTextArea);
      
        // textarea 내용 복사
        tempTextArea.select();
        document.execCommand("copy");
      
        // 임시 textarea 엘리먼트 제거
        document.body.removeChild(tempTextArea);
      
        // 복사 완료 메시지
        alert("주소가 복사되었습니다.");
      }
      
      // 버튼 클릭 이벤트 리스너 등록
      var copyButton = document.getElementById("copyButton");
      copyButton.addEventListener("click", copyAddress);
    </script>


  <!-- 부트스트랩 JS CDN 링크 -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


                          
