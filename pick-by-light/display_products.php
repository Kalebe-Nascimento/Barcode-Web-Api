<?php
try {
    $db = new SQLite3('dados.db');

    if (!$db) {
        die("Erro ao conectar ao banco de dados.");
    }

    $result = $db->querySingle('SELECT COUNT(*) FROM Product');

    if ($result > 0) {
        $stmt = $db->query('SELECT * FROM Product');

        echo "<table>";
        echo "<tr><th>Barcode</th><th>Added Time</th><th>Modified Time</th><th>Title</th><th>Description</th><th>Brand</th><th>Manufacturing</th><th>Action</th></tr>";

        while ($row = $stmt->fetchArray(SQLITE3_ASSOC)) {
            echo "<tr>";
            echo "<td>" . $row['barcode'] . "</td>";
            echo "<td>" . $row['added_time'] . "</td>";
            echo "<td>" . $row['modified_time'] . "</td>";
            echo "<td>" . $row['title'] . "</td>";
            echo "<td>" . $row['description'] . "</td>";
            echo "<td>" . $row['brand'] . "</td>";
            echo "<td>" . $row['manufacturing'] . "</td>";
            echo "<td><button class='delete-btn' onclick='confirmDelete(\"" . $row['barcode'] . "\")'>Delete</button></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p class='empty-msg'>Ainda não há nenhum produto.</p>";
    }
} catch (Exception $e) {
    echo "<p class='empty-msg'>Erro ao exibir os dados: " . $e->getMessage() . "</p>";
}
?>