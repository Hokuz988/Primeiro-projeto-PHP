<?php

function gerarId($dados) {
    if (empty($dados)) {
        return 1;
    }
    $ids = array_column($dados, 'id');
    return max($ids) + 1;
}

function validarDados($nome, $publicadora, $ativo, $data) {
    $erros = [] ;
    if (empty($nome)) {
        $erros [] = 'O nome do jogo é obrigatório.';};
    if (empty($publicadora)) {
        $erros [] = 'O publicador é obrigatório.';};
    $ativo = strtolower($ativo);
    if ($ativo != "s" && $ativo != "n") {
        $erros [] = "Status deve ser 's' ou 'n'.";};
    if (!is_numeric($data)) {
        $erros [] = 'Insira o ano de lançamento correto.';}
    return $erros;
}


function cadastrar(&$dados) {
    echo "\n Novo Cadastro\n";

    echo "NOME DO JOGO: ";
    $nome = trim(fgets(STDIN));

    echo "PUBLICADORA: ";
    $publicadora = trim(fgets(STDIN));

    echo "AINDA É ATUALIZADO? (s/n): ";
    $ativo = trim(fgets(STDIN));

    echo "DATA DE LANÇAMENTO: ";
    $data = trim(fgets(STDIN)); 

    $erros = validarDados($nome, $publicadora, $ativo, $data);
    if (!empty($erros)) {
        echo "\nErros encontrados:\n";
        foreach ($erros as $erro) {
            echo "- $erro\n";
        }
        echo "Cadastro cancelado.\n";
        return false;
    }
    $registro = [
        'id' => gerarId($dados),
        'nome' => $nome,
        'publicadora' => $publicadora,
        'ativo' => strtolower($ativo == 's'),
        'data' =>(int) $data,
    ];
    $dados[] = $registro;
    echo "\nRegistro cadastrado com sucesso! ID: " . $registro['id'] . "\n";
    return true;
}

function listarTodos($dados) {
    if (empty($dados)) {
        echo "\n Nenhum dado encontrado \n";
        return false;
    }
    
    $lista = $dados;
    usort($lista, function($a, $b) {
        return strcasecmp($a['nome'], $b['nome']);
    });
    
    echo "\n--- LISTA DE REGISTROS ---\n";
    echo "ID  | NOME                    | PUBLICADORA          | STATUS     | LANÇAMENTO\n";
    echo "--------------------------------------------------------------------------------\n";

    foreach ($lista as $reg) {
        $status = $reg['ativo'] ? 'Atualiaza   ' :'Nao atualiza';
        printf("%-3d | %-23s | %-20s | %-7s | %d\n",  
            $reg['id'],
            substr($reg['nome'], 0, 23),
            $reg['publicadora'],
            $status,
            $reg['data']
        );
    }
    echo "--------------------------------------------------------------------------------\n";
    echo "Total de registros: " . count($dados) . "\n";
}

function buscarPorNome($dados) {
    if (empty($dados)) {
        echo "\nNenhum registro cadastrado.\n";
        return false;
    }
    
    echo "Digite o que quer buscar: ";
    $termo = trim(fgets(STDIN));
    if (empty($termo)) {
        echo "Termo de busca inválido.\n";
        return;
    }
    
    $encontrado = [];
    foreach ($dados as $reg) {
        if (stripos($reg['nome'], $termo) !== false) {
            $encontrado[] = $reg;
        }
    }
    
    if (empty($encontrado)) {
        echo "Registro não encontrado\n";
        return;
    }
    
    echo "\n--- RESULTADOS DA BUSCA ---\n";
    foreach ($encontrado as $reg) {
        $status = $reg['ativo'] ? 'Atualiza    ' : 'Não Atualiza';
        echo "ID: {$reg['id']} | Nome: {$reg['nome']} | Publicadora: {$reg['publicadora']} | Status: $status | Lançamento: {$reg['data']}\n";
    }
    echo "Total encontrado: " . count($encontrado) . "\n";
}

function editarRegistro(&$dados) {
    if (empty($dados)) {
        echo "\nNenhum registro cadastrado.\n";
        return false;
    }
    
    echo "Digite o ID do registro: ";
    $id = trim(fgets(STDIN));
    
    if (!is_numeric($id)) {
        echo "ID inválido.\n";
        return false;
    }
    
    $id = (int)$id;
    $indice = null;
    foreach ($dados as $i => $reg) {
        if ($reg['id'] == $id) {
            $indice = $i;
            break;
        }
    }
    
    if ($indice === null) {
        echo "Registro não encontrado.\n";
        return false;
    }
    
    echo "\n--- EDITANDO REGISTRO ---\n";
    echo "Deixe em branco para manter o valor atual.\n\n";
    
    echo "Jogo atual: " . $dados[$indice]['nome'] . "\n";
    echo "Novo jogo: ";
    $novoNome = trim(fgets(STDIN));
    
    echo "Publicadora do jogo: " . $dados[$indice]['publicadora'] . "\n";
    echo "Publicadora do novo jogo: ";
    $novaPublicadora = trim(fgets(STDIN));
    
    echo "Status atual: " . ($dados[$indice]['ativo'] ? 'Atualiza' : 'Não atualiza') . "\n";
    echo "Novo status (s/n): ";
    $novoStatus = trim(fgets(STDIN));

    echo "Data de lançamento: " . $dados[$indice]['data'] . "\n";
    echo "Data de lançamento do novo jogo: ";
    $novaData = trim(fgets(STDIN));

    if ($novoNome != '') {
        $dados[$indice]['nome'] = $novoNome;
    }
    
    if ($novaData != '' && is_numeric($novaData)) {
        $dados[$indice]['data'] = (int)$novaData;
    }
    
    if ($novoStatus != '') {
        $dados[$indice]['ativo'] = (strtolower($novoStatus) == 's');
    }
    if  ($novaPublicadora != '') {
        $dados[$indice]['publicadora'] = $novaPublicadora;
    }
    echo "Registro atualizado com sucesso!\n";
    return true;
}

