<?php
if (isset($_GET['mailbox'])){
   $account=$_GET['mailbox'];

    $passwort_inv = "class='validate'";
    $passwort_wied_inv = "class='validate'";
    $speicher_inv = "class='validate'";

    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        if (isset($_POST['passwort'])){
            if (isset($_POST['passwort_wied'])){
                if ($_POST['passwort'] === $_POST['passwort_wied']){
                    $password = $_POST['passwort'];
                }  else {
                    $passwort_wied_inv = "data-error='Wiederholtes Passwort ist nicht identisch' class='validate invalid'";
                }
            } else {
                 $passwort_wied_inv = "data-error='Passwort muss wiederholt werden' class='validate invalid'";
            }
        } else {
            $passwort = "";
            $passwort_inv = "data-error='Passwort nicht gesetzt' class='validate invalid'";
        }

      if (isset($_POST['speicher'])){
          $speicher = $_POST['speicher'];
      } else {
          $speicher = ""; 
          $peicher_inv = "data-error='Speicher MB nicht gesetzt' class='validate invalid'";
      }

      if (isset($_POST['account_enabled'])){
         $account_enabled = "true";
      } else {
        $account_enabled = "false";
      }

      if (isset($_POST['sendonly'])){
         $sendonly = "true";
      } else {
        $sendonly = "false";
      }
  
     if ($speicher !== ""){
         if ($password !== ""){
             $salt = substr(sha1(rand()), 0, 16);
             $password = "{SHA512-CRYPT}" . crypt($password, "$6$$salt");
             $sql = "UPDATE accounts SET password='$password', quota=$speicher, enabled=$account_enabled, sendonly=$sendonly WHERE id=$account";
         } else {
             $sql = "UPDATE accounts SET quota=$speicher, enabled=$account_enabled, sendonly=$sendonly WHERE id=$account";
         }
         if ($conn->query($sql) === TRUE) {
               echo "<p class=green-text>Der Account wurde erfolgreich aktualisiert.</p>";
               echo "<meta http-equiv='refresh' content='3; URL=/?seite=mailboxes'>";
               exit;
          } else {
               echo "Error: " . $sql . "<br>" . $conn->error;
               exit;
          }

     }
 } else {
    $sql = "SELECT * FROM accounts WHERE id='$account'";
     $result = $conn->query($sql); 

   if ($result->num_rows > 0){
       // output data of each row
       while($row = $result->fetch_assoc()) {
           $username = $row["username"];
           $domain = $row["domain"];
           $speicher = $row["quota"];
           if ($row["enabled"] === "1"){
               $account_enabled = "true";
           } else {
               $account_enabled = "false";
           }
           if ($row["sendonly"] === "1"){
               $sendonly = "true";
           } else {
                $sendonly = "false";
           }
       }
   } else {
       echo "<tr><br><br><span class='red-text'>Ungültige ID.</span></tr>";
   }
 }
?>
<h1>Account editieren</h1>
<p>Hier kannst du den Account editieren. Wenn du kein neues Passwort setzen willst, einfach leer lassen.</p>
<div class="row">
  <form action="?seite=edit&mailbox=<?php echo $account;?>" method=post class="col s12">
    <div class="row">
      <div class="input-field col s9 m5">
        <i class="material-icons prefix">mail</i>
        <input id="username" name="username" type="text" value=<?php echo "'$username'"; echo $username_inv; ?> disabled>
        <label for="username">Username</label>
      </div>
      <div class="col s1">
          <span class="btn-large disabled teal">@</span>
      </div>
      <div class="input-field col s12 m6">
        <select name="domain" disabled>
          <option value="" disabled selected>Wähle Domain aus</option>
          <?php
               echo "<option value='$domain' selected>$domain</option>";
          ?>          
        </select>
        <label>Domain</label>
      </div>
    </div>
    <div class="row">
      <div class="input-field col s6">
          <i class="material-icons prefix">lock</i>   
          <input id="passwort" name="passwort" type="password" <?php echo $passwort_inv; ?>>
          <label for="passwort">Passwort</label>
      </div>
       <div class="input-field col s6">      
          <input id="passwort_wied" name="passwort_wied" type="password" <?php echo $passwort_wied_inv; ?>>
          <label for="passwort_wied">Passwort wiederholen</label>
      </div>
    </div> 
    <div class="row">
      <div class="input-field col s6">      
        <i class="material-icons prefix">save</i> 
        <input id="speicher" name="speicher" type="number" value=<?php echo "'$speicher' "; echo $speicher_inv; ?>>
        <label for="speicher">Speicher MB</label>
      </div>
    </div>  
    <div class="row">
      <div class="col s12">
         <p>Account aktiviert?
           <div class="switch">
             <label>
               Nein
              <input name="account_enabled" type="checkbox" 
              <?php
                 if ($account_enabled === "true"){ 
                     echo "checked='checked'>";
                 } else {
                     echo " >";
                 }
              ?>
              <span class="lever"></span>
              Ja
            </label>
          </div>
        </p>
      </div>
      <div class="col s12">
        <p>Nur senden?
           <div class="switch">
             <label>
               Nein
              <input name="sendonly" type="checkbox"
              <?php
                 if ($sendonly === "true"){ 
                     echo "checked='checked'>";
                 } else {
                     echo " >";
                 }
              ?>
              <span class="lever"></span>
              Ja
            </label>
          </div>
        </p>
      </div>    
    <div class="row">
      <div class="input-field col s12">
        <button class="btn waves-effect waves-light" type="submit" name="add-domain">Speichern
          <i class="material-icons right">save</i>
        </button>
      </div>
    </div>
  </form>
