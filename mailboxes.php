<h1>Mailboxes</h1>
<?php
   if (isset($_GET['domain'])){
       $where = "WHERE domain='".$_GET['domain']."'";
       echo "<p>Domain: ".$_GET['domain']."</p>";
       echo "<a class='waves-effect waves-light btn' href='?seite=add&w=account&domain=".$_GET['domain']."'>
             <i class='material-icons right'>add</i>Neuer Account</a> ";
       echo " <a class='waves-effect waves-light btn' href='?seite=mailboxes'>
             <i class='material-icons right'>remove_red_eye</i>Alle Anzeigen</a>";
   } else {
       $where = "";
       echo "<a class='waves-effect waves-light btn' href='?seite=add&w=account'>
             <i class='material-icons right'>add</i>Neuer Account</a>";
   }
?>
<table class="highlight responsive-table">
  <thead>
    <tr>
      <th>Username</th>
      <th>Domain</th>
      <th>E-Mail</th>
      <th>Speicher</th>
      <th>Aktiv</th>
      <th>Nur Senden</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
  <?php
   $sql = "SELECT * FROM accounts ".$where."";
   $result = $conn->query($sql);
   if ($result->num_rows > 0) {
       // output data of each row
       while($row = $result->fetch_assoc()) {
           echo "<tr><td>".$row["username"]."</td><td>".$row["domain"]."</td>";
           echo "<td>".$row["username"]."@".$row["domain"]."</td>";
           echo "<td>".$row["quota"]." MB</td>";
           if ($row["enabled"] === "1"){
               echo "<td><span class='card-panel small-card green'>Ja</span></td>";
           } else {
               echo "<td><span class='card-panel small-card red'>Nein</span></td>";
           }
           if ($row["sendonly"] === "1"){
               echo "<td><span class='card-panel small-card orange'>Ja</span></td>";
           } else {
               echo "<td><span class='card-panel small-card green'>Nein</span></td>";
           }
           echo "<td><a class='waves-effect waves-light btn' 
                        href='?seite=edit&mailbox=". $row["id"]."'>
                 <i class='material-icons'>edit</i></a>
                 <a class='waves-effect waves-light btn' 
                        href='?seite=aliases&domain=". $row["domain"]."&mailbox=". $row["username"]."'>
                 <i class='material-icons'>call_merge</i></a>
                 <a class='waves-effect waves-light btn' 
                        href='?seite=del&mailbox=". $row["id"]."'>
                 <i class='material-icons'>delete</i></a></td></tr>";
       }
   } else {
       echo "<tr><br><br><span class='red-text'>Keine Mailbox konfiguriert</span></tr>";
   }

?>
  </tbody>  
</table>   
