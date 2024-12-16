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
      background: #F8D7DA; /* Light red background for error */
      font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
    }
    
    h1 {
      color: #D9534F; /* Red color for error */
      font-weight: 900;
      font-size: 40px;
      margin-bottom: 10px;
    }

    p {
      color: #721C24; /* Darker red text */
      font-size: 20px;
      margin: 0 0 20px;
    }

    i {
      color: #D9534F; /* Red color for icon */
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

    .error-icon-container {
      border-radius: 200px;
      height: 200px;
      width: 200px;
      background: #FADBD8; /* Light red background for icon container */
      margin: 0 auto 20px;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .button {
      display: inline-block;
      margin-top: 20px;
      padding: 10px 20px;
      background-color: #D9534F; /* Red color for error button */
      color: white;
      font-size: 18px;
      font-weight: 600;
      border: none;
      border-radius: 5px;
      text-decoration: none;
      transition: background-color 0.3s ease;
    }

    .button:hover {
      background-color: #c0392b; /* Darker red for hover */
    }
  </style>
  <body>
    <div class="card">
      <div class="error-icon-container">
        <i class="error-icon">✘</i>
      </div>
      <h1>Error</h1> 
      <p>Something went wrong with your request;<br/> please try again later!</p>
      
      <!-- Button to navigate back to the home page -->
      <a href="/" class="button">Go Back to Home</a>
    </div>
  </body>
</html>
