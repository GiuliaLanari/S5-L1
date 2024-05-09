<?php
// collegamento database //
$host = 'localhost';
$db   = 'user_registration';
$user = 'root';
$pass = '';

$dsn = "mysql:host=$host;dbname=$db";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

$pdo = new PDO($dsn, $user, $pass, $options);

//  esporta i dati dal csv al database //

//CON DIVISORE "," //
$stmt= $pdo->prepare('SELECT * FROM user_date');
$stmt->execute();
$users = $stmt->fetchAll();

$file_name= "files/users.csv";
$file_handle = fopen($file_name, "w");

fputcsv($file_handle, array_keys($users[0]), ",");
foreach($users as $index => $users){
    fputcsv($file_handle, $users, ",");
}
fclose($file_handle);

//CON DIVISORE "\t" //

$stmt= $pdo->prepare('SELECT * FROM user_date');
$stmt->execute();
$users = $stmt->fetchAll();

$file_name= "files/usersconT.csv";
$file_handle = fopen($file_name, "w");

fputcsv($file_handle, array_keys($users[0]), "\t");
foreach($users as $index => $users){
    fputcsv($file_handle, $users, "\t");
}
fclose($file_handle);

//  importa i dati dal csv al database  INSERT//


$file_name= "files/usersconT.csv";

$file_handle = fopen($file_name, "r");//con questo mi leggo il file csv

//con il ciclo while vado a generare data che lo uso per poi aggiungere le informazioni del mio file csv nel mio databade
//usando INSERT INTO gli dico dove inserire e la stuttura che deve avere (non passo l'ID perche verà generato in automatico)
//in execute passo i vuovi valori mettendo es. "name"=>$data[1] (metto data= ai dati nel mio file.csv inoltre devo dirgli la posizione [1] ad esempio che corrisponde al nome)
while ($data = fgetcsv($file_handle, null, "\t")) {
    $stmt= $pdo->prepare('INSERT INTO user_date (name, surname, email, age) VALUES ( :name, :surname, :email, :age)');
    $stmt->execute([
        "name"=> $data[1],
        "surname"=> $data[2],
        "email"=> $data[3],
        "age"=> $data[4],
    ]);
}

fclose($file_handle);


