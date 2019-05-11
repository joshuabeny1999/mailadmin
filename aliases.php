<h1>Aliases</h1>
<?php
   if (isset($_GET['domain'])){
       if (isset($_GET['mailbox'])){
           $where = "WHERE destination_domain='".$_GET['domain']."' AND destination_username='".$_GET['mailbox']."'";
           echo "<p>Aliases für: ".$_GET['mailbox']."@".$_GET['domain']."</p>";
       } else {
           $where = "WHERE source_domain='".$_GET['domain']."'";
           echo "<p>Domain: ".$_GET['domain']."</p>";
       }
       echo "<a class='waves-effect waves-light btn' href='?seite=add&w=alias&domain=".$_GET['domain']."'>
             <i class='material-icons right'>add</i>Neue Alias</a> ";
       echo " <a class='waves-effect waves-light btn' href='?seite=aliases'>
             <i class='material-icons right'>remove_red_eye</i>Alle Anzeigen</a>";
   } else {
       $where = "";
       echo "<a class='waves-effect waves-light btn' href='?seite=add&w=alias'>
             <i class='material-icons right'>add</i>Neue Alias</a>";
   }
?>
<table class="highlight responsive-table">
  <thead>
    <tr>
      <th>Source E-Mail</th>
      <th>Ziel E-Mail</th>
      <th>Aktiv</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
  <?php
   $sql = "SELECT * FROM aliases ".$where."";
   $result = $conn->query($sql);
   if ($result->num_rows > 0) {
       // output data of each row
       while($row = $result->fetch_assoc()) {
           echo "<tr><td>".$row["source_username"]."@".$row["source_domain"]."</td>";
           echo "<td>".$row["destination_username"]."@".$row["destination_domain"]."</td>";
           if ($row["enabled"] === "1"){
               echo "<td><span class='card-panel small-card green'>Ja</span></td>";
           } else {
               echo "<td><span class='card-panel small-card red'>Nein</span></td>";
           }
           echo "<td><a class='waves-effect waves-light btn' 
                        href='?seite=edit&aliases=". $row["id"]."'>
                 <i class='material-icons'>edit</i></a>
                 <a class='waves-effect waves-light btn' 
                        href='?seite=del&aliases=". $row["id"]."'>
                 <i class='material-icons'>delete</i></a></td></tr>";
       }
   } else {
       echo "<tr><br><br><span class='red-text'>Keine Aliases konfiguriert</span></tr>";
   }

?>
  </tbody>  
</table>   
