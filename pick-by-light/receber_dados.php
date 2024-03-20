<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") //verifica se é um metodo POST{
    if (isset($_POST['barcode']) && isset($_POST['added_time']) && isset($_POST['modified_time']) && isset($_POST['title']) && isset($_POST['description']) && isset($_POST['brand']) && isset($_POST['manufacturing'])) {
        $db = new SQLite3('dados.db'); //caso seja e verifica se POST de requisição feito pelo RASP fornece todos os dados, caso sim cria um BD dados

        if (!$db) {
            die("Erro ao conectar ao banco de dados.");
        }

        $existingBarcode = $db->querySingle("SELECT COUNT(*) FROM Product WHERE barcode = '{$_POST['barcode']}'"); //verifica se já foi registado

        if ($existingBarcode == 0) //caso não inciar um novo produto na tabela{
            $stmt = $db->prepare('INSERT INTO Product (barcode, added_time, modified_time, title, description, brand, manufacturing) VALUES (:barcode, :added_time, :modified_time, :title, :description, :brand, :manufacturing)');

            $stmt->bindValue(':barcode', $_POST['barcode']);
            $stmt->bindValue(':added_time', $_POST['added_time']);
            $stmt->bindValue(':modified_time', $_POST['modified_time']);
            $stmt->bindValue(':title', $_POST['title']);
            $stmt->bindValue(':description', $_POST['description']);
            $stmt->bindValue(':brand', $_POST['brand']);
            $stmt->bindValue(':manufacturing', $_POST['manufacturing']);
            
            $result = $stmt->execute();

            if ($result) {
                echo "Dados inseridos com sucesso.";
            } else {
                echo "Erro ao inserir os dados.";
            }
        } else // caso sim, soma no contador de dados {
            $db->exec("UPDATE Product SET quantity = quantity + 1 WHERE barcode = '{$_POST['barcode']}'");
            echo "Quantidade do produto incrementada.";
        }
    } else {
        echo "Todos os campos devem ser fornecidos.";
    }
} else {
    echo "Método inválido.";
}
?>
