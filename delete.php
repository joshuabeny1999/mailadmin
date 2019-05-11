<h1>Löschen</h1>
<?php
    if (isset($_GET['mailbox'])){
        $id="mailbox=".$_GET['mailbox'];
        $seite="mailboxes";
    } elseif (isset($_GET['aliases'])){
        $id = "aliases=".$_GET['aliases'];
        $seite = "aliases";
    } elseif (isset($_GET['domain'])){
        $id = "domain=".$_GET['domain'];
        $seite = "domains";
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        if (isset($_GET['mailbox'])){
             $id=$_GET['mailbox'];
             $sql = "DELETE FROM accounts WHERE id=$id";
             $seite="mailboxes";
         } elseif (isset($_GET['aliases'])){
             $id = $_GET['aliases'];
             $sql = "DELETE FROM aliases WHERE id=$id";
             $seite = "aliases";
         } elseif (isset($_GET['domain'])){
             $id = $_GET['domain'];
             $sql = "DELETE FROM domains WHERE id=$id";
             $seite = "domains";
        }
        if ($conn->query($sql) === TRUE) {
           echo "<p class=green-text>Der Löschvorgang war erfolgreich.</p>";
           echo "<meta http-equiv='refresh' content='3; URL=/?seite=$seite'>";
           exit;
       } else {
           echo "Error: " . $sql . "<br>" . $conn->error;
           exit;
       }
    }
?>
<div class="valign-wrapper">
    <i class="medium material-icons red-text">warning</i>
     <p style="font-size: 1.2em;">Bist du sicher, dass du dieses Element löschen willst?</p>
</div>
  <div class="row">
    <form method="post" action="?seite=del&<?php echo $id?>" class="col s12">
     <div class ="row">
     <div class = "col s6">
           <button class="btn waves-effect waves-light" type="submit" name="action">Ja
           <i class="material-icons right">delete</i>
           </button>

     </div>
     <div class = "col s6">
         <a class="waves-effect waves-light btn" href="?seite=<?php echo $seite?>"><i class="material-icons right">cancel</i>Nein</a>
     </div>
     </div>
    </form>
  </div>

