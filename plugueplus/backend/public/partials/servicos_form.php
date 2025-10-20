<?php
$servicoData = $editingServico ?: [
    'nome' => '',
    'descricao' => '',
    'telefone' => '',
    'site' => '',
    'endereco' => '',
    'latitude' => '',
    'longitude' => '',
    'categoria_id' => '',
];
?>
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label" for="servico-nome">Nome</label>
        <input class="form-control" type="text" id="servico-nome" name="nome" value="<?= htmlspecialchars($servicoData['nome']) ?>" required>
    </div>
    <div class="col-md-6">
        <label class="form-label" for="servico-categoria">Categoria</label>
        <select class="form-select" id="servico-categoria" name="categoria_id" required>
            <option value="">Selecione...</option>
            <?php foreach ($categorias as $categoria): ?>
                <option value="<?= (int) $categoria['id'] ?>" <?= (int) ($servicoData['categoria_id'] ?? 0) === (int) $categoria['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($categoria['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-12">
        <label class="form-label" for="servico-descricao">Descrição</label>
        <textarea class="form-control" id="servico-descricao" name="descricao" rows="3" placeholder="Detalhes sobre o serviço"><?= htmlspecialchars($servicoData['descricao'] ?? '') ?></textarea>
    </div>
    <div class="col-md-4">
        <label class="form-label" for="servico-telefone">Telefone</label>
        <input class="form-control" type="text" id="servico-telefone" name="telefone" value="<?= htmlspecialchars($servicoData['telefone'] ?? '') ?>" placeholder="(11) 99999-0000">
    </div>
    <div class="col-md-4">
        <label class="form-label" for="servico-site">Site</label>
        <input class="form-control" type="url" id="servico-site" name="site" value="<?= htmlspecialchars($servicoData['site'] ?? '') ?>" placeholder="https://...">
    </div>
    <div class="col-md-4">
        <label class="form-label" for="servico-endereco">Endereço</label>
        <input class="form-control" type="text" id="servico-endereco" name="endereco" value="<?= htmlspecialchars($servicoData['endereco'] ?? '') ?>" placeholder="Rua, número, cidade">
    </div>
    <div class="col-md-6">
        <label class="form-label" for="servico-latitude">Latitude</label>
        <input class="form-control" type="text" id="servico-latitude" name="latitude" value="<?= htmlspecialchars($servicoData['latitude'] ?? '') ?>" placeholder="-23.1234567">
    </div>
    <div class="col-md-6">
        <label class="form-label" for="servico-longitude">Longitude</label>
        <input class="form-control" type="text" id="servico-longitude" name="longitude" value="<?= htmlspecialchars($servicoData['longitude'] ?? '') ?>" placeholder="-46.9876543">
    </div>
</div>
