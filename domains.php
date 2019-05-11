<h1>Domains</h1> <a class='waves-effect waves-light btn' href='?seite=add&w=domain'><i class='material-icons right'>add</i>Neue Domain</a>
<table class="highlight responsive-table">
  <thead>
    <tr>
      <th>Domain</th>
      <th>Mailboxes</th>
      <th>Aliases</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
  <?php
   $sql = "SELECT * FROM domains";
   $result = $conn->query($sql);
   if ($result->num_rows > 0) {
       // output data of each row
       while($row = $result->fetch_assoc()) {
           $sql2 = "SELECT (SELECT count(id) FROM accounts 
                    WHERE domain='".$row["domain"]."') as account,
                    (SELECT count(id) FROM aliases 
                    WHERE source_domain='".$row["domain"]."') as alias";
           $result2 = $conn->query($sql2);
           $row2 = $result2->fetch_assoc();
           echo "<tr><td>" . $row["domain"]. "</td><td>
                 <a href='?seite=mailboxes&domain=". $row["domain"]."'>"
                 .$row2['account']."</a>";
           echo "  <a class='waves-effect waves-light btn-floating green' 
                      href='?seite=add&w=account&domain=". $row["domain"]."'>
                   <i class='material-icons'>add</i></a></td>";
           echo "<td><a href='?seite=aliases&domain=". $row["domain"]."'>"
                 .$row2['alias']."</a>";
           echo "  <a class='waves-effect waves-light btn-floating green' 
                      href='?seite=add&w=alias&domain=". $row["domain"]."'>
                   <i class='material-icons'>add</i></a></td>";
           echo "<td><a class='waves-effect waves-light btn' 
                        href='?seite=del&domain=". $row["id"]."'>
                 <i class='material-icons'>delete</i></a></td></tr>";
       }
   } else {
       echo "<tr>Keine Domain konfiguriert</tr>";
   }

?>
  </tbody>
</table>   
