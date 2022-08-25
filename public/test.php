<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Document</title>
<style>
   body {
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
   }
   .result {
      font-size: 18px;
      font-weight: 500;
      color: rebeccapurple;
   }
   .size {
      font-size: 30px;
   }
</style>
</head>
<body>
<h1>Disable Mouse events using JavaScript</h1>
<div class="result">This is some text inside a div</div>
<button class="Btn">DISABLE</button>
<button class="Btn">ENABLE</button>
<h3>
Click on the above button to enable or disable mouse events
</h3>
<script>
   let resEle = document.querySelector(".result");
   let sampleEle = document.querySelector(".sample");
   let BtnEle = document.querySelectorAll(".Btn");
   resEle.addEventListener("click", () => {
      resEle.classList.toggle("size");
   });
   BtnEle[0].addEventListener("click", () => {
      resEle.style.pointerEvents = "none";
   });
   BtnEle[1].addEventListener("click", () => {
      resEle.style.pointerEvents = "auto";
   });
</script>
</body>
</html>