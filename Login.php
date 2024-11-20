<?php
session_start();

// Dados de conexão com o banco de dados
$servername = "localhost";  // Servidor do MySQL (geralmente 'localhost' no phpMyAdmin)
$username = "root";         // Nome de usuário do MySQL (padrão é 'root' em ambientes locais)
$password = "";             // Senha do MySQL (deixe em branco se for local)
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
    $senha = $_POST['password'];

    // Consulta SQL para verificar se o funcionário existe com o email fornecido
    $sql = "SELECT * FROM funcionarios WHERE email = '$email'";  // Tabela de funcionários
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Se o funcionário existe, pega os dados
        $row = $result->fetch_assoc();
        // Verifica a senha (supondo que esteja criptografada no banco)
        if (password_verify($senha, $row['senha'])) {
            // Login bem-sucedido, iniciando a sessão
            $_SESSION['funcionario_id'] = $row['id'];  // Armazena o ID do funcionário na sessão
            $_SESSION['funcionario_nome'] = $row['nome']; // Armazena o nome do funcionário na sessão

            // Redireciona para o Dashboard
            header("Location: Dashboard.html");
            exit();
        } else {
            // Senha incorreta
            echo "<script>alert('Senha incorreta. Tente novamente.'); window.location.href = 'Login.php';</script>";
        }
    } else {
        // E-mail não encontrado
        echo "<script>alert('E-mail não encontrado. Tente novamente.'); window.location.href = 'Login.php';</script>";
    }
}

$conn->close();
?>
