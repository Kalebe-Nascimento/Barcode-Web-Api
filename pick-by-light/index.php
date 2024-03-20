<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .empty-msg {
            text-align: center;
            font-style: italic;
            color: #777;
        }

        .delete-btn {
            background-color: #ff6666;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 8px 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .delete-btn:hover {
            background-color: #ff4d4d;
        }

        .confirm-btn,
        .cancel-btn {
            background-color: #66cc66;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 8px 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .cancel-btn {
            background-color: #ff3333;
        }

        .confirm-btn:hover {
            background-color: #4caf50;
        }

        .cancel-btn:hover {
            background-color: #cc0000;
        }

        .confirmation {
            display: flex;
            justify-content: center;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .confirmation-box {
            background-color: white;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        .search-form {
            margin-bottom: 20px;
        }

        .search-form label {
            font-weight: bold;
            margin-right: 10px;
        }

        .search-form input[type=text] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            width: 200px;
        }

        .search-form button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-form button:hover {
            background-color: #45a049;
        }

        @media screen and (max-width: 600px) {
            table {
                overflow-x: auto;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>Products</h2>
        <form class="search-form" id="searchForm" onsubmit="return searchByBarcode()">
            <label for="barcode">Buscar por código de barras:</label>
            <input type="text" id="barcode" name="barcode" required>
            <button type="submit">Buscar</button>
        </form>
        <?php include 'display_products.php'; ?>
    </div>

    <script>
        function confirmDelete(barcode) {
            var confirmation = document.createElement("div");
            confirmation.classList.add("confirmation");
            confirmation.innerHTML = `
                <div class="confirmation-box">
                    <p>Tem certeza que deseja deletar este item?</p>
                    <button class="confirm-btn" onclick="deleteItem('${barcode}')">Sim</button>
                    <button class="cancel-btn" onclick="cancelDelete()">Não</button>
                </div>
            `;
            document.body.appendChild(confirmation);
        }

        function cancelDelete() {
            var confirmation = document.querySelector(".confirmation");
            if (confirmation) {
                document.body.removeChild(confirmation);
            }
        }

        function deleteItem(barcode) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "delete_item.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            console.log(response.message);
                            location.reload();
                        } else {
                            console.error(response.message);
                        }
                    } else {
                        console.error("Erro na requisição: " + xhr.status);
                    }
                }
            };
            xhr.send("barcode=" + encodeURIComponent(barcode));
        }

        document.addEventListener("DOMContentLoaded", function() {
            var emptyMsg = document.querySelector(".empty-msg");
            var searchForm = document.getElementById("searchForm");
            if (emptyMsg && emptyMsg.parentNode.style.display !== "none") {
                searchForm.style.display = "none";
            } else {
                searchForm.style.display = "";
            }
        });

        function searchByBarcode() {
            var input = document.getElementById("barcode").value.toUpperCase();
            var table = document.querySelector(".container table");
            var rows = table.getElementsByTagName("tr");

            for (var i = 1; i < rows.length; i++) {
                var cells = rows[i].getElementsByTagName("td");
                var rowFound = false;

                for (var j = 0; j < cells.length; j++) {
                    var cellText = cells[j].textContent || cells[j].innerText;
                    if (cellText.toUpperCase().indexOf(input) > -1) {
                        rowFound = true;
                        break;
                    }
                }

                if (rowFound) {
                    rows[i].style.display = "";
                } else {
                    rows[i].style.display = "none";
                }
            }
            return false;
        }
        setInterval(function() {
            location.reload();
        }, 5000);

    </script>

</body>

</html>
