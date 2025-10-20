<?php
$carregadorData = $editingCarregador ?: [
    'nome' => '',
    'endereco' => '',
    'potencia_kw' => '',
    'tipo_conector' => '',
    'latitude' => '',
    'longitude' => '',
    'status' => 'ativo',
];
?>
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label" for="carregador-nome">Nome</label>
        <input class="form-control" type="text" id="carregador-nome" name="nome" value="<?= htmlspecialchars($carregadorData['nome']) ?>" required>
    </div>
    <div class="col-md-6">
        <label class="form-label" for="carregador-potencia">Potência (kW)</label>
        <input class="form-control" type="number" step="0.01" id="carregador-potencia" name="potencia_kw" value="<?= htmlspecialchars($carregadorData['potencia_kw']) ?>" placeholder="Ex.: 22">
    </div>
    <div class="col-md-6">
        <label class="form-label" for="carregador-tipo">Tipo de conector</label>
        <input class="form-control" type="text" id="carregador-tipo" name="tipo_conector" value="<?= htmlspecialchars($carregadorData['tipo_conector']) ?>" placeholder="Tipo 2, CCS, etc.">
    </div>
    <div class="col-md-6">
        <label class="form-label" for="carregador-status">Status</label>
        <?php $statusSelecionado = $carregadorData['status'] ?? 'ativo'; ?>
        <select class="form-select" id="carregador-status" name="status">
            <option value="ativo" <?= $statusSelecionado === 'ativo' ? 'selected' : '' ?>>Ativo</option>
            <option value="manutencao" <?= $statusSelecionado === 'manutencao' ? 'selected' : '' ?>>Em manutenção</option>
            <option value="inativo" <?= $statusSelecionado === 'inativo' ? 'selected' : '' ?>>Inativo</option>
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label" for="carregador-endereco">Endereço</label>
        <input class="form-control" type="text" id="carregador-endereco" name="endereco" value="<?= htmlspecialchars($carregadorData['endereco']) ?>" placeholder="Rua, número, cidade">
    </div>
    <div class="col-md-3">
        <label class="form-label" for="carregador-latitude">Latitude</label>
        <input class="form-control" type="text" id="carregador-latitude" name="latitude" value="<?= htmlspecialchars($carregadorData['latitude']) ?>" placeholder="-23.1234567">
    </div>
    <div class="col-md-3">
        <label class="form-label" for="carregador-longitude">Longitude</label>
        <input class="form-control" type="text" id="carregador-longitude" name="longitude" value="<?= htmlspecialchars($carregadorData['longitude']) ?>" placeholder="-46.9876543">
    </div>
</div>