</div>
<?php
} elseif (isset($_GET['aliases'])){
    $alias = $_GET['aliases'];
    $username_inv = "class='validate'";
    $zielmail_inv = "class='validate'";

    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
       if (isset($_POST['zielmail'])){
           $zielmail = $_POST['zielmail'];
           $zielmail_arr = explode('@', $zielmail);
           $zielmail_user = $zielmail_arr[0];
           $zielmail_domain = $zielmail_arr[1];
       } else {
           $zielmail_str = "";
           $zielmail_inv = "data-error='Username nicht gesetzt' class='validate invalid'";
      }

      if (isset($_POST['alias_enabled'])){
         $alias_enabled = "true";
      } else {
        $alias_enabled = "false";
      }


     if ($zielmail !== ""){
         $sql = "UPDATE aliases SET destination_username='$zielmail_user', destination_domain='$zielmail_domain', enabled=$alias_enabled WHERE id=$alias";
         if ($conn->query($sql) === TRUE) {
               echo "<p class=green-text>Die Alias wurde erfolgreich aktualisiert.</p>";
               echo "<meta http-equiv='refresh' content='3; URL=/?seite=aliases'>";
               exit;
          } else {
               echo "Error: " . $sql . "<br>" . $conn->error;
               exit;
          }

     }
    } else {

    $sql = "SELECT * FROM aliases WHERE id='$alias'";
     $result = $conn->query($sql);

       if ($result->num_rows > 0){
          // output data of each row
           while($row = $result->fetch_assoc()) {
               $username = $row["source_username"];
               $domain = $row["source_domain"];
                $zielmail = $row["destination_username"]."@".$row["destination_domain"];
               if ($row["enabled"] === "1"){
                   $alias_enabled = "true";
               } else {
                   $alias_enabled = "false";
               }
            }
       } else {
            echo "<tr><br><br><span class='red-text'>Ungültige ID.</span></tr>";
       }
   }
?>
<h2>Alias editieren</h2>
<p>Hier kannst du die Alias editieren</p>
<div class="row">
  <form action="?seite=edit&aliases=<?php echo $alias;?>" method=post class="col s12">
    <div class="row">
      <div class="input-field col s9 m5">
        <i class="material-icons prefix">mail</i>
        <input id="username" name="username" type="text" value=<?php echo "'$username'"; echo $username_inv; ?> disabled>
        <label for="username">Username</label>
      </div>
      <div class="col s1">
          <span class="btn-large disabled teal">@</span>
      </div>
      <div class="input-field col s12 m6">
        <select name="domain" disabled>
          <option value="" disabled selected>Wähle Domain aus</option>
          <?php
               echo "<option value='$domain' selected>$domain</option>";
          ?>
        </select>
        <label>Domain</label>
      </div>
    </div>
    <div class="row">
      <div class="input-field col s9 m5">
        <i class="material-icons prefix">mail_outline</i>
        <input id="zielmail" name="zielmail" type="email" value=<?php echo "'$zielmail'"; echo $zielmail_inv; ?>>
        <label for="zielmail">Ziel E-Mail-Adresse</label>
      </div>
   </div>
    <div class="row">
      <div class="col s12">
         <p>Alias aktiviert?
           <div class="switch">
             <label>
               Nein
              <input name="alias_enabled" type="checkbox"
              <?php
                 if ($alias_enabled === "true"){
                     echo "checked='checked'>";
                 } else {
                     echo " >";
                 }
              ?>
              <span class="lever"></span>
              Ja
            </label>
          </div>
        </p>
      </div>
    </div>
    <div class="row">
      <div class="input-field col s12">
        <button class="btn waves-effect waves-light" type="submit" name="add-domain">Speichern
          <i class="material-icons right">save</i>
        </button>
      </div>
    </div>
  </form>
</div>
<?php
}
?>
