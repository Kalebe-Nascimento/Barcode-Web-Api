<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['barcode'])) {
    $barcode = $_POST['barcode'];

    try {
        $db = new SQLite3('dados.db');

        if (!$db) {
            die("Erro ao conectar ao banco de dados.");
        }

        $stmt = $db->prepare('DELETE FROM Product WHERE barcode = :barcode');
        $stmt->bindValue(':barcode', $barcode, SQLITE3_TEXT);
        $result = $stmt->execute();

        if ($result) {
            echo json_encode(array("success" => true, "message" => "Item deletado com sucesso."));
        } else {
            echo json_encode(array("success" => false, "message" => "Erro ao deletar o item."));
        }

        $db->close();
    } catch (Exception $e) {
        echo json_encode(array("success" => false, "message" => "Erro ao deletar o item: " . $e->getMessage()));
    }
} else {
    echo json_encode(array("success" => false, "message" => "Código de barras do item não fornecido."));
}
?>
