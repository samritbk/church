<?php
  function getPost($post_id){
    $return=array();
    $post_id=(int) $post_id;

    if($post_id != 0){
      $query=mysql_query("SELECT * FROM posts WHERE post_id='$post_id'");
      $num_rows=mysql_num_rows($query);

      if($num_rows != 0){
        $row=mysql_fetch_assoc($query);

        $return['error']=0;
        $return['post_id']=$row['post_id'];
        $return['post_title']=$row['post_title'];
        $return['post_text']=$row['post_text'];
        $return['post_category']=$row['post_category'];
        $return['post_author_id']=$row['post_author_id'];
        $return['date_created']=$row['date_created'];
        $return['status']=$row['status'];
        $return['last_modified']=$row['last_modified'];
      }else{
        $return['error']=1;
        $return['err_msg']="Post not found.";
      }
    }else{
      $return['error']=1;
      $return['err_msg']="Post id not valid";
    }
    return $return;
  }
  function getPostsByCategory($post_category, $limit=0, $order=0){
    $return=array();
    $limit=(int) $limit;
    $order=(int) $order;
    $addon="";

    $mainQuery = "SELECT * FROM posts";
    if($order != 0 && $limit != 0){
      $addon=" ORDER BY post_id ASC LIMIT $limit";
    }else if($order != 0 && $limit == 0){
      $addon=" ORDER BY post_id ASC";
    }else if($order == 0 && $limit != 0){
      $addon=" ORDER BY post_id DESC LIMIT $limit";
    }else if($order == 0 && $limit == 0){
        $addon=" ORDER BY post_id DESC";
    }
    $query=mysql_query($mainQuery.$addon);
    while($row=mysql_fetch_assoc($query)){
      $return[]=array(
        "post_id"=>$row['post_id'],
        "post_title"=>$row['post_title'],
        "post_text"=>$row['post_text'],
        "post_category"=>$row['post_category'],
        "post_author_id"=>$row['post_author_id'],
        "date_created"=>$row['date_created'],
        "status"=>$row['status'],
        "last_modified"=>$row['last_modified']);
    }

    return $return;
  }

  function editPost($post_id,$post_title,$post_text,$postEditor){
    $return=array();

    $article_title=nl2br(mysql_real_escape_string($post_title));
    $article_text=nl2br($post_text);
    $article_text=mysql_real_escape_string($post_text);

    $query=mysql_query("UPDATE posts SET post_title='$post_title', post_text='$post_text', post_author_id='$postEditor' WHERE post_id='$post_id'");
    if($query){
      $return['error']=0;
    }else{
      $return['error']=1;
      $return['err_msg']="Edit couldn't be completed";
    }
    //echo mysql_error();
    return json_encode($return);
  }
  function writePost($post_title, $post_text, $post_author, $catId, $email_notification=0){
    $return=array();
    $post_title=nl2br(mysql_real_escape_string(addslashes(trim($post_title))));
    $post_text=nl2br($post_text);
    $post_text=mysql_real_escape_string(addslashes(trim($post_text)));
    $catId=(int) $catId;
    $post_author=(int) $post_author;
    $timestamp = time();

    if($post_author != 0){
      $query= mysql_query("INSERT INTO
        posts(post_title,post_text,post_author_id,post_category,date_created,last_modified)
        VALUES ('$post_title','$post_text','$post_author','$catId','$timestamp','$timestamp')");
      if($query){
        $return['error']=0;
        $return['last_id']=mysql_insert_id();
      }else{
        $return['error']=0;
        $retutn['err_msg']="Database error. Please try again later";
      }
    }else{
      $return['error']=1;
      $return['err_msg']="User not verified";
    }
    return json_encode($return);
  }
?>
