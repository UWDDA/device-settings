<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>CFT - WIFI Settings</title>
  <!-- Favicon icon -->
  <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png" />
  <!-- Custom Stylesheet -->
  <link rel="stylesheet" href="./css/style.css" />
</head>

<body>
  <?php
    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Capture the POST values
        $wifi = htmlspecialchars($_POST['wifi'] ?? '');
        $password = htmlspecialchars($_POST['password'] ?? '');

        // Display the captured values
        echo "<h2>Settings Saved!</h2>";
        echo "wifi: $wifi<br>";
        echo "pass: $password<br>";

        // Update hostapd.conf and restart service

        $output = shell_exec('/root/update_hostapd.sh '.$wifi.' '.$password);
        // $output = shell_exec('service hostapd restart');
    }

    $output = shell_exec('nmcli dev wifi list');

    // Parse the output for SSIDs
    $lines = explode("\n", $output);
    $ssids = [];

    foreach ($lines as $line) {
        if (preg_match('/^ *(\S+)\s+(\S+)\s+(\S+)/', $line, $matches)) {
            if(in_array($matches[2],["--","BSSID"]) || in_array($matches[2],$ssids)){ continue; }
            $ssids[] = $matches[2];
        }
    }
  ?>
  <div id="preloader"><i>.</i><i>.</i><i>.</i></div>

  <div id="main-wrapper">
    <div class="authincation section-padding">
      <div class="container h-100">
        <div class="row justify-content-center h-100 align-items-center">
          <div class="col-xl-5 col-md-6">
            <div class="mini-logo text-center my-4">
              <?php // <a href="./intro.html"><img src="./images/logo.png" alt="" /></a> ?>
              <h4 class="card-title mt-3">WIFI Settings</h4>
            </div>
            <div class="auth-form card">
              <div class="card-body">
                <form class="signin_validate row g-3" action="otp-2.html">
                  <div class="col-12">

                    <div class="col-12">
                      <label class="form-label">Select WIFI SSID</label>
                      <select placeholder="SSID (i.e. MyHome WIFI)" class="form-select" name="wifi">
                        <?php foreach ($ssids as $ssid) { ?>
                            <?php $value=htmlspecialchars($ssid); ?>
                            <option value="<?php echo $value; ?>"><?php echo $value; ?></option>
                        <?php } ?>
                      </select>
                    </div>

                  </div>
                  <div class="col-12">
                    <input type="password" class="form-control" placeholder="Password" name="password" />
                  </div>
              </div>

              <div class="text-center">
                <div class="col-12">
                  <div>
                    <button type="button" class="btn btn-primary me-3" data-toggle="modal" data-target="#addBank">
                      Refresh List
                    </button>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addCard">
                      Save Settings
                    </button>
                  </div>
                </div>
              </div>
              </form>

            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
  </div>

  <script src="./vendor/jquery/jquery.min.js"></script>
  <script src="./vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <script src="./js/scripts.js"></script>
  <script></script>
</body>

</html>