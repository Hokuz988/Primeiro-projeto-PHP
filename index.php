<?php

require_once "/home/arthur/projects/php/Projeto/projeto.php";

echo "SISTEMA DE GERENCIAMENTO INICIADO\n";

while (true) {
    $opcao = exibirMenu();
    
    switch ($opcao) {
        case '1':
            cadastrar($dados);
            break;
        case '2':
            listarTodos($dados);
            break;
        case '3':
            buscarPorNome($dados);
            break;
        case '4':
            editarRegistro($dados);
            break;
        case '5':
            removerDados($dados);
            break;
        case '6':
            exibirEstatisticas($dados);
            break;
        case '0': 
            echo "\nSaindo do sistema...\n";
            exit;
        default:
            echo "\nOpção inválida! Digite 0-6.\n";
    }
    
    echo "\nPressione Enter para continuar";
    fgets(STDIN);
}
?>