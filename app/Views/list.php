<!DOCTYPE html>
<html lang="zh-TW">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>News List</title>
  <style>
    body {
      margin: 0 auto;
      padding: 0;
      text-align: center;
    }

    ul {
      width: 50%;
      margin: 0 auto;
      padding-inline-start: 0px;
    }

    li {
      list-style: none;
      border: #333 1px solid;
      margin: 10px 0;
    }

    hr {
      border: none;
      border-top: #666 1px solid;
    }

    table{
      width:100%;
      border: solid 1px black;
      border-collapse: collapse;

    }

    @media (max-width:750px) {
      ul {
        width: 90%;
      }
    }
  </style>
</head>

<body>
  <h1>News List</h1>
  <a href="<?= base_url('news/create'); ?>">Add News</a>
  <ul>
    <li>
      <h3>News title</h3>
      <hr />
      <p>News content</p>

      <table border="1">
        <thead>
          <tr>
            <th>ID</th>
            <th>title</th>
            <th>content</th>
            <th>insertTime</th>
            <th>updateTime</th>
            <th>fn</th>
          </tr>
        </thead>

        <tbody>
          <?
            if (isset($news)){
              foreach ($news as $key => $value) {
                echo "<tr>".
                  "<td>".$value['id']."</td>".
                  "<td>".$value['title']."</td>".
                  "<td>".$value['content']."</td>".
                  "<td>".$value['insertTime']."</td>".
                  "<td>".$value['updateTime']."</td>".
                  "<td>
                    <a href=".base_url('news/update/'.$value['id']).">Update News</a>
                    <a href=".base_url('news/delete/'.$value['id']).">Delete News</a>
                  </td>".
                "</tr>";
              }
            }
          ?>    
        </tbody>
      </table>
      

      <p>
        <span>2021-05-03 00:00:00</span>

        <a href="#">Update</a> /
        <a href="#">Delete</a>
      </p>
    </li>
  </ul>
</body>

</html>