<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>PetProject</title>
    <?php include_once "head.php" ?>
</head>
<body>
<div class="container">
    <h1>PetProject</h1>
    <h2>Hi, I am PHP backend developer Maksym Prysiazhnyi and this is my pet project =)</h2>
    <h3>It autheticates user according to the credentials in config. Nothing special, no DB, just a simple string
        comparison. Countries are pulled through a free REST service.</h3>
    <?php
    if ($message == \PetProject\Message::ALL_FIELDS_SHOULD_BE_FILLED) {
        echo '<div class="alert alert-danger">All fields should be filled</div>';
    } elseif ($message == \PetProject\Message::WRONG_CREDENTIALS) {
        echo '<div class="alert alert-danger">Wrong credentials</div>';
    } elseif ($message == \PetProject\Message::LOGIN_SUCCEEDED) {
        echo '<div class="alert alert-success">Login succeeded</div>';
    }
    ?>
    <form action="helloSubmit" method="post">
        <div class="form-group">
            <label class="control-label">Name:</label>
            <input name="name" type="text" class="form-control" value="<?php echo !empty($name) ? $name : ''; ?>">
        </div>
        <div class="form-group">
            <label class="control-label">E-mail:</label>
            <input name="email" type="text" class="form-control" value="<?php echo !empty($email) ? $email : ''; ?>">
        </div>
        <div class="form-group">
            <label class="control-label">Select country:</label>
            <select name="country" class="form-control">
                <?php
                $countries = $groupKTService->getCountries();
                foreach ($countries as $c) {
                    echo '<option ' . (!empty($country) && $country == $c->name ? 'selected' : '') . '>' . $c->name . '</option>';
                }
                ?>
            </select>
        </div>
        <input class="btn btn-primary" type="submit" value="Login"/>
    </form>
</div>
</body>
</html>