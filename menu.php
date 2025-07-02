<nav class="navbar navbar-expand-lg navbar-light bg-dark">
    <a class="navbar-brand text-light" href="dashboard.php"><img src="images/logo-circle.png" width="30"
            height="30" class="d-inline-block align-top " alt=""> Embassy Of the Philippines</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-auto topnav">
            <li class="nav-item active">
                <a class="nav-link text-light" href="dashboard.php">Inicio <span
                        class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-light" href="#" id="navbarDropdown" role="button"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Opções Funcionários
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="funcionarios/registro_hora.php">&#9200; Novo
                        Registro</a>
                    <a href="funcionarios/listar_registros.php" class="dropdown-item">&#128269;
                        Consulta de Registros</a>
                    <a class="dropdown-item" href="funcionarios/listar_funcionarios.php">&#128100;
                        Funcionários</a>
                    <a href="funcionarios/cadastro_funcionario.php" class="dropdown-item">&#10133;
                        Cadastrar novo funcionário</a>
                    <div class="dropdown-divider"></div>
                    <a href="usuarios.php" class="dropdown-item">&#128736; Gerenciar Admins</a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-light" href="#" id="navbarDropdown" role="button"
                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Opções Veiculos
                </a>
                <div class="dropdown-menu " aria-labelledby="navbarDropdown">
                    <a class="dropdown-item dropleft" href="veiculos/registro_veiculos.php">&#9200;
                        Novo registro</a>
                    <a class="dropdown-item"
                        href="veiculos/registros_veiculos_agrupados.php">&#128203; Lista
                        registros</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item"
                        href="veiculos/gerenciar_veiculos.php">&#128736;Gerenciar Veiculos</a>
                </div>
            </li>
            <li>
                <span class="nav-link text-muted"> | <?= $_SESSION['usuario_nome'] ?> </span>

            </li>

            <li class="nav-item">
                <a class="nav-link btn btn-danger text-white" type="button"
                    href="logout.php">Sair</a>
            </li>
        </ul>
    </div>


</nav>