function removerDados(&$dados) {
    if (empty($dados)) {
        echo "Não foram encontrados registros.";
        return false;
    }
    
    echo "Digite o ID do registro: ";
    $id = trim(fgets(STDIN));
    
    if (!is_numeric($id)) {
        echo "ID inválido.\n";
        return false;
    }
    
    $id = (int)$id;
    $indice = null;
    foreach ($dados as $i => $reg) {
        if ($reg['id'] == $id) {
            $indice = $i;
            break;
        }
    }
    
        if ($indice === null) {
        echo "Registro não encontrado.\n";
        return false;
    }
    
    echo "\nRegistro encontrado:\n";
    echo "Nome: " . $dados[$indice]['nome'] . "\n";
    echo "Confirma exclusão? (s/n): ";
    $confirma = strtolower(trim(fgets(STDIN)));
    if ($confirma == 's') {
        array_splice($dados, $indice, 1);
        echo "Registro removido com sucesso!\n";
        return true;
    } else {
        echo "Operação cancelada.\n";
        return false;
    }
}

function exibirEstatisticas($dados) {
    if (empty($dados)) {
        echo "Registro não foi encontrado.\n";
        return;
    }    
    $total = count($dados);
    
    // Média de idade
    $somaIdades = 0;
    $ativos = 0;
    $idades = [];
    
    foreach ($dados as $reg) {
        $somaIdades += $reg['data'];
        $idades[] = $reg['data'];
        if ($reg['ativo']) {
            $ativos++;
        }
    }
    
    $maior = max($idades);
    $menor = min($idades);
    $inativos = $total - $ativos;
    
    echo "\n--- ESTATÍSTICAS ---\n";
    echo "Total de registros: $total\n";
    echo "Jogo mais novo: lançado em $maior\n";
    echo "Jogo mais antigo: lançado em $menor\n";
    echo "Atualizam: $ativos\n";    
    echo "Não atualizam: $inativos\n";
}

function exibirMenu() {
    echo "
⠀⠀⠀⠀⠀⠀⠀⠀⣀⣀⡀⠀⠀⠀⠀⠀⠀⠀⠀⢀⣀⣀⠀⠀⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⠀⣠⣾⣿⣿⣿⣦⣄⡀⠀⠀⢀⣠⣴⣿⣿⣿⣷⣄⠀⠀⠀⠀⠀⠀
⠀⠀⠀⠀⠀⣼⣿⣿⠛⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣏⠉⣹⣿⣧⠀⠀⠀⠀⠀
⠀⠀⠀⠀⣼⣿⣉⣉⠀⣉⣙⣿⣿⣿⣿⣿⣿⣿⣟⠁⣹⣿⣏⠀⣹⣧⠀⠀⠀⠀
⠀⠀⠀⢠⣿⣿⣿⣿⣀⣿⣿⣿⣉⣉⣿⣿⣉⣹⣿⣿⣏⠀⣹⣿⣿⣿⡄⠀⠀⠀
⠀⠀⠀⢸⣿⣿⣿⣿⣿⣿⣿⣿⣿⠿⠿⠿⠿⣿⣿⣿⣿⣿⣿⣿⣿⣿⡇⠀⠀⠀
⠀⠀⠀⢸⣿⣿⣿⣿⣿⠟⠉⠀⠀⠀⠀⠀⠀⠀⠀⠉⠻⣿⣿⣿⣿⣿⡇⠀⠀⠀
⠀⠀⠀⠸⣿⣿⣿⡟⠁⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠈⢻⣿⣿⣿⠇⠀⠀⠀
⠀⠀⠀⠀⠉⠉⠉⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠀⠉⠉⠉⠀⠀⠀⠀
\n";
    echo "\n" . str_repeat("=", 40) . "\n";
    echo "   SISTEMA DE GERENCIAMENTO DE JOGOS.   \n";
    echo str_repeat("=", 40) . "\n";
    echo "1. Cadastrar\n";
    echo "2. Listar todos\n";
    echo "3. Buscar por nome\n";
    echo "4. Editar\n";
    echo "5. Remover\n";
    echo "6. Estatísticas\n";
    echo "0. Sair\n";
    echo str_repeat("=", 40) . "\n";
    echo "Escolha: ";
    return trim(fgets(STDIN));
}
?>
