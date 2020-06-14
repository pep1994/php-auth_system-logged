<?php 

class AuthSys {
        private $PDO;
        public function __construct($PDOconn){
            $this -> PDO = $PDOconn;
        }

        public function registraNuovoUtente($post){

            // rimozione spazi 
            $in_uname = trim($post['uname']);
            $in_pwd = trim($post['pwd']);
            $in_repwd = trim($post['re_pwd']);
            $in_nome = trim($post['nome']);
            $in_email = trim($post['email']);
            // controllo se l'username contiene solo caratteri alfa-numerici e che sia compreso fra 8 e 12 caratteri
            if (!ctype_alnum($in_uname) && mb_strlen($in_uname) >= 8 && mb_strlen($in_uname) <= 12) {
                throw new Exception("Username non valida");  
            }

            try {
                // controllo se nel database è già presente l'username
                $q = "SELECT * FROM Utenti WHERE username = :uname";
                $rq = $this -> PDO -> prepare($q);
                $rq -> bindParam(":uname", $in_uname, PDO::PARAM_STR);
                $rq -> execute();  
                if ($rq -> rowCount() > 0) {
                    throw new Exception("Username già presente");   
                }   
            } catch (PDOException $e) {
                echo $e -> getMessage();
            }
            // controllo se la password contenga almeno una lettera
            if (!preg_match('/[a-z]/', $in_pwd)) {
                throw new Exception("La password deve contenere almeno una lettera");
            }
            // controllo se la password contenga almeno un alettera maiuscola
            if (!preg_match('/[A-Z]/', $in_pwd)) {
                throw new Exception("La password deve contenere almeno una lettera maiuscola");
            }
            // controllo se la password contenga almeno un numero
            if (!preg_match('/[0-9]/', $in_pwd)) {
                throw new Exception("La password deve contenere almeno un numero");
            }
            // controllo che la password contenga almeno un carattere speciale
            if (!preg_match('/[_\-\$@#!\?]/', $in_pwd)) {
                throw new Exception("La password deve contenere almeno un carattere speciale");
            }
            // controllo che la password sia lunga almeno 8 caratteri
            if (!mb_strlen($in_pwd) >= 8) {
                throw new Exception("La password deve essere lunga almeno 8 caratteri");
            }
            // controllo che la conferma password sia uguale alla password
            if (strcmp($in_pwd, $in_repwd) !== 0) {
                throw new Exception("La conferma non corrisponde alla password");
            }
            // controllo email
            if (!filter_var($in_email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Email non valida");             
            }
            // controllo nome
            if (!filter_var($in_nome, FILTER_SANITIZE_STRING) ) {
                throw new Exception("Nome non valido");
            }
            if (!mb_strlen($in_nome) > 0) {
                throw new Exception("Nome non indicato");
            }

            // creazione password criptata
            $pwd_hash = password_hash($in_pwd, PASSWORD_DEFAULT);

            // superati tutti i controlli realizzazione query per aggiungere l'utente al DB
            try {      
                $q = "INSERT INTO Utenti (username, password, nome, email) VALUES (:uname, :pwd, :nome, :email)";
                $rq = $this -> PDO -> prepare($q);
                $rq -> bindParam(":uname", $in_uname, PDO::PARAM_STR);
                $rq -> bindParam(":pwd", $pwd_hash, PDO::PARAM_STR);
                $rq -> bindParam(":nome", $in_nome, PDO::PARAM_STR);
                $rq -> bindParam(":email", $in_email, PDO::PARAM_STR);
                $rq -> execute();
            } catch (PDOException $e) {
                echo "Errore nell'inserimento";
            }

            return true;
        }
        public function login($username, $password){
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
                echo $e -> getMessage();
            }
        }

        public function logout(){}

        public function utenteLoggato(){}

    }
?>