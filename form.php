<?php
$name = $email = $gender = $website = $phone = $password = $confirmPassword = "";
$nameErr = $emailErr = $genderErr = $websiteErr = $phoneErr = $passwordErr = $confirmPasswordErr = $termsErr = "";
$submitCount = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $submitCount = isset($_POST["submit_count"]) ? (int)$_POST["submit_count"] + 1 : 1;

    //check if name is empty
    if (empty($_POST["name"])) {
        $nameErr = "Name is required."; //show error if empty
    } else {
        $name = test_input($_POST["name"]); //clean input
    }
    
    //check if email is empty
    if (empty($_POST["email"])) {
        $emailErr = "Email is required.";
    } else {
        $email = test_input($_POST["email"]);   //check if email format is valid

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format.";
        }
    }

    //check if gender is empty
    if (empty($_POST["gender"])) {
        $genderErr = "Gender is required.";
    } else {
        $gender = test_input($_POST["gender"]); //clean input
    }

    // VALIDATE WEBSITE: optional but must be valid URL if provided
    if (!empty($_POST["website"])) {
        $website = test_input($_POST["website"]);
        if (!filter_var($website, FILTER_VALIDATE_URL)) {
            $websiteErr = "Invalid URL format.";
        }
    }
    
    //check if phone is empty
    if (empty($_POST["phone"])) {
        $phoneErr = "Phone number is required.";
    } else {
        $phone = test_input($_POST["phone"]);

        //check if phone matches required format
        if (!preg_match('/^\+?[0-9 \-]{7,15}$/', $phone)) {
            $phoneErr = "Invalid phone format.";
        }
    }

    //check if password is empty
    if (empty($_POST["password"])) {
        $passwordErr = "Password is required.";
    } else {
        $password = $_POST["password"];   //check minimum length

        if (strlen($password) < 8) {
            $passwordErr = "Password must be at least 8 characters.";
        }
    }
   
    // VALIDATE CONFIRM PASSWORD: must match password
    if (empty($_POST["confirm_password"])) {
        $confirmPasswordErr = "Please confirm your password.";
    } else {
        $confirmPassword = $_POST["confirm_password"];
        if ($confirmPassword !== $password) {
            $confirmPasswordErr = "Passwords do not match.";
        }
    }

    // VALIDATE TERMS: checkbox must be checked
    if (!isset($_POST["terms"])) {
        $termsErr = "You must agree to the terms and conditions.";
    }
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$formValid = ($_SERVER["REQUEST_METHOD"] == "POST")
    && empty($nameErr) && empty($emailErr) && empty($genderErr)
    && empty($websiteErr) && empty($phoneErr)
    && empty($passwordErr) && empty($confirmPasswordErr)
    && empty($termsErr);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PHP Form Validation</title>
</head>
<body style="font-family: Arial, sans-serif; max-width: 500px; margin: 40px auto; padding: 0 16px;">

<h2>PHP Form Validation</h2>
<p>Fields marked <span style="color:red;">*</span> are required.</p>

<?php if ($submitCount > 0): ?>
    <p>Submission attempt: <?= $submitCount ?></p>
<?php endif; ?>

<form method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>">

    <input type="hidden" name="submit_count" value="<?= $submitCount ?>">

    <div style="margin-bottom:14px;">
        <label>Full Name *</label>
        <input type="text" name="name" value="<?= $name ?>" style="width:100%; padding:8px; margin-top:4px; box-sizing:border-box;">
        <?php if ($nameErr): ?><div style="color:red; font-size:0.85rem;"><?= $nameErr ?></div><?php endif; ?>
    </div>

    <div style="margin-bottom:14px;">
        <label>Email Address *</label>
        <input type="email" name="email" value="<?= $email ?>" style="width:100%; padding:8px; margin-top:4px; box-sizing:border-box;">
        <?php if ($emailErr): ?><div style="color:red; font-size:0.85rem;"><?= $emailErr ?></div><?php endif; ?>
    </div>

    <div style="margin-bottom:14px;">
        <label>Phone Number *</label>
        <input type="tel" name="phone" placeholder="+63 912 345 6789" value="<?= $phone ?>" style="width:100%; padding:8px; margin-top:4px; box-sizing:border-box;">
        <?php if ($phoneErr): ?><div style="color:red; font-size:0.85rem;"><?= $phoneErr ?></div><?php endif; ?>
    </div>

    <div style="margin-bottom:14px;">
        <label>Gender *</label><br>
        <label><input type="radio" name="gender" value="Female" <?= ($gender=="Female")?"checked":"" ?>> Female</label>
        <label><input type="radio" name="gender" value="Male" <?= ($gender=="Male")?"checked":"" ?>> Male</label>
        <label><input type="radio" name="gender" value="Other" <?= ($gender=="Other")?"checked":"" ?>> Other</label>
        <?php if ($genderErr): ?><div style="color:red; font-size:0.85rem;"><?= $genderErr ?></div><?php endif; ?>
    </div>

    <div style="margin-bottom:14px;">
        <label>Password *</label>
        <input type="password" name="password" placeholder="Minimum 8 characters" style="width:100%; padding:8px; margin-top:4px; box-sizing:border-box;">
        <?php if ($passwordErr): ?><div style="color:red; font-size:0.85rem;"><?= $passwordErr ?></div><?php endif; ?>
    </div>

    <div style="margin-bottom:14px;">
        <label>Confirm Password *</label>
        <input type="password" name="confirm_password" placeholder="Re-enter your password" style="width:100%; padding:8px; margin-top:4px; box-sizing:border-box;">
        <?php if ($confirmPasswordErr): ?><div style="color:red; font-size:0.85rem;"><?= $confirmPasswordErr ?></div><?php endif; ?>
    </div>

    <div style="margin-bottom:14px;">
        <label>Website URL (optional)</label>
        <input type="text" name="website" placeholder="https://example.com" value="<?= $website ?>" style="width:100%; padding:8px; margin-top:4px; box-sizing:border-box;">
        <?php if ($websiteErr): ?><div style="color:red; font-size:0.85rem;"><?= $websiteErr ?></div><?php endif; ?>
    </div>

    <div style="margin-bottom:14px;">
        <label><input type="checkbox" name="terms" <?= isset($_POST["terms"])?"checked":"" ?>>
        I agree to the Terms and Conditions *</label>
        <?php if ($termsErr): ?><div style="color:red; font-size:0.85rem;"><?= $termsErr ?></div><?php endif; ?>
    </div>

    <button type="submit" style="width:100%; padding:10px; background: blue; color:#fff; border:none; cursor:pointer;">Submit Form</button>

</form>

<?php if ($formValid): ?>
    <div style="background:#f0fdf4; padding:16px; margin-top:20px;">
        <h3>Form submitted successfully!</h3>
        <p><strong>Name:</strong> <?= $name ?></p>
        <p><strong>Email:</strong> <?= $email ?></p>
        <p><strong>Phone:</strong> <?= $phone ?></p>
        <p><strong>Gender:</strong> <?= $gender ?></p>
        <?php if ($website): ?><p><strong>Website:</strong> <?= $website ?></p><?php endif; ?>
        <p><strong>Terms Agreed:</strong> Yes</p>
    </div>
<?php endif; ?>

</body>
</html>