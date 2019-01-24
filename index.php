<?php declare(strict_types=1);
  
  $query_word = array_key_exists("word", $_GET) ? $_GET["word"] : "";
  $query_op = array_key_exists("op", $_GET) ? $_GET["op"] : "";
  
  $image_files = array_map(
      function ($name) {
        return substr($name, 0, -4);
      }, array_filter(
      scandir("dict", (int)($query_op == "prev" || $query_op == "")),
      function ($name) {
        return $name != "." and $name != "..";
      }));
  
  switch ($query_op) {
    case "search":
      $compare = function ($lhs, $rhs) {
        return $lhs >= $rhs;
      };
      break;
    case "next":
      $compare = function ($lhs, $rhs) {
        return $lhs > $rhs;
      };
      break;
    default:
      $compare = function ($lhs, $rhs) {
        return $lhs < $rhs;
      };
      break;
  }
  
  $result = end($image_files);
  foreach ($image_files as $image_file) {
    if ($compare($image_file, $query_word)) {
      $result = $image_file;
      break;
    }
  }

?>

<!DOCTYPE html>
<html lang="zh">
  
  <head>
    <meta charset="UTF-8"/>
    <title>Dict for Luisa</title>
    <link href="style.css" rel="stylesheet" type="text/css">
  </head>
  
  <body>
    <div class="container">
      <section class="banner">
        <form>
          <div class="welcome">Dict for Luisa</div>
          <label>
            <input class="line-edit" type="text" name="word"/>
          </label>
          <input type="hidden" name="op" value="search"/>
        </form>
      </section>
      <img width="100%" src="dict/<?php echo $result ?>.jpg" alt="result"/>
      <div class="page-turner" align="center">
        <form>
          <input type="hidden" name="word" value="<?php echo $result ?>"/>
          <input type="hidden" name="op" value="prev"/>
          <input class="rect-button" type="submit" value="◀︎"/>
        </form>
        <form>
          <input type="hidden" name="word" value="<?php echo $result ?>"/>
          <input type="hidden" name="op" value="next"/>
          <input class="rect-button" type="submit" value="▶︎"/>
        </form>
      </div>
    </div>
  </body>

</html>
