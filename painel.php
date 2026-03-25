<?php
// Conexão com o banco de dados
try {
    $pdo = new PDO("mysql:host=localhost;dbname=livraria_livh", "root", "");
} catch (Exception $e) {
    $erro_banco = true;
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>LIVH Bookstore - Painel</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header class="top-nav">
        <div class="logo">LIVH <span>BOOKSTORE</span></div>
        <nav>
            <button id="btn-aba-emp" class="nav-btn active">Empréstimo</button>
            <button id="btn-aba-dev" class="nav-btn">Devoluções/Atrasos</button>
        </nav>
    </header>

    <main class="container">
        <div class="card">
            <h3>Livraria LIVH</h3>
            <h4 id="card-subtitle">Novo Empréstimo</h4>

            <div id="secao-emprestimo">
                <form action="processa_emprestimo.php" method="POST">
                    <div class="input-group">
                        <label>👤 Nome do Leitor</label>
                        <input type="text" name="leitor" placeholder="Digite o nome..." required>
                    </div>
                    <div class="input-group">
                        <label>📘 Selecione o Livro</label>
                        <select name="livro" required>
                            <option value="" disabled selected>Escolha um título...</option>
                            <option value="Dom Casmurro">Dom Casmurro</option>
                            <option value="1984">1984</option>
                            <option value="O Pequeno Príncipe">O Pequeno Príncipe</option>
                            <option value="O Alquimista">O Alquimista</option>
                            <option value="A Menina que Roubava Livros">A Menina que Roubava Livros</option>
                            <option value="O Senhor dos Anéis">O Senhor dos Anéis</option>
                            <option value="Harry Potter e a Pedra Filosofal">Harry Potter e a Pedra Filosofal</option>
                            <option value="Capitães da Areia">Capitães da Areia</option>
                            <option value="Cem Anos de Solidão">Cem Anos de Solidão</option>
                            <option value="Orgulho e Preconceito">Orgulho e Preconceito</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <label>📅 Prazo de Devolução</label>
                        <select name="prazo_semanas">
                            <option value="2">2 Semanas</option>
                            <option value="3">3 Semanas</option>
                        </select>
                    </div>
                    <p class="warning">⚠️ Multa diária de R$ 2,00 em caso de atraso.</p>
                    <button type="submit" class="btn-submit">Confirmar Empréstimo</button>
                </form>
            </div>

            <div id="secao-devolucoes" style="display: none;">
                <table>
                    <thead>
                        <tr>
                            <th>Leitor</th>
                            <th>Livro</th>
                            <th>Multa</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (isset($pdo)) {
                            $consulta = $pdo->query("SELECT * FROM emprestimos WHERE status = 'ativo'");
                            while ($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {
                                $hoje = new DateTime();
                                $dataEntrega = new DateTime($linha['data_devolucao_prevista']);
                                $multaTexto = "No prazo";
                                $corMulta = "#4caf50";

                                if ($hoje > $dataEntrega) {
                                    $diferenca = $hoje->diff($dataEntrega);
                                    $diasAtraso = $diferenca->days;
                                    $valorMulta = $diasAtraso * 2.00;
                                    $multaTexto = "R$ " . number_format($valorMulta, 2, ',', '.');
                                    $corMulta = "#ff5252";
                                }

                                echo "<tr>
                                        <td>{$linha['leitor']}</td>
                                        <td>{$linha['livro_nome']}</td>
                                        <td style='color: {$corMulta}; font-weight: bold;'>{$multaTexto}</td>
                                        <td>
                                            <a href='finalizar_devolucao.php?id={$linha['id']}' 
                                               style='background:#0091ff; color:white; padding:5px 10px; border-radius:5px; text-decoration:none; font-size:12px;'>
                                               Devolver
                                            </a>
                                        </td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>Erro ao carregar banco de dados.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script>
        const btnEmp = document.getElementById('btn-aba-emp');
        const btnDev = document.getElementById('btn-aba-dev');
        const secEmp = document.getElementById('secao-emprestimo');
        const secDev = document.getElementById('secao-devolucoes');
        const subtitle = document.getElementById('card-subtitle');

        btnDev.addEventListener('click', () => {
            btnEmp.classList.remove('active');
            btnDev.classList.add('active');
            subtitle.innerText = "Devoluções e Atrasos";
            secEmp.style.display = "none";
            secDev.style.display = "block";
        });

        btnEmp.addEventListener('click', () => {
            btnDev.classList.remove('active');
            btnEmp.classList.add('active');
            subtitle.innerText = "Novo Empréstimo";
            secDev.style.display = "none";
            secEmp.style.display = "block";
        });
    </script>
</body>

</html>