<?php 

    require_once __DIR__ .  "/authSysSecure.php";

    class AuthSys extends Secure\AuthSysSecure {
        private $PDO;

        public function __construct($PDOconn){
            $this -> PDO = $PDOconn;    
        }

        public function usernameExists ($in_uname) {
            // controllo se nel database è già presente l'username
            $q = "SELECT * FROM Utenti WHERE username = :uname";
            $rq = $this -> PDO -> prepare($q);
            $rq -> bindParam(":uname", $in_uname, PDO::PARAM_STR);
            $rq -> execute();   
            if ($rq -> rowCount() > 0) {
                return true;   
            }
            return false;    
        }

        public function checkMod ($post) {
            // controllo se l'username contiene solo caratteri alfa-numerici e che sia compreso fra 8 e 12 caratteri
            if (!ctype_alnum($post['uname']) && mb_strlen($post['uname']) >= 8 && mb_strlen($post['uname']) <= 12) {
                throw new Exception("Username non valida");  
            }
            // controllo se la password contenga almeno una lettera
            if (!preg_match('/[a-z]/', $post['pwd'])) {
                throw new Exception("La password deve contenere almeno una lettera");
            }
            // controllo se la password contenga almeno un alettera maiuscola
            if (!preg_match('/[A-Z]/', $post['pwd'])) {
                throw new Exception("La password deve contenere almeno una lettera maiuscola");
            }
            // controllo se la password contenga almeno un numero
            if (!preg_match('/[0-9]/', $post['pwd'])) {
                throw new Exception("La password deve contenere almeno un numero");
            }
            // controllo che la password contenga almeno un carattere speciale
            if (!preg_match('/[_\-\$@#!\?]/', $post['pwd'])) {
                throw new Exception("La password deve contenere almeno un carattere speciale");
            }
            // controllo che la password sia lunga almeno 8 caratteri
            if (!mb_strlen($post['pwd']) >= 8) {
                throw new Exception("La password deve essere lunga almeno 8 caratteri");
            }
            // controllo che la conferma password sia uguale alla password
            if (strcmp($post['pwd'], $post['re_pwd']) !== 0) {
                throw new Exception("La conferma non corrisponde alla password");
            }
            // controllo email
            if (!filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Email non valida");             
            }
            // controllo nome
            if (!mb_strlen($post['nome']) > 0) {
                throw new Exception("Nome non indicato");
            }
        }

        public function addUser($post, $pwd_hash, $token) {
            $q = "INSERT INTO Utenti (username, password, nome, email, token) VALUES (:uname, :pwd, :nome, :email, :token)";
                $rq = $this -> PDO -> prepare($q);
                $rq -> bindParam(":uname", $post['uname'], PDO::PARAM_STR);
                $rq -> bindParam(":pwd", $pwd_hash, PDO::PARAM_STR);
                $rq -> bindParam(":nome", $post['nome'], PDO::PARAM_STR);
                $rq -> bindParam(":email", $post['email'], PDO::PARAM_STR);
                $rq -> bindParam(":token", $token, PDO::PARAM_STR);
                $rq -> execute();
                return $this -> PDO -> lastInsertId();
        }

        public function registraNuovoUtente($post){
            
            // rimozione spazi e sanificazione valori input
            foreach ($post as $key => $value) {
                $post[$key] = trim($this -> cleanInput($value, 'str'));
            }

            try {
                if($this -> usernameExists($post['uname'])){
                    return "L'username indicata è già presente";
                }
                $this -> checkMod($post);
                // creazione password criptata
                $pwd_hash = password_hash($post['pwd'], PASSWORD_DEFAULT);
                // superati tutti i controlli realizzazione query per aggiungere l'utente al DB  
                $token = bin2hex(random_bytes(32));
                $this -> addUser($post, $pwd_hash, $token);
            } 
            catch (PDOException $e) {
                return "Errore. Riprova più tardi";
            }
            catch (Exception $e) {
                return $e -> getMessage();
            }
  
            return 'Sei stato correttamente registrato';
        }

        public function login($username, $password){
            $username = $this -> cleanInput($username, 'str');
            $password = $this -> cleanInput($password, 'str');
            try {
                // controllo che l'username sia presente nel database
                $q = "SELECT * FROM Utenti WHERE username = :username";
                $rq = $this -> PDO -> prepare($q);
                $rq -> bindParam(":username", $username, PDO::PARAM_STR);
                $rq -> execute();
                if ($rq -> rowCount() === 0) {
                    throw new Exception("L'username non è valido");
                }
                $row = $rq -> fetch(PDO::FETCH_ASSOC);
                // controllo che la password inserita dall'utente corrisponda alla password criptata sul DB
                if (!password_verify($password, $row['password'])) {
                   throw new Exception("Password non corretta");
                }
                // superati i controlli possiamo inserire l'utente dentro la tabella utenti loggati
                $session_id = session_id();
                $user_id = $row['id'];
                $q = "INSERT INTO UtentiLoggati (session_id, user_id) VALUES (:sessionid, :userid)";
                $rq = $this -> PDO -> prepare($q);
                $rq -> bindParam(":sessionid", $session_id, PDO::PARAM_STR);
                $rq -> bindParam(":userid", $user_id, PDO::PARAM_INT);
                $rq -> execute();

                return true;

            } catch (PDOException $e) {
                echo  "errore";
            }
        }

        public function logout(){
            try {
                $q = "DELETE FROM UtentiLoggati WHERE session_id = :sessionid";
                $rq = $this -> PDO -> prepare($q);
                $session_id = session_id();
                $rq -> bindParam(":sessionid", $session_id, PDO::PARAM_STR);
                $rq -> execute();
            } catch (PDOException $e) {
                echo "Errore logout!";
            }
            return true;
        }

        public function utenteLoggato(){
            try {
                $q = "SELECT * FROM UtentiLoggati WHERE session_id = :sessionid";
                $rq = $this -> PDO -> prepare($q);
                $session_id = session_id();
                $rq -> bindParam(":sessionid", $session_id, PDO::PARAM_STR);
                $rq -> execute();
                if ($rq -> rowCount() == 0) {
                    return false;
                } else {
                    return true;
                }     
            } catch (PDOException $e) {
               echo "Errore";
            }
        }

        public function cancellaUtente() {
            try {
                $session_id = session_id();
                $q = "SELECT * FROM UtentiLoggati WHERE session_id = :sessionid";
                $rq = $this -> PDO -> prepare($q);
                $rq -> bindParam(":sessionid", $session_id, PDO::PARAM_STR);
                $rq -> execute();   
                if ($rq -> rowCount() !== 1) {
                    throw new Exception("Errore, impossibile eliminare l'account");              
                } 
                $row = $rq -> fetch(PDO::FETCH_ASSOC);
                $id = $row['user_id'];
                $q = "DELETE FROM UtentiLoggati WHERE session_id = :sessionid";
                $rq = $this -> PDO -> prepare($q);
                $rq -> bindParam(":sessionid", $session_id, PDO::PARAM_STR);
                $rq -> execute();
                $q = "DELETE FROM Utenti WHERE id = :id";
                $rq = $this -> PDO -> prepare($q);
                $rq -> bindParam(":id", $id, PDO::PARAM_INT);
                $rq -> execute();
                return true;

            } catch(PDOException $e) {
                echo "Errore";
            }
        }

    }
?>