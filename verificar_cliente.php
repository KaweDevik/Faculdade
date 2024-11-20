<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "seu_banco_de_dados"; // Alterar conforme o nome do seu banco

// Criação da conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Verifica se os dados foram enviados via GET
if (isset($_GET['email']) && isset($_GET['cpf'])) {
    $email = $_GET['email'];
    $cpf = $_GET['cpf'];

    // Consulta SQL para verificar se o cliente existe
    $sql = "SELECT * FROM clientes WHERE email = ? AND cpf = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $cpf); // Bind dos parâmetros
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Cliente encontrado
        echo json_encode(["status" => "success", "message" => "Cliente cadastrado."]);
    } else {
        // Cliente não encontrado
        echo json_encode(["status" => "error", "message" => "Cliente não encontrado."]);
    }

    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Dados inválidos."]);
}

$conn->close();
?>
