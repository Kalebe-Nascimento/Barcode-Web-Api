<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['barcode']) && isset($_POST['added_time']) && isset($_POST['modified_time']) && isset($_POST['title']) && isset($_POST['description']) && isset($_POST['brand']) && isset($_POST['manufacturing'])) {
        $db = new SQLite3('dados.db');

        if (!$db) {
            die("Erro ao conectar ao banco de dados.");
        }

        $existingBarcode = $db->querySingle("SELECT COUNT(*) FROM Product WHERE barcode = '{$_POST['barcode']}'");

        if ($existingBarcode == 0) {
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
        } else {
            echo "O código de barras já existe na tabela.";
        }
    } else {
        echo "Todos os campos devem ser fornecidos.";
    }
} else {
    echo "Método inválido.";
}
