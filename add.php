<?php
if (isset($_GET['w'])){
    $what = $_GET['w'];
} else {
    $what = "";
    echo "<p class='red-text'>Die Seite wurde ohne Argument aufgerufen.</p>";
    exit;
}

# Anzeige der Formulare
if ($what === "domain"){
    $domain_inv = "class='validate'";
    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        if (isset($_POST['domain'])){
            $domain = $_POST['domain'];
            if (preg_match("/^[0-9a-z-]+\.(?:(?:co|or|gv|ac)\.)?[a-z]{2,7}$/i",$domain)){
                  $sql = "INSERT INTO domains (domain) VALUES('$domain') ";
                  if ($conn->query($sql) === TRUE) {
                      echo "<p class=green-text>Die Domain $domain wurde erfolgreich in die Datenbank eingetragen.</p>";
                      echo "<meta http-equiv='refresh' content='3; URL=/'>";
                      exit;
                  } else {
                      echo "Error: " . $sql . "<br>" . $conn->error;
                      exit;
                  }                  
            } else {
                $domain_inv="data-error='Domain nicht valid' class='validate invalid'";
            }
       } else {
            $domain = "";
            $domain_inv="data-error='Domain nicht gesetzt' class='validate invalid'";
       }


}
?>
<h1>Domain erfassen</h1>
<p>Hier kannst du eine neue Domain dem Mailserver hinzufügen.</p>
<div class="row">
  <form action="?seite=add&w=domain" method=post class="col s12">
    <div class="row">
      <div class="input-field col s6">
        <input placeholder="example.com" id="domain" name="domain" type="text" <?php echo $domain_inv ?>>
        <label for="domain">Domain</label>
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
<div class="row">
  <div class="col s12 m6">
    <div class="card-panel red darken-4">
      <span class="white-text">Vergiss nicht den MX-Record der Domain auf die Hauptdomain anzupassen: <br>
    <div class="card-panel red">
      <span class="black-text">example.com 86400 IN MX 0 <?php echo $maindomain; ?>.</span>
    </div>  
      </span>
     </div>
   </div>
</div>

<?php
} elseif ($what === "account"){
    if (isset($_GET['domain'])){
        $domain=$_GET['domain'];
    } else {
        $domain="";
    }

    $username_inv = "class='validate'";
    $passwort_inv = "class='validate'";
    $passwort_wied_inv = "class='validate'";
    $speicher_inv = "class='validate'";
    $speicher = "2048";

    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        if (isset($_POST['username'])){
            $username = $_POST['username'];
        } else {
            $username = "";
            $username_inv = "data-error='Username nicht gesetzt' class='validate invalid'";
        }
        if (isset($_POST['domain'])){
            $domain = $_POST['domain'];
        } else {
            $domain = ""; 
            $username_inv = "data-error='Domain nicht gesetzt' class='validate invalid'";
       }
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
  
     if (($username !== "") && ($domain !== "") && ($passwort !== "") && ($speicher !== "")){
         $salt = substr(sha1(rand()), 0, 16);
         $password = "{SHA512-CRYPT}" . crypt($password, "$6$$salt");
         $sql = "INSERT INTO accounts (username, domain, password, quota, enabled, sendonly) values ('$username', '$domain', '$password', $speicher, $account_enabled, $sendonly)";
         if ($conn->query($sql) === TRUE) {
               echo "<p class=green-text>Der Account $username@$domain wurde erfolgreich in die Datenbank eingetragen.</p>";
               echo "<meta http-equiv='refresh' content='3; URL=/?seite=mailboxes'>";
               exit;
          } else {
               echo "Error: " . $sql . "<br>" . $conn->error;
               exit;
          }

     }
 }
?>
<h1>Account erfassen</h1>
<p>Hier kannst du einen neuen Account anlegen</p>
<div class="row">
  <form action="?seite=add&w=account" method=post class="col s12">
    <div class="row">
      <div class="input-field col s9 m5">
        <i class="material-icons prefix">mail</i>
        <input id="username" name="username" type="text" value=<?php echo "'$username'"; echo $username_inv; ?>>
        <label for="username">Username</label>
      </div>
      <div class="col s1">
          <span class="btn-large disabled teal">@</span>
      </div>
      <div class="input-field col s12 m6">
        <select name="domain">
          <option value="" disabled selected>Wähle Domain aus</option>
          <?php
              if ($domain === ""){
                $sql = "SELECT * FROM domains";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                  while($row = $result->fetch_assoc()) {
                      echo "<option value='".$row["domain"]."'>".$row["domain"]."</option>";
                  }
                }
             } else {
               echo "<option value='$domain' selected>$domain</option>";
            }
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
              <input name="account_enabled" type="checkbox" checked="checked">
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
              <input name="sendonly" type="checkbox">
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
} elseif ($what === "alias"){
    if (isset($_GET['domain'])){
        $domain=$_GET['domain'];
    } else {
        $domain="";
    }

    $username_inv = "class='validate'";
    $zielmail_inv = "class='validate'";

    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        if (isset($_POST['username'])){
            $username = $_POST['username'];
        } else {
            $username = "";
            $username_inv = "data-error='Username nicht gesetzt' class='validate invalid'";
        }

        if (isset($_POST['domain'])){
            $domain = $_POST['domain'];
        } else {
            $domain = "";
            $username_inv = "data-error='Domain nicht gesetzt' class='validate invalid'";
       }

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


     if (($username !== "") && ($domain !== "") && ($zielmail !== "")){
         $sql = "INSERT INTO aliases (source_username, source_domain, destination_username, destination_domain, enabled) values ('$username', '$domain', '$zielmail_user', '$zielmail_domain', $alias_enabled)";
         if ($conn->query($sql) === TRUE) {
               echo "<p class=green-text>Die Alias für $username@$domain wurde erfolgreich in die Datenbank eingetragen.</p>";
               echo "<meta http-equiv='refresh' content='3; URL=/?seite=aliases'>";
               exit;
          } else {
               echo "Error: " . $sql . "<br>" . $conn->error;
               exit;
          }

     }
    }
?>
<h2>Alias hinzufügen</h2>
<p>Hier kannst du eine Alias erstellen.</p>
<div class="row">
  <form action="?seite=add&w=alias" method=post class="col s12">
    <div class="row">
      <div class="input-field col s9 m5">
        <i class="material-icons prefix">mail</i>
        <input id="username" name="username" type="text" value=<?php echo "'$username'"; echo $username_inv; ?>>
        <label for="username">Username</label>
      </div>
      <div class="col s1">
          <span class="btn-large disabled teal">@</span>
      </div>
      <div class="input-field col s12 m6">
        <select name="domain">
          <option value="" disabled selected>Wähle Domain aus</option>
          <?php
              if ($domain === ""){
                $sql = "SELECT * FROM domains";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                  while($row = $result->fetch_assoc()) {
                      echo "<option value='".$row["domain"]."'>".$row["domain"]."</option>";
                  }
                }
             } else {
               echo "<option value='$domain' selected>$domain</option>";
            }
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
              <input name="alias_enabled" type="checkbox" checked="checked">
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
