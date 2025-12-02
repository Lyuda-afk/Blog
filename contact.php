<!DOCTYPE html>
<html>
    <head>
        <title>Contact us</title>
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="images/fontawesome-free-7.1.0-web/css/all.css">
    </head>
    <body>
    <?php include 'header.php'; ?>
        <main>
             <form>
  <label for="username">Username:</label><br>
  <input type="text" id="username" name="username" placeholder="Name"><br>
  <label for="text">Password:</label><br>
  <input type="text" id="text" name="text" placeholder="Last Name"><br>
    <label for="username">Choose your gen:</label>
    <div class="checkbox-container">
 <input type="checkbox" id="gen" name="gen" >
  <label for="Male"> Male</label><br>
  <input type="checkbox" id="gen" name="gen">
  <label for="Female"> Female</label><br><br>
  </div>
  <input type="email" id="email" name="email" placeholder="Enter your email"><br>
 <textarea rows="5" name="" id="" placeholder="Comment"></textarea><br>
      <input type="submit" value="Submit">
</form>
        </main>
        <?php include 'footer.php' ?>
    </body>
</html>
