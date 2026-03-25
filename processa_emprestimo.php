<?php

class Emprestimo
{
    private $pdo;

    public function __construct()
    {
        try {
            $this->pdo = new PDO("mysql:host=localhost;dbname=livraria_livh", "root", "");
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erro de conexão: " . $e->getMessage());
        }
    }

    // Verifica se o livro está disponível
    public function estaDisponivel($livro)
    {
        $sql = "SELECT id FROM emprestimos WHERE livro_nome = :livro AND status = 'ativo'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['livro' => $livro]);
        return $stmt->rowCount() === 0;
    }

    // Registra o empréstimo calculando a data automaticamente
    public function registrar($leitor, $livro, $semanas)
    {
        if (!$this->estaDisponivel($livro)) {
            return "Erro: O livro '$livro' já está emprestado!";
        }

        $dataDevolucao = date('Y-m-d', strtotime("+$semanas weeks"));

        $sql = "INSERT INTO emprestimos (leitor, livro_nome, data_devolucao_prevista, status) 
                VALUES (:leitor, :livro, :data, 'ativo')";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'leitor' => $leitor,
            'livro'  => $livro,
            'data'   => $dataDevolucao
        ]);

        return "Sucesso: Empréstimo realizado para o dia " . date('d/m/Y', strtotime($dataDevolucao));
    }
}

// --- Execução do Código ---

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $gerenciador = new Emprestimo();

    $resultado = $gerenciador->registrar(
        $_POST['leitor'],
        $_POST['livro'],
        intval($_POST['prazo_semanas'])
    );

    // Exibe o alerta (seja erro ou sucesso) e redireciona
    echo "<script>
            alert('$resultado');
            window.location.href = 'painel.php';
          </script>";
}
