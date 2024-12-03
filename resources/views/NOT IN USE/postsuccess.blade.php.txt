<html>
  <head>
    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:400,400i,700,900&display=swap" rel="stylesheet">
  </head>
  <style>
    body {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
      background: #EBF0F5;
      font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
    }
    
    h1 {
      color: #88B04B;
      font-weight: 900;
      font-size: 40px;
      margin-bottom: 10px;
    }

    p {
      color: #404F5E;
      font-size: 20px;
      margin: 0 0 20px;
    }

    i {
      color: #9ABC66;
      font-size: 100px;
      line-height: 200px;
      margin-left: -15px; 
    }

    .card {
      background: white;
      padding: 60px;
      border-radius: 4px;
      box-shadow: 0 2px 3px #C8D0D8;
      text-align: center;
    }

    .checkmark-container {
      border-radius: 200px;
      height: 200px;
      width: 200px;
      background: #F8FAF5;
      margin: 0 auto 20px;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .button {
      display: inline-block;
      margin-top: 20px;
      padding: 10px 20px;
      background-color: #88B04B;
      color: white;
      font-size: 18px;
      font-weight: 600;
      border: none;
      border-radius: 5px;
      text-decoration: none;
      transition: background-color 0.3s ease;
    }

    .button:hover {
      background-color: #6f8e3e;
    }
  </style>
  <body>
    <div class="card">
      <div class="checkmark-container">
        <i class="checkmark">✓</i>
      </div>
      <h1>Success</h1> 
      <p>We received your purchase request;<br/> we'll be in touch shortly!</p>
      
      <!-- Button to navigate to 'My Orders' page -->
      <a href="#" class="button">Go to My Orders</a>
    </div>
  </body>
</html>
