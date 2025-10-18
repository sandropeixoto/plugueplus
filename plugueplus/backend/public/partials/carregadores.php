<div class="row g-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body p-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
                    <div>
                        <h2 class="h4 mb-1">Carregadores cadastrados</h2>
                        <p class="text-muted mb-0">Cadastre pontos de recarga com localização e status atualizados.</p>
                    </div>
                    <a class="btn btn-outline-primary" href="admin.php?section=carregadores">
                        Novo carregador
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th scope="col">Nome</th>
                                <th scope="col">Potência (kW)</th>
                                <th scope="col">Tipo</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if (count($carregadores) === 0): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Nenhum carregador cadastrado até o momento.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($carregadores as $carregador): ?>
                                <tr>
                                    <td>
                                        <span class="fw-semibold d-block"><?= htmlspecialchars($carregador['nome']) ?></span>
                                        <?php if (!empty($carregador['endereco'])): ?>
                                            <small class="text-muted"><?= htmlspecialchars($carregador['endereco']) ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($carregador['potencia_kw'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($carregador['tipo_conector'] ?? '-') ?></td>
                                    <td>
                                        <?php
                                        $status = $carregador['status'] ?? 'indefinido';
                                        $badgeMap = [
                                            'ativo' => 'bg-success-subtle text-success',
                                            'manutencao' => 'bg-warning-subtle text-warning',
                                            'inativo' => 'bg-secondary-subtle text-secondary',
                                        ];
                                        $labelMap = [
                                            'ativo' => 'Ativo',
                                            'manutencao' => 'Em manutenção',
                                            'inativo' => 'Inativo',
                                        ];
                                        $badgeClass = $badgeMap[$status] ?? 'bg-secondary-subtle text-secondary';
                                        $badgeLabel = $labelMap[$status] ?? 'Indefinido';
                                        ?>
                                        <span class="badge <?= $badgeClass ?>">
                                            <?= htmlspecialchars($badgeLabel) ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a class="btn btn-sm btn-soft-primary me-2" href="admin.php?section=carregadores&amp;edit=<?= (int) $carregador['id'] ?>">
                                            Editar
                                        </a>
                                        <form method="post" action="admin.php?section=carregadores" class="d-inline" onsubmit="return confirm('Deseja remover este carregador?');">
                                            <input type="hidden" name="action" value="delete_carregador">
                                            <input type="hidden" name="id" value="<?= (int) $carregador['id'] ?>">
                                            <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-body p-4">
                <?php if ($editingCarregador): ?>
                    <h2 class="h4 mb-3">Editar carregador</h2>
                    <form method="post" action="admin.php?section=carregadores">
                        <input type="hidden" name="action" value="update_carregador">
                        <input type="hidden" name="id" value="<?= (int) $editingCarregador['id'] ?>">
                        <?php include __DIR__ . '/carregadores_form.php'; ?>
                        <div class="d-flex gap-2 mt-3">
                            <button class="btn btn-primary" type="submit">Salvar alterações</button>
                            <a class="btn btn-light" href="admin.php?section=carregadores">Cancelar</a>
                        </div>
                    </form>
                <?php else: ?>
                    <h2 class="h4 mb-3">Novo carregador</h2>
                    <form method="post" action="admin.php?section=carregadores">
                        <input type="hidden" name="action" value="create_carregador">
                        <?php $editingCarregador = null; include __DIR__ . '/carregadores_form.php'; ?>
                        <button class="btn btn-primary mt-3" type="submit">Cadastrar carregador</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
