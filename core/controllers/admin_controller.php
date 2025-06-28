<?php
require_once __DIR__ . '/../functions/admin_functions.php';
require_once __DIR__ . '/../config/database.php';

class AdminController {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function login() {
        if (isset($_POST['email']) && isset($_POST['senha'])) {
            $email = $_POST['email'];
            $senha = $_POST['senha'];

            $admin = $this->findAdminByEmail($email);

            if ($admin && password_verify($senha, $admin['senha'])) {
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_nome'] = $admin['utilizador'];
                
                // Registrar login no histórico
                registrar_historico_admin($admin['id'], 'login', 'Login realizado com sucesso');
                
                header('Location: ?a=admin');
                exit;
            }
        }

        // Caso o login falhe, redirecione de volta para a página de login
        header('Location: ?a=login');
        exit;
    }

    private function findAdminByEmail($email) {
        $sql = "SELECT * FROM admin WHERE utilizador = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function atualizarPerfil() {
        if (!isset($_SESSION['admin_id'])) {
            header('Location: ?a=login');
            exit;
        }

        $tipo = $_GET['tipo'] ?? '';
        $admin_id = $_SESSION['admin_id'];
        $senha_atual = $_POST['text_senha_atual'] ?? '';

        // Verificar senha atual
        $admin = $this->findAdminById($admin_id);
        if (!password_verify($senha_atual, $admin['senha'])) {
            $_SESSION['erro'] = 'Senha atual incorreta!';
            header('Location: ?a=perfil_admin');
            exit;
        }

        if ($tipo === 'senha') {
            $nova_senha = $_POST['text_nova_senha'];
            $confirmar_senha = $_POST['text_confirmar_senha'];

            if ($nova_senha !== $confirmar_senha) {
                $_SESSION['erro'] = 'As senhas não coincidem!';
                header('Location: ?a=perfil_admin&tab=senha');
                exit;
            }

            $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
            $sql = "UPDATE admin SET senha = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("si", $senha_hash, $admin_id);
            
            if ($stmt->execute()) {
                registrar_historico_admin($admin_id, 'senha', 'Senha alterada com sucesso');
                $_SESSION['sucesso'] = 'Senha alterada com sucesso!';
            } else {
                $_SESSION['erro'] = 'Erro ao alterar senha!';
            }
        } 
        elseif ($tipo === 'email') {
            $novo_email = $_POST['text_utilizador'];
            
            $sql = "UPDATE admin SET utilizador = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("si", $novo_email, $admin_id);
            
            if ($stmt->execute()) {
                registrar_historico_admin($admin_id, 'email', 'Email alterado de ' . $admin['utilizador'] . ' para ' . $novo_email);
                $_SESSION['sucesso'] = 'Email alterado com sucesso!';
                $_SESSION['admin_nome'] = $novo_email;
            } else {
                $_SESSION['erro'] = 'Erro ao alterar email!';
            }
        }

        header('Location: ?a=perfil_admin');
        exit;
    }

    private function findAdminById($id) {
        $sql = "SELECT * FROM admin WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
} 