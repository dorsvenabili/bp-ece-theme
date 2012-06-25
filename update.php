<?php
 
	require_once('db.class.php' ); 
	$db = new DB('localhost', 'elclubexpress', 'dir7xin4', 'elclubepxress');
 
	if (isset($_POST['comment'])) { 
 
 
	    $comment = htmlentities($_POST['comment'], ENT_QUOTES, 'UTF-8');
	    $comment = stripslashes($comment); 
	    $comment = nl2br($comment);
 
 
	    $db->execute("INSERT INTO commentstable SET comment = '$comment'");
 
 
	    $query = $db->query("SELECT id, comment FROM commentstable ORDER BY id DESC");
	    $row = $db->fetchObject($query);
	?>
	    <li class="k-<?php echo $row->id; ?>">
	    <span class="comment"><?php echo $row->comment; ?></span>
	    <span class="del"><a href="#" title="Delete" id="<?php echo $row->id; ?>" class="delete">X</a></span>
	    </li>
	<?php
	}
 
	if ($_POST['id']) { 
	    $id = (int)($_POST['id']);
	    $db->execute("DELETE FROM commentstable WHERE id = $id LIMIT 1");
	}
 
	?>
