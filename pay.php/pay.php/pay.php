<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/5c5946fe44.js" crossorigin="anonymous"></script>
    <title>Payment - Khalti</title>
    <style>
      :root {
        --primary-blue: #1a237e;
        --secondary-blue: #3949ab;
        --light-blue: #e8eaf6;
      }
      body {
        background-color: var(--light-blue);
      }
      .navbar {
        background-color: var(--primary-blue) !important;
      }
      .btn-primary {
        background-color: var(--primary-blue);
        border-color: var(--primary-blue);
      }
      .btn-primary:hover {
        background-color: var(--secondary-blue);
        border-color: var(--secondary-blue);
      }
    </style>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-light">
      <div class="container">
        <a class="navbar-brand text-white" href="#">Hotel Payment</a>
      </div>
    </nav>

    <div class="container mt-5">
      <div class="row justify-content-center">
        <div class="col-md-6">
          <div class="card">
            <div class="card-body">
              <h3 class="card-title text-center mb-4">Complete Your Payment</h3>
              <div class="text-center mb-4">
                <img src="https://web.khalti.com/static/img/logo.png" alt="Khalti" height="50">
              </div>
              <div id="payment-form">
                <div class="mb-3">
                  <label class="form-label">Amount (NPR)</label>
                  <input type="text" class="form-control" id="amount" value="300" readonly>
                </div>
                <button id="payment-button" class="btn btn-primary w-100">Pay with Khalti</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="https://khalti.s3.ap-south-1.amazonaws.com/KPG/dist/2020.12.17.0.0.0/khalti-checkout.iffe.js"></script>
    <script>
      var config = {
        publicKey: "YOUR_KHALTI_PUBLIC_KEY",
        productIdentity: "Hotel Booking",
        productName: "Room Booking",
        productUrl: window.location.href,
        eventHandler: {
          onSuccess(payload) {
            console.log(payload);
            window.location.href = 'index.php';
          },
          onError(error) {
            console.log(error);
          },
          onClose() {
            console.log('Widget is closing');
          }
        }
      };

      var checkout = new KhaltiCheckout(config);
      var btn = document.getElementById("payment-button");
      btn.onclick = function () {
        checkout.show({amount: 30000}); // Amount in paisa
      }
    </script>
  </body>
</html>