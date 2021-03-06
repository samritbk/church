<?php
  include("functions.php");
  if(isset($_SESSION['uid']) && $_SESSION['uid'] != 0){
    $uid=$_SESSION['uid'];
  }else{
    restricted();
  }
?>
<!-- <a href="logout.php">Logout</a> -->
<html>
<head>
<!--// SITE META //-->
<meta charset="UTF-8" />
<link rel='stylesheet' href="../style.css" type='text/css' media='all' />
<script type="text/javascript" src="../js/jquery-3.1.1.min.js"></script>
<script type="text/javascript" src="../js/string.js"></script>
<script type="text/javascript">
$(document).ready(function(){

  $('.saveEd').click(function(){
    var editTitle=$('#editTitle').val();
    var editText=$('#editText').val();
    var aid=$('#aid').val();
    var uid=$('#uid').val();

    $.post('request.php',{editText:editText,editTitle:editTitle,aid:aid,uid:uid},function(data){
      if(data.error != 0){
        alert("There was an error");
      }else{
        window.location.href='home.php';
      }
    },'JSON');

  });
});
</script>
<body style="background:whitesmoke;">
    <header style="height:100px; text-align:center; background:#455A64; position:relative;  color:#FFF;">
      <div style="line-height:45px; height:45px; color:#FFF;">ADMIN DASHBOARD</div>
      <div style="position:absolute; bottom:0; margin-bottom:3px; margin-left:16px; color:#FFF;">
        Logged as: <?php $data=getUsername($uid); echo $data['username']; ?>
      </div>
    </header>
    <div class="marginer">
      <div class="adminMenu">
        <a href="home.php">Home</a>
        <a href="#">Posts</a>
      </div>
      <div style="padding:10px 20px; background:#FFF;">
        <h3 class="left">Edit Article</h3>
        <div style="margin:20px 0px;">
          <a class="button saveEd">Save Edit</a>
          <div class="clear"></div>
        </div>
        <?php
        if(isset($_GET['article_id'])){
          $article=getArticle($_GET['article_id']);
        }
        ?>
        <legend>Title</legend>
        <input type="text" id="editTitle" value="<?php echo $article['article_title']; ?>" style="width:60%; font-size:16;"/><p/>
        <legend>Article text</legend>
        <textarea id="editText" style="width:100%; font-size:16;" rows="35"><?php echo str_replace("<br />","",$article['article_text']) ; ?></textarea>
        <input type="hidden" name="aid" value="<?php echo $article['article_id']; ?>" id="aid"/>
        <input type="hidden" name="uid" value="<?php echo $uid; ?>" id="uid"/>
        <div style="margin:20px 0px;">
          <a class="button saveEd">Save Edit</a>
          <div class="clear"></div>
        </div>
      </div>
    </div>
</body>
</html>
