<?php
session_start();

// Dados de conexão com o banco de dados
$servername = "localhost";
$username = "root";  // Usuário do MySQL
$password = "";      // Senha do MySQL
$dbname = "empresa_agricola"; // Nome do banco de dados

// Criação da conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica a conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Verifica se os dados foram enviados via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $cpf = mysqli_real_escape_string($conn, $_POST['cpf']);
    $senha = $_POST['password'];

    // Consulta SQL para verificar se o cliente existe com o CPF e email fornecidos
    $sql = "SELECT * FROM clientes WHERE email = '$email' AND cpf = '$cpf'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Se o cliente existe, pega os dados
        $row = $result->fetch_assoc();
        // Verifica a senha
        if (password_verify($senha, $row['senha'])) {
            // Login bem-sucedido, iniciando a sessão
            $_SESSION['cliente_id'] = $row['id'];
            $_SESSION['cliente_nome'] = $row['nome'];
            header("Location: loja.html");
            exit();
        } else {
            // Senha incorreta
            echo "<script>alert('Senha incorreta. Tente novamente.'); window.location.href = 'LoginCliente.html';</script>";
        }
    } else {
        // E-mail ou CPF não encontrados
        echo "<script>alert('E-mail ou CPF não encontrados. Tente novamente.'); window.location.href = 'LoginCliente.html';</script>";
    }
}

$conn->close();
?>
