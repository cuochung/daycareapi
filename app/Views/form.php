<!DOCTYPE html>
<html lang="zh-Hant-TW">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add News</title>
  <style>
    form {
      display: flex;
      flex-direction: column;
      width: 50%;
      margin: 0 auto;
    }

    @media (max-width:750px) {
      form {
        width: 90%;
      }
    }

    input,
    textarea {
      margin: 10px 0;
      font-size: 20px;
      font-family: Arial, Helvetica, sans-serif;
    }

    input[type='submit'] {
      background: cornflowerblue;
      border: #333 1px solid;
      padding: 10px;
    }

    input[type='submit']:hover {
      background-color: deepskyblue;
    }
  </style>
</head>

<body>
  <form action="" method="post">
    <label for="title">title:</label>
    <input type="text" name="title" value="<?
      if (isset($news)){
        echo $news['title'];
      }
    ?>
    ">
    <label for="content">content:</label>
    <textarea name="content" cols="30" rows="10" 
    ><?
      if (isset($news)){
        echo $news['content'];
      }
    ?></textarea>
    <input type="submit" value="submit">
  </form>

</body>

</html>