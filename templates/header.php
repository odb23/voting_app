<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $form_name = isset($_POST['form-name']) ? $_POST['form-name'] : "";

    if ($form_name == "logout-form") {
        session_unset();
        session_destroy();
        header("Location: login.php");
        exit();
    }
    $_POST = array();
}
?>

<header>
    <a href="./index.php" class="logo" style="margin-top: 0;"><svg fill="#000000" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30px" height="30px" viewBox="0 0 45.973 45.972">
            <g>
                <g>
                    <path d="M44.752,20.914L25.935,2.094c-0.781-0.781-1.842-1.22-2.946-1.22c-1.105,0-2.166,0.439-2.947,1.22L1.221,20.914
			c-1.191,1.191-1.548,2.968-0.903,4.525c0.646,1.557,2.165,2.557,3.85,2.557h2.404v13.461c0,2.013,1.607,3.642,3.621,3.642h3.203
			V32.93c0-0.927,0.766-1.651,1.692-1.651h6.223c0.926,0,1.673,0.725,1.673,1.651v12.168h12.799c2.013,0,3.612-1.629,3.612-3.642
			V27.996h2.411c1.685,0,3.204-1,3.85-2.557C46.3,23.882,45.944,22.106,44.752,20.914z" />
                </g>
            </g>
        </svg> VoteEase</a>
    <form name="<?php echo $logout_form_name ?>" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="hidden" name="form-name" value="logout-form">
        <button type="submit" class="link-button" style="margin-top: 0">Logout</button>
    </form>
</header>